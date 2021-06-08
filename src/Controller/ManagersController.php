<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Managers;
use App\Form\UserFormType;
use App\Form\ManagerFormType;
use App\Form\EditUserFormType;
use App\Repository\ManagersRepository;
use App\Repository\UserRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ManagersController extends AbstractController
{
    /**
     * @Route("/managers", name="managers")
     */
    public function index(UserRepository $userRepository,
                          ManagersRepository $managersRepository,
                          Request $request, UserRepository $User_repo): Response
    {
        $user = $userRepository->findAll();
        $managers = $managersRepository->findAll();

        $project_managers = array();
        foreach ($managers as $manager) {

            $request = $User_repo->findUserByEmail($manager->getEmail());
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $name = $propertyAccessor->getValue($request, '[0]')->getName();

            array_push($project_managers, [
                'id' => (string) $manager->getID(),
                'Name' => $name,
                'Seniority' => $manager->getSeniority(),
                'Projects' => $manager->getProjects()
            ]);


        }

        return $this->render('admin/managers/managers.html.twig', [
            'project_managers' => $project_managers,
        ]);
    }

    /**
     * @Route("/createManager", name="createManager")
     */
    public function createManager(Request $request, UserRepository $User_repo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        $manager = new Managers();
        $form1 = $this->createForm(ManagerFormType::class, $manager);
        $form1->handleRequest($request);

        if(($form->isSubmitted()) && ($form->isValid()) && ($form1->isSubmitted()) && ($form1->isValid())) {
            $data = $form->getData();

            $entityManager->persist($data);
            $entityManager->flush();

            $user_email = $form["Email"]->getData();
            $request = $User_repo->findUserByEmail($user_email);
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $user_id = $propertyAccessor->getValue($request, '[0]');

            $data1 = $form1->getData();
            $data1->setUserId($user_id);
            $data1->setEmail($user_email);

            $entityManager->persist($data1);
            $entityManager->flush();

            return $this->redirectToRoute('managers');

        }

        return $this->render('admin/managers/createManager.html.twig', [
            'form' => $form->createView(),
            'form1' => $form1->createView(),
        ]);
    }

    /**
     * @Route("/editManager/{id}", name="editManager")
     * @return RedirectResponse
     */
    public function editManager(int $id, UserRepository $User_repo, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $manager = $entityManager->getRepository(Managers::class)->find($id);

        $form1 = $this->createForm(ManagerFormType::class, $manager);
        $form1->handleRequest($request);

        $user_email = $manager->getEmail();
        $request1 = $User_repo->findUserByEmail($user_email);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $user_id = $propertyAccessor->getValue($request1, '[0]')->getID();

        $user = $entityManager->getRepository(User::class)->find($user_id);

        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if(($form->isSubmitted()) && ($form->isValid()) && ($form1->isSubmitted()) && ($form1->isValid())) {
            $user->setPosition($form->get('Position')->getData());
            $manager->setSeniority($form1->get('Seniority')->getData());
            $manager->setProjects($form1->get('Projects')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('managers');
        }

        return $this->render('admin/managers/editManager.html.twig', [
            'form' => $form->createView(),
            'form1' => $form1->createView(),
        ]);
    }

    /**
     * @Route("/deleteManager/{id}", name="deleteManager")
     * @return RedirectResponse
     */

    public function deleteManager(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $manager = $entityManager->getRepository(Managers::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($manager);
        $em->flush();

        return $this->redirectToRoute('managers');
    }
}
