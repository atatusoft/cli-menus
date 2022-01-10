#!/usr/bin/env php
<?php

use Atatusoft\CLIMenus\Menu;
use Atatusoft\CLIMenus\MenuItem;
use Atatusoft\CLIMenus\MenuOptions;
use Atatusoft\CLIMenus\Util\Color;

$menu = new Menu(
  title: 'With Descriptions',
  items: [
    new MenuItem(value: 'Item 1', description: 'This describes option 1'),
    new MenuItem(value: 'Item 2', description: 'This describes option 2'),
    new MenuItem(value: 'Item 3', description: 'This describes option 3'),
    new MenuItem(value: 'Item 4', description: 'This describes option 4'),
    new MenuItem(value: 'Quit', description: 'Terminates the program', indexColor: Color::RED, index: 'x'),
  ],
  options: new MenuOptions(showDescriptions: true)
);

$choice = $menu->prompt(useArrowKeys: false);

presentChoice(choice: $choice);