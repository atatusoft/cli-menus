#!/usr/bin/env php
<?php

require_once './vendor/autoload.php';
require_once 'autoload.php';

use Atatusoft\Menus\Menu;
use Atatusoft\Menus\MenuItem;
use Atatusoft\Menus\Util\Color;

$menu = new Menu(
  title: "The Matrix\n",
  items: [
    new MenuItem(value: 'Red Pill'),
    new MenuItem(value: 'Blue Pill'),
    new MenuItem(value: 'Green Pill'),
    new MenuItem(value: 'Purple Pill'),
    new MenuItem(value: 'Quit', index: 'x', indexColor: Color::RED),
  ]
);

$result = $menu->prompt(message: 'Choose wisely');

// $result = $menu->helpTip();

var_export($result->value());

echo "\n";