<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextAreaType::class)
            ->add('npcs', CollectionType::class, array(
                     'entry_type'   => NpcType::class,
                     'allow_add'    => true,
                     'allow_delete' => true
                 ))
            ->add('objects', CollectionType::class, array(
                     'entry_type'   => GameObjectType::class,
                     'allow_add'    => true,
                     'allow_delete' => true
                 ))
            ;
    }
}
