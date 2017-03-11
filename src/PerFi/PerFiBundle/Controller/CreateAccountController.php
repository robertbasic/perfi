<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Controller;

use PerFi\PerFiBundle\Form\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CreateAccountController extends Controller
{
    /**
     * @Template
     * @Route("/create-account", name="create_account")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(AccountType::class, []);

        return [
            'form' => $form->createView(),
        ];
    }
}
