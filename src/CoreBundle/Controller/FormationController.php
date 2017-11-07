<?php
namespace CoreBundle\Controller;

use CoreBundle\Entity\Formation;
use CoreBundle\Form\FormationType;
use CoreBundle\Service\FormationFileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FormationController extends Controller {
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $req
     * @param FormationFileUploader $formationFileUploader
     * @return Response
     */
    public function createAction(Request $req/*, FormationFileUploader $formationFileUploader*/) {
        $formation = new Formation();
        
        $form = $this->createForm(FormationType::class, $formation);
        
        if($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {
            $file = $formation->getFile();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move($this->getParameter('formation_dir'), $fileName);
            
            $formation->setFile($fileName);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
            
            return $this->redirectToRoute('formation_create');
        }
        
        return $this->render('CoreBundle:Formation:create.html.twig', ['form' => $form->createView()]);
    }
    
    /*
     * @Security("has_role('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function indexAction() {
        return $this->render('CoreBundle:Formation:index.html.twig', [
            'fs' => $this->getDoctrine()->getManager()->getRepository('CoreBundle:Formation')->findAll()
        ]);
    }
}