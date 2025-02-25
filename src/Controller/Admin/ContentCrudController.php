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
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Content::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $isCreating = $pageName === Crud::PAGE_NEW;

        return [
            BooleanField::new('isPublished'),
            TextField::new('title')
                ->setRequired(true),
            TextField::new('description')
                ->setRequired(true),
            TextField::new('file')
                ->setFormType(VichFileType::class)
                ->setFormTypeOptions([
                    'allow_delete' => true, // Enable file deletion
                    'delete_label' => 'Delete current file', // Optional: Add a label for the delete checkbox
                    'download_uri' => true,
                ])
                ->onlyOnForms()
                ->setRequired($isCreating)
                ->setFormTypeOption('constraints', $isCreating ? [new NotBlank(['message' => 'Please upload a file.'])] : []),
            ImageField::new('fileName', 'Contenu')
                ->setBasePath('/uploads/images')
                ->onlyOnIndex(),
            ChoiceField::new('fileType')
                ->setChoices([
                    'Video' => 'video',
                    'Image' => 'image',
                ])
                ->renderAsNativeWidget()
                ->setRequired(true),
            TextField::new('behanceLink', 'Behance link')
                ->setRequired(false)
                ->hideOnIndex(),
            BooleanField::new('isBehanceLinkActive', 'Activate Behance link')
            ->renderAsSwitch(false),

            TextField::new('instagramLink', 'Instagram link')
                ->setRequired(false)
                ->hideOnIndex(),
            BooleanField::new('isInstagramLinkActive', 'Activate Instagram link')
                ->renderAsSwitch(false),

            TextField::new('facebookLink', 'Facebook link')
                ->setRequired(false)
                ->hideOnIndex(),
            BooleanField::new('isFacebookLinkActive', 'Activate Facebook link')
                ->renderAsSwitch(false),

            TextField::new('externalWebsiteLink', 'External website link')
                ->setRequired(false)
                ->hideOnIndex(),
            BooleanField::new('isExternalWebsiteLinkActive', 'Activate external website link')
                ->renderAsSwitch(false),
        ];
    }
}

