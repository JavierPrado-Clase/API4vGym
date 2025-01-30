<?php

namespace App\Models;

class ActivityTypeDTO
{

    public function __construct(
        public int $id,
        public string $name,
        public int $requiredMonitors){}
    
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRequiredMonitors(): int
    {
        return $this->requiredMonitors;
    }

    public function setRequiredMonitors(int $requiredMonitors): void
    {
        $this->requiredMonitors = $requiredMonitors;
    }
}
