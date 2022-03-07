<?php

namespace App\Controller\Admin;

use App\Entity\Vinyl;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VinylCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vinyl::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
