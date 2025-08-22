<?php

namespace App\Domains\Notes\Models;

use App\Domains\Auth\Models\User;
use App\Models\CactusEntity;
use DateTime;
use Illuminate\Http\Request;

class Note extends CactusEntity
{
    /**
     * @var int $id
     * @JMS\Serializer\Annotation\SerializedName("id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $id;

    /**
     * @var int|null $userId
     * @JMS\Serializer\Annotation\SerializedName("user_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private ?int $userId;

    /**
     * @var ?User $user
     * @JMS\Serializer\Annotation\SerializedName("user")
     * @JMS\Serializer\Annotation\Type("App\Domains\Auth\Models\User")
     */
    private ?User $user;

    /**
     * @var string $content
     * @JMS\Serializer\Annotation\SerializedName("content")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private string $content;

    /**
     * @var \DateTime|null $createdAt
     * @JMS\Serializer\Annotation\SerializedName("created_at")
     * @JMS\Serializer\Annotation\Type("DateTime<'Y-m-d\TH:i:s.up'>")
     */
    private ?\DateTime $createdAt = null;

    /**
     * @var string|null $notableType
     * @JMS\Serializer\Annotation\SerializedName("notable_type")
     * @JMS\Serializer\Annotation\Type("string")
     */
    private ?string $notableType;

    /**
     * @var int $notableId
     * @JMS\Serializer\Annotation\SerializedName("notable_id")
     * @JMS\Serializer\Annotation\Type("int")
     */
    private int $notableId;


    /**
     * @param bool $withRelations
     * @return array{id: int, user: User|null, content: string}
     */
    public function getValues(bool $withRelations = true): array
    {
        $data = [
            'id' => $this->id,
            'content' => $this->content,
            'userId' => $this->userId,
            'notableType' => $this->notableType,
            'notableId' => $this->notableId
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
     * @return \App\Domains\Notes\Models\Note
     */
    public function setId(int $id): Note
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Summary of getContent
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Summary of setContent
     * @param string $content
     * @return \App\Domains\Notes\Models\Note
     */
    public function setContent(string $content): Note
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): Note
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Summary of getUserId
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId ?? null;
    }

    /**
     * Summary of setUserId
     * @param mixed $userId
     * @return \App\Domains\Notes\Models\Note
     */
    public function setUserId(?int $userId): Note
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Summary of getUser
     * @return \App\Domains\Auth\Models\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Summary of setUser
     * @param \App\Domains\Auth\Models\User $userId
     * @return \App\Domains\Notes\Models\Note
     */
    public function setUser(User $userId): Note
    {
        $this->user = $userId;

        return $this;
    }

    /**
     * Summary of getNotableType
     * @return string
     */
    public function getNotableType(): string
    {
        return $this->notableType;
    }

    /**
     * Summary of setNotableType
     * @param string $notableType
     * @return \App\Domains\Notes\Models\Note
     */
    public function setNotableType(string $notableType): Note
    {
        $this->notableType = $notableType;

        return $this;
    }

    /**
     * Summary of getNotableId
     * @return int
     */
    public function getNotableId(): int
    {
        return $this->notableId;
    }

    /**
     * Summary of setNotableId
     * @param int $notableId
     * @return \App\Domains\Notes\Models\Note
     */
    public function setNotableId(int $notableId): Note
    {
        $this->notableId = $notableId;

        return $this;
    }

    /**
     * Summary of fromRequest
     * @param \Illuminate\Http\Request $request
     * @return \App\Domains\Notes\Models\Note
     */
    public static function fromRequest(Request $request): Note
    {
        $note = new Note();
        return $note
            ->setContent($request['content'] ?? '')
            ->setNotableId($request['notableId'])
            ->setNotableType($request['notableType']);
    }
}
