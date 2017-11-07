<?php
namespace CoreBundle\Controller;

use CoreBundle\Entity\CptPerGain;
use CoreBundle\Entity\DemandeDeRetrait;
use CoreBundle\Entity\Descendant;
use CoreBundle\Entity\User;
use CoreBundle\Entity\UserMatrice;
use CoreBundle\Form\DemandeDeRetraitType;
use CoreBundle\Form\SMSType;
use CoreBundle\Entity\SMS;
use CoreBundle\Form\UserType;
use CoreBundle\Form\UserUpdateType;
use CoreBundle\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller {
    const MATRICE_ARGENT = 1;
    const MATRICE_OR = 2;
    const MATRICE_DIAMANT = 3;
    
    const NIVEAU_1 = 1;
    const NIVEAU_2 = 2;
    const NIVEAU_3 = 3;
    const NIVEAU_4 = 4;
    const NIVEAU_5 = 5;
    
    /**
     * @return User
     */
    private function _newUser() {
        $user = new User();
        $user->setSalt('')
            ->setRoles(['ROLE_USER'])
            ->setCodeParrain('TB0000');
        
        return $user;
    }
    
    /**
     * @param User $user
     */
    private function _setCptPerGain(User $user, ObjectManager $em) {
        $cptPerGain = new CptPerGain();
        $cptPerGain->setNom($user->getNom())
            ->setPrenom($user->getPrenoms())
            ->setTel($user->getTel());
        
        $em->persist($cptPerGain);

        $user->setCptPerGain($cptPerGain);
    }
    
    /**
     * 
     * @param type $url
     * @param Request $req
     */
    public function createAction($url, Request $req) {
        $em = $this->getDoctrine()->getManager();
        $tel = $this->_verifPaiementAction($url);
        
        if($tel == false) {
            $req->getSession()->getFlashBag()->add('warning', 'Url invalide.');
            
            return $this->redirectToRoute('payer');
        }
        
        $user = $this->_newUser();
        $user->setTel($tel);

        $form = $this->createForm(UserType::class, $user);

        if($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {    
            $user->setCode(strtoupper($user->getCode()));
            
            /*$encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            
            $this->_setCptPerGain($user, $em);*/

            $user_matrice = new UserMatrice();
            $user_matrice->setMatrice($em->getRepository('CoreBundle:Matrice')->find(1))
                ->setUser($user)
                ->setNiveau(self::NIVEAU_1);

            $em->persist($user);
            $em->persist($user_matrice);
            $em->flush();
            $em->flush();

            $this->_setAncetre($user, self::MATRICE_ARGENT, $em);
            $this->_gainDeParrainage($user);
            $this->_point($user, self::MATRICE_ARGENT);
            $this->_upgrade($user, $em, $em->getRepository('CoreBundle:User'));

            $em->flush();
        }

        return $this->render('CoreBundle:User:create.html.twig', ['form' => $form->createView()]);
    }
    
    private function _verifPaiementAction($url) {
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('CoreBundle:Paiement')->findOneBy(['url' => $url, 'inscrit' => false]);
        
        if($p != null) return $p->getTel();
        
        return false;
    }

    /**
     * @param User $user
     */
    private function _gainDeParrainage(User $user) {
        $ur = $this->getDoctrine()->getManager()->getRepository('CoreBundle:User');
        
        foreach([250, 150, 100] as $gain) {
            $parrain = $ur->getParrain($user);

            $ur->ajouterAuCompteAlimentation($parrain, $gain);
            $ur->ajouterAuComptePercevable($parrain, $gain);
            
            $user = $parrain;
        }
    }
    
    /**
     * @return array
     */
    private function _matriceTab() {
        return [
            1 => [
                0 => 3000,
                1 => 4500,
                2 => 12000,
                3 => 36000,
                4 => 192000,
                5 => 1945000
            ],
            2 => [
                0 => 1945000,
                1 => 2917500,
                2 => 7790000 ,
                3 => 23370000,
                4 => 124562000,
                5 => 1261813000
            ],
            3 => [
                0 => 1261813000,
                1 => 1892719500,
                2 => 5053561000,
                3 => 15160683000,
                4 => 80806440000,
                5 => 818569237000
            ]
        ];
    }
    
    /**
     * @param User $user
     * @param integer $matriceId
     */
    private function _point(User $user, $matriceId) {
        $ur = $this->getDoctrine()->getManager()->getRepository('CoreBundle:User');
        $point = 10 * $matriceId;

        $ancetre = $ur->getAncetre($user, $matriceId, 1);
        $cpt = 0;

        while (!$ur->estLaStructure($ancetre) && ++$cpt < 5) {
            $ur->ajouterPoint($ancetre, $point);
            
            $ancetre = $ur->getAncetre($ancetre, $matriceId, 1);
        }
    }
    
    /**
     * @param User $user
     * @param ObjectManager $em
     * @param UserRepository $ur
     * @return void
     */
    private function _upgrade(User $user, ObjectManager $em, UserRepository $ur) {
        $matrices = $this->_matriceTab();
        $cptM = 1;

        while($cptM <= 3) {
            $cptN = 1; 
            $niveaux = $matrices[$cptM];

            While($cptN <= 5) {
                $ancetre = $ur->getAncetre($user, $cptM, $cptN);
                $ur->ajouterAuCompteUpgrade($ancetre, $niveaux[$cptN - 1]);

                if($ur->siNiveauAtteint($ancetre, self::NIVEAU_4, self::MATRICE_ARGENT)) {
                    if($cptN == self::NIVEAU_4) $nbRep = 5;
                    elseif ($cptN == self::NIVEAU_5) $nbRep = 35;
                    elseif ($cptM == self::MATRICE_OR) $nbRep = 535;

                    while($ancetre->getCompteUpgrade() >= 5000 && $ur->getNbCptRep($ancetre) < $nbRep) {
                        $ur->ajouterAuCompteUpgrade($ancetre, -5000);

                        $cptRep = new User();
                        $cptRep->setCodeParrain($ancetre->getCode())
                            ->setRep(true);

                        $em->flush();
                        $em->flush();

                        $this->_setAncetre($cptRep, self::MATRICE_ARGENT, $em);
                        $this->_gainDeParrainage($cptRep);
                        $this->_upgrade($cptRep, $em, $ur);
                    }

                    return;
                }

                if($ur->siMontantUpgradeAtteint($ancetre, $niveaux[$cptN])) {
                    $ur->viderCompteUpgrade($ancetre);
                    
                    if($cptN == self::NIVEAU_5) {
                        if($cptM == self::MATRICE_DIAMANT) {
                            $ancetre->setCloturer(true);
                            $em->flush();
                            
                            return;
                        }

                        $ur->passerALaMatrice($ancetre, ++$cptM);

                        $this->_setAncetre($ancetre, $cptM, $em);
                        $this->_point($ancetre, $cptM);
                    } else {
                        $ur->passerAuNiveau($ancetre, $cptM, ++$cptN);
                    }
                    
                    $user = $ancetre;
                } else return;
            }
            
            $cptM++;
        }
    }

    /**
     * @param User $user
     * @param integer $matriceId
     * @param ObjectManager $em
     * @return void
     */
    private function _setAncetre(User $user, $matriceId, ObjectManager $em) {
        $ur = $em->getRepository('CoreBundle:User');

        $ancetre = $matriceId == 1 ? $ur->getParrain($user) : $ur->dernierEntrer($matriceId);

        if($ur->aMoinsDe2Descendants($ancetre, $matriceId)) {
            $ur->setAncetre($user, $ancetre, $matriceId);
            return;
        }

        $descendants = $ur->getDescendants($ancetre, $matriceId);
        $t = $ur->unDesAncetresAMoinsDe2Descendants($descendants, $matriceId);

        if($t[0]) $ur->setAncetre($user, $t[1], $matriceId);

        while($t[0] == false) {
            $descendants = $ur->descendantsDesAncetres($descendants, $matriceId);
            $t = $ur->unDesAncetresAMoinsDe2Descendants($descendants, $matriceId);

            if($t[0]) $ur->setAncetre($user, $t[1], $matriceId);
        }
    }
            
    public function loginAction() {
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('CoreBundle:User:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction() {
        /*$em = $this->getDoctrine()->getManager();
        $s = new User();
        $s->setCode('TB0000')
                ->setCodeParrain('TB0000');
        
        $em->persist($s);
               
        foreach ([1, 2, 3] as $id) {
            $m = $em->getRepository('CoreBundle:Matrice')->find($id);
            
            $um = new UserMatrice();
            $um->setMatrice($m)
                    ->setNiveau(1)
                    ->setUser($s);
            
            $em->persist($um);
            
            $d = new Descendant();
            $d->setIdUser(1)
                    ->setMatrice($m)
                    ->setUser($s);
            
            $em->persist($d);
        }

        $em->flush();*/
        
        return $this->render('CoreBundle:User:index.html.twig');
    }

    /**
     * 
     * @param string $code
     * @param Request $req
     * @return JsonResponse
     */
    public function xhrGetParrainAction($code, Request $req) {
        if($req->isXmlHttpRequest() && $code != 'TB0000') {
            $p = $this->getDoctrine()->getManager()->getRepository('CoreBundle:User')->findOneBy(['code' => $code]);

            if($p == null) {
                return new JsonResponse([
                    's' => 0,
                    'p' => 'ID incorrect'
                ]);
            }

            return new JsonResponse([
                's' => 1,
                'p' => $p->getNom().' '.$p->getPrenoms()
            ]);
        }
    }
        
    /**
     * @Security("has_role('ROLE_USER')") 
     */
    public function accountAction() {
        return $this->render('CoreBundle:User:account.html.twig');
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function profilUpdateAction() {
    }
    
    /**
     * @Security("has_role('ROLE_USER')") 
     */
    public function updateAction(Request $req) {
        $form = $this->createForm(UserUpdateType::class, $this->getUser());
        
        if($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('user_account');
        }
        
        return $this->render('CoreBundle:User:update.html.twig', ['form' => $form->createView()]);
    }
    
    /**
     * 
     * @return User[]
     */
    private function _listParNiveau() {
        $ur = $this->getDoctrine()->getManager()->getRepository('CoreBundle:User');
        $user = $this->getUser();
        $membres = [];
        $matrice = 1;
        
        while($ur->userEstDansMatrice($matrice, $user) && $matrice <= 3) {
            $m = [];
            $m[] = $ur->getDescendants($user, $matrice);
            $m[] = $ur->descendantsDesAncetres($m[0], $matrice);
            $m[] = $ur->descendantsDesAncetres($m[1], $matrice);
            
            $membres[] = $m;
            
            $matrice++;
        }
        
        return $membres;
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function listeNiveauAction() {
        return $this->render('CoreBundle:User:liste_nivaeu.html.twig', [
            'mm' => $this->_listParNiveau()
        ]);
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function listeGenerationAction() {
        $ur = $this->getDoctrine()->getManager()->getRepository('CoreBundle:User');
        $l = [];
        
        $l[] = $ur->getFilleuls($this->getUser());
        $l[] = $ur->getFilleulsDesParrains($l[0]);
        $l[] = $ur->getFilleulsDesParrains($l[1]);
        
        return $this->render('CoreBundle:User:liste_generation.html.twig', ['l' => $l]);
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function listeGeneralAction() {
        return $this->render('CoreBundle:User:liste_general.html.twig', ['mm' => $this->_listParNiveau()]);
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @param Request $req
     * @return Response
     */
    public function demandeDeRetraitAction(Request $req) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        
        $d = new DemandeDeRetrait();
        $d->setUser($user)
            ->setRetirer(false)
            ->setAnnuler(false)
            ->setFretSMS(0);
        
        $form = $this->createForm(DemandeDeRetraitType::class, $d);
        
        if($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {
            $d->setCommission($d->getMontant() * 0.1);
            
            $em->persist($d);
            $em->flush();
            
            return $this->redirectToRoute('demande_de_retrait');
        }
        
        return $this->render('CoreBundle:User:demande_de_retrait.html.twig', [
            'form' => $form->createView(),
            'ds' => $em->getRepository('CoreBundle:DemandeDeRetrait')->findBy(['user' => $user], ['dateDemande' => 'desc'])
        ]);
    }
    
    public function repAction() {
        
    }
    
    private function _nomPrenoms(User $user) {
        return $user->getNom().' '.$user->getPrenoms();
    }

        public function organigrammeAction() {
        $ur = $this->getDoctrine()->getManager()->getRepository('CoreBundle:User');
        $user = $this->getUser();
        $o = ['name' => $this->_nomPrenoms($user)];
        $fs = $ur->getFilleuls($user);
        
        if(count($fs) > 0) {
            $c = [];
            
            foreach ($fs as $f) {
                $c[] = ['name' => $this->_nomPrenoms($f)];
            }
            
            $o['children'] = $c;
        }
        
        return $this->render('CoreBundle:User:organigramme.html.twig', ['o' => $o]);
    }

    public function smsAction(){
        $user = new User();
        $user_id = $user->getId();
        $sms = new SMS();
        $form = $this->get('form.factory')->create(SMSType::class, $sms);
        return $this->render('CoreBundle:User:sms.html.twig',
            array(
                'form' => $form->createView()
            ));
    }
}