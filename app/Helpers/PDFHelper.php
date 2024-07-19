<?php

namespace App\Helpers;

use Smalot\PdfParser\Parser;

class PDFHelper
{
    public static function countPages($filePath)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        return count($pdf->getPages());
    }
}