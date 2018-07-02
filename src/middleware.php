<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @var $app Silex\Application
 */

/**
 * This is done in order to accept JSON requests
 */
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : []);
    }
});

$app->error(
    function (\Exception $e, $request) use ($app) {
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return new JsonResponse(['message' => $e->getMessage()]);
        }

        $app['logger']->addError($e->getMessage());
        $app['logger']->addError($e->getTraceAsString());

        return new JsonResponse(['request' => $request, 'message' => $e->getMessage(), 'stacktrace' => $e->getTraceAsString()]);
    }
);
