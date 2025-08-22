<?php

namespace App\Domains\Notifications\Models;

class Recipient
{
    private ?string $id = null;
    private string $email;
    private string $name;
    private ?string $phone;


    /**
     * @param string $email
     * @param string $name
     * @param string|null $phone
     */
    public function __construct(string $email, string $name, string $phone = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->phone = $phone;
    }

    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    public function setId(?string $id): Recipient
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }


}
