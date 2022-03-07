<?php

namespace App\Form;

use App\Entity\Vinyl;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;

class VinylType extends AbstractType
{
    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('title')
            ->add('artiste')
            ->add('annee')
            ->add('description')
            ->add('price')
            ->add('qte')
            ->add('mp3', FileType::class, [
                'mapped' => false,
                'label' => 'extrait',
                'required'=>true,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/mpeg3',
                            'audio/x-mpeg3',
                            'audio/mp4',
                            'audio/aac'
                        ],
                        'mimeTypesMessage' => 'Votre fichier doit etre au format mp3',
                    ])
                ]
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'label' => 'pochette',
                'constraints' => [
                    new File([
                        'maxSize' => '8192k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/webp',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Votre image doit etre au format jpg ou png ou w...',
                    ])
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'type',
                'choice_name' => ChoiceList::fieldName($this, 'id'),
                'choice_value' => ChoiceList::value($this, 'id'),
                'multiple' => true,
                'expanded' => true,
                'mapped' => false
            ])
            ->add('createdAt', HiddenType::class, [
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vinyl::class,
        ]);
    }
}
