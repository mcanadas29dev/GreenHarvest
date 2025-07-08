<?php


namespace App\Controller;
use App\Entity\Categoria;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoriaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CategoriaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/*
final class CategoriaController extends AbstractController
{
    #[Route('/categoria', name: 'app_categoria')]
    public function index(): Response
    {
        return $this->render('categoria/index.html.twig', [
            'controller_name' => 'CategoriaController',
        ]);
    }
}
*/

#[Route('/categorias')]
class CategoriaController extends AbstractController
{
    #[Route('/', name: 'app_home')]
        public function home(CategoriaRepository $repo): Response
        {
            $categorias = $repo->findAll();
            return $this->render('home/index.html.twig', [
                'categorias' => $categorias,
            ]);
        }

    #[Route('/', name: 'app_categoria_index', methods: ['GET'])]
        public function index(CategoriaRepository $repo, PaginatorInterface $paginator, Request $rq): Response
            {
                $qb = $repo->createQueryBuilder('c')->orderBy('c.nombre', 'ASC');
                $pag = $paginator->paginate($qb, $rq->query->getInt('page',1), 5);
                return $this->render('categoria/index.html.twig', ['categorias' => $pag]);
            }

    #[Route('/nuevo', name: 'app_categoria_new', methods: ['GET','POST'])]
        public function new(Request $request, EntityManagerInterface $em): Response
        {
            $categoria = new Categoria();
            $form = $this->createForm(CategoriaType::class, $categoria);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('foto')->getData(); // ← obtener el archivo subido

                if ($file) {
                    $fileName = uniqid() . '.' . $file->guessExtension(); // ← nombre único
                    $file->move($this->getParameter('categorias_dir'), $fileName); // ← moverlo
                    $categoria->setFoto($fileName); // ← guardar nombre en la entidad
                }

                $em->persist($categoria);
                $em->flush();

                return $this->redirectToRoute('app_categoria_index');
            }

            return $this->render('categoria/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }


    #[Route('/{id}/editar', name: 'app_categoria_edit', methods: ['GET','POST'])]
        public function edit(Request $rq, Categoria $cat, EntityManagerInterface $em): Response
            {
                $form = $this->createForm(CategoriaType::class, $cat);
                $form->handleRequest($rq);
                if ($form->isSubmitted() && $form->isValid()) {
                    $file = $form->get('foto')->getData();
                    if ($file) {
                        $name = uniqid().'.'.$file->guessExtension();
                        $file->move($this->getParameter('categorias_dir'), $name);
                        $cat->setFoto($name);
                    }
                    $em->flush();
                    return $this->redirectToRoute('app_categoria_index');
                }
                return $this->render('categoria/edit.html.twig',['form'=>$form,'categoria'=>$cat]);
            }

    #[Route('/{id}', name: 'app_categoria_delete', methods: ['POST'])]
        public function delete(Request $rq, Categoria $cat, EntityManagerInterface $em): Response
            {
                if ($this->isCsrfTokenValid('delete'.$cat->getId(), $rq->request->get('_token'))) {
                    $em->remove($cat);
                    $em->flush();
                }
                return $this->redirectToRoute('app_categoria_index');
            }
    #[Route('/tienda', name: 'app_tienda')]
        public function tienda(CategoriaRepository $repo): Response
        {
            $categorias = $repo->findAll();

            return $this->render('categoria/tienda.html.twig', [
                'categorias' => $categorias,
            ]);
        }

    #[Route('/{id}', name: 'app_categoria_show', methods: ['GET'])]
        public function show(Categoria $categoria): Response
        {
            // Por ahora solo muestra el nombre
            return $this->render('categoria/show.html.twig', [
                'categoria' => $categoria,
            ]);
        }

    #[Route('/ver/{id}', name: 'app_categoria_ver')]
        public function ver(Categoria $categoria): Response
        {
            // Cargar artículos/productos relacionados, si tienes.
            return $this->render('categoria/ver.html.twig', [
                'categoria' => $categoria,
            ]);
        }


    }
