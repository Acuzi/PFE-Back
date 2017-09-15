<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity()
* @ORM\Table(name="Adventure")
*/
class Adventure
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
    protected $id;
    
    /**
     * The name of the adventure.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Location", mappedBy="adventure")
     */
    protected $locations;

    /**
     * @ORM\OneToMany(targetEntity="Path", mappedBy="adventure")
     */
    protected $paths;

    public function __construct() 
    {
        $this->locations = new ArrayCollection();
        $this->paths = new ArrayCollection();
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
     * Add location
     *
     * @param \AppBundle\Entity\Location $location
     *
     * @return Adventure
     */
    public function addLocation(\AppBundle\Entity\Location $location)
    {
        $this->locations[$location->getName()] = $location;

        return $this;
    }

    /**
     * Remove location
     *
     * @param \AppBundle\Entity\Location $location
     */
    public function removeLocation(\AppBundle\Entity\Location $location)
    {
        $this->locations->removeElement($location);
    }

    /**
     * Get locations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Add path
     *
     * @param \AppBundle\Entity\Path $path
     *
     * @return Adventure
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
     * Set name
     *
     * @param string $name
     *
     * @return Adventure
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
}
