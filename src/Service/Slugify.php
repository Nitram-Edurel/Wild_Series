<?php

namespace App\Service;

class Slugify
{
    public function generate(string $input): string
    {
        $input = strtolower($input);        
        $input = preg_replace("/[^a-z0-9_\s-]/", " ", $input);
        $input = preg_replace("/[\s-]/", " ", $input);
        $input = preg_replace("/[\s_]/", "-", $input);
        return $input;
    }
}
