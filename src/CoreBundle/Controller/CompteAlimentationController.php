<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CompteAlimentationController extends Controller {
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function alimentationAction() {
        $as = $this->getDoctrine()->getManager()->getRepository('CoreBundle:CompteAlimentation')->findBy(
            ['user' => $this->getUser()],
            ['createAt' => 'desc']
        );
        
        return $this->render('CoreBundle:CompteAlimentation:alimentation.html.twig', ['as' => $as]);
    }
}