<?php

namespace Greg\ReaderBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SubsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChannelRepository extends EntityRepository
{
    /*
     * Renvoie la liste des channels 
     */
    public function getChannels()
    {
        $query = $this->createQueryBuilder('c')
                ->leftJoin('c.category', 's')
                ->addSelect('s')
                ->orderBy('s.name')
                ->getQuery();
        return $query->getResult();
    }
}
