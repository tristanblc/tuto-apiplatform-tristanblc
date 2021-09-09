<?php 
namespace App\Service;

use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber implements EventSubscriberInterface
{
 private $token;

 public function __construct(TokenStorageInterface $token)
 {
     $this->token = $token;
 }
 public static function getSubscribedEvents()
 {
     return[
         KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
     ];
     
 }

 public function getAuthenticatedUser(ViewEvent $event){
     $entity = $event->getControllerResult();
     $method = $event->getRequest();
     $adherent = $this->tokenStorage->getToken()->getUser();
     if($entity instanceof Pret && $method == Request::METHOD_POST){
         $entity->setAdherent($adherent);
     }
     return;

 }

}