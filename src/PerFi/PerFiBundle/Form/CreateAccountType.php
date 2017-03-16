<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Form;

use PerFi\Domain\Account\AccountType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Asset' => (string) AccountType::fromString('asset'),
                    'Expense' => (string) AccountType::fromString('expense'),
                    'Income' => (string) AccountType::fromString('income'),
                ],
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'empty_data' => '',
            ]);
    }
}
