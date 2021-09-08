<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiAuteurController extends AbstractController
{
     /**
     * @Route("/api/auteurs", name="api_auteurs"), methods="GET")
     */
    public function list(AuteurRepository $repo, SerializerInterface $serializer): Response
    {
        $auteur =  $repo->findAll();
        $resultat = $serializer->serialize(
            $auteur,
            'json',
            [
                'groups' => ['listeAuteurFull']
            ]
        );
        return new JsonResponse($resultat,200,[],true);
    }
     /**
     * @Route("/api/auteurs/{id}", name="api_auteur_show"), methods="GET")
     */
    public function lsho(Auteur $auteur, SerializerInterface $serializer): Response
    {
     
        $resultat = $serializer->serialize(
            $auteur,
            'json',
            [
                'groups' => ['listeAuteurSimple']
            ]
        );
        return new JsonResponse($resultat,Response::HTTP_OK,[],true);
    }
     /**
     * @Route("/api/auteurs/", name="api_auteurs_create"), methods="POST")
     */
    public function create(Request $request,EntityManagerInterface $manager, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $auteur = new Auteur();
        #$serializer->deserialize($data,Genre::class,'json',['object_to_populate' => $genre]);
        $genre = $serializer->deserialize($data,Auteur::class,'json');
        $manager->persist($auteur);
        $manager->flush(); 
        return new JsonResponse("Le genre a bien été crée",Response::HTTP_CREATED,
            //["location"=>"api/genres/".$genre->getId()]
            ["location" => $this->generateUrl('api_genre_show',
            ['id' => $genre->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL)],
            true);
        
    }
     /**
     * @Route("/api/auteurs/{id}", name="api_auteur_update"), methods="PUT")
     */
    public function update(EntityManagerInterface $manager,Request $request,Auteur $auteur, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $resultat = $serializer->deserialize($data,Auteur::class,'json',['object_to_populate' => $auteur]);
        $manager->persist($auteur);
        $manager->flush(); 
      
        return new JsonResponse("L'auteur a été modifié",Response::HTTP_OK,[],true);
    }
      /**
     * @Route("/api/auteurs/{id}", name="api_auteur_delete"), methods="DELETE")
     */
    public function delete(EntityManagerInterface $manager,Auteur $auteur): Response
    {
        $manager->remove($auteur);
        $manager->flush(); 
      
        return new JsonResponse("L'auteur a été supprimé",Response::HTTP_OK,[],true);
    }
       
   
}
