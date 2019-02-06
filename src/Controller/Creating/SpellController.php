<?php

namespace App\Controller\Creating;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Spell;
use App\Entity\User;

class SpellController extends AbstractController
{
    /**
     * @Route("/spell/create", name="spell_creator")
     */
    public function CreateSpell(Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $sessionData = $session->get('user');
            $spell = new Spell();

            $form = $this->createFormBuilder($spell)
                ->add('name', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('element', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('rarity', ChoiceType::class, array(
                    'choices' => array(
                        'Basic' => 'Basic',
                        'Advanced' => 'Advanced',
                        'Epic' => 'Epic',
                        'Ultimate' => 'Ultimate'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('damage', IntegerType::class, array(
                    'label' => 'Damage (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('shield', IntegerType::class, array(
                    'label' => 'Shield (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('resistance', IntegerType::class, array(
                    'label' => 'Resistance (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('heal', IntegerType::class, array(
                    'label' => 'Heal (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('count', IntegerType::class, array(
                    'label' => 'Count (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('speed', ChoiceType::class, array(
                    'label' => 'Speed (for a shield choose "normal")',
                    'choices' => array(
                        'very slow' => 'very slow',
                        'slow' => 'slow',
                        'normal' => 'normal',
                        'fast' => 'fast',
                        'very fast' => 'very fast'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('hitType', ChoiceType::class, array(
                    'choices' => array(
                        'On Hit' => 'On Hit',
                        'Over Time' => 'Over Time',
                        'On Cast' => 'On Cast',
                        'On Timeout' => 'On Timeout',
                        'On Block' => 'On Block',
                        'On Break' => 'On Break'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('description', TextAreaType::class, array(
                    'attr' => array(
                        'class' => 'form-control form-primary'
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
                        'onclick' => 'spells()'
                    )
                ))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $spell = $form->getData();
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $sessionData->getUsername()]);

                if ($spell->getDamage() < 0
                    || $spell->getShield() < 0
                    || $spell->getResistance() < 0
                    || $spell->getHeal() < 0) {
                    $spell->setDamage(abs($spell->getDamage()));
                    $spell->setShield(abs($spell->getShield()));
                    $spell->setResistance(abs($spell->getResistance()));
                    $spell->setHeal(abs($spell->getHeal()));
                }

                $user->addSpell($spell);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($spell);
                $entityManager->flush();

                return $this->redirectToRoute('user_spells');
            }

            return $this->render('creation/creating.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/show/spells", name="user_spells")
     */
    public function ShowAllSpells(Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {

            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $session->get('user')->getUsername()]);

            if (count($user->getSpells()) == 0) {
                return $this->render('spell/showAllSpells.html.twig', array(
                    'user' => $user,
                    'spellsCount' => 0
                ));
            } else {
                return $this->render('spell/showAllSpells.html.twig', array(
                    'user' => $user,
                    'spells' => $user->getSpells(),
                    'spellsCount' => 1
                ));
            }
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/spell/delete/{id}", name="delete_spell")
     */
    public function DeleteSpell($id, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $spell = $this->getDoctrine()->getRepository(Spell::class)->find($id);

            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($spell);
                $entityManager->flush();
            } catch (\Throwable $th) {
                throw new Exception("Error: " . $th);
            }

            return $this->redirectToRoute("user_spells");
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/spell/show/{id}", name="show_spell")
     */
    public function ShowSpell($id, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $spell = $this->getDoctrine()->getRepository(Spell::class)->find($id);

            if ($spell != null) {
                return $this->render('spell/showSpell.html.twig', array(
                    'spell' => $spell
                ));
            }
        } else {
            return $this->redirectToRoute('login_page');
        }
    }

    /**
     * @Route("/spell/edit/{id}", name="edit_spell")
     */
    public function EditSpell($id, Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            $spell = $this->getDoctrine()->getRepository(Spell::class)->find($id);

            $form = $this->createFormBuilder($spell)
                ->add('name', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('element', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('rarity', ChoiceType::class, array(
                    'choices' => array(
                        'Basic' => 'Basic',
                        'Advanced' => 'Advanced',
                        'Epic' => 'Epic',
                        'Ultimate' => 'Ultimate'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('damage', IntegerType::class, array(
                    'label' => 'Damage (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('shield', IntegerType::class, array(
                    'label' => 'Shield (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('resistance', IntegerType::class, array(
                    'label' => 'Resistance (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('heal', IntegerType::class, array(
                    'label' => 'Heal (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('count', IntegerType::class, array(
                    'label' => 'Count (leave blank if 0)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('speed', ChoiceType::class, array(
                    'label' => 'Speed (for a shield choose "normal")',
                    'choices' => array(
                        'very slow' => 'very slow',
                        'slow' => 'slow',
                        'normal' => 'normal',
                        'fast' => 'fast',
                        'very fast' => 'very fast'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('hitType', ChoiceType::class, array(
                    'choices' => array(
                        'On Hit' => 'On Hit',
                        'Over Time' => 'Over Time',
                        'On Cast' => 'On Cast',
                        'On Timeout' => 'On Timeout',
                        'On Block' => 'On Block',
                        'On Break' => 'On Break'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('description', TextAreaType::class, array(
                    'attr' => array(
                        'class' => 'form-control form-primary'
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
                        'onclick' => 'spells()'
                    )
                ))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $spell = $form->getData();

                if ($spell->getDamage() < 0
                    || $spell->getShield() < 0
                    || $spell->getResistance() < 0
                    || $spell->getHeal() < 0) {
                    $spell->setDamage(abs($spell->getDamage()));
                    $spell->setShield(abs($spell->getShield()));
                    $spell->setResistance(abs($spell->getResistance()));
                    $spell->setHeal(abs($spell->getHeal()));
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('user_spells');
            }

            return $this->render('creation/creating.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
            return $this->redirectToRoute('login_page');
        }
    }
}
