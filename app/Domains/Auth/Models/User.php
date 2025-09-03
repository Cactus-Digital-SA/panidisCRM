<?php

namespace App\Domains\Auth\Models;

use App\Domains\ExtraData\Models\ExtraData;
use App\Domains\Files\Models\File;
use App\Domains\Notes\Models\Note;
use App\Models\CactusEntity;
use App\Models\Enums\EloqMorphEnum;
use DateTime;
use Illuminate\Http\Request;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid as PackageUuid;

class User extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var string|null $uuid
     * @JMS\Serializer\Annotation\SerializedName("uuid")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $uuid = null;

    /**
     * @var string $name
     * @JMS\Serializer\Annotation\SerializedName("name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $name = "";

    /**
     * @var string $email
     * @JMS\Serializer\Annotation\SerializedName("email")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $email = "";

    /**
     * @var string $password
     * @JMS\Serializer\Annotation\SerializedName("password")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $password = "";

    /**
     * @var \DateTime|null $email_verified_at
     * @JMS\Serializer\Annotation\SerializedName("email_verified_at")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d\TH:i:s.up'>")
     */
    private ?\DateTime $email_verified_at = null;

    /**
     * @var \DateTime|null $password_changed_at
     * @JMS\Serializer\Annotation\SerializedName("password_changed_at")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d\TH:i:s.up'>")
     */
    private ?\DateTime $password_changed_at = null;

    /**
     * @var int $active
     * @JMS\Serializer\Annotation\SerializedName("active")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $active = 0;

    /**
     * @var DateTime|null $last_login_at
     * @JMS\Serializer\Annotation\SerializedName("last_login_at")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d\TH:i:s.up'>")
     */
    private ?\DateTime $last_login_at = null;

    /**
     * @var string|null $last_login_ip
     * @JMS\Serializer\Annotation\SerializedName("last_login_ip")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $last_login_ip = null;

    /**
     * @var bool $to_be_logged_out
     * @JMS\Serializer\Annotation\SerializedName("to_be_logged_out")
     * @JMS\Serializer\Annotation\Type("bool")
     */
    private bool $to_be_logged_out = false;

    /**
     * @var string|null $profile_photo_url
     * @JMS\Serializer\Annotation\SerializedName("profile_photo_url")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $profile_photo_url = null;

    /**
     * @var string|null $twoFactorSecret
     * @JMS\Serializer\Annotation\SerializedName("two_factor_secret")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $twoFactorSecret = null;

    /**
     * @var string|null $twoFactorConfirmed
     * @JMS\Serializer\Annotation\SerializedName("two_factor_confirmed")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $twoFactorConfirmed = null;

    /**
     * @var string|null $twoFactorRecoveryCodes
     * @JMS\Serializer\Annotation\SerializedName("two_factor_recovery_codes")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $twoFactorRecoveryCodes = null;

    /**
     * @var string|null $twoFactorQrCodeSvg
     * @JMS\Serializer\Annotation\SerializedName("twoFactorQrCodeSvg")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $twoFactorQrCodeSvg = null;

    /**
     * @var Token[] $tokens
     * @JMS\Serializer\Annotation\SerializedName("tokens")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\Token>")
     */
    private array $tokens = [];

    /**
     * @var Role[] $roles
     * @JMS\Serializer\Annotation\SerializedName("roles")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\Role>")
     */
    private array $roles = [];

    /**
     * @var Permission[] $permissions
     * @JMS\Serializer\Annotation\SerializedName("permissions")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Auth\Models\Permission>")
     */
    private array $permissions = [];

    /**
     * @var ?UserDetails $userDetails
     * @JMS\Serializer\Annotation\SerializedName("user_details")
     * @JMS\Serializer\Annotation\Type("App\Domains\Auth\Models\UserDetails")
     */
    private ?UserDetails $userDetails = null;

    /**
     * @var ExtraData[] $extraData
     * @Serializer\SerializedName("extra_data")
     * @Serializer\Type("array<App\Domains\ExtraData\Models\ExtraData>")
     */
    private array $extraData = [];

    /**
     * @var int[] $extraDataIds
     * @Serializer\SerializedName("extra_data_ids")
     * @Serializer\Type("array<int>")
     */
    private array $extraDataIds = [];

    /**
     * @var Note[] $notes
     * @JMS\Serializer\Annotation\SerializedName("notes")
     * @JMS\Serializer\Annotation\Type("array<App\Domains\Notes\Models\Note>")
     */
    private array $notes = [];

    /**
     * @var File[]|null $files
     * @Serializer\SerializedName("files")
     * @Serializer\Type("array<App\Domains\Files\Models\File>")
     */
    private ?array $files = null;

    /**
     * @var array|null $morphables
     * @Serializer\SerializedName("morphables")
     * @Serializer\Type("enum<'App\Models\Enums\EloqMorphEnum'>")
     */
    private ?array $morphables = [EloqMorphEnum::FILES, EloqMorphEnum::NOTES];


    /**
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'email_verified_at' => $this->email_verified_at?->format('Y-m-d\TH:i:s.up'),
            'password_changed_at' => $this->password_changed_at?->format('Y-m-d\TH:i:s.up'),
            'active' => $this->active,
            'last_login_at' => $this->last_login_at?->format('Y-m-d\TH:i:s.up'),
            'last_login_ip' => $this->last_login_ip,
            'to_be_logged_out' => $this->to_be_logged_out,
            'profile_photo_url' => $this->profile_photo_url,
        ];

        if ($withRelations) {
            $data['roles'] = $this->getRoles();
            $data['permissions'] = $this->getPermissions();
            $data['tokens'] = $this->getTokens();
            $data['userDetails'] = $this->getUserDetails();
            $data['notes'] = $this->getNotes();
        }

        return $data;
    }

    public function toJson(): string
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'email_verified_at' => $this->email_verified_at?->format('Y-m-d\TH:i:s.up'),
            'password_changed_at' => $this->password_changed_at?->format('Y-m-d\TH:i:s.up'),
            'active' => $this->active,
            'last_login_at' => $this->last_login_at?->format('Y-m-d\TH:i:s.up'),
            'last_login_ip' => $this->last_login_ip,
            'to_be_logged_out' => $this->to_be_logged_out,
            'profile_photo_url' => $this->profile_photo_url,
        ];

        return json_encode($data);
    }

    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): User
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getEmailVerifiedAt(): ?\DateTime
    {
        return $this->email_verified_at;
    }

    public function setEmailVerifiedAt(?\DateTime $email_verified_at): User
    {
        $this->email_verified_at = $email_verified_at;
        return $this;
    }

    public function getPasswordChangedAt(): ?\DateTime
    {
        return $this->password_changed_at;
    }

    public function setPasswordChangedAt(?\DateTime $password_changed_at): User
    {
        $this->password_changed_at = $password_changed_at;
        return $this;
    }

    public function getActive(): int
    {
        return $this->active;
    }

    public function setActive(int $active): User
    {
        $this->active = $active;
        return $this;
    }

    public function getLastLoginAt(): ?\DateTime
    {
        return $this->last_login_at;
    }

    public function setLastLoginAt(?\DateTime $last_login_at): User
    {
        $this->last_login_at = $last_login_at;
        return $this;
    }

    public function getLastLoginIp(): ?string
    {
        return $this->last_login_ip;
    }

    public function setLastLoginIp(?string $last_login_ip): User
    {
        $this->last_login_ip = $last_login_ip;
        return $this;
    }

    public function isToBeLoggedOut(): bool
    {
        return $this->to_be_logged_out;
    }

    public function setToBeLoggedOut(bool $to_be_logged_out): User
    {
        $this->to_be_logged_out = $to_be_logged_out;
        return $this;
    }

    public function getProfilePhotoUrl(): ?string
    {
        return $this->profile_photo_url;
    }

    public function setProfilePhotoUrl(?string $profile_photo_url): User
    {
        $this->profile_photo_url = $profile_photo_url;
        return $this;
    }

    public function getTwoFactorSecret(): ?string
    {
        return $this->twoFactorSecret;
    }

    public function setTwoFactorSecret(?string $twoFactorSecret): User
    {
        $this->twoFactorSecret = $twoFactorSecret;
        return $this;
    }

    public function getTwoFactorConfirmed(): ?string
    {
        return $this->twoFactorConfirmed;
    }

    public function setTwoFactorConfirmed(?string $twoFactorConfirmed): user
    {
        $this->twoFactorConfirmed = $twoFactorConfirmed;
        return $this;
    }

    public function getTwoFactorRecoveryCodes(): ?string
    {
        return $this->twoFactorRecoveryCodes;
    }

    public function setTwoFactorRecoveryCodes(?string $twoFactorRecoveryCodes): user
    {
        $this->twoFactorRecoveryCodes = $twoFactorRecoveryCodes;
        return $this;
    }

    public function getTwoFactorQrCodeSvg(): ?string
    {
        return $this->twoFactorQrCodeSvg;
    }

    public function setTwoFactorQrCodeSvg(?string $twoFactorQrCodeSvg): user
    {
        $this->twoFactorQrCodeSvg = $twoFactorQrCodeSvg;
        return $this;
    }

    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function setTokens(array $tokens): User
    {
        $this->tokens = $tokens;
        return $this;
    }

    /**
     * Summary of hasRole
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        $hasRole = false;
        foreach ($this->getRoles() as $userRole) {
            if ($userRole->getName() === $role || $userRole->getId() == $role) {
                $hasRole = true;
                break;
            }
        }

        return $hasRole;
    }

    /**
     * @return Role[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return Permission[]
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): User
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }

    public function setUserDetails(?UserDetails $userDetails): User
    {
        $this->userDetails = $userDetails;
        return $this;
    }

    /**
     * Summary of getNotes
     * @return array
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * Summary of setNotes
     * @param array $notes
     * @return \App\Domains\Auth\Models\User
     */
    public function setNotes(array $notes): User
    {
        $this->notes = $notes;
        return $this;
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }

    public function setExtraData(array $extraData): User
    {
        $this->extraData = $extraData;
        return $this;
    }

    public function getExtraDataIds(): array
    {
        return $this->extraDataIds;
    }

    public function setExtraDataIds(array $extraDataIds): User
    {
        $this->extraDataIds = $extraDataIds;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * @param array|null $files
     */
    public function setFiles(?array $files): User
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMorphables(): ?array
    {
        return $this->morphables;
    }

    /**
     * @param array|null $morphables
     * @return User
     */
    public function setMorphables(?array $morphables): User
    {
        $this->morphables = $morphables;
        return $this;
    }

    public static function fromRequest(Request $request): User
    {
        $user = new User();
        return $user
            ->setUuid(PackageUuid::uuid4()->toString())
            ->setName($request['name'])
            ->setEmail($request['email'])
            ->setPassword($request['password'])
            ->setEmailVerifiedAt($request['email_verified_at'])
            ->setPasswordChangedAt($request['password_changed_at'])
            ->setActive($request['active'])
            ->setLastLoginAt($request['last_login_at'])
            ->setLastLoginIp($request['last_login_ip'])
            ->setToBeLoggedOut($request['to_be_logged_out'])
            ->setProfilePhotoUrl($request['profile_photo_path'])
            ->setExtraDataIds($extraDataIds ?? []);
    }

    /**
     * @return array|null
     */
    public static function morphBuilder(): ?array
    {
        $user = new User();
        return $user->getMorphables();
    }
}
