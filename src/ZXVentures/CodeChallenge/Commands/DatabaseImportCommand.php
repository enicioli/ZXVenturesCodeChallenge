<?php

namespace ZXVentures\CodeChallenge\Commands;

use ZXVentures\CodeChallenge\ODM\Documents\Address;
use ZXVentures\CodeChallenge\ODM\Documents\Coordinates;
use ZXVentures\CodeChallenge\ODM\Documents\CoverageArea;
use ZXVentures\CodeChallenge\ODM\Documents\PDV;

/**
 * Class DatabaseImportCommand
 * @package ZXVentures\CodeChallenge\Commands
 */
class DatabaseImportCommand extends AbstractCommand
{
    /**
     * @throws \Exception
     */
    protected final function _execute()
    {
        $this->dm->getSchemaManager()->dropDatabases();

        $this->dm->getSchemaManager()->ensureDocumentIndexes(PDV::class);

        /** @var array $pdvObjCollection */
        $pdvObjCollection = json_decode(
            file_get_contents(ROOT_PATH . '/resources/pdv_collection.json')
        )->pdvs;

        /** @var \stdClass $pdvObj */
        foreach ($pdvObjCollection as $pdvObj) {
            $pdvDoc = new PDV();
            //$pdvDoc->setId($pdvObj->id);
            $pdvDoc->setTradingName($pdvObj->tradingName);
            $pdvDoc->setOwnerName($pdvObj->ownerName);
            $pdvDoc->setDocument($pdvObj->document);

            $pdvAddressDoc = new Address();
            $pdvAddressDoc->setType($pdvObj->address->type);
            $pdvAddressCoordinatesDoc = new Coordinates($pdvObj->address->coordinates[0], $pdvObj->address->coordinates[1]);
            $pdvAddressDoc->setCoordinates($pdvAddressCoordinatesDoc);
            $pdvDoc->setAddress($pdvAddressDoc);

            $pdvCoverageAreaDoc = new CoverageArea();
            $pdvCoverageAreaDoc->setType($pdvObj->coverageArea->type);
            $pdvCoverageAreaDocCoordinates = [];
            foreach ($pdvObj->coverageArea->coordinates[0][0] as $coordinatesObj) {
                $pdvCoverageAreaCoordinatesDoc = new Coordinates($coordinatesObj[0], $coordinatesObj[1]);
                $pdvCoverageAreaDocCoordinates[] = $pdvCoverageAreaCoordinatesDoc;
            }
            $pdvCoverageAreaDoc->setCoordinates([$pdvCoverageAreaDocCoordinates]);
            $pdvDoc->setCoverageArea($pdvCoverageAreaDoc);

            $this->dm->persist($pdvDoc);
            $this->dm->flush();
        }

        echo 'Imported documents: ' . count($pdvObjCollection) . PHP_EOL;
    }

    /**
     * @return string
     */
    protected final function _getDescription() : string
    {
        return 'Execute database import from json file';
    }
}
