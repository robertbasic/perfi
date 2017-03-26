<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RefundController extends Controller
{
    /**
     * @Route("/refund", name="refund")
     * @Method({"POST"})
     */
    public function indexAction(Request $request)
    {
        $id = $request->request->get('id');

        $commandFactory = $this->get('perfi.command.factory.refund');

        $command = $commandFactory($id);

        $this->get('command_bus')->handle($command);

        return new JsonResponse([
            $id
        ]);
    }
}
