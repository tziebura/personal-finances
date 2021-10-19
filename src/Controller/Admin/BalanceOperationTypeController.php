<?php

namespace App\Controller\Admin;

use App\Entity\BalanceOperationType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BalanceOperationTypeController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return BalanceOperationType::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            BooleanField::new('income'),
            BooleanField::new('necessary'),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
        ];
    }
}