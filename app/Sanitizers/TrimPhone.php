<?php

namespace App\Sanitizers;

class TrimPhone
{
  public static function handle(?string $value): ?string
  {
    if ($value === null)
      return null;

    $phoneStart = '7';

    $leaveDigits = preg_replace('/\D+/', '', $value);
    $phoneBody = substr($leaveDigits, 1);

    return $phoneStart . $phoneBody;
  }
}