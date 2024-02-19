<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use App\Services\FileUploader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Vehicule;
 

class AdminVehiculesController extends AbstractController
{

    private EntityManagerInterface $em;


    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;         
    }

    #[Route('/admin/vehicules', name: 'app_admin_vehicules')]
    public function index(Request $request): Response
    {


        $routeName = $request->attributes->get('_route');
        $page = substr($routeName, 4, strlen($routeName) - 1);
        $breadcrump = ['Accueil', 'Véhicules'];
        $listepage = $this->getParameter('app_list_page');
        $title = $this->getParameter('app_title_website');
 
        new CrudVehiculeController( $this->em);    
        return $this->render('admin_vehicules/index.html.twig', [
            "page" => $page,
            "breadcrump" => $breadcrump,
            "navitem" => $listepage,
            "title" => $title,
            "vehicules" => $this->em->getRepository(Vehicule::class)->findAll(),
        ]);
    }
}
