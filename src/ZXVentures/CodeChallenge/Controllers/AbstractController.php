<?php

namespace ZXVentures\CodeChallenge\Controllers;

use JsonSchema\Validator as Validator;
use Monolog\Logger;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ZXVentures\CodeChallenge\Services\PDVService;

/**
 * Class AbstractController
 * @package ZXVentures\CodeChallenge\Controllers
 */
abstract class AbstractController
{
    /** @var Application */
    protected $app;

    /** @var array */
    protected $confg;

    /** @var Logger */
    protected $logger;

    /** @var Validator */
    protected $validator;

    /** @var PDVService */
    protected $PDVService;

    /**
     * InvoiceController constructor
     *
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->config = $this->app['config'];
        $this->logger = $this->app['logger'];
        $this->validator = new Validator();
        $this->PDVService = $this->app['PDVService'];
    }

    /**
     * @param Request $request
     * @param AbstractSchema|string $schema
     * @return bool
     */
    protected function validateRequestAgainstSchema(Request $request, $schema) : bool
    {
        $this->validator->reset();
        $jsonBodyAsObj = json_decode($request->getContent());
        $this->validator->validate($jsonBodyAsObj, $schema::getSchema());

        return $this->validator->isValid();
    }

    /**
     * @param null $data
     * @param int $status
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    protected function response($data = null, $status = 200, $headers = array(), $json = false) : JsonResponse
    {
        return new JsonResponse($data, $status, $headers, $json);
    }

    /**
     * @param null $data
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    protected function responseCreated($data = null, $headers = array(), $json = false) : JsonResponse
    {
        $data = $data ?: ['message' => 'Created'];

        return new JsonResponse($data, 201, $headers, $json);
    }

    /**
     * @param null $data
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    protected function responsBadRequest($data = null, $headers = array(), $json = false) : JsonResponse
    {
        $data = $data ?: ['message' => 'Bad request'];

        return $this->response($data, 400, $headers, $json);
    }

    /**
     * @param null $data
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    protected function responseNotFound($data = null, $headers = array(), $json = false) : JsonResponse
    {
        $data = $data ?: ['message' => 'Not found'];

        return $this->response($data, 404, $headers, $json);
    }
}
