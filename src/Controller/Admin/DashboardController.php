<?php


namespace App\Controller\Admin;


use App\Entity\BalanceOperation;
use App\Entity\BalanceOperationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Operation Types', null, BalanceOperationType::class);
        yield MenuItem::linkToCrud('Operations', null, BalanceOperation::class);
    }
}