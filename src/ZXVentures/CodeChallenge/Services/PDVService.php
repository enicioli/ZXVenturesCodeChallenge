<?php

namespace ZXVentures\CodeChallenge\Services;

use ZXVentures\CodeChallenge\ODM\Documents\PDV;

/**
 * Class PDVService
 *
 * @package ZXVentures\CodeChallenge\Services
 */
class PDVService extends AbstractService
{
    /**
     * @param array $data
     * @return PDV
     */
    public function createPDV(array $data = [])
    {
        $pdv = (new PDV())->fromArray($this->dm, $data);
        $this->getPDVRepository()->persist($pdv);

        return $pdv;
    }

    /**
     * @param $id
     * @return null|object|PDV
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function getPDVById($id)
    {
        return $this->getPDVRepository()->find($id);
    }

    /**
     * @param float $x
     * @param float $y
     * @return array|null|object|PDV
     */
    public function getPDVWithCoverageAreaForCoordinates(float $x, float $y)
    {
        return $this->getPDVRepository()->findOneWithCoverageAreaForCoordinates($x, $y);
    }

    /**
     * @param float $x
     * @param float $y
     * @return array|null|object|PDV
     */
    public function getPDVNearForCoordinates(float $x, float $y)
    {
        return $this->getPDVRepository()->findOneNearForCoordinates($x, $y);
    }
}
