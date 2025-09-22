<?php

namespace App\Domains\Auth\Repositories\Eloquent;

use App\Domains\Auth\Models\RolesEnum;
use App\Domains\Auth\Repositories\Eloquent\Models\Permission as EloquentPermission;
use App\Domains\Auth\Repositories\Eloquent\Models\Role as EloquentRole;
use App\Domains\Auth\Repositories\Eloquent\Models\User as EloquentUser;
use App\Domains\Auth\Repositories\UserRepositoryInterface;
use App\Domains\ExtraData\Enums\VisibilityEnum;
use App\Exceptions\GeneralException;
use App\Facades\ObjectSerializer;
use App\Helpers\EloquentRelationHelper;
use App\Models\CactusEntity;
use App\Domains\Auth\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Ramsey\Uuid\Uuid as PackageUuid;
use Throwable;
use Yajra\DataTables\DataTables;

class EloqUserRepository extends EloquentRelationHelper implements UserRepositoryInterface
{

    private EloquentUser $model;

    public function __construct(EloquentUser $user)
    {
        $this->model = $user;
    }
    public function get(): array
    {
        $users = $this->model;
        $users = $users->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'super-admin');
        });
        $users = $users->get();
        return ObjectSerializer::deserialize($users->toJson() ?? "{}", 'array<' . User::class . '>', 'json');
    }

    public function getAuthUser(): ?User
    {
        $user = Auth::user();
        $user->makeVisible('two_factor_secret');
        $user->makeVisible('two_factor_confirmed');
        $user->makeVisible('two_factor_recovery_codes');
        if($user->two_factor_secret && $user->two_factor_confirmed){
            $user->twoFactorQrCodeSvg = $user->twoFactorQrCodeSvg();
        }


        return ObjectSerializer::deserialize($user->toJson() ?? "{}", User::class, 'json');
    }

    public function hasRole(int $userId, string|int $role):bool
    {
        $user = $this->model->findOrFail($userId);
        return $user->hasRole($role);
    }

    public function getById(string $id): ?User
    {
        $user = $this->model->with(['roles','permissions','userDetails','notes','notes.user','extraData'])->findOrFail($id);
        $user->makeVisible('two_factor_secret');

        return ObjectSerializer::deserialize($user->toJson() ?? "{}", User::class, 'json');
    }

    public function getByUuid(string $uuid): ?User
    {
        $user = $this->model->where('uuid',$uuid)->first();
        if(!$user){
            abort(404);
        }
        $user->load(['roles','permissions','userDetails','subscription']);
        $user->makeVisible('two_factor_secret');

        return ObjectSerializer::deserialize($user->toJson() ?? "{}", User::class, 'json');
    }

    public function getByIdWithMorphsAndRelations(string $modelId, array $morphs = [], array $relations = []): ?User
    {
        $project = $this->model::findOrFail($modelId);

        $project = $this->modelLoadRelations($project, $morphs);
        $project = $this->modelLoadRelations($project, $relations);

        return ObjectSerializer::deserialize($project?->toJson() ?? "{}",  User::class , 'json');
    }

    /**
     * @param string $roleId
     * @return User[]
     */
    public function getByRoleId(string $roleId): array
    {
        $users = $this->model->whereHas('roles', function ($query) use ($roleId) {
            $query->where('id', $roleId);
        })->get();

        return ObjectSerializer::deserialize($users->toJson() ?? "{}", 'array<' . User::class . '>', 'json');
    }

    public function getByEmail(string $email): ?User
    {
        $user = $this->model->with(['roles','permissions','userDetails'])->where('email',$email)->first();

        if(!$user){
            return null;
        }

        return ObjectSerializer::deserialize($user->toJson() ?? "{}", User::class, 'json');
    }

    /**
     * @return User[]
     */
    public function getWithoutRole(): array
    {
        $users = $this->model->whereDoesntHave('roles')->get();

        return ObjectSerializer::deserialize($users->toJson() ?? "{}", 'array<' . User::class . '>', 'json');
    }

    /**
     * @param User|CactusEntity $entity
     * @return User|null
     * @throws GeneralException
     * @throws Throwable
     */
    public function store(User|CactusEntity $entity): ?User
    {
        DB::beginTransaction();

        try {
            $pass = $entity->getPassword();
            if (!$pass) {
                $pass = Str::random(12);
            }

            if(!$entity->getUuid()){
                $entity->setUuid(PackageUuid::uuid4()->toString());
            }

            while ($this->model::where('uuid', $entity->getUuid())->exists()) {
                $entity->setUuid(PackageUuid::uuid4()->toString());
            }

            $user = $this->model::create([
                'uuid' => $entity->getUuid(),
                'name' => $entity->getName() ?? null,
                'email' => $entity->getEmail() ?? null,
                'password' => bcrypt($pass) ?? null,
                'email_verified_at' =>  $entity->getEmailVerifiedAt() ? now() : null,
                'active' => $entity->getActive() ?? true,
            ]);

            $roles = [];
            foreach ($entity->getRoles() as $role) {
                $roles[] = EloquentRole::find($role);
            }

            $user->syncRoles($roles ?? []);


            foreach ($entity->getPermissions() as $permission) {
                $permissions[] = EloquentPermission::find($permission);
            }

            $user->syncPermissions($permissions ?? []);

            $syncData = [];
            $extraDataWithPivot = $entity->getExtraDataIds();
            foreach ($extraDataWithPivot as $extraDataId => $data) {
                $syncData[$extraDataId] = [
                    'value' => $data,
                    'sort' => 0,
                    'visibility' => VisibilityEnum::ALL,
                ];
            }

            // Sync the extra data with the pivot table
            $user->extraData()->sync($syncData);

            //todo send email notification
//        $user->notify(new UserRegistration($pass));
        } catch (Exception $e) {
            DB::rollBack();
            // duplicate email return specific message
            if ($e->errorInfo[1] == 1062) {
                throw new GeneralException('Υπάρχει χρήστης με το ίδιο email. Δοκιμάστε ενα διαφορετικό.');
            }else{
                Log::error($e);
                dd($e);
                throw new GeneralException(__('Υπήρξε κάποιο πρόβλημα κατά την αποθήκευση. Προσπαθήστε ξανά.'));
            }

        }

        DB::commit();

        return ObjectSerializer::deserialize($user->toJson() ?? "{}", User::class, 'json');
    }

    /**
     * @throws GeneralException
     */
    public function update(User|CactusEntity $entity, string $id, bool $updateRole = false): ?User
    {
        DB::beginTransaction();

        try {
            $user = EloquentUser::find($id);

            if(!$user->email_verified_at && $entity->getEmailVerifiedAt()){
                $entity->setEmailVerifiedAt(now());
            }
            if($user->email_verified_at){
                $entity->setEmailVerifiedAt($user->email_verified_at);
            }

            $user->update([
                'name' => $entity->getName(),
                'email' => $entity->getEmail(),
                'email_verified_at' => $entity->getEmailVerifiedAt(),
                'profile_photo_url' => $entity->getProfilePhotoUrl(),
            ]);

            $syncData = [];
            $extraDataWithPivot = $entity->getExtraDataIds();
            foreach ($extraDataWithPivot as $extraDataId => $data) {
                $syncData[$extraDataId] = [
                    'value' => $data,
                    'sort' => 0,
                    'visibility' => VisibilityEnum::ALL,
                ];
            }

            // Sync the extra data with the pivot table
            $user->extraData()->sync($syncData);

            if($updateRole){
                if (!$user->isMasterAdmin()) {
                    // Replace selected roles/permissions
                    foreach ($entity->getRoles() as $role) {
                        $roles[] = EloquentRole::find($role);
                    }

                    $user->syncRoles($roles ?? []);

                    $permissions = [];
                    foreach ($entity->getPermissions() as $permission) {
                        $permissions[] = EloquentPermission::find($permission);
                    }

                    $user->syncPermissions($permissions ?? []);

                }
            }
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('Υπήρξε κάποιο πρόβλημα κατά την αποθήκευση. Προσπαθήστε ξανά.'));
        }

        DB::commit();

        return ObjectSerializer::deserialize($user->toJson() ?? "{}", User::class, 'json');
    }

    public function deleteById(string $id): bool
    {
        $user = EloquentUser::find($id);
        if ($user->id === auth()->id()) {
            return false;
        }

        $user->delete();

        return true;
    }

    /**
     * @throws Throwable
     */
    public function updatePassword(User|CactusEntity $entity, string $userId, mixed $expired): ?User
    {
        $user = EloquentUser::find($userId);

        // Reset the expiration clock
        if ($expired) {
            $user->password_changed_at = now();
        }

        $user->password = bcrypt($entity->getPassword());
        $user->save();

        return ObjectSerializer::deserialize($user->toJson() ?? "{}", User::class, 'json');
    }

    public function updateProfileImage(string $userId, UploadedFile $photo): ?User
    {
        $user = $this->model->find($userId);

        $user?->updateProfilePhoto($photo);

        return ObjectSerializer::deserialize($user->toJson() ?? "{}", User::class, 'json');
    }

    public function deleteProfilePhoto(string $userId): bool
    {
        $user = $this->model->find($userId);
        if($user){
            $user->deleteProfilePhoto();
            return true;
        }

        return false;
    }

    public function updateActive(string $userId, bool $active): bool
    {
        if ($active == 0 && (auth()->id() === $userId || $userId == 1 || $userId == 2)) {
            return false;
        }

        $user = EloquentUser::find($userId);
        $user->active = $active;
        $user->save();

        return true;
    }

    public function restore(string $userId): bool
    {
        $user = EloquentUser::withTrashed()->find($userId);
        return $user->restore();
    }

    public function destroyById(string $userId): bool
    {
        $user = EloquentUser::find($userId);
        if ($user->forceDelete()) {
            return true;
        }

        return false;
    }

    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function emailsPaginated(?string $searchTerm, int $offset, int $resultCount): array
    {
        $users = $this->model->select('id', DB::raw('email AS text'));
        if ($searchTerm != null) {
            $users = $users->where('email', 'LIKE', '%' . $searchTerm . '%');
        }

        $users = $users->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'super-admin');
        });

        $users = $users->skip($offset)->take($resultCount)->get('id');


        if ($searchTerm == null) {
            $count = $this->model->count();
        } else {
            $count = $users->count();
        }

        return array(
            "data" => $users,
            "count" => $count
        );
    }


    /**
     * @param string|null $searchTerm
     * @param int $offset
     * @param int $resultCount number of results per page
     * @return array{data: Collection, count: int} Array contains paginated data and total count.
     */
    public function namesPaginated(?string $searchTerm, int $offset, int $resultCount, bool $onlyContacts = false): array
    {
        $users = $this->model->select('id', DB::raw('name AS text'));
        if ($searchTerm != null) {
            $users = $users->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $authUser = Auth::user();

        $users = $users->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'super-admin');
        });

        if (!$onlyContacts) {
            if (!$authUser->hasRole(RolesEnum::Administrator->value) && !$authUser->hasRole(RolesEnum::SuperAdmin->value)) {
                if ($authUser->hasRole(RolesEnum::RND_DIRECTOR->value)) {
                    $users = $users->whereHas('roles', function ($q) {
                        $q->where('id', RolesEnum::RND_DIRECTOR->value)
                            ->orWhere('id', RolesEnum::RND_ENG->value);
                    });
                } else if ($authUser->hasRole(RolesEnum::SALES_DIRECTOR->value)) {
                    $users = $users->whereHas('roles', function ($q) {
                        $q->where('id', RolesEnum::SALES_DIRECTOR->value)
                            ->orWhere('id', RolesEnum::SALES_SKG->value)
                            ->orWhere('id', RolesEnum::SALES_ATH->value);
                    });
                } else {
                    $users = $users->where('id', $authUser->id);
                }
            }
        }

        if ($onlyContacts) {
            $users = $users->whereDoesntHave('roles');
        } else {
            $users = $users->whereHas('roles');
        }

        $users = $users->skip($offset)->take($resultCount)->get('id');

        if ($searchTerm == null) {
            $count = $this->model->count();
        } else {
            $count = $users->count();
        }

        return array(
            "data" => $users,
            "count" => $count
        );
    }


    /**
     * @param array $filters
     * @return JsonResponse
     * @throws Exception
     */
    public function usersDatatable(array $filters = []): JsonResponse
    {
        $users = EloquentUser::query();
        $users = $users->select('users.*')->with('roles');

        if(isset($filters['status']) && $filters['status'] == '1'){
            $users = $users->onlyTrashed();
        }

        /**
         * Custom Search - Filter in Datatables
         */
        $users = $users
            ->when($filters['active'] !== null, function ($query,$searchTerm) use ($filters){
                $query->where('users.active', $filters['active']);
            })
            ->when($filters['filterName'], function ($query,$searchTerm) {
                $query->where('users.name', 'LIKE', '%'.$searchTerm.'%');
            })
            ->when($filters['filterUserEmail'], function ($query,$searchTerm) {
                $query->where('users.email', $searchTerm);
            })
            ->when($filters['filterRole'], function ($query,$searchTerm) {
                $query->whereHas('roles',function ($q2) use($searchTerm){
                    $q2->where('id' , $searchTerm);
                });
            });

        $users = $users->whereHas('roles',function ($q){
                            $q->where('id' ,'!=', RolesEnum::SuperAdmin->value);
                        })
                        ->orWhereDoesntHave('roles');

//        if ($filters['columnName'] && $filters['columnSortOrder']) {
//            $users = $users->orderBy($filters['columnName'], $filters['columnSortOrder']);
//        }

//        $users = $users->orderBy('users.id','desc')
//            ->groupBy('users.id');

        return DataTables::of($users)
            ->editColumn('roles', function(EloquentUser $user) {
                $html="";
                foreach($user->roles as $role){
                    $html .= '<span class="badge rounded-pill bg-label-primary">';
                    $html .= $role->name;
                    $html .= '</span>';
                }
                return $html;
            })
            ->addColumn('more', function (EloquentUser $user){
                return view('backend.auth.users.includes.actions', ['user' => $user])->render();
            })
            ->addColumn('restore',function(EloquentUser $user){
                return '<form class="revert-form" method="POST" action="'.route('admin.users.restore').'">
                            <input type="hidden" name="_token" value="'. csrf_token() .'" />
                            <input type="hidden" name="revert-id" value="'.$user->id.'"/>
                            <button type="submit" class="revert btn btn-group-sm">
                                <i class="fas fa-clock-rotate-left"></i>
                            </button>
                        </form>';
            })
            ->addColumn('online_status',function(EloquentUser $user){
                if(Cache::has('user-is-online-' . $user->id)){
                    return '<span class="badge rounded-pill bg-label-primary"> Online </span>';
                }else{
                    return '<span class="badge rounded-pill bg-label-danger"> Offline </span>';
                }
            })
            ->addColumn('last_login',function(EloquentUser $user){
                if($user->last_login_at){
                    return Carbon::parse($user->last_login_at)->diffForHumans();
                }
                return ' - ';
            })
            ->rawColumns(['roles','online_status','online_status','edit','delete','more','restore'])
            ->toJson();

    }


    /**
     * @param User $user
     * @return JsonResponse
     */
    public function apiAuthentication(User $user): JsonResponse
    {
        if(Auth::attempt(['email' => $user->getEmail(), 'password' => $user->getPassword()])){
            $eloquentUser = Auth::user();
            if($eloquentUser->canApi()):
                $success['token'] =  $eloquentUser->createToken('CactusCRM',['*'], Carbon::now()->addHours(4))->plainTextToken;
                return response()->json(['success' => $success], 200);
            else:
                return response()->json(['error'=>'Unauthorised'], 401);
            endif;
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    /**
     * @return JsonResponse
     */
    public function apiLogOut(): JsonResponse
    {
        $eloquentUser = Auth::user();
        $eloquentUser->currentAccessToken()->delete();
        Auth::guard('web')->logout();
        return response()->json(['success'=>'Logged Out'],200);
    }

    /**
     * @param string $code
     * @return bool
     */
    public function confirmTwoFactorAuth(string $code): bool
    {
        $eloquentUser = Auth::user();

        $codeIsValid = app(TwoFactorAuthenticationProvider::class)
            ->verify(decrypt($eloquentUser->two_factor_secret), $code);

        if ($codeIsValid) {
            $eloquentUser->two_factor_confirmed = true;
            $eloquentUser->two_factor_confirmed_at = now();
            $eloquentUser->save();

            return true;
        }

        return false;
    }


    /**
     * @inheritDoc
     */
    public function getByIds(array $ids): ?array
    {
        $users = EloquentUser::whereIn('id', $ids)->get();

        return ObjectSerializer::deserialize($users->toJson() ?? "{}", 'array<' . User::class . '>', 'json');

    }

    /**
     * @param string $model
     * @param string $id
     * @return Model
     */
    private function relationModel (string $model, string $id) :Model
    {
        // Find Relation Model By Class Name
        $relationModelClass = Relation::getMorphedModel($model);

        // Find Relation Model By id
        return $relationModelClass::find($id);
    }

    /**
     * @inheritDoc
     */
    public function sync(array $assignees, string $model, string $id): bool
    {
        // Find Relation Model
        $relationModel = $this->relationModel($model, $id);


        // Sync Assignees
        $relationModel?->assignees()?->sync($assignees);

        return true;
    }
}
