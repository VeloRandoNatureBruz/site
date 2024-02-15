<?php

namespace App\Repository;

use App\Entity\User;
use function get_class;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface, PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByIdentifier(string $identifier): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
                'SELECT u
                FROM App\Entity\User u
                WHERE u.username = :query'
            )
            ->setParameter('query', $identifier)
            ->getOneOrNullResult();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param PasswordAuthenticatedUserInterface $user
     * @param string $newHashedPassword
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    #pour lister tous les user en une seule requete pour le trombi
    public function OrfindUsers()
    {
        $qb = $this->createQueryBuilder('u');

        $qb->leftJoin('u.photos', 'p')
            ->addSelect('p')
            ->orderBy('u.username', 'ASC');

        $query = $qb->getQuery();
        return new Paginator($query);
    }

    #On récupère la table User et la table Referent #
    #Ensuite on tris les users via le champ ordre de la table Referent#
    public function orderUserByReferent(): array
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.referents', 'r') // Utilisation de innerJoin pour récupérer uniquement les utilisateurs ayant des référents associés
            ->orderBy('r.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    #On récupère la table User, Photo et la table Referent #
    #Ensuite on tris les users via le champ ordre de la table Referent#
    public function orderUserByReferentWithPhoto(){
        $query = $this
            ->createQueryBuilder('u')
            ->innerJoin('u.referents', 'r')
            ->addSelect('r')
            ->leftJoin('u.photos','p')
            ->addSelect('p')
            ->orderBy('r.ordre', 'ASC')

        ;
        return $query->getQuery()->getResult();
    }

    /**
     * Trouve les utilisateurs ayant un bureau associé et les ordonne par l'ordre de leur bureau.
     *
     * @return User[] Retourne un tableau d'utilisateurs ayant un bureau associé, ordonné par l'ordre de leur bureau.
     */
    public function orderUserByBureau(): array
    {
        return $this->createQueryBuilder('u')
            ->join('u.bureau', 'b')
            ->orderBy('b.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    #On récupère la table User, Photo et la table Bureau #
    #Ensuite on tris les users via le champ ordre de la table Bureau#
    public function orderUserByBureauWithPhoto()
    {
            $query = $this->createQueryBuilder('u')
                ->join('u.bureau', 'b')
                ->addSelect('b')
                ->leftJoin('u.photos', 'p')
                ->addSelect('p')
                ->orderBy('b.ordre', 'ASC');

        return $query->getQuery()->getResult();
    }


    // public function findUsers2()
    // {
    //     $qb = $this->createQueryBuilder('u');
    //     $qb->leftJoin('u.photos', 'p')
    //         ->addSelect('p')
    //         ->orderBy('u.nom', 'ASC');

    //     $query = $qb->getQuery();
    //     return new Paginator($query);
    // }


    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
