<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Location;
use AppBundle\Entity\GameEntity;
use AppBundle\Entity\Adventure;
use AppBundle\Entity\Path;
use AppBundle\Form\NpcType;
use AppBundle\Form\LocationType;
use AppBundle\Form\GameObjectType;
use Symfony\Component\HttpFoundation\Cookie;

class AdventureController extends Controller
{
    private function createErrorResponse($msg) {
        $errorArray = [];
        $errorArray["name"] = "Erreur";
        $errorArray['desc'] = $msg;
        $errorArray['type'] = "Erreur";
        $errorResponse = new JsonResponse($errorArray);
        $errorResponse->setStatusCode(404);
        return $errorResponse;

    }

    /**
     * @Route("/", name="start")
     */
    public function onStartAction(Request $request)
    {
        return $this->redirectToRoute('location_show', array('loc' => 'entree'));
    }

    /**
     * @Route("/{loc}", name="location_show")
     */
    public function onLocationAction(Request $request, $loc)
    {
        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository('AppBundle:Location')
            ->findOneByUrlName($loc);

        if (!$location) {
            return $this->createErrorResponse("Il n'y a rien ici.");
        }

        $infoArray = [];
        $infoArray['name'] = $location->getName();
        $infoArray['type'] = 'Lieu';
        $infoArray['desc'] = $location->createFullDescription();
        $infoArray['action'] = $location->createActions();
        
        return new JsonResponse($infoArray);

    }

    /**
     * @Route("/{loc}/{entityType}")
     */
    public function onWrongPathAction(Request $request) {
        return $this->createErrorResponse("Il n'y a rien ici.");
    }

    /**
     * @Route("/{loc}/{entityType}/{name}", name="entity_show")
     */
    public function onEntityAction(Request $request,$loc, $entityType, $name)
    {
        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository('AppBundle:Location')->findOneByUrlName($loc);
        $entity = $em->getRepository('AppBundle:GameEntity')->findOneBy(
                array('urlName' => $name, 'location' => $location, 'urlType' => $entityType)
            );
        if(!$location || !$entity) {
            return $this->createErrorResponse("Il n'y a rien ici.");
        }

        $infoArray = [];
        $infoArray['name'] = $entity->getName();
        $infoArray['type'] = $entity->getType();
        $infoArray['desc'] = $entity->getDescription();

        foreach ($entity->getVerbs() as $verb) {
            foreach ($verb->getActions() as $action) {
                if ($action->getIsVisible()) {
                    $infoArray["action"][] = 
                        "<a href='' ng-click='link(\"/" . $entity->getLocation()->getUrlName() . 
                        "/" . $entity->getUrlType() ."/" . $entity->getUrlName() . "/" .
                        $verb->getUrl() . 
                        (($action->getUrl() != "") ? "/" : "") .
                        $action->getUrl() .
                        "\")'>" . $action->getDescription() . "</a>"
                        ;
                }
            }
        }
        $infoArray['action'][] = 
            "<a href='' ng-click='link(\"/" . 
            $entity->getLocation()->getUrlName() . 
            "\")'>Retour.</a>";

        $cookies = $request->cookies;
        $cookieName = $entity->getCookieName();
        if ($cookieName && 
            ($cookies->get($cookieName) == $cookieName)
        ) 
        {
            $infoArray["name"] = "Vide";
            $infoArray["desc"] = "Il n'y a plus rien ici.";
            $infoArray['action'] = [];
            $infoArray['action'][] =
                "<a href='' ng-click='link(\"/" . 
                $entity->getLocation()->getUrlName() . 
                "\")'>Retour.</a>";

        }
        return new JsonResponse($infoArray);
    }

    /**
     * @Route("/{loc}/{entityType}/{name}/{verbId}/{arg}", name="any_action")
     */
   
    public function onActionAction(
        Request $request, $loc, $entityType, $name, $verbId, $arg="")
    {
        //load everything from the database
        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository('AppBundle:Location')->findOneByUrlName($loc);
        $entity = $em->getRepository('AppBundle:GameEntity')->findOneBy(
            array(
                'urlName' => $name, 
                'location' => $location, 
                'urlType' => $entityType
            ));
        $verb = $em->getRepository('AppBundle:Verb')->findOneBy(
                array('entity' => $entity, 'url' => $verbId)
            );
        $action = $em->getRepository('AppBundle:Action')->findOneBy(
                array('verb' => $verb, 'url' => $arg)
            );

        // check if every object has been found in the database
        if(!$location || !$entity) {
            return $this->createErrorResponse("Il n'y a rien ici.");
        }
        if(!$verb || !$action) {
            return $this->createErrorResponse("Vous ne pouvez pas effectuer cette action.");
        }

        //check if the request have the good parameters
        $errors = $action->checkRequest($request);
        if (count($errors) > 0) {
            $errorMessage = 
                'La requête est refusée.';
            foreach ($errors as $error) {
                $errorMessage = $errorMessage . " " . $error;
            }
            $errorArray = [];
            $errorArray["name"] = "Erreur";
            $errorArray['desc'] = $errorMessage;
            $errorArray['type'] = "Erreur";
            $errorResponse = new JsonResponse($errorArray);
            if (strpos($errorResponse,"header")) {
                $errorResponse->setStatusCode(406);
            }
            else {
                $errorResponse->setStatusCode(404);
            }
            $headers = $action->getHeaders();
            if ($headers) {
                foreach ($headers as $headerKey => $headerValue) {
                    $errorResponse->headers->set($headerKey, $headerValue);
                }
            }
            return $errorResponse;
        }

        //create the Json
        $infoArray = [];
        $infoArray['name'] = $entity->getName() . " - " . $action->getName();
        $infoArray['desc'] = $action->getFullDescription();
        $infoArray['type'] = "Action";
        $infoArray['action'] = [];
        $infoArray['action'][] = 
            "<a href='' ng-click='link(\"/". $entity->getLocation()->getUrlName() .
            "\")'>Retour.</a>";

        //check if the item is in the inventory
        //if yes, show that is not available anymore.
        $cookies = $request->cookies;
        $cookieName = $entity->getCookieName();
        if ($cookieName && 
            ($cookies->get($cookieName) == $cookieName)
        ) 
        {
            $infoArray['desc'] = "Il n'y a rien à faire ici.";
        }
        $response = new JsonResponse($infoArray);
        $headers = $action->getHeaders();
        if ($headers) {
            foreach ($headers as $headerKey => $headerValue) {
                $response->headers->set($headerKey, $headerValue);
            }
        }
        $response->setStatusCode($action->getReturnedStatusCode());
        
        $action->createCookie();
        $cookie = $action->getCookie();
        if ($cookie) {
            $response->headers->setCookie($cookie);
        }
        return $response;
    }

    /**
     * @Route("/createadventure", name="create")
     */
  /*  public function onCreatingAdventure(Request $request)
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();
            return $this->redirectToRoute("start");
        }

        return $this->render('adventure/location.html.twig', array(
            "form" => $form->createView()
        ));
    }
*/
}
