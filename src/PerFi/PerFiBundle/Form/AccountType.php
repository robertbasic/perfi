<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Form;

use PerFi\Domain\Account\AccountTypeView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'required' => true,
                'choices' => AccountTypeView::getTypes(),
            ])
            ->add('title', TextType::class, [
                'required' => true,
            ]);
    }
}
