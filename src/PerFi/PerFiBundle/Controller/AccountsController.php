<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Controller;

use PerFi\Domain\Account\AccountTypeView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountsController extends Controller
{
    /**
     * @Template
     * @Route("/accounts", name="accounts")
     */
    public function indexAction(Request $request)
    {
        $accountTypes = AccountTypeView::getTypes();

        return [
            'account_types' => $accountTypes
        ];
    }

    /**
     * @Route("/accounts-list", name="accounts_list")
     */
    public function listAction(Request $request)
    {
        $type = $request->get('type');

        $repository = $this->get('perfi.repository.account');
        $accounts = $repository->getAllByType($type);

        return new JsonResponse([
            'data' => $accounts
        ]);
    }
}
