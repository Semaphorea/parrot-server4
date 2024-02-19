<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Exception; 
use Psr\Log\LoggerInterface;
use App\Services\LogService;
use App\Services\ApiService;
use App\Entity\Visitor;   
  
 
class VisitorController extends AbstractController
{ 
 
 
    protected EntityManagerInterface $entityManager; 
    protected Request $request;
    protected string $apiName = "garage";
    protected LogService $log;  
    protected LoggerInterface $log2;
    protected ApiService $apiService ; 
   

    function __construct( Request $request,EntityManagerInterface $entityManager, LogService $log, LoggerInterface $log2,ApiService $apiService)
    { 
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->log = $log;    
        $this->log2 = $log2;    
        $this->apiService = $apiService;
    }


    #[Route('/garage/visitors', name: 'app_visitors')]
    public function visitor(): Response
    {
             
            $entities= $this->entityManager->getRepository(Visitor::class)->findAll();
        
        return $this->json($entities,200);
    }


    #[Route('/garage/visitor/lastid', name: 'app_visitor_lastid')]  
    public function visitorLastId(): Response
    {   

        $lastId=0;  
        $lastEntity = $this->entityManager->getRepository(Visitor::class)->findOneBy([], ['id' => 'desc']);
        if($lastEntity != null){ $lastId = $lastEntity->getId();
            return $this->json($lastId, 200);    
            
        } 
        return $this->json("fail", 200);   
        
    }  
  

   
    #[Route('/garage/visitor/create', name: 'app_visitor_create', methods: "POST")]
    public function visitorCreate(): Response
    {

       $donnees=json_decode($this->request->getContent()); 
          
       $this->log2->debug('visitorController.php  L69 visiteur:'.json_encode($donnees) );  
       
       $lastid = $this->visitorLastId()->getContent();
       if ($lastid != null) {
           $this->log2->debug('visitorController.php  L70 LastId:'.$lastid );   
           //$this->log->debug('visitorController.php  L70 LastId:'.$lastid ); 
           $id = ((int)$lastid) + 1; 
        } else {  
            $id = 1;
        }
       
      
        $visitor= new Visitor($id,$donnees->lastname,$donnees->firstname, $donnees->email);
        $this->log2->debug('visitorController.php  L81 visiteur:'.json_encode($visitor) );  
         
          try {
            $this->entityManager->persist($visitor);
            $this->entityManager->flush();
            return $this->json("success", 200);    
        } catch (\Exception $e) {   
            $this->log->error(' visitorController.php  ' .  $e->getMessage() . '\r\n  ' . $e->getTraceAsString());
        }  
        return $this->json("fail", 200);
    }


    #[Route('/garage/visitor/delete/{id}', name: 'app_visitor_delete')]   
    public function vehiculeDelete($id): Response
    {

        $entity = $this->entityManager->getRepository(Visitor::class)->findOneById($id);
        if ($entity != null) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush(); 
            return $this->json("deleted", 200);
        } else {
            $this->log->error("VehiculeContoler L112, delete(); Entity doesn't exist");
        }

        return $this->json("deleted", 200);  
    }
     

    #[Route('/garage/visitor/{id}', name: 'app_visitor_individu')]
    public function visitorIndividu(int $id): Response
    {
        $entities= $this->entityManager->getRepository(Visitor::class)->findById($id);
        return $this->json($entities, 200);  
    }
}
