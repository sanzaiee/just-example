<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\CanadaPostResult;

class CanadaPostService
{
    protected string $apiKey;

    protected const FIND_URL = 'https://ws1.postescanada-canadapost.ca/addresscomplete/interactive/find/v2.10/json3.ws';
    protected const RETRIEVE_URL = 'https://ws1.postescanada-canadapost.ca/addresscomplete/interactive/retrieve/v2.11/json3.ws';

    public function __construct()
    {
        $this->apiKey = config('services.api_key.canada_post_key');
    }

    /**
     * Step 1: Find suggestions for an incomplete address
     */
    public function find(string $search): CanadaPostResult
    {
        $response = Http::acceptJson()->get(self::FIND_URL, [
            'Key'                => $this->apiKey,
            'SearchTerm'         => $search,
            'Country'            => 'CAN',
            'LanguagePreference' => 'en',
            'MaxSuggestions'     => 10,
        ]);

        if (! $response->ok()) {
            throw new \RuntimeException('Canada Post Find API error');
        }
        logger()->info('Canada Post Find API response', ['response' => $response->json()]); 

        return new CanadaPostResult($response->json(), CanadaPostResult::TYPE_FIND);
    }

    /**
     * Step 1b: Find using a LastId (multi-round Find)
     */
    public function findByLastId(string $lastId): CanadaPostResult
    {
        $response = Http::acceptJson()->get(self::FIND_URL, [
            'Key'                => $this->apiKey,
            'LastId'             => $lastId,
            'Country'            => 'CAN',
            'LanguagePreference' => 'en',
            'MaxSuggestions'     => 10,
        ]);

        if (! $response->ok()) {
            throw new \RuntimeException('Canada Post Find (LastId) API error');
        }

        logger()->info('Canada Post Find (LastId) response', ['lastId' => $lastId, 'response' => $response->json()]);

        return new CanadaPostResult($response->json(), CanadaPostResult::TYPE_FIND);
    }

    /**
     * Step 2: Retrieve a validated address (placeholder)
     */
    public function retrieve(string $id): CanadaPostResult
    {
        $response = Http::acceptJson()->get(self::RETRIEVE_URL, [
            'Key' => $this->apiKey,
            'Id'  => $id,
        ]);

        if (! $response->ok()) {
            throw new \RuntimeException('Canada Post Retrieve API error');
        }

        logger()->info('Canada Post Retrieve response', ['id' => $id, 'response' => $response->json()]);

        // Returns CanadaPostResult with TYPE_RETRIEVE
        return new CanadaPostResult($response->json(), CanadaPostResult::TYPE_RETRIEVE);
    }
}
