<?php

namespace App\Sanitizers;

use App\Sanitizers\TrimPhone;
use App\Enums\Enum;

class AppealSanitizer
{
  public static function sanitize(array $values): array
  {
    if (isset($values['phone'])) {
      $values['phone'] = TrimPhone::handle($values['phone']);
    }

    if (isset($values['gender'])) {
      $values['gender'] = Enum::Gender[$values['gender']];
    }

    return $values;
  }
}