<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\GameEntity;

class LoadGameEntityData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager) {
        $npc1 = new GameEntity();
        //type of the entity, will be shown as a title in the
        //user interface.
        $npc1->setType("Personnage");
        //the url id of the entity type.
        $npc1->setUrlType("npc");
        //the url id of the entity name
        $npc1->setUrlName("anglais");
        //the name of the entity
        $npc1->setName("An English Person");
        //the description of the entity when the user request it.
        $npc1->setDescription("Il n'a pas l'air de comprendre le français.");
        //the description of the entity when the user request the location.
        $npc1->setDescriptionFromAfar(
            "Vous apercevez un homme debout qui fixe le mur devant lui."
        );
        $npc1->setLocation($this->getReference('grandesalle'));

        $object1 = new GameEntity();
        $object1->setType("Objet");
        $object1->setUrlType("objet");
        $object1->setName("Feuille de Papier");
        $object1->setUrlName("papier");
        $object1->setDescription(
            "Il y a quelque chose de marqué sur le papier."
        );
        $object1->setDescriptionFromAfar(
            "Cet objet est une simple feuille de papier par terre."
        );
        $object1->setLocation($this->getReference('piece'));

        $object2 = new GameEntity();
        $object2->setType("Objet");
        $object2->setUrlType("objet");
        $object2->setName("Porte");
        $object2->setUrlName("porte");
        $object2->setDescription(
            "D'après ce qui est marqué sur la porte, un code est nécessaire" .
            " pour que cette dernière s'ouvre."
        );
        $object2->setDescriptionFromAfar(
            "Il faut passer par la porte pour aller à la pièce suivante."
        );
        $object2->setLocation($this->getReference('piece'));

        $object3 = new GameEntity();
        $object3->setType("Objet");
        $object3->setUrlType("objet");
        $object3->setName("Clef");
        $object3->setUrlName("clef");
        $object3->setDescription(
            "Une simple clef." 
        );
        $object3->setDescriptionFromAfar(
            "Une clef se trouve par terre."
        );
        //When the cookie name is set, the entity can be taken into the 
        //inventory, and the cookie created when you take the object is
        // $cookieName:$cookieName
        $object3->setCookieName('clefcouloir');
        $object3->setLocation($this->getReference('couloir'));

        $object4 = new GameEntity();
        $object4->setType("Objet");
        $object4->setUrlType("objet");
        $object4->setName("Porte");
        $object4->setUrlName("porte");
        $object4->setDescription(
            "Une porte avec une serrure."
        );
        $object4->setDescriptionFromAfar(
            "Une porte se trouve au bout du couloir."
        );
        $object4->setLocation($this->getReference('couloir'));

        $teapot = new GameEntity();
        $teapot->setType("Objet");
        $teapot->setUrlType("objet");
        $teapot->setName("Théière");
        $teapot->setUrlName("theiere");
        $teapot->setDescription(
            "C'est juste une théière."
        );
        $teapot->setDescriptionFromAfar(
            "Vous voyez une théière."
        );
        $teapot->setLocation($this->getReference('treasureroom'));


        $manager->persist($object1);
        $manager->persist($object2);
        $manager->persist($object3);
        $manager->persist($object4);
        $manager->persist($teapot);
        $manager->persist($npc1);
        $manager->flush();

        $this->addReference('npc1', $npc1);
        $this->addReference('papier', $object1);
        $this->addReference('portepiece', $object2);
        $this->addReference('clef', $object3);
        $this->addReference('portecouloir', $object4);
        $this->addReference('teapot', $teapot);

    }

    public function getOrder() {
        return 30;
    }
}
