<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Controller;

use PerFi\Domain\Account\Command\CreateAccount;
use PerFi\PerFiBundle\Form\CreateAccountType;
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
        $form = $this->createForm(CreateAccountType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $type = $formData['type'];
            $title = $formData['title'];

            $command = new CreateAccount($type, $title);
            $this->get('command_bus')->handle($command);

            $this->addFlash('success', 'Account created!');

            return $this->redirectToRoute('create_account');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
