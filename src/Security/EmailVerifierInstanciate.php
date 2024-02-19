<?php
namespace App\Security;

use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Generator\VerifyEmailTokenGenerator;
use SymfonyCasts\Bundle\VerifyEmail\Util\VerifyEmailQueryUtility;


class EmailVerifierInstanciate  
{
    private EntityManagerInterface $em;
    private MailerInterface $mailer;
    private UrlGeneratorInterface $router;
    private VerifyEmailHelper $verifyEmailHelper;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $em, UrlGeneratorInterface $router)
    {
        $this->$mailer = $mailer;
        $this->router = $router;
        $this->em = $em;  
    }

    function instanciateEmailVerifier()
    {

        $queryUtility = new VerifyEmailQueryUtility();

        $generator = new VerifyEmailTokenGenerator($this->tokenGenrator(20));  
        $lifetime = 3600; //unity second
        $uriSigner = '';
        $this->verifyEmailHelper = new  VerifyEmailHelper($this->router, $uriSigner, $queryUtility, $generator, $lifetime);
       return new EmailVerifier($this->verifyEmailHelper, $this->mailer, $this->em); 
    }

    function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }
    
 

    function tokenGenrator($length)
    {
        {
            $token = "";
            $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
            $codeAlphabet.= "0123456789";
            $max = strlen($codeAlphabet); // edited
        
            for ($i=0; $i < $length; $i++) {
                $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
            }
        
            return $token;
        }
    }
}
