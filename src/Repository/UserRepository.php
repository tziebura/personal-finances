<?php


namespace App\Repository;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function findByLogin(string $login): ?User
    {
        $qb = $this->em->createQueryBuilder();

        return $qb->select('user')
            ->from(User::class, 'user')
            ->where('user.email = :login')
            ->setParameter('login', $login)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}