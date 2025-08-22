<?php

namespace App\Domains\Notifications\Models;

class Sender
{
    private string $email;
    private string $name;
    private ?string $phone = null;

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

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}
