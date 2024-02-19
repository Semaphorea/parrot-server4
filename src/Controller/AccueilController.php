<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/admin/accueil', name: 'app_admin_accueil')]
    public function index(Request $request): Response
    {
            $routeName= $request->attributes->get('_route');    
            $page = substr($routeName, 10, strlen($routeName)) ;    
            
           $title= $this->getParameter('app_title_website');
           $listepage= $this->getParameter('app_list_page');
 
       

        return $this->render('accueil/index.html.twig', [
            "navitem"=>$listepage,
            "page"=>$page ,  
            "title"=>$title, 
        ]);
    }
}
