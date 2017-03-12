<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Template
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return [
        ];
    }
}
