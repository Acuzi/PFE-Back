<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A path connect two different locations.
 * A path is oriented.
 * Two paths are needed if you want the player to be allowed to come back.
 *
 * @ORM\Entity()
 * @ORM\Table(name="Path")
 */
class Path
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
    protected $id;

    /**
     * description of the path
     *
     * @ORM\Column(type="string")
	 */
    protected $description;

    /**
     * The first location is the beginning of the path.
     * The path goes from first location to second location.
     *
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="first_location_id", referencedColumnName="id")
	 */
    protected $location1;

    /**
     * The second location is the ending of the path.
     * The path goes from first location to second location.
     *
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="second_location_id", referencedColumnName="id")
	 */
    protected $location2;

    /**
    * @ORM\ManyToOne(targetEntity="Adventure", inversedBy="paths")
    * @ORM\JoinColumn(name="adventure_id", referencedColumnName="id")
	*/
    protected $adventure;

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
     * Set description
     *
     * @param string $description
     *
     * @return Path
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
     * Set location1
     *
     * @param \AppBundle\Entity\Location $location1
     *
     * @return Path
     */
    public function setLocation1(\AppBundle\Entity\Location $location1 = null)
    {
        $this->location1 = $location1;

        return $this;
    }

    /**
     * Get location1
     *
     * @return \AppBundle\Entity\Location
     */
    public function getLocation1()
    {
        return $this->location1;
    }

    /**
     * Set location2
     *
     * @param \AppBundle\Entity\Location $location2
     *
     * @return Path
     */
    public function setLocation2(\AppBundle\Entity\Location $location2 = null)
    {
        $this->location2 = $location2;

        return $this;
    }

    /**
     * Get location2
     *
     * @return \AppBundle\Entity\Location
     */
    public function getLocation2()
    {
        return $this->location2;
    }

    /**
     * Set adventure
     *
     * @param \AppBundle\Entity\Adventure $adventure
     *
     * @return Path
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
}
