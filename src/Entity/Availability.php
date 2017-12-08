<?php
namespace App\Entity;

abstract class Availability {
    const AVAILABLE = "verfügbar";
    const ALMOST_FULLY_BOOKED = "fast ausgebucht";
    const FULLY_BOOKED = "ausgebucht";

    public static function getStates() {
        return [
            self::AVAILABLE,
            self::ALMOST_FULLY_BOOKED,
            self::FULLY_BOOKED,
        ];
    }
}
