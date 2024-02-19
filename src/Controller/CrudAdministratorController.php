<?php

namespace App\Controller;

use App\Entity\Administrator;
use App\Form\AdministratorType;
use App\Repository\AdministratorRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud/administrator')]
class CrudAdministratorController extends AbstractController
{
  
    #[Route('/', name: 'app_crud_administrator_index', methods: ['GET'])]
    public function index(Request $request,AdministratorRepository $administratorRepository): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');  
        $title=$this->getParameter('app_title_website');
 
       $administrator=$administratorRepository->findAll();
     
        return $this->render('Crud/crud_administrator/index.html.twig', [  
            'administrators' => $administrator,  
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
        ]);
    } 
      
    #[Route('/new', name: 'app_crud_administrator_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $administrator = new Administrator();
        $form = $this->createForm(AdministratorType::class, $administrator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($administrator);
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_administrator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud\crud_administrator\new.html.twig', [
            'administrator' => $administrator,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}', name: 'app_crud_administrator_show', methods: ['GET'])]
    public function show(Request $request,Administrator $administrator): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        return $this->render('Crud/crud_administrator/show.html.twig', [
            'administrator' => $administrator,
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_administrator_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Administrator $administrator, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $form = $this->createForm(AdministratorType::class, $administrator);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_administrator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/crud_administrator/edit.html.twig', [
            'administrator' => $administrator,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}', name: 'app_crud_administrator_delete', methods: ['POST'])]
    public function delete(Request $request, Administrator $administrator, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$administrator->getId(), $request->request->get('_token'))) {
            $entityManager->remove($administrator);
            $entityManager->flush();
        }  
 
        return $this->redirectToRoute('app_crud_administrator_index', [], Response::HTTP_SEE_OTHER);
    }
}
