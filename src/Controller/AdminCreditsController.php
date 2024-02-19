<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class AdminCreditsController extends AbstractController
{



 
    #[Route('/admin/credits', name: 'app_admin_credits')]
    public function index(Request $request): Response
    {
        $routeName= $request->attributes->get('_route');          
        $page = substr($routeName, 4, strlen($routeName)-1) ;
       

        $breadcrump=['Accueil', 'Credits' ];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');  

    return $this->render('admin_credits/index.html.twig', [
        "page"=>$page ,  
        "breadcrump"=>$breadcrump,
        "navitem"=>$listepage,
        "title"=>$title,
    ]);
}
}
 