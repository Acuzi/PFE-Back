<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Adventure;

class LoadAdventureData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager) {
        $adventure = new Adventure();
        $adventure->setName("niveau1");
        $manager->persist($adventure);
        $manager->flush();

        $this->addReference('adventure', $adventure);

    }

    public function getOrder() {
        return 1;
    }
}
