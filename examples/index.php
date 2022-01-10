#!/usr/bin/env php
<?php

use Atatusoft\CLIMenus\Menu;
use Atatusoft\CLIMenus\MenuItem;

$rootPath = dirname(__FILE__, 2);

require_once "$rootPath/vendor/autoload.php";
require_once "./autoload.php";

enum MenuTitle: string
{
  case BASIC = 'Basic';
  case ARROW_KEY = 'Keys Navigation';
  case MULTI_SELECT = 'Multiple Selections';
  case WITH_DESCRIPTIONS = 'With Descriptions';

  function tag(): ?string
  {
    return match ($this) {
      self::BASIC => 'basic-example',
      self::ARROW_KEY => 'arrow-keys-example',
      self::MULTI_SELECT => 'multiselect-example',
      self::WITH_DESCRIPTIONS => 'with-descriptions-example',
      default => null
    };
  }
}

$examplesMenu = new Menu(
  title: "Examples menu\n",
  items: [
    new MenuItem(MenuTitle::BASIC->value),
    new MenuItem(MenuTitle::ARROW_KEY->value),
    new MenuItem(MenuTitle::MULTI_SELECT->value),
    new MenuItem(MenuTitle::WITH_DESCRIPTIONS->value),
    new MenuItem('Quit'),
  ]
);

$choice = $examplesMenu->prompt(message: "Which exmple do you want to run");

$filename = match ($choice->value()) {
  MenuTitle::BASIC->value => MenuTitle::BASIC->tag(),
  MenuTitle::ARROW_KEY->value => MenuTitle::ARROW_KEY->tag(),
  MenuTitle::MULTI_SELECT->value => MenuTitle::MULTI_SELECT->tag(),
  MenuTitle::WITH_DESCRIPTIONS->value => MenuTitle::WITH_DESCRIPTIONS->tag(),
  default => null,
};

if ($filename)
{
  require_once __DIR__ . "/${filename}.php";
}