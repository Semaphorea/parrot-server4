<?php

namespace App\Controller;

use App\Entity\Authentity;
use App\Form\RegistrationFormType;
use App\Repository\AuthentityRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/admin/createadminidentifiant', name: 'app_createadminidentifiant')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        $routeName = $request->attributes->get('_route');
        $page = substr($routeName, 4, strlen($routeName) - 1);
        $title = $this->getParameter('app_title_website');

        $admin = $entityManager->getRepository(Authentity::class)->findOneBy(['roles' => 'ROLE_ADMIN']);    
        if ($admin != null) {
            $request->get('session')->getFlashBag()->add('notice', 'Un administrateur est déjà présent en base de donnée. Veuillez vous tourner vers celui-ci pour en créer un nouveau.'); 
        } else {
            $user = new Authentity();
            $user->setRoles(['ROLE_ADMIN']);
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('contact@parrot.com', 'Mr Parrot'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                // do anything else you need here, like send an email

                return $this->redirectToRoute('app_accueil');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'page' => $page,
            'navitem' => '',
            "title" => $title,

        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, AuthentityRepository $authentityRepository): Response
    {

        $routeName = $request->attributes->get('_route');
        $page = substr($routeName, 4, strlen($routeName) - 1);
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_createadminidentifiant');
        }

        $user = $authentityRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_createadminidentifiant');
        }
        // validate email confirmation link, sets User::isVerified=true and persists  
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register', ['page' => $page]);
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register', ['page' => $page]);
    }



    //Todo : Envoie de mail avec token pour la creation de mot de passe.  
    #[Route('/employee/passwordcreation', name: 'app_passwordcreation')]
    public function modifypasswordemployee(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        $routeName = $request->attributes->get('_route');
        $page = substr($routeName, 4, strlen($routeName) - 1);  
        $title = $this->getParameter('app_title_website');
  
     
            $user = new Authentity();
            $user->setRoles(['ROLE_USER']);
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $entityManager->getRepository(Authentity::class)->findOneBy(['email'=> $form->get('email')->getData()]);      
                if($user==null){
                    $request->get('session')->getFlashBag()->add('notice', 'Vous n\' êtes pas répertorié sur notre base de donnée en tant qu\'employé, vous allez être redirigé dans quelques instants. En cas d\'erreur merci de le signaler à votre employeur.'); 
                //    $this->redirectToRoute('app_portail');  

                }

                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                
        
               

                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('contact@parrot.com', 'Mr Parrot'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')  
                );
                // do anything else you need here, like send an email

             //   return $this->redirectToRoute('app_portail');
            }
        

        return $this->render('registration/register.employee.html.twig', [
            'registrationForm' => $form->createView(),
            'page' => $page,
            'navitem' => '',
            "title" => $title,

        ]);
    }}


