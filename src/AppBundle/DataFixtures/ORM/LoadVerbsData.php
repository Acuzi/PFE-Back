<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Verb;

class LoadVerbData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager) {
        $parler = new Verb();
        $parler->setUrl("parler");
        $parler->setEntity($this->getReference('npc1'));

        $lire = new Verb();
        $lire->setUrl("lire");
        $lire->setEntity($this->getReference('papier'));

        $ouvrirpiece = new Verb();
        $ouvrirpiece->setUrl("ouvrir");
        $ouvrirpiece->setEntity($this->getReference('portepiece'));

        $ouvrircouloir = new Verb();
        $ouvrircouloir->setUrl("ouvrir");
        $ouvrircouloir->setEntity($this->getReference('portecouloir'));

        $prendre = new Verb();
        $prendre->setUrl("prendre");
        $prendre->setEntity($this->getReference('clef'));

        $coffee = new Verb();
        $coffee->setUrl("cafe");
        $coffee->setEntity($this->getReference('teapot'));

        $manager->persist($parler);
        $manager->persist($lire);
        $manager->persist($ouvrirpiece);
        $manager->persist($ouvrircouloir);
        $manager->persist($prendre);
        $manager->persist($coffee);

        $manager->flush();
        $this->addReference('parler', $parler);
        $this->addReference('lire', $lire);
        $this->addReference('ouvrirpiece', $ouvrirpiece);
        $this->addReference('ouvrircouloir', $ouvrircouloir);
        $this->addReference('prendre', $prendre);
        $this->addReference('coffee', $coffee);

    }

    public function getOrder() {
        return 33;
    }
}
