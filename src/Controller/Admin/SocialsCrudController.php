<?php

namespace App\Controller\Admin;

use App\Entity\Socials;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SocialsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Socials::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('instagram', 'Instagram'),
            TextField::new('behance', 'Behance'),
            TextField::new('linkedin', 'Linkedin'),
            TextField::new('email', 'Email'),
            BooleanField::new('isEmailVisible', 'Make email visible on website')
            ->renderAsSwitch()

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW) // Disable the "Add new" button
            ->disable(Action::DELETE); // Optionally disable deletion
    }
}
