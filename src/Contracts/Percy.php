<?php declare(strict_types=1);

namespace SamirNijenhuis\LaravelPercy\Contracts;

interface Percy
{
    public function snapshot(string $name, ?array $widths = null, ?int $minHeight = null, bool $enableJavaScript = true, ?string $percyCSS = null): void;
}
