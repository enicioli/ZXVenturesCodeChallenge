<?php

namespace ZXVentures\Tests\CodeChallenge\Services;

use Mockery;
use PHPUnit\Framework\TestCase;
use ZXVentures\CodeChallenge\ODM\Documents\Address;
use ZXVentures\CodeChallenge\ODM\Documents\Coordinates;
use ZXVentures\CodeChallenge\ODM\Documents\CoverageArea;
use ZXVentures\CodeChallenge\ODM\Documents\PDV;
use ZXVentures\CodeChallenge\Services\PDVService;

/**
 * Class PDVServiceTest
 * @package ZXVentures\Tests\CodeChallenge\Services
 */
class PDVServiceTest extends TestCase
{
    public function testCreatePDV() : void
    {
        $json = file_get_contents('./tests/ZXVentures/CodeChallenge/Services/fixtures/createPDV.json');
        $pdvArray = json_decode($json, true);

        $mockRepository = Mockery::mock('ZXVentures\CodeChallenge\ODM\Repositories\PDVRepository')->makePartial();
        $mockRepository->shouldReceive('persist')->withAnyArgs();

        $mockDocumentManager = Mockery::mock('Doctrine\ODM\MongoDB\DocumentManager')->makePartial();
        $mockDocumentManager->shouldReceive('getRepository')->with(PDV::class)->andReturn($mockRepository);
        $mockDocumentManager->shouldReceive('persist');
        $mockDocumentManager->shouldReceive('flush');

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('mongodbodm.dm')->andReturn($mockDocumentManager);

        $PDVService = new PDVService($mockApp);
        $response = $PDVService->createPDV($pdvArray);

        $this->assertNotNull($response);
        $this->assertInstanceOf('ZXVentures\CodeChallenge\ODM\Documents\PDV', $response);
    }

    public function testGetPDVById(): void
    {
        $json = file_get_contents('./tests/ZXVentures/CodeChallenge/Services/fixtures/createPDV.json');
        $pdvObj = json_decode($json);

        $id = $pdvObj->id;

        $pdvDoc = $this->createPDVDocument($pdvObj);

        $mockRepository = Mockery::mock('ZXVentures\CodeChallenge\ODM\Repositories\PDVRepository')->makePartial();
        $mockRepository->shouldReceive('find')->with($id)->andReturn($pdvDoc);

        $mockDocumentManager = Mockery::mock('Doctrine\ODM\MongoDB\DocumentManager')->makePartial();
        $mockDocumentManager->shouldReceive('getRepository')->with(PDV::class)->andReturn($mockRepository);

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('mongodbodm.dm')->andReturn($mockDocumentManager);

        $PDVService = new PDVService($mockApp);
        $response = $PDVService->getPDVById($id);

        $this->assertNotNull($response);
        $this->assertInstanceOf('ZXVentures\CodeChallenge\ODM\Documents\PDV', $response);
        $this->assertEquals($response->toArray(), $pdvDoc->toArray());
    }

    public function testGetPDVWithCoverageAreaForCoordinates(): void
    {
        $x = -38.561737;
        $y = -3.736494;

        $json = file_get_contents('./tests/ZXVentures/CodeChallenge/Services/fixtures/createPDV.json');
        $pdvObj = json_decode($json);

        $pdvDoc = $this->createPDVDocument($pdvObj);

        $mockRepository = Mockery::mock('ZXVentures\CodeChallenge\ODM\Repositories\PDVRepository')->makePartial();
        $mockRepository->shouldReceive('findOneWithCoverageAreaForCoordinates')->with($x, $y)->andReturn($pdvDoc);

        $mockDocumentManager = Mockery::mock('Doctrine\ODM\MongoDB\DocumentManager')->makePartial();
        $mockDocumentManager->shouldReceive('getRepository')->with(PDV::class)->andReturn($mockRepository);

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('mongodbodm.dm')->andReturn($mockDocumentManager);

        $PDVService = new PDVService($mockApp);
        $response = $PDVService->getPDVWithCoverageAreaForCoordinates($x, $y);

        $this->assertNotNull($response);
        $this->assertInstanceOf('ZXVentures\CodeChallenge\ODM\Documents\PDV', $response);
        $this->assertEquals($response->toArray(), $pdvDoc->toArray());
    }

    public function testGetPDVNearForCoordinates(): void
    {
        $x = -38.561737;
        $y = -3.736494;

        $json = file_get_contents('./tests/ZXVentures/CodeChallenge/Services/fixtures/createPDV.json');
        $pdvObj = json_decode($json);

        $pdvDoc = $this->createPDVDocument($pdvObj);

        $mockRepository = Mockery::mock('ZXVentures\CodeChallenge\ODM\Repositories\PDVRepository')->makePartial();
        $mockRepository->shouldReceive('findOneNearForCoordinates')->with($x, $y)->andReturn($pdvDoc);

        $mockDocumentManager = Mockery::mock('Doctrine\ODM\MongoDB\DocumentManager')->makePartial();
        $mockDocumentManager->shouldReceive('getRepository')->with(PDV::class)->andReturn($mockRepository);

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('mongodbodm.dm')->andReturn($mockDocumentManager);

        $PDVService = new PDVService($mockApp);
        $response = $PDVService->getPDVNearForCoordinates($x, $y);

        $this->assertNotNull($response);
        $this->assertInstanceOf('ZXVentures\CodeChallenge\ODM\Documents\PDV', $response);
        $this->assertEquals($response->toArray(), $pdvDoc->toArray());
    }

    /**
     * @param \stdClass $pdvObj
     * @return PDV
     */
    private function createPDVDocument(\stdClass $pdvObj): PDV
    {
        $pdvDoc = new PDV();
        $pdvDoc->setId($pdvObj->id);
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

        $pdvCoverageAreaDoc->setCoordinates($pdvCoverageAreaDocCoordinates);
        $pdvDoc->setCoverageArea($pdvCoverageAreaDoc);

        return $pdvDoc;
    }
}
