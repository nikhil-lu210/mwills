<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMXPath;

class PostBody
{
    /**
     * Prepend a favicon image to external http(s) links so the public post shows a site icon next to pasted URLs.
     * Skips links that already contain an image (e.g. from the editor paste handler).
     */
    public static function enhanceLinksWithFavicons(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'UTF-8');
        $wrapped = '<?xml encoding="UTF-8"?><div id="post-body-root">'.$html.'</div>';
        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementById('post-body-root');
        if (! $root) {
            return $html;
        }

        $xpath = new DOMXPath($dom);
        foreach ($xpath->query('.//a[@href]') as $a) {
            if (! $a instanceof DOMElement) {
                continue;
            }

            $href = $a->getAttribute('href');
            if (! preg_match('#^https?://#i', $href)) {
                continue;
            }

            $host = parse_url($href, PHP_URL_HOST);
            if (! $host) {
                continue;
            }

            foreach ($a->getElementsByTagName('img') as $_) {
                continue 2;
            }

            $img = $dom->createElement('img');
            $img->setAttribute('src', 'https://www.google.com/s2/favicons?domain='.rawurlencode($host).'&sz=32');
            $img->setAttribute('width', '16');
            $img->setAttribute('height', '16');
            $img->setAttribute('alt', '');
            $img->setAttribute('class', 'post-link-favicon');
            $img->setAttribute('loading', 'lazy');
            $a->insertBefore($img, $a->firstChild);

            $existing = $a->getAttribute('class');
            $a->setAttribute('class', trim($existing.' post-link-with-favicon'));
            if ($a->getAttribute('target') === '') {
                $a->setAttribute('target', '_blank');
            }
            if ($a->getAttribute('rel') === '') {
                $a->setAttribute('rel', 'noopener noreferrer');
            }
        }

        $inner = '';
        foreach ($root->childNodes as $child) {
            $inner .= $dom->saveHTML($child);
        }

        return $inner;
    }
}
