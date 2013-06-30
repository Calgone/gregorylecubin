<?php

namespace Greg\ReaderBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FeedRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FeedRepository extends EntityRepository
{
    function getFeeds($subId)
    {
        if (!$subId)
        {
            throw new \InvalidArgumentException("L'argument subId n'est pas défini");
        }
        
        $query = $this->createQueryBuilder('f')
                ->where('f.sub_id = :subId')
                ->setParameter('sub_id', $subId);
    }
}