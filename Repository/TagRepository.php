<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\TagBundle\Repository;

use Claroline\CoreBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findAllPlatformTags($orderedBy = 'name', $order = 'ASC')
    {
        $dql = "
            SELECT t
            FROM Claroline\TagBundle\Entity\Tag t
            WHERE t.user IS NULL
            ORDER BY t.{$orderedBy} {$order}
        ";
        $query = $this->_em->createQuery($dql);

        return $query->getResult();
    }

    public function findSearchedPlatformTags($search = '', $orderedBy = 'name', $order = 'ASC')
    {
        $dql = "
            SELECT t
            FROM Claroline\TagBundle\Entity\Tag t
            WHERE t.user IS NULL
            AND UPPER(t.name) LIKE :search
            ORDER BY t.{$orderedBy} {$order}
        ";
        $query = $this->_em->createQuery($dql);
        $upperSearch = strtoupper($search);
        $query->setParameter('search', "%{$upperSearch}%");

        return $query->getResult();
    }

    public function findAllUserTags(
        User $user,
        $withPlatform = false,
        $orderedBy = 'name',
        $order = 'ASC'
    )
    {
        if ($withPlatform) {
            $dql = "
                SELECT t
                FROM Claroline\TagBundle\Entity\Tag t
                WHERE t.user = :user
                OR t.user IS NULL
                ORDER BY t.{$orderedBy} {$order}
            " ;
        } else {
            $dql = "
                SELECT t
                FROM Claroline\TagBundle\Entity\Tag t
                WHERE t.user = :user
                ORDER BY t.{$orderedBy} {$order}
            ";
        }
        $query = $this->_em->createQuery($dql);
        $query->setParameter('user', $user);

        return $query->getResult();
    }

    public function findSearchedUserTags(
        User $user,
        $search = '',
        $withPlatform = false,
        $orderedBy = 'name',
        $order = 'ASC'
    )
    {
        if ($withPlatform) {
            $dql = "
                SELECT t
                FROM Claroline\TagBundle\Entity\Tag t
                WHERE (
                    t.user = :user
                    OR t.user IS NULL
                )
                AND UPPER(t.name) LIKE :search
                ORDER BY t.{$orderedBy} {$order}
            ";
        } else {
            $dql = "
                SELECT t
                FROM Claroline\TagBundle\Entity\Tag t
                WHERE t.user = :user
                AND UPPER(t.name) LIKE :search
                ORDER BY t.{$orderedBy} {$order}
            ";
        }
        $query = $this->_em->createQuery($dql);
        $query->setParameter('user', $user);
        $upperSearch = strtoupper($search);
        $query->setParameter('search', "%{$upperSearch}%");

        return $query->getResult();
    }

    public function findOnePlatformTagByName($name)
    {
        $dql = '
            SELECT t
            FROM Claroline\TagBundle\Entity\Tag t
            WHERE t.user IS NULL
            AND t.name = :name
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('name', $name);

        return $query->getOneOrNullResult();
    }

    public function findOneUserTagByName(User $user, $name)
    {
        $dql = '
            SELECT t
            FROM Claroline\TagBundle\Entity\Tag t
            WHERE t.user = :user
            AND t.name = :name
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('user', $user);
        $query->setParameter('name', $name);

        return $query->getOneOrNullResult();
    }
}
