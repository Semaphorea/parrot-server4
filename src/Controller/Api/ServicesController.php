<?php

namespace App\Controller\Api;



use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Psr\Log\LoggerInterface;
use App\Services\ApiService;
use App\Configuration\Configuration;
use DateTime;
use Rs\Json\Patch;

use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\Exception\RuntimeException;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class ServicesController extends AbstractController
{

    protected EntityManagerInterface $entityManager;
    protected Request $request;
    protected string $apiName = "garage";
    protected LoggerInterface $log;
    protected ApiService $apiService;
    protected ValidatorInterface $validator;
     
    //const TIMEZONE = Configuration::TIMEZONE;

    const TIMEZONE = "Europe/Paris";



    function __construct(Request $request, EntityManagerInterface $entityManager, LoggerInterface $log, ApiService $apiService, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->log = $log;
        $this->apiService = $apiService;
        $this->validator = $validator;
       
    }

    #[Route('/garage/services', name: 'app_services', methods: "GET")]
    public function index(): Response
    {
        $entities = $this->entityManager->getRepository(Service::class)->findAll();
        return $this->json($entities, 200);
    }

    #[Route('/garage/services/create', name: 'app_service_create', methods: "POST")]
    public function serviceCreate(): Response
    {

        $donnees = $this->request->getContent();
        $object =  json_decode($donnees);


        $lastid = $this->serviceLastId()->getContent();
        if ($lastid != null) {
            $id = (int) $lastid;
        } else {
            $id = 1;
        }


        // $datetime = \DateTime::createFromFormat('YYYY-mm-dd HH:ii:ss',time(), new \DateTimeZone(ServicesController::TIMEZONE));
        $datetime = new  \DateTime("now", new \DateTimeZone(ServicesController::TIMEZONE));
        if ($datetime == false) {
            $this->log->error("ServicesController.php  L62 Datetime was not well formed and return false");
        }

        //$this->log->debug("ServiceController.php L66 Datetime : " .  $datetime);

        $service = new Service($id, $object->services, $datetime);

        try {
            $this->entityManager->persist($service);
            $this->entityManager->flush();
            return $this->json("success", 200);
        } catch (\Exception $e) {
            $this->log->error("ServiceController.php  " .  $e->getMessage() . "\r\n  " . $e->getTraceAsString());
        }
        return $this->json("fail", 200);
    }


    #[Route('/garage/service/{id}', name: 'app_service', methods: "GET")]
    public function serviceIndividuel(int $id): Response
    {
        $service = $this->entityManager->getRepository(Service::class)->findOneById($id);
        return $this->json($service, 200);
    }

    #[Route('/garage/service/delete/{id}', name: 'app_service_delete')]
    public function serviceDelete($id): Response
    {

        $entity = $this->entityManager->getRepository(Service::class)->findOneById($id);
        if ($entity != null) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            return $this->json("deleted", 200);
        } else {
            $this->log->error("ServiceControler L89, delete(); Entity Service doesn't exist");
        }
        return $this->json("fail", 200);
    }  

    #[Route('/garage/service/patch/{id}', name: 'app_service_patch', methods: "PATCH")]
    public function servicePatch($id): Response
    {
       

    $content = $this->request->getContent();
    $entity = $this->entityManager->getRepository(Service::class)->findOneById(["id" => $id]);
    // $entity = $this->entityManager->getRepository(Service::class)->find($id);  
    
    
    
    if (!is_string($content)) {
        throw new BadRequestException('Invalid JSON');
    }
    $request = json_decode($content, true);  //Les requêtes peuvent être multiples.
    
    $service=$entity->getServices();
  
    if (!$service) {
        throw new NotFoundHttpException('Service not found');
    }

    foreach ($request["request"] as $operation) {
        
        $path = $operation['path'];
        $value = $operation['value'];
        $op = $operation['op'];
        
        
       $this->log->debug("ServicesController  L158 ".implode(",",$value));
        $accessor = PropertyAccess::createPropertyAccessor();
        
        try {
            switch ($op) {
                case 'add':
                    $accessor->setValue($service, $path, $value);
                    break;
                case 'replace':
                    $accessor->setValue($service,"[". $path."]", $value); 
                    break;
                case 'remove':
                    $accessor->setValue($service, $path, null);  
                    break; 
                default:
                    throw new BadRequestException(sprintf('Invalid operation "%s"', $op));
            }
        } catch (NoSuchPropertyException | InvalidArgumentException | UnexpectedTypeException | RuntimeException | AccessException $e) {
            throw new BadRequestException(sprintf('Invalid patch operation: %s', $e->getMessage()));
        }



  

       //Error return cannot extract operation
        $patch = new Patch(json_encode($entity), json_encode($operation));    
        $patch->apply($service, $accessor);
        $errors = $this->validator->validate($service);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }


        try {

            $entity->setProperty("date_modification", new  \DateTime("now", new \DateTimeZone(ServicesController::TIMEZONE)));
            //    $patch = new Patch(json_encode($entity), json_encode($content));
            //   $patchedDocument = $patch->apply(); 
          

            // $ret=json_decode($patchedDocument);  
            $this->entityManager->persist($service);
            $this->entityManager->flush();
            
        
            return $this->json(json_encode($service), Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->log->error("ServiceController.php ,  servicePatch() :  " .  $e->getMessage() . "\r\n  " . $e->getTraceAsString());
        }
            return $this->json("fail", 200);
        
    }

    }


    #[Route('/garage/service/lastid', name: 'app_service_lastid')]
    public function serviceLastId(): Response
    {

        $lastEntity = $this->entityManager->getRepository(Service::class)->findOneBy([], ['id' => 'desc']);
        if ($lastEntity != null) {
            $lastId = $lastEntity->getId();
            return $this->json($lastId, 200);
        }
        return $this->json("fail", 200);
    }
}
