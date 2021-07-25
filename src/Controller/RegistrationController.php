<?php


namespace App\Controller;


use App\Form\UserRegistrationForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function index(Request $request): Response
    {

        $form = $this->createForm(UserRegistrationForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            throw new \Exception('Not implemented');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}