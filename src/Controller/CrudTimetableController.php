<?php

namespace App\Controller;

use App\Entity\Timetable;
use App\Form\TimetableType;
use App\Repository\TimetableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/timetable/crud')]
class CrudTimetableController extends AbstractController
{
    #[Route('/', name: 'app_timetable_crud_index', methods: ['GET'])]
    public function index(Request $request,TimetableRepository $timetableRepository): Response
    {
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');

        return $this->render('Crud/timetable_crud/index.html.twig', [
            'timetables' => $timetableRepository->findAll(),
            "page"=>$page, 
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,  
            "crud"=>"crud"
            ]);
    }

    #[Route('/new', name: 'app_timetable_crud_new', methods: ['GET', 'POST'])] 
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');

        $timetable = new Timetable();
        $form = $this->createForm(TimetableType::class, $timetable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timetable);
            $entityManager->flush(); 

            return $this->redirectToRoute('app_admin_horaires', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/timetable_crud/new.html.twig', [
            'timetable' => $timetable,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}', name: 'app_timetable_crud_show', methods: ['GET'])]
    public function show(Request $request,Timetable $timetable): Response
    { 
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');

        return $this->render('Crud/timetable_crud/show.html.twig', [
            'timetable' => $timetable,
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}/edit', name: 'app_timetable_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Timetable $timetable, EntityManagerInterface $entityManager): Response
        
    {   $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $form = $this->createForm(TimetableType::class, $timetable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_horaires', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/timetable_crud/edit.html.twig', [
            'timetable' => $timetable,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>'crud'
        ]);
    }

    #[Route('/{id}', name: 'app_timetable_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Timetable $timetable, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timetable->getId(), $request->request->get('_token'))) {
            $entityManager->remove($timetable);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_horaires', [], Response::HTTP_SEE_OTHER);
    }
}
