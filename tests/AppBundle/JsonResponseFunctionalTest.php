<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

class JsonResponseFunctionalTest extends WebTestCase {
    
    /**
     * @dataProvider urlProvider
     *           
     */

    public function testJsonIsJsonResponse($url) {
        $client = self::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful(), "The response is a success.");
        $this->assertTrue( $response instanceof JsonResponse, "The response is a JsonResponse");
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
    }

    public function urlProvider() {
        return array(
            array('/entree'),
            array('/piece'),
            array('/piece/objet/papier'),
            array('/piece/objet/porte/ouvrir/motdepasse'),
            array('/couloir'),
            array('/grandesalle'),
            array('/grandesalle/npc/anglais'),
            array('/treasureroom'),
        );
    }

    /**
     * @dataProvider wrongUrlProvider
     *           
     */

    public function testUnknownRequest($url) {
        $client = self::createClient();
        $client->request('GET', $url);
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $response = $client->getResponse()->getStatusCode();
        $this->assertEquals(
            404, $response
        );
        $this->assertEquals("Erreur", $jsonArray["type"]);
        $this->assertEquals("Erreur", $jsonArray["name"]);
        $this->assertEquals("Il n'y a rien ici.", $jsonArray["desc"]);
    }

    public function wrongUrlProvider() {
        return array(
            array('/unemauvaiseurl'),
            array('/unemauvaiseurl/objet/objetinexistant'),
            array('/unemauvaiseurl/unemauvaisentite'),
            array('/unemauvaiseurl/unemauvaisentite/objetinexistant'),
            array('/unemauvaiselocation/npc/anglais/mauvaiseaction'),
            array('/entree/objet/objetinexistant'),
            array('/entree/unemauvaisentite'),
            array('/entree/unemauvaisentite/objetinexistant'),
            array('/entree/npc/objetinexistant'),
            array('/grandesalle/npc/papier/lire/mauvaisargument')
        );
    }

    /**
     * @dataProvider wrongActionUrlProvider
     *           
     */

    public function testUnknownActionRequest($url) {
        $client = self::createClient();
        $client->request('GET', $url);
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $response = $client->getResponse()->getStatusCode();
        $this->assertEquals(
            404, $response 
        );
        $this->assertEquals("Erreur", $jsonArray["type"]);
        $this->assertEquals("Erreur", $jsonArray["name"]);
        $this->assertEquals("Vous ne pouvez pas effectuer cette action.", 
            $jsonArray["desc"]);
    }

    public function wrongActionUrlProvider() {
        return array(
            array('/grandesalle/npc/anglais/mauvaiseaction'),
            array('/piece/objet/papier/lire/mauvaisargument'),
        );
    }

    public function testFirstLocation() {
        $client = self::createClient();
        $client->request('GET', '/entree');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Lieu", $jsonArray["type"]);
        $this->assertEquals("Entrée", $jsonArray["name"]);
        $this->assertEquals(
            "Parcourir cette aventure vous donnera des explications sur " . 
            "comment jouer. " .
            "Toutes les interactions se font avec des requêtes HTTP, que vous " . 
            "pouvez envoyer depuis l'interface dans le navigateur. " .
            "Vous pouvez utiliser les requêtes HTTP créées automatiquement " . 
            "en cliquant sur les liens. Passez dans la prochaine pièce en " . 
            "cliquant sur le lien ci-dessous."
            ,
            $jsonArray["desc"]
         );
        $this->assertEquals(
            array(
                "Aller vers <a href='' ng-click='link(\"/piece\")'>" . 
                "Pièce</a>.",
            ),
            $jsonArray["action"]
        );
    }

    public function testSecondLocation() {
        $client = self::createClient();
        $client->request('GET', '/piece');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Lieu", $jsonArray["type"]);
        $this->assertEquals("Pièce", $jsonArray["name"]);
        $this->assertEquals(
            "Dans cette pièce se trouve un objet. ".
            "Vous pouvez vous en rapprocher en utilisant les liens " .
            "correspondant. " .
            "Cet objet est une simple feuille de papier par terre. " . 
            "Il faut passer par la porte pour aller à la pièce suivante."
            ,
             $jsonArray["desc"]
         );
        $this->assertEquals(
            array(
                "Se rapprocher de <a href='' ".
                "ng-click='link(\"/piece/objet/papier\")'>Feuille de Papier</a>.",
                "Se rapprocher de <a href='' ".
                "ng-click='link(\"/piece/objet/porte\")'>Porte</a>."
            ),
            $jsonArray["action"]
        );
    }

    public function testThirdLocation() {
        $client = self::createClient();
        $client->request('GET', '/couloir');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Lieu", $jsonArray["type"]);
        $this->assertEquals("Couloir", $jsonArray["name"]);
        $this->assertEquals(
            'Vous possédez un inventaire. Essayez de ramasser la clef dans ' .
            "cette pièce pour ouvrir la porte. Pour utiliser un objet, il " . 
            "faut faire une requête PUT avec pour body le nom de l'objet. " .
            "Une clef se trouve par terre. Une porte se trouve au bout du ".
            "couloir." 
            ,
             $jsonArray["desc"]
         );
        $this->assertEquals(
            array(
                "Se rapprocher de <a href='' ".
                "ng-click='link(\"/couloir/objet/clef\")'>Clef</a>.",
                "Se rapprocher de <a href='' ".
                "ng-click='link(\"/couloir/objet/porte\")'>Porte</a>."
            ),
            $jsonArray["action"]
        );
    }

    public function testFourthLocation() {
        $client = self::createClient();
        $client->request('GET', '/grandesalle');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Lieu", $jsonArray["type"]);
        $this->assertEquals("Grande Salle", $jsonArray["name"]);
        $this->assertEquals(
            "Il est aussi possible de modifier les headers de la requête." . 
            " Ce PNJ ne parle qu'en anglais, il faut donc rajouter le header " . 
            "\"Accept-Language: en\" à la requête lorsqu'on veut lui parler. " .
            "Vous apercevez un homme debout qui fixe le mur devant lui."
            ,
             $jsonArray["desc"]
         );
        $this->assertEquals(
            array(
                "Se rapprocher de <a href='' ".
                "ng-click='link(\"/grandesalle/npc/anglais\")'>An English Person</a>."
            ),
            $jsonArray["action"]
        );
    }

    public function testFifthLocation() {
        $client = self::createClient();
        $client->request('GET', '/treasureroom');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Lieu", $jsonArray["type"]);
        $this->assertEquals("Salle du Trésor", $jsonArray["name"]);
        $this->assertEquals(
            "Vous avez terminé le tutoriel. Vous voyez une théière.",
             $jsonArray["desc"]
         );
        $this->assertEquals(
            array(
                "Se rapprocher de <a href='' ".
                "ng-click='link(\"/treasureroom/objet/theiere\")'>Théière</a>."
            ),
            $jsonArray["action"]
        );
    }

    public function testNpc() {
        $client = self::createClient();
        $client->request('GET', '/grandesalle/npc/anglais');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Personnage",$jsonArray["type"]);
        $this->assertEquals("An English Person", $jsonArray["name"]);
        $this->assertEquals(
            "Il n'a pas l'air de comprendre le français.",
            $jsonArray["desc"] 
         );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/grandesalle/npc/anglais/parler\")'>Lui parler.</a>",
                "<a href='' ng-click='link(\"/grandesalle\")'>Retour.</a>",
            ),
            $jsonArray["action"]
        );
    }

    public function testSheet() {
        $client = self::createClient();
        $client->request('GET', '/piece/objet/papier');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Objet", $jsonArray["type"]);
        $this->assertEquals("Feuille de Papier", $jsonArray["name"]);
        $this->assertEquals(
            "Il y a quelque chose de marqué sur le papier.",
            $jsonArray["desc"] 
        );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/piece/objet/papier/lire\")'>" .
                "Lire le papier.</a>",
                "<a href='' ng-click='link(\"/piece\")'>Retour.</a>"
            ),
            $jsonArray["action"]
        );
    }

    public function testDoor() {
        $client = self::createClient();
        $client->request('GET', '/piece/objet/porte');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Objet", $jsonArray["type"]);
        $this->assertEquals("Porte", $jsonArray["name"]);
        $this->assertEquals(
            "D'après ce qui est marqué sur la porte, un code est nécessaire" .
            " pour que cette dernière s'ouvre.",
            $jsonArray["desc"] 
        );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/piece/objet/porte/ouvrir\")'>" .
                "Essayer d'ouvrir la porte.</a>",
                "<a href='' ng-click='link(\"/piece\")'>Retour.</a>"
            ),
            $jsonArray["action"]
        );
    }
    
    public function testSecondDoor() {
        $client = self::createClient();
        $client->request('GET', '/couloir/objet/porte');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Objet", $jsonArray["type"]);
        $this->assertEquals("Porte", $jsonArray["name"]);
        $this->assertEquals(
            "Une porte avec une serrure.",
            $jsonArray["desc"] 
        );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/couloir/objet/porte/ouvrir\")'>" .
                "Essayer d'ouvrir la porte.</a>",
                "<a href='' ng-click='link(\"/couloir\")'>Retour.</a>"
            ),
            $jsonArray["action"]
        );
    }
    
    public function testKey() {
        $client = self::createClient();
        $client->request('GET', '/couloir/objet/clef');
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Objet", $jsonArray["type"]);
        $this->assertEquals("Clef", $jsonArray["name"]);
        $this->assertEquals(
            "Une simple clef.",
            $jsonArray["desc"] 
        );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/couloir/objet/clef/prendre\")'>" .
                "Prendre la clef.</a>",
                "<a href='' ng-click='link(\"/couloir\")'>Retour.</a>"
            ),
            $jsonArray["action"]
        );
    }

    public function testKeyWithCookie() {
        $client = self::createClient();
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie("clefcouloir","clefcouloir"));
        $client->request('GET', '/couloir/objet/clef');
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Objet", $jsonArray["type"]);
        $this->assertEquals("Vide", $jsonArray["name"]);
        $this->assertEquals(
            "Il n'y a plus rien ici.",
            $jsonArray["desc"] 
        );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/couloir\")'>Retour.</a>"
            ),
            $jsonArray["action"]
        );
    }

    public function testTakeAction() {
        $client = self::createClient();
        $client->request('GET', '/couloir/objet/clef/prendre');
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Action", $jsonArray["type"]);
        $this->assertEquals("Clef - Prendre", $jsonArray["name"]);
        $this->assertEquals(
            "Vous prenez la clef." 
            , 
            $jsonArray["desc"]
        );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/couloir\")'>Retour.</a>"
            ),
            $jsonArray["action"]
        );
        $cookieArray = [];
        $cookieArray[] = new Cookie("clefcouloir","clefcouloir", 0, '/', null, false, false);
        $this->assertEquals(
            $cookieArray,
            $client->getResponse()->headers->getCookies()
        );
    }

    public function testTakeActionWithCookie() {

        $client = self::createClient();
        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie("clefcouloir","clefcouloir"));
        $client->request(
            'GET', '/couloir/objet/clef/prendre',
            array(),
            array(),
            array()
        );
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals("Action", $jsonArray["type"]);
        $this->assertEquals("Clef - Prendre", $jsonArray["name"]);
        $this->assertEquals(
            "Il n'y a rien à faire ici." 
            , 
            $jsonArray["desc"]
        );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/couloir\")'>Retour.</a>"
            ),
            $jsonArray["action"]
        );

    }

    public function testTalkAction() {
        //request without header
        $client = self::createClient();
        $client->request('GET', '/grandesalle/npc/anglais/parler');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            406, 
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals("Erreur", $jsonArray["type"]);
        $this->assertEquals("Erreur", $jsonArray["name"]);
        $this->assertEquals(
            'La requête est refusée. Mauvais header.'
            , 
            $jsonArray["desc"]
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                "Accept-Language",
                'en'
            )
        );
        
       //request with correct header 
        $client = self::createClient();
        $client->request(
            'GET', 
            '/grandesalle/npc/anglais/parler',
            array(),
            array(),
            array(
                'HTTP_ACCEPT_LANGUAGE' => 'en'
            )
        );
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            "You can go to the final location now. Try to get the location" . 
            " treasureroom"
