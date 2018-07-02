<?php

namespace ZXVentures\CodeChallenge\ODM\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class PDV
 * @package ZXVentures\CodeChallenge\ODM\Documents
 *
 * @ODM\Document(repositoryClass="ZXVentures\CodeChallenge\ODM\Repositories\PDVRepository")
 */
class PDV extends AbstractDocument
{
    /**
     * @ODM\Id(strategy="increment")
     * @var int
     */
    private $id;

    /**
     * @ODM\Field(type="string")
     * @var string
     */
    private $tradingName;

    /**
     * @ODM\Field(type="string")
     * @var string
     */
    private $ownerName;

    /**
     * @ODM\Field(type="string") @ODM\UniqueIndex
     * @var string
     */
    private $document;

    /**
     * @ODM\EmbedOne(targetDocument="Address")
     * @var Address
     */
    private $address;

    /**
     * @ODM\EmbedOne(targetDocument="CoverageArea")
     * @var CoverageArea
     */
    private $coverageArea;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return PDV
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTradingName()
    {
        return $this->tradingName;
    }

    /**
     * @param string $tradingName
     * @return PDV
     */
    public function setTradingName(string $tradingName)
    {
        $this->tradingName = $tradingName;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * @param string $ownerName
     * @return PDV
     */
    public function setOwnerName(string $ownerName)
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param string $document
     * @return PDV
     */
    public function setDocument(string $document)
    {
        $this->document = $document;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return PDV
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return CoverageArea
     */
    public function getCoverageArea()
    {
        return $this->coverageArea;
    }

    /**
     * @param CoverageArea $coverageArea
     * @return PDV
     */
    public function setCoverageArea(CoverageArea $coverageArea)
    {
        $this->coverageArea = $coverageArea;
        return $this;
    }
}

/**
 * @ODM\EmbeddedDocument
 * @ODM\Index(keys={"coordinates"="2d"})
 */
class Address extends AbstractDocument
{
    /**
     * @ODM\Field(type="string")
     * @var string
     */
    private $type;

    /**
     * @ODM\EmbedOne(targetDocument="Coordinates")
     * @var Coordinates
     */
    private $coordinates;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Address
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Coordinates
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param Coordinates $coordinates
     * @return Address
     */
    public function setCoordinates(Coordinates $coordinates)
    {
        $this->coordinates = $coordinates;
        return $this;
    }
}

/**
 * @ODM\EmbeddedDocument
 * @ODM\Index(keys={"coordinates"="2dsphere"})
 */
class CoverageArea extends AbstractDocument
{
    /**
     * @ODM\Field(type="string")
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $coordinates = array();

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return CoverageArea
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    /**
     * @param array $coordinates
     * @return CoverageArea
     */
    public function setCoordinates(array $coordinates): CoverageArea
    {
        $this->coordinates = $coordinates;
        return $this;
    }
}

/**
 * @ODM\EmbeddedDocument
 */
class Coordinates extends AbstractDocument
{
    /**
     * @ODM\Field(type="float")
     * @var float
     */
    private $x;

    /**
     * @ODM\Field(type="float")
     * @var float
     */
    private $y;

    /**
     * Coordinates constructor.
     * @param null|float $x
     * @param null|float $y
     */
    public function __construct($x = null, $y = null)
    {
        if ($x) $this->setX($x);
        if ($y) $this->setY($y);
    }

    /**
     * @return float
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param float $x
     * @return Coordinates
     */
    public function setX(float $x)
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return float
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param float $y
     * @return Coordinates
     */
    public function setY(float $y)
    {
        $this->y = $y;
        return $this;
    }
}
