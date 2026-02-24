<?php

namespace App\Services;

use App\Services\CanadaPostResult;

class CanadaPostService_mock
{
    /**
     * Step 1: Find suggestions for an incomplete address
     */
    public function find(string $search): CanadaPostResult
    {
        $path = base_path('canadapost-find-api-response.json');
        $json = file_get_contents($path);

        if ($json === false) {
            throw new \RuntimeException('Unable to read canadapost-find-api-response.json');
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in find-response.json');
        }

        return new CanadaPostResult($data, CanadaPostResult::TYPE_FIND);
    }

    /**
     * Step 1b: Find using a LastId (multi-round Find)
     */
    public function findByLastId(string $lastId): CanadaPostResult
    {
        $path = base_path('canadapost-find-byitem-api-response.json');

        $json = file_get_contents($path);

        if ($json === false) {
            throw new \RuntimeException('Unable to read canadapost-find-byitem-api-response.json');
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in canadapost-find-byitem-api-response.json');
        }

        return new CanadaPostResult($data, CanadaPostResult::TYPE_FIND);
    }

    /**
     * Step 2: Retrieve a validated address (placeholder)
     */
    public function retrieve(string $id): CanadaPostResult
    {
        $path = base_path('canadapost-find-retrieve-response.json');

        $json = file_get_contents($path);

        if ($json === false) {
            throw new \RuntimeException('Unable to read canadapost-find-retrieve-response.json');
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON in canadapost-find-retrieve-response.json');
        }

        // Returns CanadaPostResult with TYPE_RETRIEVE
        return new CanadaPostResult($data, CanadaPostResult::TYPE_RETRIEVE);
    }
}
