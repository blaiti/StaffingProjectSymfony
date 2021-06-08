<?php

namespace App\Controller;

use App\Entity\Consultants;
use App\Entity\User;
use App\Form\UserFormType;
use App\Form\EditUserFormType;
use App\Form\ConsultantFormType;
use App\Repository\UserRepository;
use App\Repository\ConsultantsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ConsultantController extends AbstractController
{
    /**
     * @Route("/consultants", name="consultants")
     */
    public function consultants(UserRepository $userRepository,
                          ConsultantsRepository $consultantsRepository,
                          Request $request,): Response
    {
        $user = $userRepository->findAll();
        $consultants = $consultantsRepository->findAll();

        $consultants_array = array();
        foreach ($consultants as $consultant) {

            $request = $userRepository->findUserByID($consultant->getUserId());
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $name = $propertyAccessor->getValue($request, '[0]')->getName();

            array_push($consultants_array, [
                'id' => (string) $consultant->getID(),
                'Name' => $name,
                'Seniority' => $consultant->getSeniority(),
                'Projects' => $consultant->getProjects()
            ]);
        }

        return $this->render('admin/consultants/consultants.html.twig', [
            'consultants' => $consultants_array,
        ]);
    }

    /**
     * @Route("/createConsultant", name="createConsultant")
     */
    public function createConsultant(Request $request, UserRepository $User_repo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        $consultant = new Consultants();
        $form1 = $this->createForm(ConsultantFormType::class, $consultant);
        $form1->handleRequest($request);

        if(($form->isSubmitted()) && ($form->isValid()) && ($form1->isSubmitted()) && ($form1->isValid())) {
            $data = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            $user_email = $form["Email"]->getData();
            $request = $User_repo->findUserByEmail($user_email);
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $user_id = $propertyAccessor->getValue($request, '[0]');

            $data1 = $form1->getData();
            $data1->setUserId($user_id);

            $entityManager->persist($data1);
            $entityManager->flush();

            return $this->redirectToRoute('consultants');

        }

        return $this->render('admin/consultants/createConsultant.html.twig', [
            'form' => $form->createView(),
            'form1' => $form1->createView(),
        ]);
    }

    /**
     * @Route("/editConsultant/{id}", name="editConsultant")
     * @return RedirectResponse
     */
    public function editConsultant(int $id, UserRepository $User_repo, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $consultant = $entityManager->getRepository(Consultants::class)->find($id);

        $form1 = $this->createForm(ConsultantFormType::class, $consultant);
        $form1->handleRequest($request);

        $user_email = $consultant->getUserId();
        $user_id = $User_repo->find($user_email);

        $user = $entityManager->getRepository(User::class)->find($user_id);

        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if(($form->isSubmitted()) && ($form->isValid()) && ($form1->isSubmitted()) && ($form1->isValid())) {
            $user->setPosition($form->get('Position')->getData());
            $consultant->setSeniority($form1->get('Seniority')->getData());
            $consultant->setProjects($form1->get('Projects')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('consultants');
        }

        return $this->render('admin/consultants/editConsultant.html.twig', [
            'form' => $form->createView(),
            'form1' => $form1->createView(),
        ]);
    }

    /**
     * @Route("/deleteConsultant/{id}", name="deleteConsultant")
     * @return RedirectResponse
     */

    public function deleteConsultant(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $consultants = $entityManager->getRepository(Consultants::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($consultants);
        $em->flush();

        return $this->redirectToRoute('consultants');
    }
}
