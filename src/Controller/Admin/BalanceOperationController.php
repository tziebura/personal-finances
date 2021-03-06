<?php

namespace App\Controller\Admin;

use App\Entity\BalanceOperation;
use App\Entity\BalanceOperationType;
use App\Event\BalanceOperationCreatedEvent;
use App\Event\BalanceOperationDeletedEvent;
use App\Event\BalanceOperationEvents;
use App\Event\BalanceOperationUpdatedEvent;
use App\ValueObject\OperationAmount;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\InsufficientEntityPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BalanceOperationController extends AbstractCrudController
{
    private EventDispatcherInterface $eventDispatcher;
    private OperationAmount $originalOperationAmount;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getEntityFqcn(): string
    {
        return BalanceOperation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('type'),
            TextField::new('title'),
            NumberField::new('amount')->onlyOnForms(),
            Field::new('operationAmount')
                ->setTemplatePath('admin/fields/balance-operation-amount.html.twig')
                ->onlyOnIndex()
                ->setVirtual(true),
            DateField::new('operationDate'),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
        ];
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param BalanceOperation $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->eventDispatcher->dispatch(new BalanceOperationCreatedEvent(
            $entityInstance->getOperationAmount(),
            $this->getUser()
        ), BalanceOperationEvents::BALANCE_OPERATION_CREATED);

        parent::persistEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
    }

    public function edit(AdminContext $context)
    {
        $event = new BeforeCrudActionEvent($context);
        $this->get('event_dispatcher')->dispatch($event);
        if ($event->isPropagationStopped()) {
            return $event->getResponse();
        }

        if (!$this->isGranted(Permission::EA_EXECUTE_ACTION, ['action' => Action::EDIT, 'entity' => $context->getEntity()])) {
            throw new ForbiddenActionException($context);
        }

        if (!$context->getEntity()->isAccessible()) {
            throw new InsufficientEntityPermissionException($context);
        }

        $this->get(EntityFactory::class)->processFields($context->getEntity(), FieldCollection::new($this->configureFields(Crud::PAGE_EDIT)));
        $this->get(EntityFactory::class)->processActions($context->getEntity(), $context->getCrud()->getActionsConfig());
        $entityInstance = $context->getEntity()->getInstance();

        $this->originalOperationAmount = clone $entityInstance->getOperationAmount();

        $editForm = $this->createEditForm($context->getEntity(), $context->getCrud()->getEditFormOptions(), $context);
        $editForm->handleRequest($context->getRequest());
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->processUploadedFiles($editForm);

            $event = new BeforeEntityUpdatedEvent($entityInstance);
            $this->get('event_dispatcher')->dispatch($event);
            $entityInstance = $event->getEntityInstance();

            $this->updateEntity($this->get('doctrine')->getManagerForClass($context->getEntity()->getFqcn()), $entityInstance);

            $this->get('event_dispatcher')->dispatch(new AfterEntityUpdatedEvent($entityInstance));

            $submitButtonName = $context->getRequest()->request->all()['ea']['newForm']['btn'];
            if (Action::SAVE_AND_CONTINUE === $submitButtonName) {
                $url = $this->get(AdminUrlGenerator::class)
                    ->setAction(Action::EDIT)
                    ->setEntityId($context->getEntity()->getPrimaryKeyValue())
                    ->generateUrl();

                return $this->redirect($url);
            }

            if (Action::SAVE_AND_RETURN === $submitButtonName) {
                $url = empty($context->getReferrer())
                    ? $this->get(AdminUrlGenerator::class)->setAction(Action::INDEX)->generateUrl()
                    : $context->getReferrer();

                return $this->redirect($url);
            }

            return $this->redirectToRoute($context->getDashboardRouteName());
        }

        $responseParameters = $this->configureResponseParameters(KeyValueStore::new([
            'pageName' => Crud::PAGE_EDIT,
            'templateName' => 'crud/edit',
            'edit_form' => $editForm,
            'entity' => $context->getEntity(),
        ]));

        $event = new AfterCrudActionEvent($context, $responseParameters);
        $this->get('event_dispatcher')->dispatch($event);
        if ($event->isPropagationStopped()) {
            return $event->getResponse();
        }

        return $responseParameters;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param BalanceOperation $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->eventDispatcher->dispatch(new BalanceOperationUpdatedEvent(
            $this->originalOperationAmount,
            $entityInstance->getOperationAmount(),
            $this->getUser()
        ), BalanceOperationEvents::BALANCE_OPERATION_UPDATED);

        parent::updateEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param BalanceOperation $entityInstance
     */
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->eventDispatcher->dispatch(new BalanceOperationDeletedEvent(
            $entityInstance->getOperationAmount(),
            $this->getUser()
        ), BalanceOperationEvents::BALANCE_OPERATION_DELETED);

        parent::deleteEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
    }
}