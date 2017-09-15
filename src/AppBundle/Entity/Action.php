<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
/**
 * @ORM\Entity()
 * @ORM\Table(name="Action")
 */
class Action 
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The id of the action in the url.
     * The default action for a given 
     * name has a url equal to null.
     *
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * The name of the action as it will be showed in the response.
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Description of the action which will be showed when
     * the player makes a call to the entity.
     *
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * Full description when the player makes a direct call to this action.
     *
     * @ORM\Column(type="string")
     */
    protected $fullDescription;

    /**
     * headers sent in the response when the player call this action.
     *
     * @ORM\Column(type="array")
     */
    protected $headers;

    /**
     * True if the action is shown to the player when he is calling the entity.
     *
     * @ORM\Column(type="boolean")
     */
    protected $isVisible;


    /**
     * Array of required headers. If the headers are missing, an error is
     * returned instead of the action.
     *
     * @ORM\Column(type="array")
     */
    protected $requiredHeaders;

    /**
     * Array of required items. If the items are missing, an error is
     * returned instead of the action. Items are stored in cookie.
     *
     * @ORM\Column(type="array")
     */
    protected $requiredItems;

    /**
     * Required HTTP method for the request to be successful (can be GET, PUT,
     * POST,..).
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $requiredHttpMethod;

    /**
     * Required body for the request to be successful.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $requiredBody;

    /**
     * the HTTP status code that will be returned.
     *
     * @ORM\Column(type="integer")
     */
    protected $returnedStatusCode;

    /* cookie that will be returned. Is used to implement items. */
    protected $cookie;

    /**
     * @ORM\ManyToOne(targetEntity="Verb", inversedBy="actions")
     * @ORM\JoinColumn(name="verb_id", referencedColumnName="id")
     */
    protected $verb;

    /*When the entity refered by this action can be in the inventory
    prepare the cookie attribute with the CookieName of the entity.*/
    
    public function createCookie(){
        $value = $this->getVerb()->getEntity()->getCookieName();
        if ($value) {
            $this->cookie = new Cookie($value, $value, 0, '/', null, false, false);
        }
    }

    public function getCookie() {
        return $this->cookie;
    }

    public function setCookie($cookie) {
        $this->cookie = $cookie;
    }

    //test if the request meet the requirement to do the action.
    //if not, sent a non null array of errors that will be displayed in the
    //response.
    public function checkRequest(Request $request) 
    {
        $errors = [];
        $headers = $request->headers->all();
        $cookies = $request->cookies;
        if ($this->requiredHeaders)
        {
            foreach($this->requiredHeaders as $requiredKey => $requiredValue) 
            {
                if ((!array_key_exists($requiredKey, $headers)) ||
                    ($headers[$requiredKey][0] != $requiredValue))
                {
                    $errors[] = "Mauvais header."; 
                }
            }
        }
        if ($this->requiredHttpMethod) {
            if ($this->requiredHttpMethod != $request->getMethod()) 
            {
                $errors[] = "La mauvaise méthode est utilisée.";
            }
        }
        if ($this->requiredItems) {
            foreach($this->requiredItems as $item) {
                if ( (!$cookies->has($item)) ||
                    ($cookies->get($item) != $item)) {
                    $errors[] = "Il vous manque un objet.";
                }
            }
        }
        if ($this->requiredBody) {
            if ($this->requiredBody != $request->getContent()) {
                $errors[] = "Le corps de la requête n'est pas le bon.";
            }

        }
        return $errors;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Action
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set requiredHeaders
     *
     * @param array $requiredHeaders
     *
     * @return Action
     */
    public function setRequiredHeaders($requiredHeaders)
    {
        $this->requiredHeaders = $requiredHeaders;

        return $this;
    }

    /**
     * Get requiredHeaders
     *
     * @return array
     */
    public function getRequiredHeaders()
    {
        return $this->requiredHeaders;
    }

    /**
     * Set requiredItems
     *
     * @param array $requiredItems
     *
     * @return Action
     */
    public function setRequiredItems($requiredItems)
    {
        $this->requiredItems = $requiredItems;

        return $this;
    }

    /**
     * Get requiredItems
     *
     * @return array
     */
    public function getRequiredItems()
    {
        return $this->requiredItems;
    }

    /**
     * Set returnedStatusCode
     *
     * @param integer $returnedStatusCode
     *
     * @return Action
     */
    public function setReturnedStatusCode($returnedStatusCode)
    {
        $this->returnedStatusCode = $returnedStatusCode;

        return $this;
    }

    /**
     * Get returnedStatusCode
     *
     * @return integer
     */
    public function getReturnedStatusCode()
    {
        return $this->returnedStatusCode;
    }

    /**
     * Set entity
     *
     * @param \AppBundle\Entity\GameEntity $entity
     *
     * @return Action
     */
    public function setEntity(\AppBundle\Entity\GameEntity $entity = null)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return \AppBundle\Entity\GameEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Action
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Action
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set fullDescription
     *
     * @param string $fullDescription
     *
     * @return Action
     */
    public function setFullDescription($fullDescription)
    {
        $this->fullDescription = $fullDescription;

        return $this;
    }

    /**
     * Get fullDescription
     *
     * @return string
     */
    public function getFullDescription()
    {
        return $this->fullDescription;
    }

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return Action
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return boolean
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * Set verb
     *
     * @param \AppBundle\Entity\Verb $verb
     *
     * @return Action
     */
    public function setVerb(\AppBundle\Entity\Verb $verb = null)
    {
        $this->verb = $verb;

        return $this;
    }

    /**
     * Get verb
     *
     * @return \AppBundle\Entity\Verb
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * Set requiredHttpMethod
     *
     * @param string $requiredHttpMethod
     *
     * @return Action
     */
    public function setRequiredHttpMethod($requiredHttpMethod)
    {
        $this->requiredHttpMethod = $requiredHttpMethod;

        return $this;
    }

    /**
     * Get requiredHttpMethod
     *
     * @return string
     */
    public function getRequiredHttpMethod()
    {
        return $this->requiredHttpMethod;
    }

    /**
     * Set headers
     *
     * @param array $headers
     *
     * @return Action
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set requiredBody
     *
     * @param string $requiredBody
     *
     * @return Action
     */
    public function setRequiredBody($requiredBody)
    {
        $this->requiredBody = $requiredBody;

        return $this;
    }

    /**
     * Get requiredBody
     *
     * @return string
     */
    public function getRequiredBody()
    {
        return $this->requiredBody;
    }
}
