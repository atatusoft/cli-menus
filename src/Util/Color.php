#!/usr/bin/php
<?php

namespace Atatusoft\CLIMenus\Util;

enum Color: string
{
  case BLACK         = "\e[0;30m";
  case RED           = "\e[0;31m";
  case GREEN         = "\e[0;32m";
  case YELLOW        = "\e[0;33m";
  case BLUE          = "\e[0;34m";
  case MAGENTA       = "\e[0;35m";
  case CYAN          = "\e[0;36m";
  case WHITE         = "\e[0;37m";
  case RESET         = "\e[0;0m";
  case LIGHT_BLACK   = "\e[1;30m";
  case LIGHT_RED     = "\e[1;31m";
  case LIGHT_GREEN   = "\e[1;32m";
  case LIGHT_YELLOW  = "\e[1;33m";
  case LIGHT_BLUE    = "\e[1;34m";
  case LIGHT_MAGENTA = "\e[1;35m";
  case LIGHT_CYAN    = "\e[1;36m";
  case LIGHT_WHITE   = "\e[1;37m";
  case LIGHT_RESET   = "\e[1;0m";
  case DARK_BLACK    = "\e[2;30m";
  case DARK_RED      = "\e[2;31m";
  case DARK_GREEN    = "\e[2;32m";
  case DARK_YELLOW   = "\e[2;33m";
  case DARK_BLUE     = "\e[2;34m";
  case DARK_MAGENTA  = "\e[2;35m";
  case DARK_CYAN     = "\e[2;36m";
  case DARK_WHITE    = "\e[2;37m";
  case DARK_RESET    = "\e[2;0m";
}