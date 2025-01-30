<?php

namespace App\Models;

use DateTime;

class ActivityNewDTO
{
    public function __construct(
        #[Assert\NotBlank] public int $id,
        #[Assert\NotBlank] public ActivityTypeDTO $activityType,
        #[Assert\NotBlank] public DateTime $dateStart,
        #[Assert\NotBlank] public DateTime $dateEnd,
        #[Assert\NotBlank] public array $monitors){}
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDateStart(): ?DateTime
    {
        return $this->dateStart;
    }

    public function setDateStart(?DateTime $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    public function getDateEnd(): ?DateTime
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?DateTime $dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }

    public function getActivityType(): ActivityTypeDTO
    {
        return $this->activityType;
    }

    public function setActivityType(ActivityTypeDTO $activityType): void
    {
        $this->activityType = $activityType;
    }

    public function getMonitors(): array
    {
        return $this->monitors;
    }

    public function setMonitors(array $monitors): void
    {
        $this->monitors = $monitors;
    }

}