<?php

namespace App\Controller\Crud;

use App\Entity\Slider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class SliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slider::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield Field\TextField::new('caption', 'Légende');
        yield Field\TextField::new('imageFile', 'Image')
            ->setFormType(\Vich\UploaderBundle\Form\Type\VichImageType::class);
        yield Field\TextField::new('mignatureFile', 'Logo Entreprise')
            ->setFormType(\Vich\UploaderBundle\Form\Type\VichImageType::class);
        yield Field\TextField::new('link', 'Lien miniature');
    }
}
