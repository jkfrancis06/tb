<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteAlimentation
 *
 * @ORM\Table(name="compte_alimentation")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CompteAlimentationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CompteAlimentation
{
    /**
     * @var int
     *
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return CompteAlimentation
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set gain
     *
     * @param integer $gain
     *
     * @return CompteAlimentation
     */
    public function setGain($gain)
    {
        $this->gain = $gain;

        return $this;
    }

    /**
     * Get gain
     *
     * @return int
     */
    public function getGain()
    {
        return $this->gain;
    }

    /**
     * Set user
     *
     * @param \CoreBundle\Entity\User $user
     *
     * @return CompteAlimentation
     */
    public function setUser(\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
