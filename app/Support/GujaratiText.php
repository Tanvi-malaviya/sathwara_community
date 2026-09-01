<?php

namespace App\Support;

class GujaratiText
{
    /**
     * dompdf has no Indic text-shaping engine: it draws each Unicode
     * codepoint's glyph in storage order with no reordering or ligature
     * substitution. Gujarati (like other Brahmic scripts) stores the
     * vowel sign િ (U+0ABF, "i") after its consonant/conjunct cluster but
     * renders it visually *before* that cluster. Left unreordered, dompdf
     * draws the િ glyph stranded after the consonant it belongs to,
     * producing garbled/disconnected output (e.g. "વિગત" -> "વગિત").
     *
     * This moves િ to the front of the consonant (or consonant+halant
     * conjunct) cluster it attaches to, so dompdf's naive left-to-right
     * glyph placement lines up with correct Gujarati reading order.
     */
    public static function reorderMatra(string $text): string
    {
        // Only run matra reordering if the string actually contains Gujarati script
        if (!preg_match('/[\x{0A80}-\x{0AFF}]/u', $text)) {
            return $text;
        }

        $pattern = '/((?:[\x{0A95}-\x{0AB9}]\x{0ACD})*[\x{0A95}-\x{0AB9}])\x{0ABF}/u';

        return preg_replace($pattern, "\u{0ABF}$1", $text) ?? $text;
    }

    /**
     * Receipts render Gujarati text in "HindVadodara" (matches the site's
     * own Gujarati font, resources/fonts/gujarati) but that family ships no
     * Gujarati digit glyphs (૦-૯ / U+0AE6-U+0AEF) at all, so any digits
     * inside Gujarati content (event titles/descriptions with a year, etc.)
     * render as empty tofu boxes. NotoSansGujarati does have them, so wrap
     * just the digit runs in that font as a targeted fallback.
     */
    public static function wrapDigitsWithFallbackFont(string $html): string
    {
        $pattern = '/[\x{0AE6}-\x{0AEF}]+/u';

        return preg_replace_callback($pattern, function (array $m) {
            return '<span style="font-family: \'NotoSansGujarati\';">' . $m[0] . '</span>';
        }, $html) ?? $html;
    }
}
