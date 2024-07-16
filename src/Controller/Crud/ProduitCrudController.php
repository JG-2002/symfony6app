<?php

namespace App\Controller\Crud;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use App\Form\ImageFormType;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield Field\TextField::new('name', 'Nom')
            ->setColumns(12);
        yield Field\TextField::new('ean13', 'Ean13')
            ->setColumns(12);
        yield Field\AssociationField::new('category', 'Catégorie')
            ->setColumns(6);
        yield Field\AssociationField::new('marque', 'Marque')
            ->setColumns(6);
        yield Field\MoneyField::new('price', 'Prix')
            ->setColumns(6)
            ->setCustomOption('storedAsCents', false)
            ->setCurrency('XAF');
        yield Field\IntegerField::new('quantity', 'Quantité')
            ->setColumns(6);
        yield Field\TextEditorField::new('description', 'Description')
            ->setColumns(12);
        yield Field\TextField::new('imageFile', 'Image principale')
            ->setColumns(12)
            ->onlyOnForms()
            ->setFormType(\Vich\UploaderBundle\Form\Type\VichImageType::class);
        yield Field\CollectionField::new('productImages', 'Images')
            ->onlyOnForms()
            ->setColumns(12)
            ->setFormTypeOption('by_reference', false)
            ->setEntryType(ImageFormType::class);
    }
}
