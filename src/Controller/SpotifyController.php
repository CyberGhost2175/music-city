<?php

namespace App\Controller;

use App\Service\SpotifyService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpotifyController extends  AbstractController
{

    #[Route('/artists/', name: 'artists_show')]

    public function artistShow(SpotifyService $spotifyService): Response
    {
        $artists = $spotifyService->getArtists();

        return $this->render('artist.html.twig', [
            'data' => $artists

        ]);
    }

#[Route('/albums/', name: 'albums_show')]
public  function albumsShow(SpotifyService $spotifyService) : Response{
    $albums = $spotifyService->getAlbums();

    return $this->render('albums.html.twig', [
        'data' => $albums

    ]);
}




}