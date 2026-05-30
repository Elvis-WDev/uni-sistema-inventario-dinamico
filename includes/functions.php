<?php

declare(strict_types=1);

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function text_length(mixed $value): int
{
    $text = (string) $value;

    if (function_exists('mb_strlen')) {
        return mb_strlen($text, 'UTF-8');
    }

    return strlen($text);
}

function is_active(string $file): string
{
    $current = basename($_SERVER['SCRIPT_NAME'] ?? '');

    return $current === $file ? ' aria-current="page" class="active"' : '';
}
