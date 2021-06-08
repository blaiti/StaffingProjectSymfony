<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/editProfile/{id}", name="editProfile")
     * @return RedirectResponse
     */
    public function editProfile(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find(27);

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if(($form->isSubmitted()) && ($form->isValid())) {
            $user->setName($form->get('name')->getData());
            $user->setEmail($form->get('Email')->getData());
            $user->setPassword($form->get('Password')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('managers');
        }

        return $this->render('profile/editProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
