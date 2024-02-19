<?php

namespace App\Controller\Api;

 
use App\Entity\Vehicule;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Exception;
use Psr\Log\LoggerInterface;
use App\Services\ApiService;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\Validator\Constraints\Json;
use App\Configuration\Configuration;




class VehiculeController extends AbstractController
{
 

    protected EntityManagerInterface $entityManager;
    protected Request $request;
    protected string $apiName = "garage";
    protected LoggerInterface $log;
    protected ApiService $apiService;
    //const TIMEZONE = Configuration::TIMEZONE; 
    const TIMEZONE = "Europe/Paris";


    function __construct(
        Request $request,
        EntityManagerInterface $entityManager,
        LoggerInterface $log,
        ApiService $apiService,

    ) {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->log = $log;
        $this->apiService = $apiService;
    }

    #[Route('/garage/vehicules', name: 'app_vehicules', methods: "GET")]
    public function index(): Response
    {
        $entities = $this->entityManager->getRepository(Vehicule::class)->findAll();
        return $this->json($entities, 200);
    }

    #[Route('/garage/vehicule/create', name: 'app_vehicule_create', methods: "POST")]   
    public function vehiculeCreate(): Response
    {
        $this->log->debug("VehiculeController.php L63,  vehiculeCreate() :  Value : Hello Create" );  

        $donnees = $this->request->getContent();  
        //var_dump($donnees);
        $object =  json_decode($donnees);



        $lastid = $this->vehiculeLastId()->getContent();
        if ($lastid != null) {

            //   var_dump($lastid);   
            $id = (int) $lastid + 1;
        } else {
            $id = null;
        }
          
           $this->log->debug("VehiculeController.php L73,  vehiculeCreate() :  Value : ".$id );  
          


        $vehicule = new Vehicule($id, $object->brandt, $object->model, $object->features,   new \DateTime($object->year,new \DateTimeZone(VehiculeController::TIMEZONE)), $object->kilometers, $object->type, $object->price);
        

        try {
            $this->entityManager->persist($vehicule);
            $this->entityManager->flush();
            return $this->json("success", 200);
        } catch (\Exception $e) {




            $this->log->error("VehiculeController.php vehiculecreate()  L74" . $e->getMessage());
        }
        return $this->json("fail", 200);


        // Exemple de requête : 
        // curl -X 'POST'  'http://localhost:8000/garage/vehicule/create'  -H 'accept: */*'  -H 'Content-Type: application/ld+json'  -d '{ "brandt": "citroen", "model": "DS 3 Crossback", "features": [ { "gear": "1000" }, { "taxegear": "15" }, { "color": "blue" } ], "year": 2020, "kilometers": 28132, "type": "berline", "price": 36990 }'
    }


    #[Route('/garage/vehicule/lastid', name: 'app_vehicule_lastid')]
    public function vehiculeLastId(): Response
    {
        $lastId=0; 
        $lastEntity = $this->entityManager->getRepository(Vehicule::class)->findOneBy([], ['id' => 'desc']);
        if($lastEntity != null){ $lastId = $lastEntity->getId();
            return $this->json($lastId, 200);    
        } 
        return $this->json("fail", 200);   
    }

    #[Route('/garage/vehicule/{id}', name: 'app_vehicule', methods: "GET")]
    public function vehiculeIndividuel(int $id): Response
    {
        $vehicule = $this->entityManager->getRepository(Vehicule::class)->findOneById($id);
        return $this->json($vehicule, 200);
    }

    #[Route('/garage/vehicule/delete/{id}', name: 'app_vehicule_delete')]
    public function vehiculeDelete($id): Response
    {
        $vehicule = $this->entityManager->getRepository(Vehicule::class)->findOneById($id);
        if ($vehicule != null) {
            $this->entityManager->remove($vehicule);
            $this->entityManager->flush();
            return $this->json("deleted", 200);
        } else {
            $this->log->error("VehiculeContoler L112, delete(); Entity doesn't exist");
        }
        return $this->json("fail", 200);
    }

    #[Route('/garage/vehicule/patch/{id}', name: 'app_vehicule_patch', methods: "PATCH")]
    public function vehiculePatch($id): Response
    {

        try { 
            $content = $this->request->getContent();
            // $this->log->debug("VehiculeController.php L138,  vehiculePatch() :  Value : " .  json_encode($content, true));


            $entity = $this->entityManager->getRepository(Vehicule::class)->findOneById($id);
            $entity->setProperties(json_decode($content));
 
            $this->entityManager->flush();

            return $this->json("success", 200);
        } catch (\Exception $e) {
            $this->log->error("VehiculeController.php L141,  vehiculePatch() :  " . $e->getMessage() . " ; \r\n Trace : " . $e->getTraceAsString());
        }

        return $this->json("fail", 200);
    }
}
