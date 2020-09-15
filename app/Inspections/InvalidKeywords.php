<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    protected $keyWords = [
        'yahoo customer service'
    ];

    public function detect($body)
    {
        foreach ($this->keyWords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('your reply contains spam.');
            }
        }
    }
}
