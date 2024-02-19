<?php

namespace App\Controller;

use App\Entity\Administrator;
use App\Entity\Employee;
use Symfony\Component\Security\Http\Attribute\IsGranted ; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 


class AdministrationController extends AbstractController
{
    private EntityManagerInterface $em;  

    function __construct(EntityManagerInterface $em){
        
        $this->em=$em ;
    }


    #[IsGranted('ROLE_ADMIN')] 
    #[Route('/admin/administration', name: 'app_administration')]   
    public function index(Request $request): Response
    {
       

        $routeName= $request->attributes->get('_route');          
        $page = substr($routeName, 4, strlen($routeName)-1) ;
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');


           new CrudAdministratorController();  
           new CrudEmployeeController();  

           $administrator=$this->em->getRepository(Administrator::class)->findAll();    


           $employee=$this->em->getRepository(Employee::class)->findAll();
        
           return $this->render('administration/index.html.twig', [  
                "page"=>$page,
                "breadcrump"=>$breadcrump  ,
                "navitem"=>$listepage, 
                "title"=>$title,
                "administrators"=>$administrator, 
                "employees"=>$employee,
        ]);

           
    }
}
