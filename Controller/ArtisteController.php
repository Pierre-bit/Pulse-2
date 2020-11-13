<?php

namespace App\Controller;

use App\Entity\Albums;
use App\Entity\Single;
use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Entity\ArtisteSearch;
use App\Form\ArtisteSearchType;
use App\Repository\ArtisteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/artiste")
 */
class ArtisteController extends AbstractController
{
    /**
     * @var ArtisteRepository
     */
    private $repository;
  

    public function __construct(ArtisteRepository $repository)
    {
        $this->repository = $repository;
       
    }
    /**
     * @Route("/", name="artiste_index", methods={"GET"})
     */
    public function index(ArtisteRepository $artisteRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $search = new ArtisteSearch();
        $form = $this->createForm(ArtisteSearchType::class, $search);
        $form->handleRequest($request);
         
        $donnees =  $this->repository->findAllVisibleQuery($search);
        // $this->getDoctrine()->getRepository(Artiste::class)->findBy([], ['nomArtiste' => 'asc']);
        $artiste = $paginator->paginate(
            $donnees,// Requête contenant les données à paginer (ici nos artistes)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3// Nombre de résultats par page
        );
        

        return $this->render('artiste/index.html.twig', [     
            'artiste' => $artiste,
            'form' => $form->createView(),
            'attr'=>array('autocomplete' => 'off')
        ]);
    }






    /**
     * @Route("/new", name="artiste_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($artiste);
            $entityManager->flush();

            return $this->redirectToRoute('artiste_index');
        }

        return $this->render('artiste/new.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="artiste_show", methods={"GET"})
     */
    public function show(Artiste $artiste): Response
    {
        $idArtiste = $artiste->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $album2 = $entityManager->getRepository('App\Entity\Albums')->selectAlbums($idArtiste);
        $singles = $entityManager->getRepository('App\Entity\Single')->selectSingle($idArtiste);
        $tour = $entityManager->getRepository('App\Entity\Tour')->selectTour($idArtiste);

            return $this->render('artiste/show.html.twig', [
                'artiste' => $artiste,
                'album' => $album2,
                'singles' => $singles,
                'tour' => $tour
            ]);
        
        
    }

    /**
     * @Route("/{id}/edit", name="artiste_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Artiste $artiste): Response
    {
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('artiste_show',['id'=>$artiste->getId()]);
        }

        return $this->render('artiste/edit.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="artiste_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Artiste $artiste): Response
    {
        if ($this->isCsrfTokenValid('delete' . $artiste->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($artiste);
            $entityManager->flush();
            $request->getSession()->getFlashBag()->add('Delete', "L'artiste a bien été supprimer.");
        }

        return $this->redirectToRoute('artiste_index');
    }
    /**
     * @Route("/ajax", name="artiste_indexsearch", methods={"POST"}) 
     *  
     */
    public function recherche(Request $request)
    {
        $term = $request->request->get('motcle');

        $array = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Artiste')
            ->AutocompleteArtiste($term);

        $response = new Response(json_encode($array));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
