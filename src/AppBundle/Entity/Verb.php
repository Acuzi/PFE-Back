<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A verb is a collection of action.
 * 
 * @ORM\Entity()
 * @ORM\Table(name="Verb")
 */
class Verb 
{

	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
    protected $id;

    /**
     * the url name that will be used to get the verb.
     * 
     * @ORM\Column(type="string")
	 */
    protected $url;

    /**
    * @ORM\ManyToOne(targetEntity="GameEntity", inversedBy="actions")
    * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
    */
    protected $entity;

    /**
    * @ORM\OneToMany(targetEntity="Action", mappedBy="verb")
    */
    protected $actions;

    public function __construct()  
    {
        $this->actions = new ArrayCollection();
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
     * @return Verb
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
     * Set entity
     *
     * @param \AppBundle\Entity\GameEntity $entity
     *
     * @return Verb
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
     * Add action
     *
     * @param \AppBundle\Entity\Action $action
     *
     * @return Verb
     */
    public function addAction(\AppBundle\Entity\Action $action)
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * Remove action
     *
     * @param \AppBundle\Entity\Action $action
     */
    public function removeAction(\AppBundle\Entity\Action $action)
    {
        $this->actions->removeElement($action);
    }

    /**
     * Get actions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActions()
    {
        return $this->actions;
    }
}
