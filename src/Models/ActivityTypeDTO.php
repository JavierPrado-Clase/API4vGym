<?php

namespace App\Models;

class ActivityTypeDTO
{

    public function __construct(
        public int $id,
        public string $name,
        public int $requiredMonitors){}
}