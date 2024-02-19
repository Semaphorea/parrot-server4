<?php

namespace App\Controller;

use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Doctrine\ORM\EntityManagerInterface;


class AdminServicesController extends AbstractController
{

  
    private EntityManagerInterface $em;

    function __construct( private ContainerBagInterface $params,EntityManagerInterface $em){        
        $this->em=$em ;
    }
  
    #[IsGranted('ROLE_ADMIN')] 
    #[Route('/admin/services', name: 'app_admin_service')]
    public function index(Request $request): Response
    {  

       // var_dump('Hello');
        $routeName= $request->attributes->get('_route');              
        $page = substr($routeName, 4, strlen($routeName)-1) ; 
        $breadcrump=['Accueil', 'Services' ];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->params->get('app_title_website');        
  
        new CrudServiceController();  

    return $this->render('admin_services/index.html.twig', [
        "page"=>$page , 
        "breadcrump"=>$breadcrump ,
        "title"=>$title,
        "navitem"=>$listepage,
        "services"=>$this->em->getRepository(Service::class)->findAll(), 
    ]);
}
}
