<?php

namespace App\Controller\Registration;

use App\Entity\User;
use App\Entity\Spell;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Length;

class SigningController extends AbstractController
{
    /**
     * @Route("/registration", name="registration_page")
     * Method({"GET", "POST"})
     */
    public function RegistrationForm(Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            ?><script>
                alert('You are already logged!');
                window.location.replace("/");
                </script><?php
                    } 

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Password',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ),
                'second_options' => array(
                    'label' => 'Repeat Password',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ),

            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('nickname', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('gender', ChoiceType::class, array(
                'choices' => array(
                    'male' => 'male',
                    'female' => 'female'
                ),
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Register',
                'attr' => array(
                    'class' => 'btn btn-primary mt-3'
                )
            ))->add('register', ButtonType::class, array(
                'label' => 'Login',
                'attr' => array(
                    'class' => 'btn btn-primary mt-3',
                    'onclick' => 'login()'
                )
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            ?> <script>
            alert('Registration successful!');
            </script><?php

            return $this->redirectToRoute('welcome_page');
        }

        return $this->render('registration/signing.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Register'
        ));
    }

    /**
     * @Route("/login", name="login_page")
     * Method({"GET", "POST"})
     */
    public function Login(Request $request, SessionInterface $session)
    {
        if ($session->get('user') != null) {
            ?><script>
                alert('You are already logged!');
                window.location.replace("/");
                </script><?php
                    }

        $form = $this->createFormBuilder([])
            ->add('username', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('password', PasswordType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('sign', SubmitType::class, array(
                'label' => 'Login',
                'attr' => array(
                    'class' => 'btn btn-primary mt-3'
                )
            ))
            ->add('register', ButtonType::class, array(
                'label' => 'Register',
                'attr' => array(
                    'class' => 'btn btn-primary mt-3',
                    'onclick' => 'register()'
                )
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userDB = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $form->get('username')->getData()]);

            if ($userDB != null) {
                if (password_verify($form->get('password')->getData(), $userDB->getPassword())) {
                    $session->set('user', $userDB);
                    return $this->redirectToRoute('welcome_page');
                } else {
                    return new Response("Incorrect password.");
                }
            } else {
                return new Response("Incorrect login.");
            }
        }

        return $this->render('registration/signing.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Sign In'
        ));
    }

    /**
     * @Route("/logout", name="logout_page")
     */
    public function Logout(SessionInterface $session)
    {
        try {
            $session->invalidate();
        } catch (\Throwable $th) {
            ?>
            <script>alert('Session not found!');</script>
            <?php

        }
        return $this->redirectToRoute('welcome_page');
    }

}