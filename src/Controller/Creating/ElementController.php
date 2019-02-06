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

use App\Entity\Element;
use App\Entity\User;

class ElementController extends AbstractController
{
    /**
     * @Route("/element/create", name="element_creator")
     */
    public function CreateElement(Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $sessionData = $session->get('user');
            $element = new Element();

            $form = $this->createFormBuilder($element)
                ->add('name', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('mechanics', TextAreaType::class, array(
                    'label' => 'Mechanics (How the element works)',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('origin', TextAreaType::class, array(
                    'label' => 'Origin (How has the element borned)',
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
                        'onclick' => 'elements()'
                    )
                ))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $element = $form->getData();
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $sessionData->getUsername()]);

                $user->addElement($element);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($element);
                $entityManager->flush();

                return $this->redirectToRoute('user_elements');
            }

            return $this->render('creation/creating.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/show/elements", name="user_elements")
     */
    public function ShowAllElements(Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {

            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $session->get('user')->getUsername()]);

            if (count($user->getElements()) == 0) {
                return $this->render('element/showAllElements.html.twig', array(
                    'user' => $user,
                    'elementsCount' => 0
                ));
            } else {
                return $this->render('element/showAllElements.html.twig', array(
                    'user' => $user,
                    'elements' => $user->getElements(),
                    'elementsCount' => 1
                ));
            }
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/element/delete/{id}", name="delete_element")
     */
    public function DeleteElement($id, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $element = $this->getDoctrine()->getRepository(Element::class)->find($id);

            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($element);
                $entityManager->flush();
            } catch (\Throwable $th) {
                throw new Exception("Error: " . $th);
            }

            return $this->redirectToRoute("user_elements");
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/element/show/{id}", name="show_element")
     */
    public function ShowElement($id, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $element = $this->getDoctrine()->getRepository(Element::class)->find($id);

            if ($element != null) {
                return $this->render('element/showElement.html.twig', array(
                    'element' => $element
                ));
            }
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/element/edit/{id}", name="edit_element")
     */
    public function EditElement($id, Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $element = $this->getDoctrine()->getRepository(Element::class)->find($id);

            $form = $this->createFormBuilder($element)
                ->add('name', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('mechanics', TextAreaType::class, array(
                    'label' => 'Mechanics (How the element works)',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('origin', TextAreaType::class, array(
                    'label' => 'Origin (How has the element borned)',
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
                        'onclick' => 'elements()'
                    )
                ))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $element = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('user_elements');
            }

            return $this->render('creation/creating.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
            return $this->redirectToRoute('login_page');
        }
    }
}
