#!/usr/bin/env php
<?php

use Atatusoft\CLIMenus\Menu;
use Atatusoft\CLIMenus\MenuItem;

$menu = new Menu(
  title: '',
  items: [
    new MenuItem(value: 'Option 1'),
    new MenuItem(value: 'Option 2'),
    new MenuItem(value: 'Option 3'),
    new MenuItem(value: 'Option 4'),
  ]
);

$choice = $menu->prompt();

presentChoice(choice: $choice);