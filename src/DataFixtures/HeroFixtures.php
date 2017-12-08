<?php

namespace App\DataFixtures;

use App\Entity\Hero;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class HeroFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        $heros = array(
            new Hero("Batman", "100 €/h", "ausgebucht",
                "Nachtaktiv und günstig im Doppelpack mit Robin. " .
                "Gutes Gehör für hohe Töne, singt im Heldenchor aber im Bass."),
            new Hero("Flash", "10 €/sec", "fast ausgebucht",
                "Ihr Held für eilige Einsätze. " .
                "Jetzt neu: Sekundengenaue Abrechnung für Ihren Auftrag."),
            new Hero("Robin", "50 €/h", "verfügbar",
                "Jung und dynamisch. Guter Freund von Batman. " .
                "Trägt gerne Strumpfhosen, vor allem im Winter."),
            new Hero("Batgirl"),
            new Hero("Groot"),
            new Hero("Joker"),
            new Hero("Supergirl"),
            new Hero("Superman"),
            new Hero("Thor"),
        );

        foreach ($heros as $hero) {
            $manager->persist($hero);
        }
        $manager->flush();
    }
}
