<?php
namespace CoreBundle\Controller;

use CoreBundle\Entity\Produit;
use CoreBundle\Entity\UserProduit;
use CoreBundle\Form\ProduitType;
use CoreBundle\Form\UserProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProduitController extends Controller {
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $req
     * @return Response
     */
    public function createAction(Request $req) {
        $p = new Produit();
        
        $form = $this->createForm(ProduitType::class, $p);
        
        if($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            
            return $this->redirectToRoute('produit_create');
        }
        
        return $this->render('CoreBundle:Produit:create.html.twig', ['form' => $form->createView()]);
    }
    
    /**
     * 
     * @param Request $req
     * @return Response
     */
    public function commanderAction(Request $req) {
        $up = new UserProduit();
        $up->setQte(1);

        $form = $this->createForm(UserProduitType::class, $up);

        if($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {
            $up->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($up);
            $em->flush();

            return $this->redirectToRoute('produit_create');
        }

        return $this->render('CoreBundle:Produit:commander.html.twig', ['form' => $form->createView()]);
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function achatsAction() {
        $upr = $this->getDoctrine()->getManager()->getRepository('CoreBundle:UserProduit');
        
        return $this->render('CoreBundle:Produit:achats.html.twig', ['ps' => $upr->findBy(['user' => $this->getUser()])]);
    }
}