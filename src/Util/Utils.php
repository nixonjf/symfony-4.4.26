<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Helper functions for api bundle.
 *
 * @author Pit Solutions Pvt Ltd
 */
class Utils {

    /**
     * Generic function to return api response.
     *
     * @param type $status
     * @param type $data
     *
     * @return JsonResponse
     */
    public static function generateJsonResponse($status, $data) {
        return JsonResponse::create(['data' => $data, 'status' => $status], $status, [], true);
    }

}
