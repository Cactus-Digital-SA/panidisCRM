<?php

namespace App\Domains\Files\Models;

use App\Domains\Leads\Models\LeadStatus;
use App\Models\CactusEntity;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class File extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var string $name
     * @JMS\Serializer\Annotation\SerializedName("name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $name;

    /**
     * @var string $path
     * @JMS\Serializer\Annotation\SerializedName("path")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $path;

    /**
     * @var string $fileName
     * @JMS\Serializer\Annotation\SerializedName("file_name")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $fileName;

    /**
     * @var string $mimeType
     * @JMS\Serializer\Annotation\SerializedName("mime_type")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $mimeType;

    /**
     * @var string $extension
     * @JMS\Serializer\Annotation\SerializedName("extension")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $extension;

    /**
     * @var int $size
     * @JMS\Serializer\Annotation\SerializedName("size")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $size;

    /**
     * @var ?int $uploadedBy
     * @JMS\Serializer\Annotation\SerializedName("uploaded_by")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $uploadedBy;

    /**
     * @var int|null $statusId
     * @JMS\Serializer\Annotation\SerializedName("lead_status_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $statusId;

    /**
     * @var DateTime|null $createdAt
     * @JMS\Serializer\Annotation\SerializedName("created_at")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d\TH:i:s.up'>")
     */
    private ?DateTime $createdAt;

    /**
     * @var ?User $user
     * @JMS\Serializer\Annotation\SerializedName("user")
     * @JMS\Serializer\Annotation\Type("App\Models\User")
     */
    private ?User $user = null;

    /**
     * @var ?LeadStatus $leadStatus
     * @JMS\Serializer\Annotation\SerializedName("lead_status")
     * @JMS\Serializer\Annotation\Type("App\Domains\Leads\Models\LeadStatus")
     */
    private ?LeadStatus $leadStatus = null;

    /**
     * Summary of getValues
     * @param bool $withRelations
     * @return array
     */
    public function getValues(bool $withRelations = true): array
    {

        $data = [
            'name' => $this->getId(),
            'path' => $this->getPath(),
            'fileName' => $this->getFileName(),
            'mimeType' => $this->getMimeType(),
            'extension' => $this->getExtension(),
            'size' => $this->getSize(),
            'uploadedBy' => $this->getUploadedBy(),
        ];

        if ($withRelations) {
            $data['user'] = $this->getUser();
        }

        return $data;
    }

    /**
     * Summary of getId
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Summary of setId
     * @param int $id
     * @return \App\Domains\Files\Models\File
     */
    public function setId(int $id): File
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Summary of getName
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Summary of setName
     * @param string $name
     * @return \App\Domains\Files\Models\File
     */
    public function setName(string $name): File
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Summary of getPath
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Summary of setPath
     * @param string $path
     * @return \App\Domains\Files\Models\File
     */
    public function setPath(string $path): File
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Summary of getFileName
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Summary of setFileName
     * @param string $fileName
     * @return \App\Domains\Files\Models\File
     */
    public function setFileName(string $fileName): File
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Summary of getMimeType
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Summary of setMimeType
     * @param string $mimeType
     * @return \App\Domains\Files\Models\File
     */
    public function setMimeType(string $mimeType): File
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Summary of getExtension
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Summary of setExtension
     * @param string $extension
     * @return \App\Domains\Files\Models\File
     */
    public function setExtension(string $extension): File
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * Summary of getSize
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Summary of setSize
     * @param int $size
     * @return \App\Domains\Files\Models\File
     */
    public function setSize(int $size): File
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Summary of getUploadedBy
     * @return int|null
     */
    public function getUploadedBy(): ?int
    {
        return $this->uploadedBy ?? null;
    }

    /**
     * Summary of setUploadedBy
     * @param int $uploadedBy
     * @return \App\Domains\Files\Models\File
     */
    public function setUploadedBy(int $uploadedBy): File
    {
        $this->uploadedBy = $uploadedBy;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Summary of setUser
     * @param \App\Models\User $userId
     * @return \App\Domains\Files\Models\File
     */
    public function setUser(User $userId): File
    {
        $this->user = $userId;
        return $this;
    }

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    public function setStatusId(?int $statusId): File
    {
        $this->statusId = $statusId;
        return $this;
    }

    public function getLeadStatus(): ?LeadStatus
    {
        return $this->leadStatus;
    }

    public function setLeadStatus(?LeadStatus $leadStatus): File
    {
        $this->leadStatus = $leadStatus;
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): File
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Summary of fromRequest
     * @param UploadedFile $file
     * @return File
     */
    public static function fromRequest(UploadedFile $file): File
    {
        $fileDTO = new File();
        return $fileDTO
            ->setName($file->hashName())
            ->setPath($file->storeAs('files',$fileDTO->getName()))
            ->setFileName($file->getClientOriginalName())
            ->setMimeType($file->getMimeType())
            ->setExtension($file->extension())
            ->setSize($file->getSize());
    }
}
