#!/usr/bin/env php
<?php

namespace Atatusoft\Menus\Util;

final class Mathf
{
  public static function clamp(int|float $value, int|float $min, int|float $max): int|float
  {
    if ($value < $min)
    {
      return $min;
    }

    if ($value > $max)
    {
      return $max;
    }

    return $value;
  }

  public static function wrap(int $value, int $min, int $max): int
  {
    if ($value < $min)
    {
      return $max - 1;
    }

    if ($value >= $max)
    {
      return $min;
    }

    return $value;
  }
}