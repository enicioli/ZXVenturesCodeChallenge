<?php

namespace ZXVentures\CodeChallenge\Controllers\PDV;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use ZXVentures\CodeChallenge\Controllers\AbstractController;

/**
 * Class PDVController
 * @package ZXVentures\CodeChallenge\Controllers\PDV
 */
class PDVController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        if (!$this->validateRequestAgainstSchema($request, POSTPDVSchema::class)) {
            return $this->responsBadRequest($this->validator->getErrors());
        }

        try {
            $jsonBodyAsArray = json_decode($request->getContent(), true);
            $pdv = $this->PDVService->createPDV($jsonBodyAsArray);

            return $this->responseCreated($pdv->toArray());
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $this->logger->error($message);
        }

        return $this->responsBadRequest((isset($message) ? ['message' => $message] : null));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function read($id) : JsonResponse
    {
        if (!is_numeric($id)) {
            return $this->responsBadRequest();
        }

        try {
            if ($pdv = $this->PDVService->getPDVById((int)$id)) {
                return $this->response($pdv->toArray());
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $this->logger->error($message);
        }

        return $this->responseNotFound((isset($message) ? ['message' => $message] : null));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function searchByCoverageArea(Request $request) : JsonResponse
    {
        $x = (float)$request->get('lng');
        $y = (float)$request->get('lat');

        if (empty($x) || empty($y)) {
            return $this->responsBadRequest();
        }

        try {
            if ($pdv = $this->PDVService->getPDVWithCoverageAreaForCoordinates($x, $y)) {
                return $this->response($pdv->toArray());
            }

            if ($pdv = $this->PDVService->getPDVNearForCoordinates($x, $y)) {
                return $this->response($pdv->toArray());
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $this->logger->error($message);
        }

        return $this->responseNotFound((isset($message) ? ['message' => $message] : null));
    }
}
