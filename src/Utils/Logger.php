<?php

namespace App\Utils;

class Logger
{
    public static function log($log, $editDate = true)
    {
        if ($editDate) $log = date("Y-m-d H:i:s") . ' - ' . $log . "\n";
        else $log = $log . "\n";

        $filename = date('Ymd');
        $file = __DIR__ . '/../../logs/' . $filename . '.txt';
        fopen($file, 'a');
        file_put_contents($file, $log, FILE_APPEND);
    }
}
