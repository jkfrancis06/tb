<?php
namespace CoreBundle\Repository;

use CoreBundle\Entity\CompteAlimentation;
use CoreBundle\Entity\ComptePercevable;
use CoreBundle\Entity\Descendant;
use CoreBundle\Entity\Point;
use CoreBundle\Entity\User;
use CoreBundle\Entity\UserMatrice;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {
    const CODE_STRUCTURE = 'TB0000';
    
    /**
     * 
     * @param integer $matrice
     * @return User
     */
    public function dernierEntrer($matrice) {
        $um = $this->_em->getRepository('CoreBundle:UserMatrice')->findOneBy(
            ['matrice' => $this->_em->getRepository('CoreBundle:Matrice')->find($matrice)], 
            ['createAt' => 'desc']
        );
        
        return $um->getUser();
    }
    
    /**
     * 
     * @param User $user
     * @param integer $matrice
     * @param integer $niveau
     */
    public function setNiveau(User $user, $matrice, $niveau) {
        $um = $this->_em->getRepository('CoreBundle:UserMatrice')->findOneBy([
            'user' => $user,
            'matrice' => $this->_em->getRepository('CoreBundle:Matrice')->find($matrice)
        ]);
        
        $um->setNiveau($niveau);
        
//        $this->_em->flush();
    }
    /**
     * 
     * @param User $user
     * @return User
     */
    public function getParrain(User $user) {
        return $this->findOneBy(['code' => $user->getCodeParrain()]);
    }

    /**
     * 
     * @param User $user
     * @return User
     */
    public function getFilleuls(User $user) {
        return $this->findBy(['codeParrain' => $user->getCode()]);
    }
    
    /**
     * 
     * @param User $user
     * @param integer $point
     */
    public function ajouterPoint(User $user, $point) {
        $_point = new Point();
        $_point->setUser($user)
            ->setPoint($point);

        $this->_em->persist($_point);
        $this->_em->flush();
    }
    
    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function estLaStructure(User $user) {
        return $user->getCode() == 'TB0000';
    }
    /**
     * 
     * @param User $user
     * @return int
     */
    public function totalDePointDe(User $user) {
        $points = $this->_em->getRepository('CoreBundle:Point')->findBy(['user' => $user]);
        $total = 0;
        
        foreach ($points as $point) $total += $point->getPoint();
        
        return $total;
    }
    /**
     * 
     * @param array $parrains
     * @return Uers[]
     */
    public function getFilleulsDesParrains(array $parrains) {
        $filleuls = [];
        
        foreach($parrains as $parrain) {
            foreach($this->getFilleuls($parrain) as $filleul) {
                $filleuls[] = $filleul;
            }
        }
        
        return $filleuls;
    }

    /**
     * 
     * @param User $ancetre
     * @param type $matrice
     * @param ObjectManager $em
     * @return User[]
     */
    public function getDescendants(User $ancetre, $matrice) {
        $_matrice = $this->_em->getRepository('CoreBundle:Matrice')->find($matrice);
        
        $dr = $this->_em->getRepository('CoreBundle:Descendant');
        
        $_ds = $dr->findBy([
            'matrice' => $_matrice,
            'user' => $ancetre->getId(),
        ]);
        
        $ds = [];
        
        foreach ($_ds as $d) {
            $ds[] = $this->find($d->getIdUser());
        }
        
        return $ds;
    }
    
    /**
     * 
     * @param User $user
     * @param integer $matriceId
     * @return User
     */
    private function _getAncetre(User $user, $matriceId) {
        $matrice = $this->_em->getRepository('CoreBundle:Matrice')->find($matriceId);

        $descendant = $this->_em->getRepository('CoreBundle:Descendant')->findOneBy([
            'idUser' => $user->getId(),
            'matrice' => $matrice
        ]);

        return $this->find($descendant->getUser()->getId());
    }

    /**
     * 
     * @param User $user
     * @param integer $matriceId
     * @param integer $generation
     * @return User
     */
    public function getAncetre(User $user, $matriceId, $generation) {
        $ancetre = $this->_getAncetre($user, $matriceId);
        $cpt = 1;
        
        while($cpt < $generation) {
            $ancetre = $this->_getAncetre($user, $matriceId);
            $cpt++;
        }
        
        return $ancetre;
    }
    
    /**
     * 
     * @param User $desendant
     * @param User $ancetre
     * @param integer $matriceId
     */
    public function setAncetre(User $desendant, User $ancetre, $matriceId) {
        $matrice = $this->_em->getRepository('CoreBundle:Matrice')->find($matriceId);
        
        $d = new Descendant();
        $d->setMatrice($matrice);
        $d->setIdUser($desendant->getId());
        $d->setUser($ancetre);
        
        $this->_em->persist($d);
        $this->_em->flush();
    }
    
    /**
     * 
     * @param User $ancetre
     * @return type  boolean
     */
    public function aMoinsDe2Descendants(User $ancetre, $matrice) {
        return count($this->getDescendants($ancetre, $matrice)) < 2;
    }
    
    /**
     * 
     * @param User $ancetre
     * @return type  boolean
     */
    public function aAuMoins1Descendants(User $ancetre) {
        return count($this->getDescendants($ancetre)) >= 1;
    }
    
    /**
     * 
     * @param User $user
     * @return type  boolean
     */
    public function laStructureEstLAncetre(User $user) {
        return $user->getCodeAncetre() == self::CODE_STRUCTURE;
    }
    
    /**
     * 
     * @param array $ancetres
     * @return type User[]
     */
    public function descendantsDesAncetres(array $ancetres, $matrice) {
        $descendants = [];

        foreach ($ancetres as $ancetre) {
            foreach($this->getDescendants($ancetre, $matrice) as $_descendant) {
                $descendants[] = $_descendant;
            }
        }

        return $descendants;
    }
    
    /**
     * 
     * @param array $users
     * @return type User[]
     */
    public function chaqueUserA2Descendant(array $users) {
        foreach ($users as $_user) {
            if($this->aMoinsDe2Descendants($_user)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 
     * @param array $ancetres
     * @return type [boolean, User(if boolean is true)]
     */
    public function unDesAncetresAMoinsDe2Descendants(array $ancetres, $matrice) {
        foreach ($ancetres as $_ancetre) {
            if($this->aMoinsDe2Descendants($_ancetre, $matrice)) {
                return [true, $_ancetre];
            }
        }

        return [false];
    }
    
    /**
     * 
     * @param User $user
     * @param integer $matriceId
     * @param integer $niveau
     */
    public function passerAuNiveau(User $user, $matriceId, $niveau) {
        $um = $this->_em->getRepository('CoreBundle:UserMatrice')->findOneBy([
            'user' => $user,
            'matrice' => $this->_em->getRepository('CoreBundle:Matrice')->find($matriceId)
        ]);
        $um->setNiveau($niveau);
        
        $this->_em->flush();
    }
    
    public function passerALaMatrice(User $user, $matriceId) {
        $um = new UserMatrice();
        $um->setMatrice($this->_em->getRepository('CoreBundle:Matrice')->find($matriceId))
            ->setUser($user)
            ->setNiveau(1);

        $this->_em->persist($um);
        $this->_em->flush();
    }
    
    /**
     * 
     * @param User $user
     * @param number $montant
     */
    public function ajouterAuCompteUpgrade(User $user, $montant) {
        $user->setCompteUpgrade($user->getCompteUpgrade() + $montant);
        $this->_em->flush();
    }
    
    /**
     * 
     * @param User $user
     * @param integer $gain
     */
    public function ajouterAuCompteAlimentation(User $user, $gain) {
        $c = new CompteAlimentation();
        $c->setUser($user)
            ->setGain($gain);
        
        $this->_em->persist($c);
        $this->_em->flush();
    }
    
    /**
     * 
     * @param User $user
     * @return integer
     */
    public function getNbCptRep(User $user) {
        return count($this->findBy([
            'rep' => true,
            'codeParrain' => $user->getCode()
        ]));
    }

    /**
     * 
     * @param User $user
     * @param integer $gain
     */
    public function ajouterAuComptePercevable(User $user, $gain) {
        $c = new ComptePercevable();
        $c->setUser($user)
            ->setGain($gain);
        
        $this->_em->persist($c);
        $this->_em->flush();
    }
    
    /**
     * 
     * @param User $user
     * @param integer $montant
     * @return boolean
     */
    public function siMontantUpgradeAtteint(User $user, $montant) {
        if($user->getCompteUpgrade() >= $montant) {
            $this->ajouterAuCompteUpgrade($user, (-1) * $montant);
            $this->_em->flush();
            
            return true;
        }
        
        return false;
    }
    
    /**
     * 
     * @param User $user
     * @param type $niveau
     * @return boolean
     */
    public function siNiveauAtteint(User $user, $niveau, $matrice) {
        $um = $this->_em->getRepository('CoreBundle:UserMatrice')->findOneBy([
            'user' => $user,
            'matrice' => $this->_em->getRepository('CoreBundle:Matrice')->find($matrice)
        ]);
        
        return $um->getNiveau() >= $niveau;
    }
    
    /**
     * 
     * @param User $user
     */
    public function viderCompteUpgrade(User $user) {
        $m = $user->getCompteUpgrade() / 2;
        
        $this->ajouterAuCompteAlimentation($user, $m);
        $this->ajouterAuComptePercevable($user, $m);
        
        $user->setCompteUpgrade(0);
        
        $this->_em->flush();
    }
    
    /**
     * 
     * @param User $user
     * @return boolean
     */
    public function userEstDansMatrice($matrice, User $user) {
        $um = $this->_em->getRepository('CoreBundle:UserMatrice')->findOneBy([
            'user' => $user,
            'matrice' => $this->_em->getRepository('CoreBundle:Matrice')->find($matrice)
        ]);
        
        return $um !== null;
    }
}