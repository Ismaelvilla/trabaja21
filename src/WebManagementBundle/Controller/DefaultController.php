<?php

namespace WebManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirectToRoute("fos_user_security_login");
    }

    public function homeAction()
    {
        return $this->render('WebManagementBundle:Default:index.html.twig');
    }

}
