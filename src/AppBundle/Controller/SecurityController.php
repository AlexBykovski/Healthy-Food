<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserRegistrationType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     *
     * @@todo need to check and complete validation
     */
    public function registrationAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $adminRole = $this->getDoctrine()->getRepository("AppBundle:Role")->findOneBy(["role" => "ROLE_ADMIN"]);
            /** @var Role $simpleUserRole */
            $simpleUserRole = $this->getDoctrine()->getRepository("AppBundle:Role")->findOneBy(["role" => "ROLE_SIMPLE_USER"]);

            $user->setPassword($password);
            //$user->addUserRole($adminRole);
            $user->addUserRole($simpleUserRole);
            $user->setCreatedAt(new DateTime());
            $user->refreshUpdated();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            return $this->redirectToRoute("eating_list", ["date" => (new DateTime())->format("d-m-Y")]);
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     *
     * @@todo need to check and complete validation
     */
    public function loginAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'security/login.html.twig',
            [
                "error" => $this->get('security.authentication_utils')->getLastAuthenticationError(),
            ]
        );
    }
}