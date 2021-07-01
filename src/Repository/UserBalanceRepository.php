<?php


namespace App\Repository;


use App\Entity\User;
use App\Entity\UserBalance;
use Doctrine\ORM\EntityManagerInterface;

class UserBalanceRepository implements UserBalanceRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findUsersBalance(User $user): ?UserBalance
    {
        $qb = $this->em->createQueryBuilder();

        return $qb->select('userBalance')
            ->from(UserBalance::class, 'userBalance')
            ->where('userBalance.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function save(UserBalance $balance): void
    {
        $this->em->persist($balance);
        $this->em->flush();
    }
}