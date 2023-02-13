<?php

namespace App\Extractor;

class ConstructorExtractor implements ExtractorInterface
{
    public function create_table($xml, string $tableName): string
    {
        return '';
    }

    public function drop_table(string $tableName): string
    {
        return '';
    }

    public function insert($xml, string $tableName): array
    {
        return [];
    }
}
