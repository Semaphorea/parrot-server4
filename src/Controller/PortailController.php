<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PortailController extends AbstractController 
{
    private ContainerBagInterface $params;

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route('/admin/portail', name: 'app_portail')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {

        $routeName = $request->attributes->get('_route');
        $page = substr($routeName, 4, strlen($routeName) - 1);
        $title = $this->params->get('app_title_website');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
  

        return $this->render('login/index.html.twig', [
            'page' => $page,
            'title' => $title,
            'navitem' => '',
            'breadcrump' => '',
            'last_username' => $lastUsername,
            'error'         => $error,


        ]);
    }
}
