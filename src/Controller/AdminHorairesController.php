<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\CrudTimetableController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Timetable;

class AdminHorairesController extends AbstractController
{
    private EntityManagerInterface $em;

    function __construct(EntityManagerInterface $em){
        
        $this->em=$em ;
    }
    
    #[IsGranted('ROLE_ADMIN')] 
    #[Route('/admin/horaires', name: 'app_admin_horaires')]
    public function index(Request $request): Response
    {
        $routeName= $request->attributes->get('_route');          
        $page = substr($routeName, 4, strlen($routeName)-1) ;
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');


           new CrudTimetableController();
           $timetables="";

        return $this->render('admin_horaires/index.html.twig', [
           "page"=>$page,
           "breadcrump"=>$breadcrump  ,
           "navitem"=>$listepage, 
           "title"=>$title,
           "timetables"=>$this->em->getRepository(Timetable::class)->findAll(), 
        ]);
    }
}
 