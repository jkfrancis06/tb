<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ComptePercevableController extends Controller {
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function gainsAction() {
        $gains = $this->getDoctrine()->getManager()->getRepository('CoreBundle:ComptePercevable')->findBy(
            ['user' => $this->getUser()],
            ['createAt' => 'desc']
        );
        
        return $this->render('CoreBundle:ComptePercevable:gains.html.twig', ['gains' => $gains]);
    }
}