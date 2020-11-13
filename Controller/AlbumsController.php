<?php

namespace App\Controller;

use App\Entity\Albums;
use App\Entity\Artiste;
use App\Form\AlbumsType;
use App\Entity\AlbumsSearch;
use App\Form\AlbumsSearchType;
use App\Repository\AlbumsRepository;
use App\Repository\ArtisteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/albums")
 */
class AlbumsController extends AbstractController
{
    /**
     * @var AlbumsRepository
     */
    private $repository;


    public function __construct(AlbumsRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @Route("/", name="albums_index", methods={"GET"})
     */
    public function index(AlbumsRepository $albumsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $search = new AlbumsSearch();
        $form = $this->createForm(AlbumsSearchType::class, $search);
        $form->handleRequest($request);

        $donnees =  $this->repository->findAllVisibleQuery($search);
        $albums = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos albums)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('albums/index.html.twig', [
            'albums' =>  $albums,
            'form' => $form->createView()

           
        ]);
        
    }

    /**
     * @Route("/new", name="albums_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $album = new Albums();
        $form = $this->createForm(AlbumsType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('albums_index');
        }

        return $this->render('albums/new.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="albums_show", methods={"GET","POST"})
     */
    public function show(Albums $album , ArtisteRepository $artisteRepository, AlbumsRepository $albumsRepository  ): Response
    {
        $idArtist = $album->getId();
        $artiste = $artisteRepository->nomArtiste($idArtist);
        return $this->render('albums/show.html.twig', [
            'album' => $album,
            'artiste' => $artiste
            
            
            
        ]);
    }

    /**
     * @Route("/{id}/edit", name="albums_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit( Request $request, Albums $album): Response
    {
        $form = $this->createFormBuilder($album)
        ->add('titre')
        ->add('DateSorties', DateType::class, [
                'format' => 'dd-MM-yyyy',
                'widget' => 'choice',
                'years' => range(date('Y') + 15, date('Y') - 49),

            ])
        ->add('Artiste', EntityType::class, [
            'class' => Artiste::class,
            'choice_label' => 'nomArtiste',
            'multiple' => false,
            'expanded' => false,
        ])

        ->add('deezerId',IntegerType::class,[
            'required' => false,
        ])
        ->add('Idyoutube')
        ->add('imageAlbum', FileType::class, [ 
            'required' => false
        ])
        
         
         ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('albums_index');
        }

        return $this->render('albums/edit.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="albums_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Albums $album): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($album);
            $entityManager->flush();
            $request->getSession()->getFlashBag()->add('Album', "L'album a bien été supprimer.");
        }

        return $this->redirectToRoute('albums_index');
    }
    /**
     * @Route("/recherche", name="albums_indexsearch", methods={"POST"}) 
     */
    public function recherche(Request $request)
    {
        $term = $request->request->get('motcle');

        $array = $this->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Albums')
            ->RechercheAlbum($term);

        $response = new Response(json_encode($array));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
