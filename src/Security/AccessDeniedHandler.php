<?php
// src/Security/AccessDeniedHandler.php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
       $content="Vous n'êtes pas autorisé à entrer sur cette page. Veuillez essayer de vous autentifier de nouveau. Si le problème persiste merci d'envoyer un email au service technique à l'adresse suivante : contact@parot.com";
  
        return new Response($content, 403);
    }
}