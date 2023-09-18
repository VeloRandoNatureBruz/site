<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function get_class;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
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
    public function orderUserByReferent(){
        $query = $this
            ->createQueryBuilder('u')
            ->innerJoin('u.referents', 'r')
            ->addSelect('r')
            ->orderBy('r.ordre', 'ASC')
            ->orderBy('r.id','ASC')
        ;
        return $query->getQuery()->getResult();
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
