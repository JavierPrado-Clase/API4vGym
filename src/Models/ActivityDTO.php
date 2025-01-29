<?php

namespace App\Models;

use DateTime;

class ActivityDTO
{
    public function __construct(
        public int $id,
        public ActivityTypeDTO $activityType,
        public DateTime $dateStart,
        public DateTime $dateEnd,
        public array $monitors){}
}