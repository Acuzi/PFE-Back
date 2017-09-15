<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity()
* @ORM\Table(name="GameEntity")
*/
class GameEntity
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
    protected $id;

    /**
     * the name of the entity as it will be
     * showed in the response.
     *
     * @ORM\Column(type="string")
	 */
    protected $name;

    /**
     * The url name that will be used to
     * get this entity in the request.
     *
     * @ORM\Column(type="string")
	 */
    protected $urlName;
    
    /**
    * the type of the entity (ex : Objet, Personnage)
    *
    * @ORM\Column(type="string")
	*/
    protected $type;

    /**
     * the type of the entity as it is written in the url
     * (ex : object, npc)
     *
     * @ORM\Column(type="string")
	 */
    protected $urlType;

    /**
     * This entity belong to this location.
     *
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="npcs")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
	 */
    protected $location;
    
    /**
     * If null, the object is not transportable.
     * If set, the object is transportable, and this will be
     * the name of the cookie.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cookieName;

    /**
     * Description of the entity which will be 
     * added to the location description.
     *
     * @ORM\Column(type="string")
	 */
    protected $descriptionFromAfar;
    
    /**
     * Full description when the player
     * makes a direct call to this entity.
     *
     * @ORM\Column(type="string")
	 */
    protected $description;

    /**
     * Verbs that belong to this entity
     *
     * @ORM\OneToMany(targetEntity="Verb", mappedBy="entity")
     */
    protected $verbs;

    public function __construct() 
    {
        $this->name = "";
        $this->descriptionFromAfar = "";
        $this->description = "";
        $this->verbs = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return GameEntity
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
     * Set type
     *
     * @param string $type
     *
     * @return GameEntity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set descriptionFromAfar
     *
     * @param string $descriptionFromAfar
     *
     * @return GameEntity
     */
    public function setDescriptionFromAfar($descriptionFromAfar)
    {
        $this->descriptionFromAfar = $descriptionFromAfar;

        return $this;
    }

    /**
     * Get descriptionFromAfar
     *
     * @return string
     */
    public function getDescriptionFromAfar()
    {
        return $this->descriptionFromAfar;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return GameEntity
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
     * Set location
     *
     * @param \AppBundle\Entity\Location $location
     *
     * @return GameEntity
     */
    public function setLocation(\AppBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \AppBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Add verb
     *
     * @param \AppBundle\Entity\Verb $verb
     *
     * @return GameEntity
     */
    public function addVerb(\AppBundle\Entity\Verb $verb)
    {
        $this->verbs[] = $verb;

        return $this;
    }

    /**
     * Remove verb
     *
     * @param \AppBundle\Entity\Verb $verb
     */
    public function removeVerb(\AppBundle\Entity\Verb $verb)
    {
        $this->verbs->removeElement($verb);
    }

    /**
     * Get verbs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVerbs()
    {
        return $this->verbs;
    }

    /**
     * Set urlType
     *
     * @param string $urlType
     *
     * @return GameEntity
     */
    public function setUrlType($urlType)
    {
        $this->urlType = $urlType;

        return $this;
    }

    /**
     * Get urlType
     *
     * @return string
     */
    public function getUrlType()
    {
        return $this->urlType;
    }

    /**
     * Set urlName
     *
     * @param string $urlName
     *
     * @return GameEntity
     */
    public function setUrlName($urlName)
    {
        $this->urlName = $urlName;

        return $this;
    }

    /**
     * Get urlName
     *
     * @return string
     */
    public function getUrlName()
    {
        return $this->urlName;
    }


    /**
     * Set cookieName
     *
     * @param string $cookieName
     *
     * @return GameEntity
     */
    public function setCookieName($cookieName)
    {
        $this->cookieName = $cookieName;

        return $this;
    }

    /**
     * Get cookieName
     *
     * @return string
     */
    public function getCookieName()
    {
        return $this->cookieName;
    }
}
