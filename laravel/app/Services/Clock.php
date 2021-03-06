<?php declare(strict_types=1);

namespace App\Services;

use DateTime;

class Clock
{
    public function createFromTimestamp(int $timestamp): DateTime
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);

        return $date;
    }
}
