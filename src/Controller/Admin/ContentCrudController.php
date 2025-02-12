<?php

namespace App\Controller\Admin;

use App\Entity\Content;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ContentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Content::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')
                ->setRequired(true),
            TextEditorField::new('description')
                ->setRequired(true),
            TextField::new('file')
            ->setFormType(VichFileType::class)
                ->setFormTypeOptions([
                    'allow_delete' => false,
                    'download_uri' => true,
                    'image_uri' => true,
                ])
                ->onlyOnForms()
                ->setRequired(true)
                ->setFormTypeOption('attr', ['accept' => 'image/*, video/*']),
            ImageField::new('fileName', 'Contenu')
                ->setBasePath('/uploads/images')
                ->onlyOnIndex()
                ->setRequired(true),
            BooleanField::new('isPublished'),
            ChoiceField::new('fileType')
                ->setChoices([
                    'Video' => 'video',
                    'Image' => 'image',
                ])
                ->renderAsNativeWidget()
                ->setRequired(true),
            ];
    }

}
