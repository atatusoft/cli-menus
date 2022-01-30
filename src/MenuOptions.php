#!/usr/bin/php
<?php

namespace Atatusoft\CLIMenus;

use Atatusoft\CLIMenus\Util\Color;

class MenuOptions
{
  public function __construct(
    private ?bool $showDescriptions = null,
    private bool $showIndexes = true,
    private Color $titleColor = Color::YELLOW,
    private Color $selectedColor = Color::LIGHT_BLUE,
    public readonly string $cursor = "❯"
  ) { }

  public function showDescriptions(): ?bool { return $this->showDescriptions; }

  public function showIndexes(): bool { return $this->showIndexes; }

  public function titleColor(): string { return $this->titleColor->value; }

  public function setShowDescriptions(bool $showDescriptions): void { $this->showDescriptions = $showDescriptions; }

  public function setShowIndexes(bool $showIndexes): void { $this->showIndexes = $showIndexes; }

  public function setTitleColor(string $titleColor): void { $this->titleColor = $titleColor; }
}