<?php
declare(strict_types=1);

use App\Core\Session;

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(Session::csrfToken()) . '">';
}

function old(array $old, string $key, string $default = ''): string
{
    return e((string) ($old[$key] ?? $default));
}

function format_date(?string $date): string
{
    return $date ? date('d M Y', strtotime($date)) : '-';
}
