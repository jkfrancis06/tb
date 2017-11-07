<?php
namespace CoreBundle\Controller;

use CoreBundle\Entity\Paiement;
use CoreBundle\Form\PaiementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PaiementController extends Controller {
    /**
     * @param Request $req
     */
    public function payerAction(Request $req) {
        $p = new Paiement();
        $form = $this->createForm(PaiementType::class, $p);

        if($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            
            $req->getSession()->getFlashBag()->add('info', 'Paiement effectue.');
    
            return $this->redirectToRoute('payer');
        }

        return $this->render('CoreBundle:Paiement:payer.html.twig', ['form' => $form->createView()]);
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function paiementsInvalidesAction() {
        return $this->render('CoreBundle:Paiement:paiements_invalides.html.twig', [
            'ps' => $this->getDoctrine()->getManager()->getRepository('CoreBundle:Paiement')->findBy(['valider' => false], ['createAt' => 'desc'])
        ]);
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function validerPaiementAction($id) {
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('CoreBundle:Paiement')->find($id);
        $p->setValider(true);
        $p->setUrl(md5($p->getId().$p->getTel().$p->getCode()));
        
        $em->flush();
        
        return $this->redirectToRoute('paiements_invalides');
    }
}