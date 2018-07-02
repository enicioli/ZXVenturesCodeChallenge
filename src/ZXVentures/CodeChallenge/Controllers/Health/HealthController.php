<?php

namespace ZXVentures\CodeChallenge\Controllers\Health;

use Symfony\Component\HttpFoundation\JsonResponse;
use ZXVentures\CodeChallenge\Controllers\AbstractController;

/**
 * Class HealthController
 * @package ZXVentures\CodeChallenge\Controllers\Health
 */
class HealthController extends AbstractController
{
    /** @var float */
    private $uptime;

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->response([
            'app' => $this->config['api']['name'],
            'version' => $this->config['api']['version'],
            'uptime' => $this->getTime()
        ]);
    }

    /**
     * actually trigger a read of the system clock and cache the value
     * @return float
     */
    private function read_uptime() {
        $uptime_raw = @file_get_contents("/proc/uptime");
        $this->uptime = floatval($uptime_raw);

        return $this->uptime;
    }

    /**
     * @return float
     */
    private function get_uptime_cached() {
        if (is_null($this->uptime)) $this->read_uptime();

        return $this->uptime;
    }

    /**
     * recursively run mods on time value up to given depth
     * @param int $d
     * @return int
     **/
    private function doModDep($d)
    {
        $modVals = [31556926, 86400, 3600, 60, 60];
        $start = $this->get_uptime_cached();
        for ($i = 0; $i < $d; $i++) {
            $start = $start % $modVals[$i];
        }

        return intval($start / $modVals[$d]);
    }

    /**
     * @return int
     */
    private function getDays()
    {
        return $this->doModDep(1);
    }

    /**
     * @return int
     */
    private function getHours() {
        return $this->doModDep(2);
    }

    /**
     * @return int
     */
    private function getMinutes()
    {
        return $this->doModDep(3);
    }

    /**
     * @return int
     */
    private function getSeconds()
    {
        return $this->doModDep(4);
    }

    /**
     * @param bool $cached
     * @return string
     */
    private function getTime($cached = false)
    {
        if($cached) $this->read_uptime();

        return sprintf(
            "%03d:%02d:%02d:%02d",
            $this->getDays(),
            $this->getHours(),
            $this->getMinutes(),
            $this->getSeconds()
        );
    }
}
