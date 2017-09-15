<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class NpcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextAreaType::class)
            ->add('descriptionFromAfar', TextAreaType::class)
            ->add('answers', CollectionType::class, array(
                     'entry_type'   => TextType::class,
                     'allow_add'    => true,
                     'allow_delete' => true
                 )) 
            ->add('questions', CollectionType::class, array(
                     'entry_type'   => TextType::class,
                     'allow_add'    => true,
                     'allow_delete' => true
                 )) 
            ;
    }
}
