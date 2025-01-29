<?php

namespace App\Models;

class MonitorDTO
{
    public function __construct(public int $id,
        public string $name,
        public string $email,
        public int $phoneNumber,
        public string $image){}
}