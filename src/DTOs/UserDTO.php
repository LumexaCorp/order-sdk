<?php

declare(strict_types=1);

readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $email,
        public string $first_name,
        public string $last_name,
        public string $phone,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            email: $data['email'],
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            phone: $data['phone'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
        ];
    }
}
