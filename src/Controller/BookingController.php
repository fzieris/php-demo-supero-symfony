<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends Controller {
    /**
     * @Route("/buchen", name="buchen")
     * @Template
     */
    public function new() {
    }
}
