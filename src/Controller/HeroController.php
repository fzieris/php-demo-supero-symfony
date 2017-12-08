<?php

namespace App\Controller;

use App\Entity\Hero;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HeroController extends Controller {
    /**
     * @Route("/helden", name="helden_liste")
     * @Template
     */
    public function overview(EntityManagerInterface $doctrine) {
        return ['heros' => $doctrine->getRepository('App:Hero')->findAll()];
    }

    /**
     * @Route("/helden/{name}", name="helden_details")
     * @Template
     */
    public function steckbrief(Hero $hero) {
    }
}
