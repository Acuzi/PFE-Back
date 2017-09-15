<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Path;

class LoadPathData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager) {
        $path1 = new Path();
        $path1->setDescription("Passez dans la prochaine pièce en " . 
            "cliquant sur le lien ci-dessous.");
        $path1->setLocation1($this->getReference('entree'));
        $path1->setLocation2($this->getReference('piece'));

        $path1->setAdventure($this->getReference('adventure'));

        $manager->persist($path1);
        $manager->flush();

        $this->addReference('path1',$path1);
    }

    public function getOrder() {
        return 6;
    }
}
