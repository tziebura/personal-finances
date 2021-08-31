<?php

namespace App\Controller\Admin;

use App\Entity\BalanceOperationType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BalanceOperationTypeController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return BalanceOperationType::class;
    }
}