<?php  
// src/Security/AuthenticationEntryPoint.php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;


class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
         // add a custom flash message and redirect to the login page
         //$session=$request->getSession(); //initial line pb getFlashBag was undefined
         $session=$request->get('session');  
        if($session != null) $session->getFlashBag()->add('note', 'You have to login in order to access this page.');
        //$session->addFlash('note', 'You have to login in order to access this page.');
        //$session->getBag('flashes')->add('note', 'You have to login in order to access this page.'); //Toutes  fonctionnent  
        return new RedirectResponse($this->urlGenerator->generate('app_portail'));
    }
}