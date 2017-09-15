<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Location;

class LoadLocationData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager) {
        $entree = new Location();
        //the url id of the location.
        $entree->setUrlName("entree");
        //the name of the location.
        $entree->setName("Entrée");
        //the description of the location.
        $entree->setDescription(
            "Parcourir cette aventure vous donnera des explications sur " . 
            "comment jouer. " .
            "Toutes les interactions se font avec des requêtes HTTP, que vous " . 
            "pouvez envoyer depuis l'interface dans le navigateur. " .
            "Vous pouvez utiliser les requêtes HTTP créées automatiquement " . 
            "en cliquant sur les liens." 
        );
        $entree->setAdventure($this->getReference('adventure'));

        $piece = new Location();
        $piece->setUrlName("piece");
        $piece->setName("Pièce");
        $piece->setDescription(
            "Dans cette pièce se trouve un objet. ".
            "Vous pouvez vous en rapprocher en utilisant les liens ". 
            "correspondant." 
        );
        $piece->setAdventure($this->getReference('adventure'));

        $couloir = new Location();
        $couloir->setUrlName("couloir");
        $couloir->setName("Couloir");
        $couloir->setDescription(
            "Vous possédez un inventaire. Essayez de ramasser la clef dans " . 
            "cette pièce pour ouvrir la porte. Pour utiliser un objet, il " . 
            "faut faire une requête PUT avec pour body le nom de l'objet."
        );
        $couloir->setAdventure($this->getReference('adventure'));

        $grandesalle = new Location();
        $grandesalle->setUrlName("grandesalle");
        $grandesalle->setName("Grande Salle");
        $grandesalle->setDescription(
            "Il est aussi possible de modifier les headers de la requête. " . 
            "Ce PNJ ne parle qu'en anglais, il faut donc rajouter le header " . 
            "\"Accept-Language: en\" à la requête lorsqu'on veut lui parler."
        );
        $grandesalle->setAdventure($this->getReference('adventure'));

        $treasureroom = new Location();
        $treasureroom->setUrlName("treasureroom");
        $treasureroom->setName("Salle du Trésor");
        $treasureroom->setDescription(
            "Vous avez terminé le tutoriel."
        );
        $treasureroom->setAdventure($this->getReference('adventure'));


        $manager->persist($entree);
        $manager->persist($piece);
        $manager->persist($grandesalle);
        $manager->persist($treasureroom);
        $manager->persist($couloir);
        $manager->flush();

        $this->addReference('entree',$entree);
        $this->addReference('piece',$piece);
        $this->addReference('grandesalle',$grandesalle);
        $this->addReference('treasureroom',$treasureroom);
        $this->addReference('couloir',$couloir);
    }

    public function getOrder() {
        return 2;
    }
}
