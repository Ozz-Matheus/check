<?php

namespace App\Traits;

trait HasStatusMetadata
{
    public static function byContextAndTitle(string $context, string $title): ?self
    {
        static $cache = [];

        $key = "{$context}:{$title}";

        if (! isset($cache[$key])) {
            $cache[$key] = self::query()
                ->where('context', $context)
                ->where('title', $title)
                ->first();
        }

        return $cache[$key];
    }

    public static function labelFromTitle(string $title): ?string
    {
        return self::query()
            ->where('title', $title)
            ->value('label');
    }

    public static function iconFromTitle(string $title): ?string
    {
        return self::query()
            ->where('title', $title)
            ->value('icon');
    }

    public static function colorFromTitle(string $title): ?string
    {
        return self::query()
            ->where('title', $title)
            ->value('color');
    }

    public function isProtected(): bool
    {
        return $this->protected ?? false;
    }

    public function colorName(): string
    {
        return $this->color ?? 'gray';
    }

    public function iconName(): string
    {
        return $this->icon ?? 'heroicon-o-information-circle';
    }
}
