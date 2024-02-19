<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Entity\Photo;
use App\Tool\PhotoTool;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

#[Route('/crud/vehicule')]
class CrudVehiculeController extends AbstractController 
{ 
    use PhotoTool; 
   
    private ?FileUploader $fileUploader = null;  
    private ?EntityManagerInterface $em = null;
    
  
    function __construct(
       EntityManagerInterface $em,
       //  FileUploader $fileUploader = null,
         SluggerInterface $slug =null,
    
     )  
    {      
        $sl= new AsciiSlugger();
         $this->fileUploader =  new FileUploader('%env(PHOTO_DIRECTORY)%',$sl);    
        //  $this->fileUploader =  $fileUploader;    
        $this->em=$em;  
        
    }




    #[Route('/', name: 'app_crud_vehicule_index', methods: ['GET'])]
    public function index(Request $request,VehiculeRepository $vehiculeRepository): Response
    {  $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');

        $vehicules=$vehiculeRepository->findAll();  
        

        return $this->render('Crud/crud_vehicule/index.html.twig', [
            'vehicules' => $vehicules,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/new', name: 'app_crud_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {    
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);

       
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $featuresValue = $form->get('features')->getData();  
               
                    
            $this->photoCreate($form, $vehicule);   
            $entityManager->persist($vehicule);
            $entityManager->flush();
           

            return $this->redirectToRoute('app_crud_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/crud_vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}', name: 'app_crud_vehicule_show', methods: ['GET'])]
    public function show(Request $request,Vehicule $vehicule): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        return $this->render('Crud/crud_vehicule/show.html.twig', [
            'vehicule' => $vehicule,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->photoCreate($form, $vehicule);   
            
            $entityManager->flush();
            return $this->redirectToRoute('app_crud_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Crud/crud_vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud" 
        ]);
    }

    #[Route('/{id}', name: 'app_crud_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crud_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }



      /**
     * Function photoCreate
     * Args FileUploader
     * Args Form
     * Args Entité
     * Return Void  
     */
    function photoCreate($form, $entity)
    {
       
        if($form->offsetGet('photo')->getData() != null ){
        $photoFilePath = $form->offsetGet('photo')->getData()->getPath();  //Je trouve la photo lorsque je recherche les éléments enfants
        $photoFile = $form->offsetGet('photo')->getData();
          }else{
            new \Exception("Error Processing Request  CrudVehiculeController L176 , No photo transmitted by the form");
          }


        if (isset($photoFile)) {
            $uploadedphoto = $this->fileUploader->upload($photoFile);
     

        $file = file_get_contents($this->fileUploader->getPhotoNewPath() . DIRECTORY_SEPARATOR . $uploadedphoto, false);
        $photoEntity = new Photo(null, $uploadedphoto, $file);

        $doublons = $this->searchDoublonPhoto($uploadedphoto);  

        if (!$doublons) {

            $photorep = $this->em->getRepository(Photo::class);
            $photorep->persist($photoEntity, true);
            $photorep->flush();

            $photoid = $photorep->findIdByTitreDQL($uploadedphoto);

            $photoEntity->setId($photoid['id']);

            $entity->setPhoto($photoEntity);
            // dd($entity);
        } else {
            echo "<p class='bs-danger'> Notre base de donnée contient déjà votre photo, ou nous n'avons pas été en mesure d'intégrer celle-ci à notre base de donnée. Merci de réitérer votre envoie ou contacter le service technique si le problème persiste ! </p>";
        }}
    }


    function searchDoublonPhoto($titre)
    {
        try {
            $photoid = $this->em->getRepository(Photo::class)->findIdByTitreDQL($titre);
            if ($photoid[0] != null) {
                return true;
            }
        } catch (\Exception $e) {
            echo "<p class='bs-danger'>cf. CrudVehiculeController L168 : La recherche de doublons dans la table Photo n'a pas abouti : " . $e->getTraceAsString() . " </p>";
        };
        return false;
    }

    /**
     * Function fetchDonneesPhoto
     * Return   tabentites[tabentite[dataview [set[binary,titre] ]]]
     */
    function fetchDonneesEntity($entity, $id=null, EntityManagerInterface $em = null): array
    { 
        if ($id != null) {
           $entites[0]= $this->em->getRepository($entity)->findByIdDQL($entity,$id);
        } else {
            $entites = $this->em->getRepository($entity)->findAllDQL($entity);
        }

       
        $i = 0;
        foreach ($entites as $entite) {
            $entites[$i]['dataview'] = ['set' => $this->displayFile($entite['binaryfile'], $entite['titre'])];
            $i++;
        }
        return $entites;    
    }

  



}
