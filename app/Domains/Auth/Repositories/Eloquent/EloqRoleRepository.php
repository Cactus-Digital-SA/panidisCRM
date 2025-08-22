<?php

namespace App\Domains\Auth\Repositories\Eloquent;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Repositories\Eloquent\Models\Permission as EloquentPermission;
use App\Domains\Auth\Repositories\Eloquent\Models\Role as EloquentRole;
use App\Domains\Auth\Repositories\Eloquent\Models\User as EloquentUser;
use App\Domains\Auth\Repositories\RoleRepositoryInterface;
use App\Exceptions\GeneralException;
use App\Facades\ObjectSerializer;
use App\Models\CactusEntity;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;
use Yajra\DataTables\DataTables;

class EloqRoleRepository implements RoleRepositoryInterface
{
    private EloquentRole $model;

    public function __construct(EloquentRole $role)
    {
        $this->model = $role;
    }

    public function getById(string $id): ?Role
    {
        $role = $this->model->with('permissions')->findOrFail($id);
        return ObjectSerializer::deserialize($role->toJson() ?? "{}", Role::class, 'json');
    }

    public function getByName(string $roleName): ?Role
    {
        $role = $this->model->where('name', $roleName)->first();
        return ObjectSerializer::deserialize($role->toJson() ?? "{}", Role::class, 'json');
    }

    /**
     * @return Role[]
     */
    public function get(): array
    {
        $roles = $this->model::where('name', '!=', 'super-admin')->get();

        return ObjectSerializer::deserialize($roles->toJson() ?? "{}", 'array<' . Role::class . '>', 'json');

    }

    /**
     * @param string $userId
     * @return int[] Array contains role ids by userId.
     */
    public function getRolesIdByUserId(string $userId): array
    {
        $user = new EloquentUser();
        $user = $user->find($userId);

        return $user->roles->pluck('id')->toArray();
    }


    /**
     * @throws GeneralException
     */
    public function store(Role|CactusEntity $entity): ?Role
    {
        DB::beginTransaction();

        try {
            $role = $this->model::create(['guard_name' => $entity->getGuardName(), 'name' => $entity->getName()]);

            $permissions = [];
            foreach ($entity->getPermissions() as $permission) {
                $permissions[] = EloquentPermission::find($permission);
            }

            $role->syncPermissions($permissions ?? []);


            app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem creating the role.'));
        }

        DB::commit();

        return ObjectSerializer::deserialize($role->toJson() ?? "{}", Role::class, 'json');
    }

    /**
     * @throws GeneralException
     */
    public function update($entity, string $id): ?CactusEntity
    {
        DB::beginTransaction();

        try {
            $role = EloquentRole::find($id);

            $role->update(['name' => $entity->getName()]);
            $permissions = [];
            foreach ($entity->getPermissions() as $permission) {
                $permissions[] = EloquentPermission::find($permission);
            }

            $role->syncPermissions($permissions ?? []);


            app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException(__('There was a problem updating the role.'));
        }

        DB::commit();

        return ObjectSerializer::deserialize($role->toJson() ?? "{}", Role::class, 'json');
    }

    /**
     * @throws BindingResolutionException
     */
    public function deleteById(string $id): bool
    {
        $role = EloquentRole::where('id', '!=', 1)
            ->where('id', '!=', 2)
            ->find($id);

        if (!$role || $role->users()->count()) {
            return false;
        }

        $role->delete();
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        return true;
    }

    /**
     * @param array $filters
     * @return JsonResponse
     * @throws Exception
     */
    public function rolesDatatable(array $filters = []): JsonResponse
    {
        $roles = EloquentRole::where('name' ,'!=', 'super-admin');
        return Datatables::of($roles)
            ->addColumn('actions', function (EloquentRole $role){
                return '
                <div class="d-flex align-items-center">
                <a href="'.route("admin.roles.edit",$role->id).'" data-type="edit" class="btn-sm btn item-edit">'
                    .'<i class="fas fa-pen"></i>'.
                    '</a>
                        <form class="delete-form" method="POST" action="'.route('admin.roles.delete',$role->id).'">
                            <input type="hidden" name="_method" value="delete" />
                            <input type="hidden" name="_token" value="'. csrf_token() .'" />
                            <button type="submit" class="delete delete-button btn btn-sm btn-flat-danger">
                              <i class="fas fa-times"></i>
                            </button>
                            </form>
                        </div>';
            })
            ->blacklist(['actions'])
            ->rawColumns(['actions'])
            ->toJson();
    }
}
