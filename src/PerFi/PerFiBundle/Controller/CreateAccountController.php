<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CreateAccountController extends Controller
{
    /**
     * @Template
     * @Route("/", name="create_account")
     */
    public function indexAction(Request $request)
    {
        return [
        ];
    }
}
