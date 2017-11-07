<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Descendant
 *
 * @ORM\Table(name="descendant")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\DescendantRepository")
 */
class Descendant
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
     * @ORM\Column(name="id_user", type="integer")
     */
    private $idUser;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Descendant
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Descendant
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
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
     * @return Descendant
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
}