,
            $jsonArray["desc"]
        );
        $this->assertEquals("Action", $jsonArray["type"] );
        $this->assertEquals("An English Person - Parler", $jsonArray["name"] );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/grandesalle\")'>" .
                "Retour.</a>",
            ),
            $jsonArray["action"]
        );
        $this->assertEquals(
            200, 
            $client->getResponse()->getStatusCode()
        );
    }

    public function testGetOpenWithoutKeyAction() {
        $client = self::createClient();
        $client->request('GET', '/couloir/objet/porte/ouvrir');
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            404, 
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals("Erreur", $jsonArray["type"]);
        $this->assertEquals("Erreur",
            $jsonArray["name"]);
        $this->assertEquals(
            'La requête est refusée. '.
            "La mauvaise méthode est utilisée. ". 
            "Il vous manque un objet. Le corps de la requête n'est pas le bon."
            , 
            $jsonArray["desc"]
        );
    }

    public function testOpenWithoutKeyAction() {
        $client = self::createClient();
        $client->request('PUT', '/couloir/objet/porte/ouvrir');
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            404, 
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals("Erreur", $jsonArray["type"]);
        $this->assertEquals("Erreur",
            $jsonArray["name"]);
        $this->assertEquals(
            'La requête est refusée. Il vous manque un objet. ' . 
            'Le corps de la requête n\'est pas le bon.',             
            $jsonArray["desc"]
        );
    }

    public function testOpenWithKeyAction() {
        $client = self::createClient();

        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie("clefcouloir","clefcouloir"));
        $client->request(
            'PUT', '/couloir/objet/porte/ouvrir',
            array(),
            array(),
            array(),
            "clefcouloir"
        );
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            200, 
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals("Action", $jsonArray["type"]);
        $this->assertEquals("Porte - Ouvrir",
            $jsonArray["name"]);
        $this->assertEquals(
            'La porte s\'ouvre et vous permet d\'aller vers ' . 
            "<a href='' ng-click='link(\"/grandesalle\")'>". 
            "la prochaine salle.</a>",
             
            $jsonArray["desc"]
        );
    }
    

    public function testReadAction() {
        $client = self::createClient();
        $client->request('GET', '/piece/objet/papier/lire');

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            200, 
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals("Action", $jsonArray["type"]);
        $this->assertEquals("Feuille de Papier - Lire",
            $jsonArray["name"]);
        $this->assertEquals(
            'Le code pour ouvrir la porte est motdepasse.'
            , 
            $jsonArray["desc"]
        );
    }

    public function testOpenAction() {
        $client = self::createClient();
        $client->request(
            'GET', 
            '/piece/objet/porte/ouvrir'
            );
        

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            'La porte ne veut pas s\'ouvrir.',
            $jsonArray["desc"]
        );
        $this->assertEquals("Action", $jsonArray["type"]);
        $this->assertEquals("Porte - Ouvrir", $jsonArray["name"]);
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/piece\")'>Retour.</a>",
            ),
            $jsonArray["action"]
        );
        $this->assertEquals(
            403, 
            $client->getResponse()->getStatusCode()
        );
    }

    public function testOpenPasswordAction() {
        $client = self::createClient();
        $client->request(
            'GET', 
            '/piece/objet/porte/ouvrir/motdepasse'
        );
       

        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            'La porte s\'ouvre et vous permet d\'aller vers ' . 
            "<a href='' ng-click='link(\"/couloir\")'>". 
            "la prochaine salle.</a>",
            $jsonArray["desc"]
        );
        $this->assertEquals("Action", $jsonArray["type"]);
        $this->assertEquals("Porte - Ouvrir", $jsonArray["name"]);
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/piece\")'>Retour.</a>",
            ),
            $jsonArray["action"]
        );
        $this->assertEquals(
            200, 
            $client->getResponse()->getStatusCode()
        );
    }

    public function testTeapot() {
        $client = self::createClient();
        $client->request('GET', '/treasureroom/objet/theiere');
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            "Théière",
            $jsonArray["name"]
        );
        $this->assertEquals(
            "C'est juste une théière.",
            $jsonArray["desc"]
        );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/treasureroom/objet/theiere/cafe\")'>Se servir un café.</a>",
                "<a href='' ng-click='link(\"/treasureroom\")'>Retour.</a>",
            ),
            $jsonArray["action"]
        );
    }
    public function testCoffeeAction() {
        $client = self::createClient();
        $client->request('GET', '/treasureroom/objet/theiere/cafe');
        $jsonArray = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            "Théière - Faire du café",
            $jsonArray["name"]
        );
        $this->assertEquals(
            "Vous avez essayé de faire du café avec une théière.",
            $jsonArray["desc"]
        );
        $this->assertEquals(
            array(
                "<a href='' ng-click='link(\"/treasureroom\")'>Retour.</a>",
            ),
            $jsonArray["action"]
        );
        $this->assertEquals(
            418, 
            $client->getResponse()->getStatusCode()
        );
    }
}

