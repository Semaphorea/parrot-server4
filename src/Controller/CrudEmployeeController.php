<?php

namespace App\Controller;

use App\Entity\Authentity;
use App\Entity\Employee;
use App\Form\EmployeeType; 
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


use App\Security\EmailVerifierInstanciate;
 
 
#[Route('/crud/employee')]
class CrudEmployeeController extends AbstractController
{
         
    private $verifyEmail;

   // public function __construct(MailerInterface $mailer, EntityManagerInterface $em, UrlGeneratorInterface $router )       
    public function __construct( )       
    {
     //  $this->verifyEmail=  new EmailVerifierInstanciate($mailer,  $em, $router);
        
    }


    #[Route('/', name: 'app_crud_employee_index', methods: ['GET'])]
    public function index(Request $request,EmployeeRepository $employeeRepository): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        return $this->render('Crud/crud_employee/index.html.twig', [
            'employees' => $employeeRepository->findAll(),
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"              
        ]);  
    }

    #[Route('/new', name: 'app_crud_employee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');

        $employee = new Employee();
        $user= new Authentity();

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->flush();

  
            

            /*TODO Envoi de mail avec lien vers endpoint sécurisé par token pour demander à l'employé de créer son mot de passe */  
         // generate a signed url and email it to the user
         $this->verifyEmail->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->from(new Address('contact@parrot.com', 'Mr Parrot'))
                ->to($user->getEmail())
                ->subject('Merci de créer votre mot de passe et de confirmer votre email')
                ->htmlTemplate('registration/creation.password.employee.html.twig')  
        );
        // do anything else you need here, like send an email






            return $this->redirectToRoute('app_crud_employee_index', [], Response::HTTP_SEE_OTHER);
        }
        
 
       


        return $this->render('Crud/crud_employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
            
        ]);
    }

    #[Route('/{id}', name: 'app_crud_employee_show', methods: ['GET'])]
    public function show(Request $request,Employee $employee): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        return $this->render('Crud/crud_employee/show.html.twig', [
            'employee' => $employee,
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crud_employee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {  
        $routeName= $request->attributes->get('_route'); 
        $page = substr($routeName, 4, strlen($routeName)-1) ;  
        $breadcrump=["Accueil","Horaires"];
        $listepage= $this->getParameter('app_list_page');
        $title=$this->getParameter('app_title_website');
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crud_employee_index', [], Response::HTTP_SEE_OTHER);
        }
 
        return $this->render('Crud/crud_employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
            "page"=>$page,
            "breadcrump"=>$breadcrump  ,
            "navitem"=>$listepage, 
            "title"=>$title,
            "crud"=>"crud"
            
        ]);
    }

    #[Route('/{id}', name: 'app_crud_employee_delete', methods: ['POST'])]
    public function delete(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employee->getId(), $request->request->get('_token'))) {
            $entityManager->remove($employee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crud_employee_index', [], Response::HTTP_SEE_OTHER);
    }
}
