<?php
namespace App\Controller;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    
    /*
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
        */
    #[Route('/', name: 'app_home')]
    public function index(CategoriaRepository $categoriaRepository): Response
    {
        $categorias = $categoriaRepository->findAll();

        return $this->render('index.html.twig', [
            'categorias' => $categorias,
        ]);
    }
}
