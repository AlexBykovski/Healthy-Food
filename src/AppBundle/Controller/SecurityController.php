<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        /*var_dump($form->isSubmitted() );
        var_dump($form->isValid() );die;*/
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->redirectToRoute("homepage");
        }
        // replace this example code with whatever you need
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}