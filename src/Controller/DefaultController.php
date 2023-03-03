<?php

namespace App\Controller;

use Doctrine\DBAL\Exception;
use http\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use App\Service\SpotifyService;
use function PHPUnit\Framework\throwException;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function  index( SpotifyService $spotifyService) {
        $artists = $spotifyService->getArtists();
        $tracks  = $spotifyService->getTracks();
    return $this->render('home.html.twig', [
        'data' => $artists,
        'tracks'=>$tracks
    ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout(){}

}