<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\User;

class MainController extends AbstractController
{
    /**
+      * @Route("/", name="welcome_page")
+      */
    public function index(Request $request, SessionInterface $session)
    {
        if ($this->getDoctrine()->getRepository(User::class)->findAll() == null) {
            $session->invalidate();
        }

        if ($request->hasPreviousSession()) {
            if ($session->get('user') != null) {
                return $this->render('welcome.html.twig', array(
                    'user' => $session->get('user'),
                    'spellCount' => count($this
                        ->getDoctrine()
                        ->getRepository(User::class)
                        ->findOneBy(['username' => $session->get('user')->getUsername()])
                        ->getSpells())
                ));
            } else {
                $session->invalidate();
                return $this->redirectToRoute('login_page');
            }
        } else {
            return $this->redirectToRoute('login_page');
        }
    }
}