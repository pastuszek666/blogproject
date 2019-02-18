<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Common\UserBundle\Exception\UserException;
use Common\UserBundle\Entity\User;
use Common\UserBundle\Form\Type\LoginType;
use Common\UserBundle\Form\Type\RememberPasswordType;
use Common\UserBundle\Form\Type\RegisterUserType;
use Symfony\Component\HttpFoundation\Response;

class PagesController extends AbstractController
{
    /**
     * @Route("admin/dashboard", name = "dashboard")
     * 
     */
    public function dashboard()
    {
        return $this->render('backend/dashboard/dashboard.html.twig', [
            
        ]);
    }
    
    
}
