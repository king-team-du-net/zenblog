<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\Data\PasswordRequirementData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class PasswordRequirementDataType extends AbstractType
{
    private Request $request;

    public function __construct(
        private readonly ParameterBagInterface $params,
        RequestStack $requestStack,
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('length', ChoiceType::class, [
                //'label' => 'label.length',
                'label' => false,
                'choices' => range(
                    $this->params->get('generator_password_min_length'),
                    $this->params->get('generator_password_max_length')
                ),
                'choice_label' => fn ($value) => $value,
            ])
            ->add('uppercase_letters', CheckboxType::class, [
                'label' => 'label.uppercase_letters',
                'label_attr' => ['class' => 'form-check-label',],
                'required' => false,
                'row_attr' => ['class' => 'form-check radio-bg-light',],
                'attr' => ['class' => 'form-check-input',],
            ])
            ->add('digits', CheckboxType::class, [
                'required' => false,
                'label_attr' => ['class' => 'form-check-label',],
                'row_attr' => ['class' => 'form-check radio-bg-light',],
                'attr' => ['class' => 'form-check-input',],
            ])
            ->add('special_characters', CheckboxType::class, [
                'label' => 'label.special_characters',
                'label_attr' => ['class' => 'form-check-label',],
                'required' => false,
                'row_attr' => ['class' => 'form-check radio-bg-light',],
                'attr' => ['class' => 'form-check-input',],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $passwordRequirementData = new PasswordRequirementData();

        $passwordRequirementData->setLength($this->request->cookies->getInt(
            'generator_password_length',
            $this->params->get('generator_password_default_length')
        ));
        $passwordRequirementData->setUppercaseLetters(
            $this->request->cookies->getBoolean('generator_password_uppercase_letters', false)
        );
        $passwordRequirementData->setDigits(
            $this->request->cookies->getBoolean('generator_password_digits', false)
        );
        $passwordRequirementData->setSpecialCharacters(
            $this->request->cookies->getBoolean('generator_password_special_characters', false)
        );

        $resolver->setDefaults([
            'data_class' => PasswordRequirementData::class,
            'data' => $passwordRequirementData,
        ]);
    }
}
