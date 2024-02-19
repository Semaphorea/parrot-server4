<?php

namespace App\Controller\Api;





use App\Entity\Timetable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Psr\Log\LoggerInterface;
use App\Services\ApiService;
use DateTimeZone;
use App\Configuration\Configuration;


class TimetableController extends AbstractController
{ 


    protected EntityManagerInterface $entityManager;
    protected Request $request;
    protected string $apiName = "garage";
    protected LoggerInterface $log;
    protected ApiService $apiService;
    //const TIMEZONE = Configuration::TIMEZONE; 
    const TIMEZONE = "Europe/Paris";   
    
    function __construct(Request $request, EntityManagerInterface $entityManager, LoggerInterface $log, ApiService $apiService)
    {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->log = $log;
        $this->apiService = $apiService;
  
    }

    #[Route('/garage/timetables', name: 'app_timetables', methods: "GET")]
    public function index(): Response
    {
        $entities = $this->entityManager->getRepository(Timetable::class)->findAll();
        return $this->json($entities, 200);
    }

    #[Route('/garage/timetable/create', name: 'app_timetable_create', methods: "POST")]
    public function timetableCreate(): Response
    {

        $donnees = $this->request->getContent();
        $object =  json_decode($donnees);


        $lastid = $this->timetableLastId()->getContent();
        if ($lastid != null) {
            $id = (int)$lastid + 1;
        } else {
            $id = 1;  
        } 
         
        
        $datetime = \DateTime::createFromFormat('d/m/Y', $object->date, new DateTimeZone(TimetableController::TIMEZONE));
        if ($datetime == false) { 
            $this->log->error("TimetableController.php  L64 Datetime was not well formed and return " . $datetime);
            $datetime = null;
        }

        $this->log->debug("TimetableController.php API L72 " . $object->day);  
        
        
        if ($datetime != null | $object->day != null) {
            $this->log->debug("TimetableController.php API L76 jour " . $object->day);  
            $this->log->debug("TimetableController.php API L77 datetime " . $datetime);  
            $this->log->debug("TimetableController.php API L78 timetable" . implode(',',$object->timetable));   
           
            $timetable = new Timetable($id, $object->day, $datetime, $object->timetable,true); 
            $this->log->debug("TimetableController.php API L78 " . json_encode($timetable));      

            try {
                $this->entityManager->persist($timetable);
                $this->entityManager->flush();
                return $this->json("success", 200);
            } catch (\Exception $e) {
                $this->log->error("TimetableController.php  " +  $e->getMessage() + "\r\n  " + $e->getTrace());
            }
        }
        return $this->json("fail", 200);
    } 


    #[Route('/garage/timetable/{id}', name: 'app_timetable', methods: "GET")]
    public function timetableIndividuel(int $id): Response
    {
        $timetable = $this->entityManager->getRepository(Timetable::class)->findOneById($id);
        return $this->json($timetable, 200);
    }

    #[Route('/garage/timetable/delete/{id}', name: 'app_timetable_delete')]
    public function timetableDelete($id): Response
    {

        $entity = $this->entityManager->getRepository(Timetable::class)->findOneById($id);
        if ($entity != null) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            return $this->json("deleted", 200);
        } else {
            $this->log->error("Timetable L89, delete(); Entity Timtable doesn't exist");
        }
        return $this->json("fail", 200);
    }

    #[Route('/garage/timetable/patch/{id}', name: 'app_timetable_patch', methods: "PATCH")] 
    public function timetablePatch($id): Response
    {

          
        // try {  

            $content = $this->request->getContent();
            $entity = $this->entityManager->getRepository(Timetable::class)->findOneById($id);
            $entity->setProperties(json_decode($content));

            $this->entityManager->flush(); 
            return $this->json("success", 200); 
        // } catch (\Exception $e) {
        //     $this->log->error("TimetableController.php ,  timetablePatch() :  " .  $e->getMessage() . "\r\n  " . $e->getTraceAsString());   
        // }
        return $this->json("fail", 200);
    }

    #[Route('/garage/timetable/lastid', name: 'app_timetable_lastid')]
    public function timetableLastId(): Response
    {

        $lastEntity = $this->entityManager->getRepository(Timetable::class)->findOneBy([], ['id' => 'desc']);

        if ($lastEntity != null) {
            $lastId = $lastEntity->getId();
            return $this->json($lastId, 200);
        }
        return $this->json("fail", 200);
    }
}
