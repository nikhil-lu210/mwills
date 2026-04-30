<?php

namespace Tests\Unit;

use App\Support\PostBody;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PostBodyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_does_not_inject_favicon_for_normal_links(): void
    {
        $html = '<p>See <a href="https://mcwillsconsulting.com/contact">our contact page</a>.</p>';
        $out = PostBody::enhanceLinksWithFavicons($html);

        $this->assertStringNotContainsString('google.com/s2/favicons', $out);
    }

    public function test_opt_in_links_get_a_favicon_image(): void
    {
        $html = '<p><a href="https://example.com/page" data-mw-favicon="1">x</a></p>';
        $out = PostBody::enhanceLinksWithFavicons($html);

        $this->assertStringContainsString('https://www.google.com/s2/favicons?domain=', $out);
        $this->assertStringContainsString(rawurlencode('example.com'), $out);
        $this->assertStringContainsString('post-link-favicon', $out);
    }

    public function test_skips_opt_in_links_that_already_have_an_image(): void
    {
        $html = '<p><a href="https://example.com/page" data-mw-favicon="1"><img src="/x.png" alt="" />text</a></p>';
        $out = PostBody::enhanceLinksWithFavicons($html);

        $this->assertStringNotContainsString('google.com/s2/favicons', $out);
    }

    public function test_skips_relative_and_mailto_links_even_when_opt_in(): void
    {
        $html = '<p><a href="/local" data-mw-favicon="1">x</a> <a href="mailto:a@b.com" data-mw-favicon="1">e</a></p>';
        $out = PostBody::enhanceLinksWithFavicons($html);

        $this->assertStringNotContainsString('google.com/s2/favicons', $out);
    }
}
