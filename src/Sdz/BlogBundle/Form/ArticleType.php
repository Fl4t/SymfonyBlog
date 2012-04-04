<?php

namespace Sdz\BlogBundle\Form;

use Sdz\BlogBundle\Form\TagType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('titre')
            ->add('contenu')
            ->add('auteur')
            ->add('tags', 'collection', array('type'      => new TagType,
                                              'prototype' => true,
                                              'allow_add' => true))
        ;
    }

    public function getName()
    {
        return 'sdz_blogbundle_articletype';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Sdz\BlogBundle\Entity\Article',
        );
    }
}
