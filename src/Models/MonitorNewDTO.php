<?php

namespace App\Models;

class MonitorNewDTO
{
    public function __construct(
        #[Assert\NotBlank] public int $id,
        #[Assert\NotBlank] public string $name,
        #[Assert\NotBlank] public string $email,
        #[Assert\NotBlank] public int $phoneNumber,
        #[Assert\NotBlank] public string $image){}
}