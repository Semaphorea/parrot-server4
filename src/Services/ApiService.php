<?php
namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\NativeHttpClient;
use Psr\Log\LoggerInterface;
 
class ApiService{  


    protected HttpClientInterface $client;
    protected LoggerInterface $log;

    function __construct(HttpClientInterface $client, LoggerInterface $log){
        $this->client= $client;
        $this->log=$log;
    }

    /** 
     * Function send
     * Return JSON 
     */
    function send($request,$method,$donnees="{}"){  
 

        
        try{  
            
            if($method=="GET"){
                $response=  $this->client->request($method,$this->$_SERVER['REQUEST_URI']+"/"+$request)  ; }
                if($method=="POST"||$method=="PATCH"){
        
            $header=["http"=>["header"=> ["Access-Control-Allow-Origin"=>"*",
                                          "Content Type"=>"application/json", 
                                          "Access-Control-Allow-Method"=>$method,
                                          "Access-Control-Allow-Headers"=>"Content-Type",
                                          "encode"=>"utf-8",
                                          "content"=>$donnees]
                              ], 
                            "ssl"=>array(
                                "verify_peer"=>FALSE,
                                "verify_peer_name"=>FALSE,
                                'allow_self_signed'  => TRUE
                             ),
                    ];
            
            $response=  $this->client->request($method,$this->$_SERVER['REQUEST_URI']+"/"+$request, $header)  ; }
        

        $this->log->info("apiService L27, server URL :"+$this->$_SERVER['REQUEST_URI']);
      
      //  $statusCode = $response->getStatusCode();

  
    


       
        $contentType = $response->getHeaders()['content-type'][0];
        $this->log->info("apiService L27, contentType :"+$contentType);
        $content = json_decode($response->getContent());
      //  $this->log->info("apiService L28, statusCode : "+$statusCode);
 
        //$content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

       return($content);
      }catch(\Exception $e){$this->log->error("apiService send(req,methode) : "+$e->getMessage());}  

      return ; 

    }




}