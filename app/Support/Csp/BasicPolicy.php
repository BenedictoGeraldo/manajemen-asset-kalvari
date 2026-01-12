<?php

namespace App\Support\Csp;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;

class BasicPolicy extends Basic
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, [
                Keyword::SELF,
                'http://localhost:5173',
                'ws://localhost:5173',
            ])
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, [
                Keyword::SELF,
                'data:',
                'raw.githubusercontent.com',
                'img.shields.io',
                'http://localhost:5173',
            ])
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE, // Required for Vite/Alpine init
                Keyword::UNSAFE_EVAL,   // Required for AlpineJS
                'cdn.jsdelivr.net',     // AlpineJS source
                'http://localhost:5173',
            ])
            ->addDirective(Directive::STYLE, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE, // Required for Tailwind
                'fonts.googleapis.com',
                'http://localhost:5173',
            ])
            ->addDirective(Directive::FONT, [
                Keyword::SELF,
                'fonts.gstatic.com',
            ]);
    }
}
