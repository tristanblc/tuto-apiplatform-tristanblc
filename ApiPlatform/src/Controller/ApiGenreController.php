<?php

namespace App\Controller;


use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres"), methods="GET")
     */
    public function list(GenreRepository $repo, SerializerInterface $serializer): Response
    {
        $genres =  $repo->findAll();
        $resultat = $serializer->serialize(
            $genres,
            'json',
            [
                'groups' => ['listeGenreFull']
            ]
        );
        return new JsonResponse($resultat,200,[],true);
    }
     /**
     * @Route("/api/genres/{id}", name="api_genre_show"), methods="GET")
     */
    public function lsho(Genre $genre, SerializerInterface $serializer): Response
    {
     
        $resultat = $serializer->serialize(
            $genre,
            'json',
            [
                'groups' => ['listeGenreSimple']
            ]
        );
        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }
     /**
     * @Route("/api/genres/", name="api_genre_create"), methods="POST")
     */
    public function create(Request $request,EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $genre = new Genre();
        #$serializer->deserialize($data,Genre::class,'json',['object_to_populate' => $genre]);
        $genre = $serializer->deserialize($data,Genre::class,'json');
        $manager->persist($genre);
        $manager->flush(); 
        return new JsonResponse("Le genre a bien été crée",Response::HTTP_CREATED,
            //["location"=>"api/genres/".$genre->getId()]
            ["location" => $this->generateUrl('api_genre_show',
            ['id' => $genre->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL)],
            true);
        
    }
     /**
     * @Route("/api/genres/{id}", name="api_genre_update"), methods="PUT")
     */
    public function update(EntityManagerInterface $manager,Request $request,Genre $genre, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $resultat = $serializer->deserialize($data,Genre::class,'json',['object_to_populate' => $genre]);
        $manager->persist($genre);
        $manager->flush(); 
      
        return new JsonResponse("Le genre a été modifié",Response::HTTP_OK,[],true);
    }
      /**
     * @Route("/api/genres/{id}", name="api_genre_delete"), methods="DELETE")
     */
    public function delete(EntityManagerInterface $manager,Genre $genre, SerializerInterface $serializer): Response
    {
        $manager->remove($genre);
        $manager->flush(); 
      
        return new JsonResponse("Le genre a été supprimé",Response::HTTP_OK,[],true);
    }
       
}
