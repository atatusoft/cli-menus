#!/usr/bin/php
<?php

namespace Atatusoft\CLIMenus;

use Atatusoft\CLIMenus\Util\Color;

class MenuItem
{
  public function __construct(
    private string $value,
    private string $description = '',
    private ?string $index = null,
    private Color $indexColor = Color::BLUE,
    private ?string $alias = null,
    private ?string $fullDescription = null,
    private ?MenuOptions $options = null
  ) {
    if (is_null($this->options))
    {
      $this->options = new MenuOptions();
    }
  }

  public function value(): string { return $this->value; }

  public function description(): string { return $this->description; }

  public function index(): ?string { return $this->index; }

  public function setIndex(string $index): void { $this->index = $index; }

  public function alias(): ?string { return $this->alias; }

  public function fullDescription(): ?string { return $this->fullDescription; }

  public function options(): ?MenuOptions { return $this->options; }

  public function __toString(): string
  {
    $output = '';

    if ($this->options()->showIndexes())
    {
      $output .= $this->indexColor->value . $this->index . "\e[0m) ";
    }

    $alias = is_null($this->alias()) ? '' : ' (' . $this->alias() . ')';

    return sprintf("%-2s%s%s", $output, $this->value, $alias);
  }

  public function display(?bool $withDescriptions = null): string
  {
    if (!is_null($this->options()->showDescriptions()))
    {
      $withDescriptions = $this->options()->showDescriptions();
    }

    $output =
      $withDescriptions
      ? sprintf("%s%-18s \t%s%s", Color::LIGHT_BLUE->value, strval($this), $this->description(), Color::RESET->value)
      : sprintf("%s%s%s", Color::LIGHT_BLUE->value, strval($this), Color::RESET->value);

    return $output;
  }

  public function print_display(?bool $withDescriptions = null): void
  {
    echo $this->display(withDescriptions: $withDescriptions);
  }

  public function getHelp(): string
  {
    $help = 'Not Implemented';
    return $help;
  }

  public function help(): void
  {
    echo $this->getHelp();
  }
}
