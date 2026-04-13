<?php

namespace Tests\Unit;

use App\Support\PostBody;
use PHPUnit\Framework\TestCase;

class PostBodyTest extends TestCase
{
    public function test_prepends_favicon_to_external_http_links(): void
    {
        $html = '<p>See <a href="https://mcwillsconsulting.com/contact">our contact page</a>.</p>';
        $out = PostBody::enhanceLinksWithFavicons($html);

        $this->assertStringContainsString('https://www.google.com/s2/favicons?domain=', $out);
        $this->assertStringContainsString(rawurlencode('mcwillsconsulting.com'), $out);
        $this->assertStringContainsString('post-link-favicon', $out);
        $this->assertStringContainsString('post-link-with-favicon', $out);
        $this->assertStringContainsString('target="_blank"', $out);
    }

    public function test_skips_links_that_already_have_an_image(): void
    {
        $html = '<p><a href="https://example.com/page"><img src="/x.png" alt="" />text</a></p>';
        $out = PostBody::enhanceLinksWithFavicons($html);

        $this->assertStringNotContainsString('google.com/s2/favicons', $out);
    }

    public function test_skips_relative_and_mailto_links(): void
    {
        $html = '<p><a href="/local">x</a> <a href="mailto:a@b.com">e</a></p>';
        $out = PostBody::enhanceLinksWithFavicons($html);

        $this->assertStringNotContainsString('google.com/s2/favicons', $out);
    }
}
