<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Officer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class OfficerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fio', 'text', array(
                'label' => 'ФИО',
                'constraints' => array(
                    new NotBlank([
                        'message' => "Укажите ФИО должностного лица"
                    ]),
                ),
            ))
            ->add('token', 'text', array(
                'label' => 'Номер жетона',
                'constraints' => array(
                    new NotBlank([
                        'message' => "Укажите номер жетона"
                    ]),
                ),
            ))
            ->add('photoFile', 'file', [
                'label' => 'Фото',
                'required' => false
            ])
            ->add('mediaFile', 'file', [
                'label' => 'Аудио/Видео',
                'required' => false
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'officer_handle';
    }
} 
