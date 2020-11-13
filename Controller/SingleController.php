<?php

namespace App\Controller;

use App\Entity\Single;
use App\Entity\Artiste;
use App\Form\SingleType;
use App\Entity\SingleSearch;
use App\Form\SingleSearchType;
use App\Repository\SingleRepository;
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
 * @Route("/single")
 */
class SingleController extends AbstractController
{
    /**
     * @var SingleRepository
     */
    private $repository;


    public function __construct(SingleRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @Route("/", name="single_index", methods={"GET"})
     */
    public function index(SingleRepository $singleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $search = new SingleSearch();
        $form = $this->createForm(SingleSearchType::class, $search);
        $form->handleRequest($request);

        $donnees =  $this->repository->findAllVisibleQuery($search);
        $single = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos albums)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('single/index.html.twig', [
            'singles' =>  $single,
            'form' => $form->createView()
        ]);
        
    }

    /**
     * @Route("/new", name="single_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $single = new Single();
        $form = $this->createForm(SingleType::class, $single);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($single);
            $entityManager->flush();

            return $this->redirectToRoute('single_index');
        }

        return $this->render('single/new.html.twig', [
            'single' => $single,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="single_show", methods={"GET"})
     */
    public function show(Single $single,ArtisteRepository $artisteRepository): Response
    {
        $idArtist = $single->getId();
        $artiste = $artisteRepository->nomArtisteSingle($idArtist);
        return $this->render('single/show.html.twig', [
            'single' => $single,
            'artistes' => $artiste
        ]);
    }

    /**
     * @Route("/{id}/edit", name="single_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Single $single): Response
    {
        $form = $this->createFormBuilder($single)
            ->add('titre')
            ->add('DateSorties', DateType::class, [
                'format' => 'dd-MM-yyyy',
                'widget' => 'choice',
                'years' => range(date('Y') + 15, date('Y') - 49),

            ])
            ->add('Artistes', EntityType::class, [
                'class' => Artiste::class,
                'choice_label' => 'nomArtiste',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('deezerId', IntegerType::class,[
                'required' => false,
            ])
            ->add('Idyoutube')
            ->add('imageSingle', FileType::class, [
                'required' => false
            ])


            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('single_index');
        }

        return $this->render('single/edit.html.twig', [
            'single' => $single,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="single_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Single $single): Response
    {
        if ($this->isCsrfTokenValid('delete'.$single->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($single);
            $entityManager->flush();
            $request->getSession()->getFlashBag()->add('Single', "Le single a bien été supprimer.");
        }

        return $this->redirectToRoute('single_index');
    }
}
