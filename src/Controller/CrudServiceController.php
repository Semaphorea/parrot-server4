<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud/service')]
class CrudServiceController extends AbstractController
{


    #[Route('/', name: 'app_admin_services', methods: ['GET'])]
    public function index(Request $request,ServiceRepository $serviceRepository): Response
    {  $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"]; 
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        return $this->render('Crud/crud_service/index.html.twig', [
            'services' => $serviceRepository->findAll(),"page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/new', name: 'app_crud_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');    
        $title=$this->getParameter('app_title_website');
        $service = new Service();
        $serviceType= new ServiceType($entityManager);
        $form = $this->createForm(ServiceType::class, $service);
        
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);  
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_services', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/crud_service/new.html.twig', [
            'service' => $service,
            'form' => $form,"page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }
 
    #[Route('/{id}', name: 'app_crud_service_show', methods: ['GET'])]
    public function show(Request $request,Service $service): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
         
        return $this->render('Crud/crud_service/show.html.twig', [
            'service' => $service,"page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_services', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/crud_service/edit.html.twig', [
            'service' => $service,
            'form' => $form,"page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}', name: 'app_crud_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush(); 
        }

        return $this->redirectToRoute('app_admin_services', [], Response::HTTP_SEE_OTHER);
    }
}
