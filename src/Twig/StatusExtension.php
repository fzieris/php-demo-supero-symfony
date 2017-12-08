<?php

namespace App\Twig;

use App\Entity\Availability;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig-Filter 'bootstrap', um Verfügbarkeiten in Bootstrap-CSS-Klassen umzuwandeln
 */
class StatusExtension extends AbstractExtension {
    public function getFilters() {
        return [new TwigFilter('bootstrap', [$this, 'status2css'])];
    }

    public function status2css($status) {
        $statusClasses = [
            Availability::AVAILABLE => 'success',
            Availability::ALMOST_FULLY_BOOKED => 'warning',
            Availability::FULLY_BOOKED => 'danger',
        ];

        if (in_array($status, array_keys($statusClasses))) {
            return $statusClasses[$status];
        }
        return '';
    }
}
