<?php

namespace App\Controller\Crud;

use App\Entity\CategorieProduit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;


class CategorieProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategorieProduit::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield Field\TextField::new('name', 'Nom');
        yield Field\TextField::new('slug', 'Slug');
        yield Field\IntegerField::new('position', 'Posision');
        yield Field\TextareaField::new('description', 'Description')
        ->onlyOnForms();
        yield Field\AssociationField::new('produits', 'Produits')
            ->onlyOnIndex();
    }
    
}
