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
            ->add('tags', 'entity', array(
                                          'class' => 'SdzBlogBundle:Tag',
                                          'property' => 'nom',
                                          'expanded' => true,
                                          'multiple' => true))
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
