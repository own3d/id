<?php

namespace Own3d\Id\Traits;

use Own3d\Id\ApiOperations\Json;
use Own3d\Id\Result;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
trait EventsTrait
{
    use Json;

    /**
     * Returns a event object
     *
     * @param string $type
     * @param string $data
     * @param string $version
     * @return Result Result object
     */
    public function sendEvent(string $type, string $data, string $version = '2020-08-22'): Result
    {
        return $this->json('POST', 'events', [
            'type' => $type,
            'data' => $data,
            'api_version' => $version,
        ]);
    }
}
