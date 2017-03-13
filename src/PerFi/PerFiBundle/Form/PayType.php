<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Form;

use PerFi\Domain\Account\AccountRepository;
use PerFi\Domain\Account\AccountType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PayType extends AbstractType
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    public function __construct(AccountRepository $repository)
    {
        $this->accountRepository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('source', ChoiceType::class, [
                'required' => true,
                'choices' => $this->getAssetAccounts(),
            ])
            ->add('destination', ChoiceType::class, [
                'required' => true,
                'choices' => $this->getExpenseAccounts(),
            ])
            ->add('amount', NumberType::class, [
                'required' => true,
            ])
            ->add('currency', ChoiceType::class, [
                'required' => true,
                'choices' => []
            ])
            ->add('description', TextType::class, [
                'required' => true,
            ]);
    }

    private function getAssetAccounts() : array
    {
        $type = AccountType::fromString('asset');

        $assets = $this->accountRepository->getAllByType($type);

        return $this->buildAccountChoices($assets);
    }

    private function getExpenseAccounts() : array
    {
        $type = AccountType::fromString('expense');

        $expenses = $this->accountRepository->getAllByType($type);

        return $this->buildAccountChoices($expenses);
    }

    private function buildAccountChoices(array $accounts) : array
    {
        $choices = [];

        foreach ($accounts as $account) {
            $choices[$account->title()] = (string) $account->id();
        }

        return $choices;
    }
}
