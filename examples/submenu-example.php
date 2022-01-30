#!/usr/bin/env php
<?php

use Atatusoft\CLIMenus\Menu;
use Atatusoft\CLIMenus\MenuItem;

$menu = new Menu(
  title: 'Main Menu',
  items: [
    new Menu(title: 'File', items: [
      new MenuItem(value: 'Save'),
      new MenuItem(value: 'Save As...'),
      new MenuItem(value: 'Save All'),
    ]),
    new Menu(title: 'Edit', items: [
      new MenuItem(value: 'Undo'),
      new MenuItem(value: 'Redo'),
    ]),
  ]
);