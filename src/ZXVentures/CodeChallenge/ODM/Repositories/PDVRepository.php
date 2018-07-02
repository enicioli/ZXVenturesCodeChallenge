<?php

namespace ZXVentures\CodeChallenge\ODM\Repositories;

use Doctrine\ODM\MongoDB\DocumentRepository;
use ZXVentures\CodeChallenge\ODM\Documents\PDV;

/**
 * Class PDVRepository
 * @package ZXVentures\CodeChallenge\ODM\Repositories
 */
class PDVRepository extends DocumentRepository
{
    /**
     * @param PDV $pdv
     */
    public function persist(PDV $pdv)
    {
        $this->dm->persist($pdv);
        $this->dm->flush();
    }

    /**
     * @param float $x
     * @param float $y
     * @return array|null|object|PDV
     */
    public function findOneWithCoverageAreaForCoordinates(float $x, float $y)
    {
        return $this->dm
            ->createQueryBuilder(PDV::class)
            ->field('coverageArea.coordinates')
            ->geoNear($x, $y)
            ->spherical(true)
            ->geoIntersects(['type' => 'Point', 'coordinates' => [$x, $y]])
            ->limit(1)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param float $x
     * @param float $y
     * @return array|null|object|PDV
     */
    public function findOneNearForCoordinates(float $x, float $y)
    {
        return $this->dm
            ->createQueryBuilder(PDV::class)
            ->field('address.coordinates')
            ->near($x, $y)
            ->limit(1)
            ->getQuery()
            ->getSingleResult();
    }
}
