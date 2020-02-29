<?php

namespace ZXVentures\Tests\CodeChallenge\Controllers;

use Mockery;
use PHPUnit\Framework\TestCase;
use ZXVentures\CodeChallenge\Controllers\PDV\PDVController;
use ZXVentures\CodeChallenge\ODM\Documents\Address;
use ZXVentures\CodeChallenge\ODM\Documents\Coordinates;
use ZXVentures\CodeChallenge\ODM\Documents\CoverageArea;
use ZXVentures\CodeChallenge\ODM\Documents\PDV;

/**
 * Class PDVControllerTest
 * @package ZXVentures\Tests\CodeChallenge\Controllers
 */
class PDVControllerTest extends TestCase
{
    public function testCreate() : void
    {
        $json = file_get_contents('./tests/ZXVentures/CodeChallenge/Controllers/fixtures/createPDV.json');
        $pdvArray = json_decode($json, true);
        $pdvObj = json_decode($json);

        $pdvDoc = $this->createPDVDocument($pdvObj);

        $mockPDVService = Mockery::mock('ZXVentures\CodeChallenge\Services\PDVService')->makePartial();
        $mockPDVService->shouldReceive('createPDV')->with($pdvArray)->andReturn($pdvDoc);

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('PDVService')->andReturn($mockPDVService);

        $mockRequest = Mockery::mock('Symfony\Component\HttpFoundation\Request')->makePartial();
        $mockRequest->shouldReceive('getContent')->andReturn($json);

        $PDVController = new PDVController($mockApp);
        $response = $PDVController->create($mockRequest);

        $this->assertJson($response->getContent());
        $this->assertEquals($response->getStatusCode(), 201);
        $this->assertEquals($response->getContent(), json_encode($pdvDoc->toArray()));
    }

    public function testCreateWithBadRequest() : void
    {
        $json = '{"document": 123456}';

        $mockPDVService = Mockery::mock('ZXVentures\CodeChallenge\Services\PDVService')->makePartial();

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('PDVService')->andReturn($mockPDVService);

        $mockRequest = Mockery::mock('Symfony\Component\HttpFoundation\Request')->makePartial();
        $mockRequest->shouldReceive('getContent')->andReturn($json);

        $PDVController = new PDVController($mockApp);
        $response = $PDVController->create($mockRequest);

        $this->assertJson($response->getContent());
        $this->assertEquals($response->getStatusCode(), 400);
    }

    public function testRead() : void
    {
        $json = file_get_contents('./tests/ZXVentures/CodeChallenge/Controllers/fixtures/createPDV.json');
        $pdvObj = json_decode($json);
        $id = $pdvObj->id;

        $pdvDoc = $this->createPDVDocument($pdvObj);
        $mockPDVService = Mockery::mock('ZXVentures\CodeChallenge\Services\PDVService')->makePartial();
        $mockPDVService->shouldReceive('getPDVById')->with($id)->andReturn($pdvDoc);

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('PDVService')->andReturn($mockPDVService);

        $PDVController = new PDVController($mockApp);
        $response = $PDVController->read($id);

        $this->assertJson($response->getContent());
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals($response->getContent(), json_encode($pdvDoc->toArray()));
    }

    public function testReadNotFound() : void
    {
        $json = file_get_contents('./tests/ZXVentures/CodeChallenge/Controllers/fixtures/createPDV.json');
        $pdvObj = json_decode($json);
        $id = $pdvObj->id;

        $mockPDVService = Mockery::mock('ZXVentures\CodeChallenge\Services\PDVService')->makePartial();
        $mockPDVService->shouldReceive('getPDVById')->with($id)->andReturn(null);

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('PDVService')->andReturn($mockPDVService);

        $PDVController = new PDVController($mockApp);
        $response = $PDVController->read($id);

        $this->assertJson($response->getContent());
        $this->assertEquals($response->getStatusCode(), 404);
    }

