<?php

// src/Controller/RolController.php
namespace App\Controller;

use App\Entity\Rol;
use App\Form\RolType;
use App\Repository\RolRepository;
use App\Service\RolService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/roles')]
class RolController extends AbstractController
{
    #[Route('/', name: 'app_rol_index', methods: ['GET'])]
    public function index(RolRepository $rolRepository): Response
    {
        return $this->render('rol/index.html.twig', [
            'roles' => $rolRepository->findAll(),
        ]);
    }
    #[Route('/nuevo', name: 'app_rol_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RolService $rolService): Response
    {
        $rol = new Rol();
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rolService->createRol($rol);
            return $this->redirectToRoute('app_rol_index');
        }

        return $this->render('rol/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/editar', name: 'app_rol_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rol $rol, RolService $rolService): Response
    {
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rolService->updateRol();
            return $this->redirectToRoute('app_rol_index');
        }

        return $this->render('rol/edit.html.twig', [
            'form' => $form->createView(),
            'rol' => $rol,
        ]);
    }

    #[Route('/{id}', name: 'app_rol_delete', methods: ['POST'])]
    public function delete(Request $request, Rol $rol, RolService $rolService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rol->getId(), $request->request->get('_token'))) {
            $rolService->deleteRol($rol);
        }

        return $this->redirectToRoute('app_rol_index');
    }
    /*
    #[Route('/', name: 'app_rol_index', methods: ['GET'])]
    public function index(RolRepository $rolRepository): Response
    {
        return $this->render('rol/index.html.twig', [
            'roles' => $rolRepository->findAll(),
        ]);
    }

    #[Route('/nuevo', name: 'app_rol_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $rol = new Rol();
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rol);
            $em->flush();

            return $this->redirectToRoute('app_rol_index');
        }

        return $this->render('rol/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/editar', name: 'app_rol_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rol $rol, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RolType::class, $rol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_rol_index');
        }

        return $this->render('rol/edit.html.twig', [
            'form' => $form->createView(),
            'rol' => $rol,
        ]);
    }

    #[Route('/{id}', name: 'app_rol_delete', methods: ['POST'])]
    public function delete(Request $request, Rol $rol, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rol->getId(), $request->request->get('_token'))) {
            $em->remove($rol);
            $em->flush();
        }

        return $this->redirectToRoute('app_rol_index');
    }
        */
}

