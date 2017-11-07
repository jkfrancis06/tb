<?php
namespace CoreBundle\Controller;

use CoreBundle\Entity\Admin;
use CoreBundle\Form\AdminType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller {
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $req
     * @return Response
     */
    public function createAction(Request $req) {
        $admin = new Admin();
        $admin->setRoles(array('ROLE_ADMIN'));
        $form = $this->createForm(AdminType::class, $admin);


        if($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {    
            $admin->setRoles(array('ROLE_ADMIN'));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();
            
            return $this->redirectToRoute('admin_create');
        }

        return $this->render('CoreBundle:Admin:create.html.twig', ['form' => $form->createView()]);
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $req
     * @return Response
     */
    public function modifierProfilAction(Request $req) {
        $admin = $this->getUser();
        $form = $this->createForm(AdminType::class, $admin);

        if($req->isMethod('POST') && $form->handleRequest($req)->isValid()) {    
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            return $this->redirectToRoute('admin_profil');
        }

        return $this->render('CoreBundle:Admin:create.html.twig', ['form' => $form->createView()]);
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function profilAction(Request $req) {
        return $this->render('CoreBundle:Admin:profil.html.twig');
    }
    
    /**
     * @return Response
     */
    public function loginAction() {
//        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
//            return $this->redirectToRoute('_index');
//        }
        /*$a = new Admin();
        $a->setRoles(['ROLE_ADMIN'])
            ->setNom('ADMIN')
            ->setPrenom('Admin')
            ->setPassword('to')
            ->setEmail('admin@admin.admin')
            ->setTel('00000001');
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($a);
        $em->flush();*/
        
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('CoreBundle:Admin:login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }
    
    public function ajouterMembreAction() {
        return $this->redirectToRoute('', ['url' => 'admin']);
    }
}