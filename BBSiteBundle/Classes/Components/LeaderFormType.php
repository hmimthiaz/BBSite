<?php

namespace BlueBeetle\BBSiteBundle\Classes\Components;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class LeaderFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', 'bwcms_content',
            array(
                'label' => 'Image',
                'contentType' => 'Media',
                'schema' => 'File',
                'onlyImage' => true,
                'required' => false,
                'constraints' => array(new NotBlank())
            )
        );

        $builder->add('name', 'text',
            array(
                'label' => 'name',
                'required' => false,
                'constraints' => array(new NotBlank())
            )
        );

        $builder->add('designation', 'text',
            array(
                'label' => 'Designation',
                'required' => false,
                'constraints' => array(new NotBlank())
            )
        );

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'LUF';
    }
}
