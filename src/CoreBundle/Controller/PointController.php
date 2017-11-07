<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PointController extends Controller {
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function pointsAction() {
        $points = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Point')->findBy(
            ['user' => $this->getUser()],
            ['createAt' => 'desc']
        );
        
        return $this->render('CoreBundle:Point:points.html.twig', ['points' => $points]);
    }
}