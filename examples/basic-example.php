#!/usr/bin/env php
<?php

use Atatusoft\CLIMenus\Menu;
use Atatusoft\CLIMenus\MenuItem;
use Atatusoft\CLIMenus\Util\Color;

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

$choice = $menu->prompt(message: 'Choose wisely');

presentChoice(choice: $choice->value());