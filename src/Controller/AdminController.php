<?php
namespace App\Controller;

use App\Entity\Hero;
use App\Form\HeroType;
use App\Repository\HeroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller {
    /**
     * @Route("/admin", name="admin")
     */
    public function index() {
        return $this->redirectToRoute('admin-list-heros');
    }

    /**
     * @Route("/admin/list-heros", name="admin-list-heros")
     * @Template
     */
    public function listHeros(EntityManagerInterface $doctrine) {
        return ['heros' => $doctrine->getRepository('App:Hero')->findAll()];
    }

    /**
     * @Route("/admin/add-hero", name="admin-add-hero")
     * @Template
     */
    public function addHero(Request $request, EntityManagerInterface $doctrine) {
        $hero = new Hero("Neuer Held");
        $form = $this->createForm(HeroType::class, $hero);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hero = $form->getData();

            $doctrine->persist($hero);
            $doctrine->flush();

            $this->addFlash('notice', 'Held ' . $hero->getName() . ' erfolgreich hinzugefügt.');

            return $this->redirectToRoute('admin-list-heros');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/edit-hero/{id}", name="admin-edit-hero")
     * @Template
     */
    public function editHero(Request $request, Hero $hero, EntityManagerInterface $doctrine) {
        $form = $this->createForm(HeroType::class, $hero);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hero = $form->getData();

            $doctrine->persist($hero);
            $doctrine->flush();

            $this->addFlash('notice', 'Held ' . $hero->getName() . ' erfolgreich beabeitet.');

            return $this->redirectToRoute('admin-list-heros');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/delete-hero/{id}", name="admin-delete-hero")
     */
    public function deleteHero(Hero $hero, EntityManagerInterface $doctrine) {
        $doctrine->remove($hero);
        $doctrine->flush();

        $this->addFlash('notice', 'Held ' . $hero->getName() . ' gelöscht!');

        return $this->redirectToRoute('admin-list-heros');
    }
}