#!/usr/bin/env php
<?php

use Atatusoft\CLIMenus\Menu;
use Atatusoft\CLIMenus\MenuItem;

$menu = new Menu(
  items: [
    new MenuItem(value: 'Option 1', description: 'This describes option 1'),
    new MenuItem(value: 'Option 2', description: 'This describes option 2'),
    new MenuItem(value: 'Option 3', description: 'This describes option 3'),
    new MenuItem(value: 'Option 4', description: 'This describes option 4'),
  ]
);

$choice = $menu->prompt(multiSelect: true);

presentChoice(choice: $choice);