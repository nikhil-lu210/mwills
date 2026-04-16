<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4 — Data API (server-side reports)
    |--------------------------------------------------------------------------
    |
    | Values are loaded from the database (Site settings → Google Analytics).
    | Defaults here apply only before settings exist or when a key is empty.
    |
    */

    'property_id' => null,

    'credentials_path' => null,

    /*
    |--------------------------------------------------------------------------
    | GA4 Web (gtag.js) — Measurement ID for the public site
    |--------------------------------------------------------------------------
    */

    'measurement_id' => null,

    'cache_ttl_seconds' => 300,

    'blog_path_contains' => 'intelligence',

];
