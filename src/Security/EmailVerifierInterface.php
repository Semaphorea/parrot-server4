<?php
namespace App\Security;  
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;


interface EmailVerifierInterface{

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void;
    public function handleEmailConfirmation(Request $request, UserInterface $user): void;  

     
}