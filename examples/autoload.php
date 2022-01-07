#!/usr/bin/env php
<?php

spl_autoload_register(function ($class) {
  $filename = str_replace('Atatusoft\\CLIMenus\\', 'src/', dirname(__FILE__, 2) . "/$class.php");
  $filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);

  require_once $filename;
});