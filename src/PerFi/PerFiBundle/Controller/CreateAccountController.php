<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Controller;

use PerFi\Domain\Account\Command\CreateAccount;
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

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $type = $formData['type'];
            $title = $formData['title'];

            $command = new CreateAccount($type, $title);

            $this->get('command_bus')->handle($command);
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
