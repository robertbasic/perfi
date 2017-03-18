<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionsController extends Controller
{
    /**
     * @Template
     * @Route("/transactions", name="transactions")
     */
    public function indexAction(Request $request)
    {
        return [
        ];
    }

    /**
     * @Route("/transactions-list", name="transactions_list")
     */
    public function listAction(Request $request)
    {
        $repository = $this->get('perfi.repository.transaction');
        $transactions = $repository->getAll();

        return new JsonResponse([
            'data' => $transactions
        ]);
    }
}
