<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity()
* @ORM\Table(name="Location")
*/
class Location
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
    protected $id;

    /**
     * The name of the location as it will be showed in the response.
     *
     * @ORM\Column(type="string")
	 */
    protected $name;

    /**
     * The url name that will be used to
     * get this location in the request.
     *
     * @ORM\Column(type="string")
	 */
    protected $urlName;
    
    /**
     * The description of the location. 
     * Description of entities in this location will be added to this 
     * description.
     * @ORM\Column(type="string")
	 */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="GameEntity", mappedBy="location")
     */
    protected $entities;

    /**
     * @ORM\OneToMany(targetEntity="Path", mappedBy="location1")
     */
    protected $paths;


    /**
    * @ORM\ManyToOne(targetEntity="Adventure", inversedBy="locations")
    * @ORM\JoinColumn(name="adventure_id", referencedColumnName="id")
	*/
    protected $adventure;

    public function __construct() 
    {
        $this->name = "";
        $this->description = "";
        $this->entities = new ArrayCollection();
        $this->paths = new ArrayCollection();
    }

    /* Create every possible action knowing entities and paths to other
     locations. */
    public function createActions() 
    {
        $actions = [];
        foreach ($this->paths as $path) {
            $actions[] = "Aller vers <a href='' ng-click='link(\"/". 
                $path->getLocation2()->getUrlName() . "\")'>" .
                $path->getLocation2()->getName() . "</a>.";
        }
        foreach ($this->entities as $object) {
            $actions[] = 
                "Se rapprocher de <a href='' ng-click='link(\"/". 
                $object->getLocation()->getUrlName() . 
                "/" . $object->getUrlType() . "/" . $object->getUrlName() . 
                "\")'>" . $object->getName() .
                "</a>.";

        }
        return $actions;
    }

    // return the full description : description of the location
    // with added description of paths and entities.
    public function createFullDescription() 
    {
        $desc = "";
        $desc = $desc . $this->description;
        foreach ($this->entities as $entity) {
            $desc = $desc . ' ' . $entity->getDescriptionFromAfar();
        }
        foreach ($this->paths as $path) {
            $desc = $desc . ' ' . $path->getDescription();
        }
        return $desc;
    }

    public function getName() 
    {
        return $this->name;
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
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Location
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
     * Set adventure
     *
     * @param \AppBundle\Entity\Adventure $adventure
     *
     * @return Location
     */
    public function setAdventure(\AppBundle\Entity\Adventure $adventure = null)
    {
        $this->adventure = $adventure;

        return $this;
    }

    /**
     * Get adventure
     *
     * @return \AppBundle\Entity\Adventure
     */
    public function getAdventure()
    {
        return $this->adventure;
    }

    /**
     * Add path
     *
     * @param \AppBundle\Entity\Path $path
     *
     * @return Location
     */
    public function addPath(\AppBundle\Entity\Path $path)
    {
        $this->paths[] = $path;

        return $this;
    }

    /**
     * Remove path
     *
     * @param \AppBundle\Entity\Path $path
     */
    public function removePath(\AppBundle\Entity\Path $path)
    {
        $this->paths->removeElement($path);
    }

    /**
     * Get paths
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Set urlName
     *
     * @param string $urlName
     *
     * @return Location
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
     * Add entity
     *
     * @param \AppBundle\Entity\GameEntity $entity
     *
     * @return Location
     */
    public function addEntity(\AppBundle\Entity\GameEntity $entity)
    {
        $this->entities[] = $entity;

        return $this;
    }

    /**
     * Remove entity
     *
     * @param \AppBundle\Entity\GameEntity $entity
     */
    public function removeEntity(\AppBundle\Entity\GameEntity $entity)
    {
        $this->entities->removeElement($entity);
    }

    /**
     * Get entities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntities()
    {
        return $this->entities;
    }
}
