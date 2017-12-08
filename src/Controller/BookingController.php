<?php

namespace App\Controller;

use App\Entity\Hero;
use App\Form\BookHeroType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends Controller {
    /**
     * @Route("/buchen/{name}", name="buchen", defaults={"name"=null})
     * @ParamConverter(isOptional="true")
     * @Template
     */
    public function new(Request $request, Hero $hero = null) {
        // Über die URL kann man optional einen Wunschhelden angeben,
        // und dieser wird als Start-Wert übernommen
        $form = $this->createForm(BookHeroType::class, ['hero' => $hero], [
            // Beim Formular-Submit brauchen wir diesen Start-Wert nicht mehr,
            // sondern können den Controller ganz normal aufrufen.
            'action' => $this->generateUrl('buchen'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking = $form->getData();
            // (... hier müsste man noch eine E-Mail versenden, o.ä.)
            return $this->render('booking/success.html.twig', ['booking' => $booking]);
        }

        return ['contact_form' => $form->createView()];
    }
}
