<?php
 
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class ContactController extends AbstractController  
{ 

    protected Request $request; 
    protected LoggerInterface $log;

    function __construct(Request $request, LoggerInterface $log,){
        $this->request=$request;
        $this->log=$log;  
    }

    #[Route('/contact', name: 'app_contact',methods:"POST")]
    public function index(): Response
    {         
        $email= json_decode( $this->request->getContent());   
        
        try{
         mail($email->to,$email->subject, $email->message);
        }catch(\Exception $e){   $this->log->error($e->getMessage(),[$e->getTrace()]);}  

        return $this->json("success",200);
    }
}
