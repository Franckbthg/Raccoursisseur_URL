<?php

namespace App\Service;

use App\Entity\Url;
use App\Repository\UrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class UrlService 
{
    private EntityManagerInterface $em; //enregistrer dans la bdd et de supp ou ajout d'élèment
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em; //j'attribue se que je recois en param
    }

    public function addUrl( string $longUrl, string $domain ): Url// permet de rajouter lien dans bdd
    {
        $url = new Url();
        $hash = $this-> generateHash();
        $link = $_SERVER['HTTP_ORIGIN'] . "/$hash";
        $url ->setLongUrl($longUrl); //lien partager à l'utilisateur, TROUVER LURL du server avec HTTP origin
        $url -> setDomain($domain);
        $url-> setHash($this ->generateHash());
        $url -> setLink($link);
        $url-> setHash($hash);
        $url->setCreatedAt(new \DateTime);

        $this->em->persist($url);
        $this->em->flush(); //syncro avec BDD

        return $url;
    }
//function qui retourne chaine de characters ou booleans
    public function parseUrl(string $url): string|bool //vérifiersi l'url de l'utilsateur exist et si il a une adress de site en ligne
    {
        $domain = parse_url($url, PHP_URL_HOST );
        if( $domain) {
            return false;
        }
        if (!filter_var(gethostbyname($domain), FILTER_VALIDATE_IP ))//verifie si le nom de domaine est bien en ligne sur internet
        {   return false;
        }
        return $domain;
       
    }

    public function generateHash(int $offset = 0, int $length = 8): string
    {
        return substr(md5(uniqid(mt_rand(), true)), $offset, $length); //generer notre H
    }

}