<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class NewsController extends AbstractController
{
    /**
     * @var NewsRepository
     */
    private $repository;


    public function __construct(NewsRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @Route("/", name="news_index", methods={"GET"})
     */
    public function index(NewsRepository $newsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $carousels = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Carousel')->findAll();
        $news = $this->repository->news();

        $bandeauNews = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\News')->findBy([], ['created_at' => 'DESC'], 3);
        $pagination = $paginator->paginate(
            $news, // Requ�te contenant les donn�es � paginer (ici les news)
            $request->query->getInt('page', 1), // Num�ro de la page en cours, pass� dans l'URL, 1 si aucune page
            6 // Nombre de r�sultats par page
        );
        return $this->render('news/index.html.twig', [
            'newss' => $pagination,
            'carousels' => $carousels,
            'bandeauNews' => $bandeauNews,

        ]);
    }

    /**
     * @Route("/news/new", name="news_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($news);
            $entityManager->flush();

            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/news/{id}", name="news_show", methods={"GET"})
     */
    public function show(News $news): Response
    {
        $idNews = $news->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $newss = $entityManager->getRepository('App\Entity\News')->artisteNews($idNews);

        return $this->render('news/show.html.twig', [
            'newss' => $newss,
            
        ]);
    }

    /**
     * @Route("/news/{id}/edit", name="news_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, News $news): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/news/{id}", name="news_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, News $news): Response
    {
        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($news);
            $entityManager->flush();
        }

        return $this->redirectToRoute('news_index');
    }
}
