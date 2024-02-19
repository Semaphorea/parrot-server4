<?php

namespace App\Controller;

use App\Entity\Visitor;
use App\Form\VisitorType;
use App\Repository\VisitorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud/visitor')]
class CrudVisitorController extends AbstractController
{
    #[Route('/', name: 'app_crud_visitor_index', methods: ['GET'])]
    public function index(Request $request,VisitorRepository $visitorRepository): Response
    {     $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        return $this->render('Crud/crud_visitor/index.html.twig', [
            'visitors' => $visitorRepository->findAll(),
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/new', name: 'app_crud_visitor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $visitor = new Visitor();
        $form = $this->createForm(VisitorType::class, $visitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($visitor);
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_visitor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/crud_visitor/new.html.twig', [
            'visitor' => $visitor,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}', name: 'app_crud_visitor_show', methods: ['GET'])]
    public function show(Request $request,Visitor $visitor): Response
    {
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        return $this->render('Crud/crud_visitor/show.html.twig', [
            'visitor' => $visitor,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_visitor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Visitor $visitor, EntityManagerInterface $entityManager): Response
    {
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');

        $form = $this->createForm(VisitorType::class, $visitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_visitor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/crud_visitor/edit.html.twig', [
            'visitor' => $visitor,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}', name: 'app_crud_visitor_delete', methods: ['POST'])]
    public function delete(Request $request, Visitor $visitor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visitor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($visitor);
            $entityManager->flush();  
        }  

        return $this->redirectToRoute('app_crud_visitor_index', [], Response::HTTP_SEE_OTHER);
    }
}
