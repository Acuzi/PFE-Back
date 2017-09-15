<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Action;

class LoadActionsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager) {

        $action1 = new Action();
        // default action for verb "lire".
        $action1->setVerb($this->getReference('lire'));
        //empty url means this action will be called when
        //the "argument" part of the url is empty.
        // GET /piece/objet/papier/lire will call this action.
        $action1->setUrl("");
        $action1->setName("Lire");
        //setting this to true means that the action will
        //be shown to the user when he requests the object.
        $action1->setIsVisible(true);
        //this description will be shown to the user when he requests
        //the object. This attribute is useless if isVisible is false.
        $action1->setDescription("Lire le papier.");
        //this description will be shown to the user when he requests
        //the action.
        $action1->setFullDescription("Le code pour ouvrir la porte est motdepasse.");

        //the status code returned by the action
        $action1->setReturnedStatusCode(200);

        $action2 = new Action();
        $action2->setUrl("");
        $action2->setName("Parler");
        $action2->setIsVisible(true);

        //headers that will be returned by the action.
        $headers = [];
        $headers["accept-language"] = "en";
        $action2->setHeaders($headers);
        $action2->setDescription("Lui parler.");
        $action2->setFullDescription(
            "You can go to the final location now. Try to get the location" . 
            " treasureroom"
);
        $action2->setVerb($this->getReference('parler'));
        $action2->setReturnedStatusCode(200);
        //headers required in order for the action to succeed.
        $requiredHeaders = [];
        $requiredHeaders["accept-language"] = "en";
        $action2->setRequiredHeaders($requiredHeaders);

        $action3 = new Action();
        $action3->setUrl("");
        $action3->setName("Ouvrir");
        $action3->setIsVisible(true);
        $action3->setDescription("Essayer d'ouvrir la porte.");
        $action3->setFullDescription('La porte ne veut pas s\'ouvrir.');
        $action3->setVerb($this->getReference('ouvrirpiece'));
        $action3->setReturnedStatusCode(403);

        $action4 = new Action();
        $action4->setUrl("motdepasse");
        $action4->setName("Ouvrir");
        $action4->setIsVisible(false);
        $action4->setDescription("");
        $action4->setFullDescription(
            'La porte s\'ouvre et vous permet d\'aller vers ' . 
            "<a href='' ng-click='link(\"/couloir\")'>". 
            "la prochaine salle.</a>"
        );
        $action4->setVerb($this->getReference('ouvrirpiece'));
        $action4->setReturnedStatusCode(200);

        $action5 = new Action();
        $action5->setUrl("");
        $action5->setName("Ouvrir");
        $action5->setIsVisible(true);
        $action5->setDescription(
            "Essayer d'ouvrir la porte."
        );
        $action5->setFullDescription(
            'La porte s\'ouvre et vous permet d\'aller vers ' . 
            "<a href='' ng-click='link(\"/grandesalle\")'>". 
            "la prochaine salle.</a>"
        );
        $action5->setVerb($this->getReference('ouvrircouloir'));
        $action5->setReturnedStatusCode(200);
        $items = [];
        $items[] = "clefcouloir";
        //the required items in order for the action to succeed.
        //the items are stored in a cookie with a key/value pair
        //equal to cookieName=cookieName
        $action5->setRequiredItems($items);
        $action5->setRequiredHttpMethod("PUT");
        $action5->setRequiredBody("clefcouloir");

        $action6 = new Action();
        $action6->setUrl("");
        $action6->setName("Prendre");
        $action6->setIsVisible(true);
        $action6->setDescription(
            "Prendre la clef."
        );
        $action6->setFullDescription(
            "Vous prenez la clef."
        );
        $action6->setVerb($this->getReference('prendre'));
        $action6->setReturnedStatusCode(200);

        $coffee = new Action();
        $coffee->setVerb($this->getReference('coffee'));
        $coffee->setUrl("");
        $coffee->setName("Faire du café");
        $coffee->setIsVisible(true);
        $coffee->setDescription(
            "Se servir un café."
        );
        $coffee->setFullDescription(
            "Vous avez essayé de faire du café avec une théière."
        );
        $coffee->setReturnedStatusCode(418);

        //command to persist actions in database 
        $manager->persist($action1);
        $manager->persist($action2);
        $manager->persist($action3);
        $manager->persist($action4);
        $manager->persist($action5);
        $manager->persist($action6);
        $manager->persist($coffee);
        $manager->flush();
        $this->addReference('action1', $action1);
        $this->addReference('action2', $action2);
        $this->addReference('action3', $action3);
        $this->addReference('action4', $action4);
        $this->addReference('action5', $action5);
        $this->addReference('action6', $action6);
        $this->addReference('coffeeaction', $coffee);

    }

    public function getOrder() {
        return 35;
    }
}
