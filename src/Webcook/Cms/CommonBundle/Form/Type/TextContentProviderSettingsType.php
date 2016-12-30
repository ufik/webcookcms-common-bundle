<?php

/**
 * This file is part of Webcook security bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook 
 */

namespace Webcook\Cms\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Settings form type.
 */
class TextContentProviderSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, array(
                
            ))
            ->add('page', EntityType::class, array(
                'class' => 'WebcookCmsCoreBundle:Page'
            ))
            ->add('section', EntityType::class, array(
                'class' => 'WebcookCmsCoreBundle:Section'
            ))
            ->add('version', HiddenType::class, array('mapped' => false));
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings::class,
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'text_content_provider_settings';
    }
}
