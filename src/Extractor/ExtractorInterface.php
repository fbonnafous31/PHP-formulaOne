<?php

namespace App\Extractor;

interface ExtractorInterface
{
    public function create_table($xml, string $tableName): string;
    public function drop_table(string $tableName): string;
    public function insert($xml, string $tableName): array;
}
