<?php

namespace App\Services;

use App\Support\GujaratiText;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PdfInstance;

class ReceiptPdfService
{
    /**
     * Render a receipt Blade view to a dompdf instance, fixing up any
     * Gujarati text so it doesn't get garbled by dompdf's lack of Indic
     * text shaping. Centralized here so every receipt (downloads and
     * emailed attachments) gets the same fix without repeating it per
     * call site.
     */
    public static function make(string $view, array $data): PdfInstance
    {
        $html = view($view, $data)->render();
        $html = GujaratiText::reorderMatra($html);
        $html = GujaratiText::wrapDigitsWithFallbackFont($html);

        return Pdf::loadHTML($html);
    }
}