    public function testReadBadRequest() : void
    {
        $id = 'abc';

        $mockPDVService = Mockery::mock('ZXVentures\CodeChallenge\Services\PDVService')->makePartial();

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('PDVService')->andReturn($mockPDVService);

        $PDVController = new PDVController($mockApp);
        $response = $PDVController->read($id);

        $this->assertJson($response->getContent());
        $this->assertEquals($response->getStatusCode(), 400);
    }

    public function testSearchByCoverageArea() : void
    {
        $x = -38.561737;
        $y = -3.736494;

        $json = file_get_contents('./tests/ZXVentures/CodeChallenge/Controllers/fixtures/createPDV.json');
        $pdvObj = json_decode($json);

        $pdvDoc = $this->createPDVDocument($pdvObj);

        $mockPDVService = Mockery::mock('ZXVentures\CodeChallenge\Services\PDVService')->makePartial();
        $mockPDVService->shouldReceive('getPDVWithCoverageAreaForCoordinates')->with($x, $y)->andReturn($pdvDoc);

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('PDVService')->andReturn($mockPDVService);

        $mockRequest = Mockery::mock('Symfony\Component\HttpFoundation\Request')->makePartial();
        $mockRequest->shouldReceive('get')->with('lng')->andReturn($x);
        $mockRequest->shouldReceive('get')->with('lat')->andReturn($y);

        $PDVController = new PDVController($mockApp);
        $response = $PDVController->searchByCoverageArea($mockRequest);

        $this->assertJson($response->getContent());
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals($response->getContent(), json_encode($pdvDoc->toArray()));
    }

    public function testSearchByCoverageAreaNear() : void
    {
        $x = -38.561737;
        $y = -3.736494;

        $json = file_get_contents('./tests/ZXVentures/CodeChallenge/Controllers/fixtures/createPDV.json');
        $pdvObj = json_decode($json);

        $pdvDoc = $this->createPDVDocument($pdvObj);

        $mockPDVService = Mockery::mock('ZXVentures\CodeChallenge\Services\PDVService')->makePartial();
        $mockPDVService->shouldReceive('getPDVWithCoverageAreaForCoordinates')->with($x, $y)->andReturn(null);
        $mockPDVService->shouldReceive('getPDVNearForCoordinates')->with($x, $y)->andReturn($pdvDoc);

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('PDVService')->andReturn($mockPDVService);

        $mockRequest = Mockery::mock('Symfony\Component\HttpFoundation\Request')->makePartial();
        $mockRequest->shouldReceive('get')->with('lng')->andReturn($x);
        $mockRequest->shouldReceive('get')->with('lat')->andReturn($y);

        $PDVController = new PDVController($mockApp);
        $response = $PDVController->searchByCoverageArea($mockRequest);

        $this->assertJson($response->getContent());
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals($response->getContent(), json_encode($pdvDoc->toArray()));
    }

    public function testSearchByCoverageAreaNotFound() : void
    {
        $x = -38.561737;
        $y = -3.736494;

        $mockPDVService = Mockery::mock('ZXVentures\CodeChallenge\Services\PDVService')->makePartial();
        $mockPDVService->shouldReceive('getPDVWithCoverageAreaForCoordinates')->with($x, $y)->andReturn(null);
        $mockPDVService->shouldReceive('getPDVNearForCoordinates')->with($x, $y)->andReturn(null);

        $mockApp = Mockery::mock('Silex\Application')->makePartial();
        $mockApp->shouldReceive('offsetGet')->with('config')->andReturn([]);
        $mockApp->shouldReceive('offsetGet')->with('logger')->andReturn(null);
        $mockApp->shouldReceive('offsetGet')->with('PDVService')->andReturn($mockPDVService);

        $mockRequest = Mockery::mock('Symfony\Component\HttpFoundation\Request')->makePartial();
        $mockRequest->shouldReceive('get')->with('lng')->andReturn($x);
        $mockRequest->shouldReceive('get')->with('lat')->andReturn($y);

        $PDVController = new PDVController($mockApp);
        $response = $PDVController->searchByCoverageArea($mockRequest);

        $this->assertJson($response->getContent());
        $this->assertEquals($response->getStatusCode(), 404);
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
