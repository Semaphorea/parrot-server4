<?php

namespace App\Controller;

use App\Entity\Notice;
use App\Form\NoticeType;
use App\Repository\NoticeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
 
#[Route('/crud/notice')]
class CrudNoticeController extends AbstractController
{
    #[Route('/', name: 'app_crud_notice_index', methods: ['GET'])]
    public function index(Request $request,NoticeRepository $noticeRepository): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        return $this->render('Crud/crud_notice/index.html.twig', [
            'notices' => $noticeRepository->findAll(),
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title, 
            "crud"=>'crud' 
            
        ]);
    }

    #[Route('/new', name: 'app_crud_notice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $notice = new Notice();
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($notice);
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/crud_notice/new.html.twig', [
            'notice' => $notice,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
            
        ]);
    }

    #[Route('/{id}', name: 'app_crud_notice_show', methods: ['GET'])]
    public function show(Request $request,Notice $notice): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        return $this->render('Crud/crud_notice/show.html.twig', [
            'notice' => $notice,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_notice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Notice $notice, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/crud_notice/edit.html.twig', [
            'notice' => $notice,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
            
        ]);
    }

    #[Route('/{id}', name: 'app_crud_notice_delete', methods: ['POST'])]
    public function delete(Request $request, Notice $notice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($notice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crud_notice_index', [], Response::HTTP_SEE_OTHER);
    }
}
 