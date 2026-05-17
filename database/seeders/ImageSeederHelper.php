<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;

trait ImageSeederHelper
{
    protected function normalizeImageName(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', trim($text));
        return $text;
    }

    protected function findBestImagePath(string $category, string $name): ?string
    {
        $categoryDir = strtolower($category);

        if (!Storage::disk('public')->exists($categoryDir)) {
            return null;
        }

        $files = Storage::disk('public')->files($categoryDir);
        $target = $this->normalizeImageName($name);

        $bestPath = null;
        $bestScore = 0;

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $normalized = $this->normalizeImageName(str_replace(['_', '-', '.'], ' ', $filename));

            if ($normalized === $target) {
                return $file;
            }

            similar_text($target, $normalized, $percent);
            if ($percent > $bestScore) {
                $bestScore = $percent;
                $bestPath = $file;
            }
        }

        if ($bestScore >= 75) {
            return $bestPath;
        }

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $normalized = $this->normalizeImageName(str_replace(['_', '-', '.'], ' ', $filename));
            if (str_contains($normalized, $target) || str_contains($target, $normalized)) {
                return $file;
            }
        }

        return null;
    }

    protected function copyImageToEncrypted(string $origin): ?string
    {
        if (!Storage::disk('public')->exists($origin)) {
            return null;
        }

        $content = Storage::disk('public')->get($origin);
        $extension = pathinfo($origin, PATHINFO_EXTENSION);
        $encryptedName = hash('sha256', $content . time() . $origin) . '.' . $extension;

        Storage::disk('public')->put('encrypted/' . $encryptedName, $content);

        return $encryptedName;
    }
}
