<?php 

// src/Service/FileUploader.php

namespace App\Services;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{ 

    private $photoNewPath =null;

 
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,          
    ) {

       }

    public function upload(UploadedFile $file): string 
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            
            if(strtoupper(substr(PHP_OS,0,3)) =="WIN"){
                $formated=str_replace('/','\\', $this->getTargetDirectory());
            }else{$formated=$this->getTargetDirectory();}     
            
            
           $this->photoNewPath=substr(dirname(__DIR__),0,strlen(dirname(__DIR__))-3).DIRECTORY_SEPARATOR.$formated;;
            $f=$file->move($this->getPhotoNewPath(), $fileName);
               

        } catch (FileException $e) {
           var_dump( 'FileUploader.php L30 : '.$e->getTraceAsString());
           echo "<p>Le fichier n'a pas été chargé. Merci, d'essayer à un autre moment ou bien veuillez contacter le service technique !</p>";
        }
  
        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function getPhotoNewPath(){
        return $this->photoNewPath;
    }
}