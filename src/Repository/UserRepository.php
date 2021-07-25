<?php


namespace App\Repository;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class UserRepository implements UserRepositoryInterface, UserProviderInterface
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

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->findByLogin($user->getUsername());
    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class)
    {
        return $class === User::class;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername(string $username): UserInterface
    {
        $user = $this->findByLogin($username);

        if (!$user) {
            throw new UserNotFoundException(sprintf('User with username %s was not found', $username));
        }

        return $user;
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->loadUserByUsername($identifier);
    }

}