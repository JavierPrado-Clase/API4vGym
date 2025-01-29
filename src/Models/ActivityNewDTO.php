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
}