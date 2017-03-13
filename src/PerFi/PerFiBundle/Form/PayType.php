<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('source', ChoiceType::class, [
                'required' => true,
                'choices' => [],
            ])
            ->add('destination', ChoiceType::class, [
                'required' => true,
                'choices' => []
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
}
