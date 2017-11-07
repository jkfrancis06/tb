<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserMatrice
 *
 * @ORM\Table(name="user_matrice")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\UserMatriceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserMatrice
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
     * @var int
     *
     * @ORM\Column(name="niveau", type="integer")
     */
    private $niveau;

    /**
    * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;

   /**
    * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Matrice")
    * @ORM\JoinColumn(nullable=false)
    */
    private $matrice;
   
   /**
     * @var int
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
   private $createAt;

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
     * Set niveau
     *
     * @param integer $niveau
     *
     * @return UserMatrice
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return int
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set user
     *
     * @param \CoreBundle\Entity\User $user
     *
     * @return UserMatrice
     */
    public function setUser(\CoreBundle\Entity\User $user)
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

    /**
     * Set matrice
     *
     * @param \CoreBundle\Entity\Matrice $matrice
     *
     * @return UserMatrice
     */
    public function setMatrice(\CoreBundle\Entity\Matrice $matrice)
    {
        $this->matrice = $matrice;

        return $this;
    }

    /**
     * Get matrice
     *
     * @return \CoreBundle\Entity\Matrice
     */
    public function getMatrice()
    {
        return $this->matrice;
    }
    
    public function pre() {
        
    }
    
    /**
    * @ORM\PrePersist
    */
    public function prePersist()
    {
        $this->setCreateAt(new \DateTime());
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return UserMatrice
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
}
