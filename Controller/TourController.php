<?php

namespace App\Controller;

use App\Entity\Tour;
use App\Form\TourType;
use App\Entity\Artiste;
use App\Entity\TourSearch;
use App\Form\TourSearchType;
use App\Repository\TourRepository;
use App\Repository\ArtisteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tour")
 */
class TourController extends AbstractController
{
    /**
     * @var TourRepository
     */
    private $repository;


    public function __construct(TourRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="tour_index", methods={"GET"})
     */
    public function index(TourRepository $tourRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $search = new TourSearch();
        $form = $this->createForm(TourSearchType::class, $search);
        $form->handleRequest($request);

        $donnees =  $this->repository->findAllVisibleQuery($search);
        $tour = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos concerts/tournées)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->render('tour/index.html.twig', [
            'tour' =>  $tour,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="tour_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $tour = new Tour();
        $form = $this->createForm(TourType::class, $tour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tour);
            $entityManager->flush();

            return $this->redirectToRoute('tour_index');
        }

        return $this->render('tour/new.html.twig', [
            'tour' => $tour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tour_show", methods={"GET"})
     */
    public function show(Tour $tour, ArtisteRepository $artisteRepository): Response
    {
        $idArtist = $tour->getId();
        $artiste = $artisteRepository->nomArtisteTour($idArtist);
        return $this->render('tour/show.html.twig', [
            'tour' => $tour,
            'artistes' => $artiste
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tour_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Tour $tour): Response
    {
        $form = $this->createFormBuilder($tour)
            ->add('nom')
            ->add('Date', DateType::class, [
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
            ->add('Adresse')
            ->add('Ville')
            ->add('Pays')
            ->add('Idyoutube')
            ->add('imageTour', FileType::class, [
                'required' => false
            ])


            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tour_index');
        }

        return $this->render('tour/edit.html.twig', [
            'tour' => $tour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tour_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Tour $tour): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tour->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tour);
            $entityManager->flush();
            $request->getSession()->getFlashBag()->add('Tour', "La Tournée/Concert a bien été supprimer.");
        }
 
        return $this->redirectToRoute('tour_index');
    }
}
