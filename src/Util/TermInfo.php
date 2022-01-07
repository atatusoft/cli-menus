#!/usr/bin/env php
<?php

namespace Atatusoft\Menus\Util;

final class TermInfo
{
  public static function windowSize(): Rect
  {
    return new Rect(width: exec('tput cols'), height: exec('tput lines'));
  }
}