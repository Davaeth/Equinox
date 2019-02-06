<?php

namespace App\Controller\Creating;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Hero;
use App\Entity\User;

class HeroController extends AbstractController
{
    /**
     * @Route("/hero/create", name="hero_creator")
     */
    public function CreateHero(Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $sessionData = $session->get('user');
            $hero = new Hero();

            $form = $this->createFormBuilder($hero)
                ->add('name', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('title', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('firstElement', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('secondElement', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('appearance', TextAreaType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('personality', TextAreaType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Create',
                    'attr' => array(
                        'class' => 'btn btn-primary mt-3'
                    )
                ))
                ->add('return', ButtonType::class, array(
                    'label' => 'Return',
                    'attr' => array(
                        'class' => 'btn btn-primary mt-3',
                        'onclick' => 'heroes()'
                    )
                ))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $hero = $form->getData();
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $sessionData->getUsername()]);

                $user->addHero($hero);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($hero);
                $entityManager->flush();

                return $this->redirectToRoute('user_heroes');
            }

            return $this->render('creation/creating.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/show/heroes", name="user_heroes")
     */
    public function ShowAllHeroes(Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {

            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $session->get('user')->getUsername()]);

            if (count($user->getHeroes()) == 0) {
                return $this->render('hero/showAllHeroes.html.twig', array(
                    'user' => $user,
                    'heroesCount' => 0
                ));
            } else {
                return $this->render('hero/showAllHeroes.html.twig', array(
                    'user' => $user,
                    'heroes' => $user->getHeroes(),
                    'heroesCount' => 1
                ));
            }
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/hero/delete/{id}", name="delete_hero")
     */
    public function DeleteHero($id, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $hero = $this->getDoctrine()->getRepository(Hero::class)->find($id);

            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($hero);
                $entityManager->flush();
            } catch (\Throwable $th) {
                throw new Exception("Error: " . $th);
            }

            return $this->redirectToRoute("user_heroes");
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/hero/show/{id}", name="show_hero")
     */
    public function ShowHero($id, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $hero = $this->getDoctrine()->getRepository(Hero::class)->find($id);

            if ($hero != null) {
                return $this->render('hero/showHero.html.twig', array(
                    'hero' => $hero
                ));
            }
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/hero/edit/{id}", name="edit_hero")
     */
    public function EditHero($id, Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $hero = $this->getDoctrine()->getRepository(Hero::class)->find($id);

            $form = $this->createFormBuilder($hero)
                ->add('name', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('title', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('firstElement', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('secondElement', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('appearance', TextAreaType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('personality', TextAreaType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Change',
                    'attr' => array(
                        'class' => 'btn btn-primary mt-3'
                    )
                ))
                ->add('return', ButtonType::class, array(
                    'label' => 'Return',
                    'attr' => array(
                        'class' => 'btn btn-primary mt-3',
                        'onclick' => 'heroes()'
                    )
                ))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $hero = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('user_heroes');
            }

            return $this->render('creation/creating.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
            return $this->redirectToRoute('login_page');
        }
    }
}
