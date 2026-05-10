<?php

use Illuminate\Support\HtmlString;
use Jeffersongoncalves\FilamentFlux\Support\HeroiconNormalizer;

it('strips heroicon-{variant}-* prefixes', function () {
    expect(HeroiconNormalizer::name('heroicon-o-document-text'))->toBe('document-text');
    expect(HeroiconNormalizer::name('heroicon-s-cog'))->toBe('cog');
    expect(HeroiconNormalizer::name('heroicon-mini-trash'))->toBe('trash');
    expect(HeroiconNormalizer::name('heroicon-micro-bell'))->toBe('bell');
    expect(HeroiconNormalizer::name('heroicon-c-info'))->toBe('info');
    expect(HeroiconNormalizer::name('heroicon-m-star'))->toBe('star');
    expect(HeroiconNormalizer::name('heroicon-outline-home'))->toBe('home');
    expect(HeroiconNormalizer::name('heroicon-solid-fire'))->toBe('fire');
});

it('passes bare names through (assumes heroicon)', function () {
    expect(HeroiconNormalizer::name('document-text'))->toBe('document-text');
    expect(HeroiconNormalizer::name('star'))->toBe('star');
});

it('strips Filament v5 Heroicon enum prefixes (no heroicon- prefix)', function () {
    expect(HeroiconNormalizer::name('o-bars-3'))->toBe('bars-3');
    expect(HeroiconNormalizer::name('s-cog'))->toBe('cog');
    expect(HeroiconNormalizer::name('m-trash'))->toBe('trash');
    expect(HeroiconNormalizer::name('c-bell'))->toBe('bell');
    expect(HeroiconNormalizer::name('mini-info'))->toBe('info');
    expect(HeroiconNormalizer::name('micro-star'))->toBe('star');
    expect(HeroiconNormalizer::name('outline-fire'))->toBe('fire');
    expect(HeroiconNormalizer::name('solid-home'))->toBe('home');
});

it('returns null for non-heroicon icon sets', function () {
    expect(HeroiconNormalizer::name('fontawesome-brands-github'))->toBeNull();
    expect(HeroiconNormalizer::name('tabler-user'))->toBeNull();
    expect(HeroiconNormalizer::name('lucide-rocket'))->toBeNull();
    expect(HeroiconNormalizer::name('phosphor-tree'))->toBeNull();
    expect(HeroiconNormalizer::name('mdi-account'))->toBeNull();
    expect(HeroiconNormalizer::name('octicon-bell'))->toBeNull();
});

it('returns null for non-string non-enum inputs', function () {
    expect(HeroiconNormalizer::name(null))->toBeNull();
    expect(HeroiconNormalizer::name(fn () => 'star'))->toBeNull();
    expect(HeroiconNormalizer::name(new HtmlString('<svg/>')))->toBeNull();
});

it('returns null for empty input', function () {
    expect(HeroiconNormalizer::name(''))->toBeNull();
    expect(HeroiconNormalizer::name('   '))->toBeNull();
});

it('resolves variant from heroicon prefix', function () {
    expect(HeroiconNormalizer::variant('heroicon-o-star'))->toBe('outline');
    expect(HeroiconNormalizer::variant('heroicon-s-star'))->toBe('solid');
    expect(HeroiconNormalizer::variant('heroicon-m-star'))->toBe('mini');
    expect(HeroiconNormalizer::variant('heroicon-mini-star'))->toBe('mini');
    expect(HeroiconNormalizer::variant('heroicon-c-star'))->toBe('micro');
    expect(HeroiconNormalizer::variant('heroicon-micro-star'))->toBe('micro');
    expect(HeroiconNormalizer::variant('heroicon-outline-star'))->toBe('outline');
    expect(HeroiconNormalizer::variant('heroicon-solid-star'))->toBe('solid');
});

it('resolves variant from Filament v5 enum prefix (no heroicon-)', function () {
    expect(HeroiconNormalizer::variant('o-bars-3'))->toBe('outline');
    expect(HeroiconNormalizer::variant('s-cog'))->toBe('solid');
    expect(HeroiconNormalizer::variant('m-trash'))->toBe('mini');
    expect(HeroiconNormalizer::variant('c-bell'))->toBe('micro');
    expect(HeroiconNormalizer::variant('mini-info'))->toBe('mini');
    expect(HeroiconNormalizer::variant('micro-star'))->toBe('micro');
    expect(HeroiconNormalizer::variant('outline-fire'))->toBe('outline');
    expect(HeroiconNormalizer::variant('solid-home'))->toBe('solid');
});

it('returns null variant for bare names and non-heroicon sets', function () {
    expect(HeroiconNormalizer::variant('star'))->toBeNull();
    expect(HeroiconNormalizer::variant('tabler-user'))->toBeNull();
    expect(HeroiconNormalizer::variant(null))->toBeNull();
});

it('isResolvable agrees with name()', function () {
    expect(HeroiconNormalizer::isResolvable('heroicon-o-star'))->toBeTrue();
    expect(HeroiconNormalizer::isResolvable('star'))->toBeTrue();
    expect(HeroiconNormalizer::isResolvable('tabler-user'))->toBeFalse();
    expect(HeroiconNormalizer::isResolvable(null))->toBeFalse();
    expect(HeroiconNormalizer::isResolvable(new HtmlString('<svg/>')))->toBeFalse();
});
