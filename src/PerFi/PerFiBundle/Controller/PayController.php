<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Controller;

use PerFi\Domain\Account\Command\CreateAccount;
use PerFi\Domain\Transaction\Command\Pay;
use PerFi\PerFiBundle\Form\PayType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PayController extends Controller
{
    /**
     * @Template
     * @Route("/pay", name="pay")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(PayType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $command = new Pay(
                $sourceAccount,
                $destinationAccount,
                $amount,
                $currency,
                $description
            );
            $this->get('command_bus')->handle($command);

            $this->addFlash('success', 'Payment made!');

            return $this->redirectToRoute('pay');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
