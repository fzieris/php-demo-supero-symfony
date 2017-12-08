<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Bedingung, die anzeigt dass ein Held buchbar sein muss.
 *
 * (Die Angabe von '@Annotation' wäre nicht zwingend nötig, da es aktuell keine
 * Entity gibt, für die diese Bedingung gilt.)
 *
 * @Annotation
 */
class Bookable extends Constraint {
    public $message = "{{ name }} is currently not available for booking.";
}
