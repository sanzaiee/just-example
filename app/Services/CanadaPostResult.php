<?php

namespace App\Services;

class CanadaPostResult
{
    public const TYPE_FIND     = 'find';
    public const TYPE_RETRIEVE = 'retrieve';

    protected array $items;
    protected string $type;

    public function __construct(array $response, string $type)
    {
        logger()->info($response);
        logger()->info('...........');
        $this->items = $response['Items'] ?? [];
        logger()->info($this->items);
        $this->type  = $type;
    }

    /**
     * Check if FIND returned suggestions
     */
    public function hasSuggestions(): bool
    {
        $hasSugestion = $this->type === self::TYPE_FIND && !empty($this->items);
        logger()->info('CanadaPostResult hasSuggestions', [
            'type' => $this->type,
            'items_count' => count($this->items),
            'has_suggestion' => $hasSugestion,
        ]);
        return $hasSugestion;
    }

    /**
     * Decode FIND suggestions for display
     */
    public function suggestions(): array
    {
        if ($this->type !== self::TYPE_FIND) {
            return [];
        }

        return collect($this->items)
            ->map(fn($item) => [
                'id'          => $item['Id'],
                'text'        => $item['Text'],
                'description' => $item['Description'] ?? '',
                'next'        => $item['Next'] ?? null,
            ])
            ->values()
            ->toArray();
    }

    /**
     * Placeholder for RETRIEVE decoding
     */
    public function normalized(): array
    {
        if ($this->type !== self::TYPE_RETRIEVE || empty($this->items)) {
            return [];
        }

        // Prefer ENG item (case-insensitive), fallback to first item
        $item = null;

        foreach ($this->items as $candidate) {
            if (
                isset($candidate['Language']) &&
                strcasecmp($candidate['Language'], 'ENG') === 0
            ) {
                $item = $candidate;
                break;
            }
        }

        $item ??= $this->items[0];

        return [
            'house_no'    => $item['SubBuilding'] ?? '',
            'address'     => $item['Line1'] ?? '',
            'city'        => $item['City'] ?? '',
            'province'    => $item['ProvinceName'] ?? '',
            'postal_code' => $item['PostalCode'] ?? '',
            'description' => $item['Label'] ?? '',
            // 'description'   => trim(implode(', ', array_filter([
            //     $item['Line1'] ?? null,
            //     $item['City'] ?? null,
            //     $item['ProvinceName'] ?? null,
            //     $item['PostalCode'] ?? null,
            // ]))),
        ];
    }
}