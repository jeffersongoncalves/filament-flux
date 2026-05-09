<?php

namespace Jeffersongoncalves\FilamentFlux\Support;

use Illuminate\Support\Facades\File;

class ThemeFileEditor
{
    /**
     * Idempotently insert one or more @import directives into a Tailwind v4 theme file.
     *
     * @param  array<int, string>  $importPaths  Relative paths to add as @import entries
     * @return bool True if file was modified, false if all entries already exist
     */
    public function addImportLines(string $themePath, array $importPaths): bool
    {
        return $this->addDirectiveLines($themePath, '@import', $importPaths);
    }

    /**
     * Idempotently insert one or more @source directives into a Tailwind v4 theme file.
     *
     * @param  array<int, string>  $sourcePaths
     */
    public function addSourceLines(string $themePath, array $sourcePaths): bool
    {
        return $this->addDirectiveLines($themePath, '@source', $sourcePaths);
    }

    public function hasImport(string $contents, string $path): bool
    {
        return $this->hasDirective($contents, '@import', $path);
    }

    public function hasSource(string $contents, string $path): bool
    {
        return $this->hasDirective($contents, '@source', $path);
    }

    /**
     * @param  array<int, string>  $paths
     */
    protected function addDirectiveLines(string $themePath, string $directive, array $paths): bool
    {
        if (! File::exists($themePath)) {
            throw new \RuntimeException("Theme file not found: {$themePath}");
        }

        $contents = File::get($themePath);
        $original = $contents;

        $missing = array_filter(
            $paths,
            fn (string $path): bool => ! $this->hasDirective($contents, $directive, $path),
        );

        if (empty($missing)) {
            return false;
        }

        $insertion = collect($missing)
            ->map(fn (string $path): string => "{$directive} \"{$path}\";")
            ->implode("\n");

        $contents = $this->insertAfterAnchor($contents, $insertion);

        if ($contents === $original) {
            return false;
        }

        File::put($themePath, $contents);

        return true;
    }

    protected function hasDirective(string $contents, string $directive, string $path): bool
    {
        $escapedDirective = preg_quote($directive, '/');
        $escapedPath = preg_quote($path, '/');

        return (bool) preg_match("/{$escapedDirective}\s+['\"]".$escapedPath."['\"]/", $contents);
    }

    protected function insertAfterAnchor(string $contents, string $insertion): string
    {
        $patterns = [
            "/(@import\s+['\"]tailwindcss['\"];\s*\n)/",
            "/((?:@import\s+['\"][^'\"]+['\"];\s*\n)+)/",
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $contents, $matches, PREG_OFFSET_CAPTURE)) {
                $offset = $matches[0][1] + strlen($matches[0][0]);

                return substr($contents, 0, $offset)
                    .$insertion."\n"
                    .substr($contents, $offset);
            }
        }

        return rtrim($contents)."\n\n".$insertion."\n";
    }
}
