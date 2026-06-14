<?php

namespace App\Utility;

class Slugify {
  public static function slugify(string $text): string {
    // Replace non-letter or non-digit characters by a single hyphen
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // Transliterate to ASCII (requires php-intl extension for best results)
    $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);

    // Remove unwanted characters (anything that isn't a letter, number, or hyphen)
    $text = preg_replace('~[^-\w]+~', '', $text);

    // Trim leading and trailing hyphens
    $text = trim($text, '-');

    // Remove duplicate hyphens
    $text = preg_replace('~-+~', '-', $text);

    return empty($text) ? 'n-a' : strtolower($text);
  }

}