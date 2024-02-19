<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Notice;

class AdminAvisController extends AbstractController
{

 
    private EntityManagerInterface $em;

    function __construct(EntityManagerInterface $em){
        
        $this->em=$em ;
    }
    #[Route('/admin/avis', name: 'app_admin_avis')]  
    public function index(Request $request): Response
    {
        $routeName= $request->attributes->get('_route');          
        $page = substr($routeName, 4, strlen($routeName)-1) ;
        $breadcrump=['Accueil', 'Avis' ];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        new CrudNoticeController();

    return $this->render('admin_avis/index.html.twig', [    
        "breadcrump"=>$breadcrump,
        "navitem"=>$listepage  ,
        "page"=>$page ,
        "title"=>$title,
        "notices"=> $this->em->getRepository(Notice::class)->findAll(),   
    ]);
}
}
 