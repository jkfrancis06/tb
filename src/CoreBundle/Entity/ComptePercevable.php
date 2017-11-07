<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="compte_percevable")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ComptePercevableRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ComptePercevable
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @var int
     * @ORM\Column(name="gain", type="integer")
     */
    private $gain;
    
    /**
    * @var CoreBundle\Entity\User
    * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $user;
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->setCreateAt(new \Datetime());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $createAt
     * @return ComptePercevable
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @param integer $gain
     * @return ComptePercevable
     */
    public function setGain($gain)
    {
        $this->gain = $gain;

        return $this;
    }

    /**
     * @return int
     */
    public function getGain()
    {
        return $this->gain;
    }

    /**
     * @param \CoreBundle\Entity\User $user
     * @return ComptePercevable
     */
    public function setUser(\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

}