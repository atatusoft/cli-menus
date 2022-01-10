#!/usr/bin/env php
<?php

namespace Atatusoft\CLIMenus\IO;

use Atatusoft\CLIMenus\Util\Color;
use Atatusoft\CLIMenus\Util\Console;
use Atatusoft\CLIMenus\Util\ConsoleCursor;
use Atatusoft\CLIMenus\Util\ConsoleEraser;
use Atatusoft\CLIMenus\Util\Mathf;
use Atatusoft\CLIMenus\Util\TermInfo;
use Iber\Phkey\Environment\Detector;
use Iber\Phkey\Events\KeyPressEvent;

class Input
{
  public static function prompt(string $message = 'Enter choice', ?string $defaultValue = null, ?int $attempts = null): string
  {
    $defaultHint = '';
    $line = '';
    if (!empty($defaultValue))
    {
      $defaultHint = Color::DARK_WHITE->value . "($defaultValue) " . Color::RESET->value;
    }

    $isValid = false;
    $attemptsLeft = $attempts;

    do
    {
      printf("%s?%s %s: %s%s", Color::GREEN->value, Color::RESET->value, $message, $defaultHint, Color::LIGHT_BLUE->value);
      $line = trim(fgets(STDIN));
      echo Color::RESET->value;

      if (is_null($attemptsLeft))
      {
        $isValid = true;
      }
      else
      {
        if(empty($line) && !empty($defaultValue))
        {
          $line = $defaultValue;
        }

        --$attemptsLeft;
        if (!empty($line))
        {
          $isValid = true;
        }
        else if ($attemptsLeft === 0)
        {
          exit(1);
        }
        else
        {
          printf("%sInvalid input: %d attempts left%s\n", Color::MAGENTA->value, $attemptsLeft, Color::RESET->value);
        }
      }
    }
    while(!$isValid);

    if (empty($line) && !is_null($defaultValue))
    {
      $line = $defaultValue;
    }

    return $line;
  }

  public static function promptPassword(string $message = 'Password', ?int $attempts = null): string
  {
    # Turn echo off
    `/bin/stty -echo`;

    $line = Input::prompt(message: $message, attempts: $attempts);

    # Turn echo no
    `/bin/stty echo`;
    echo "\n";

    return $line;
  }

  public static function confirm(string $message, bool $defaultYes = true): bool
  {
    $suffix = $defaultYes ? 'Y/n' : 'y/N';
    $response = $defaultYes ? true : false;
    $defaultHint = Color::DARK_WHITE->value . "($suffix) " . Color::RESET->value;

    $line = '';

    printf("%s?%s %s: %s%s", Color::GREEN->value, Color::RESET->value, $message, $defaultHint, Color::LIGHT_BLUE->value);
    $line = trim(fgets(STDIN));

    if (!empty($line))
    {
      $response = match(strtolower($line)) {
        'yes',
        'y',
        'yeah',
        'yep',
        'correct',
        'true',
        'affirmative' => true,
        default       => false
      };
    }

    if ($response === $defaultYes)
    {
      Console::cursor()::moveUpBy(numberOfLines: 1);
      Console::eraser()::entireLine();
      $suffix = $defaultYes ? 'Y' : 'N';
      $defaultHint = Color::LIGHT_BLUE->value . "$suffix " . Color::RESET->value;
      printf("\r%s?%s %s: %s%s\n", Color::GREEN->value, Color::RESET->value, $message, $defaultHint, Color::LIGHT_BLUE->value);
    }
    echo Color::RESET->value;

    return $response;
  }

  public static function promptSelect(
    array $options,
    ?string $message = null,
    int &$selectedIndex = 0,
    bool $multiSelect = false
  ): string|array
  {
    $GLOBALS['selectedOption'] = null;
    $GLOBALS['promptOptions'] = $options;
    $GLOBALS['totalOptions'] = count($options);
    $GLOBALS['selectedIndex'] = $selectedIndex;
    $GLOBALS['multiSelect'] = $multiSelect;
    $hint = $multiSelect
      ? sprintf(
          "(Press %s<space>%s to select, %s<a>%s to toggle all, %s<i>%s to invert selection)",
          Color::CYAN->value, Color::RESET->value,
          Color::CYAN->value, Color::RESET->value,
          Color::CYAN->value, Color::RESET->value,
        )
      : sprintf("%s(Use arrow keys)%s", Color::DARK_WHITE->value, Color::RESET->value);

    $checkedOptions = [];

    if ($multiSelect)
    {
      foreach ($options as $index => $option)
      {
        $checkedOptions[$index] = false; 
      }
    }

    $GLOBALS['checkedOptions'] = $checkedOptions;

    printf("\r%s?%s %s: %s%s\n", Color::GREEN->value, Color::RESET->value, $message, $hint, Color::RESET->value);

    Input::printOptions(options: $options, selectedIndex: $selectedIndex);

    ConsoleCursor::setVisibility(isVisible: false);
    $detector = new Detector();
    $listener = $detector->getListenerInstance();

    $eventDispatcher = $listener->getEventDispatcher();

    $eventDispatcher->addListener('key:press', function (KeyPressEvent $event) {
      global $selectedIndex, $totalOptions, $promptOptions, $multiSelect, $checkedOptions;

      switch ($event->getKey())
      {
        case 'up':
          --$selectedIndex;
          break;

        case 'down':
          ++$selectedIndex;
          break;

        case 'a':
          if ($multiSelect && $checkedOptions)
          {
            if (self::arrayValuesIdentical(input: $checkedOptions))
            {
              $checkedOptions = self::invertBooleanArray(input: $checkedOptions);
            }
            else
            {
              $toggleValue = in_array(true, $checkedOptions);
              foreach ($checkedOptions as $index => $option)
              {
                $checkedOptions[$index] = $toggleValue;
              }
            }
          }
          break;

        case 'i':
          if ($multiSelect && $checkedOptions)
          {
            $checkedOptions = self::invertBooleanArray(input: $checkedOptions);
          }
          break;
        
        case 'space':
          if ($multiSelect && $checkedOptions)
          {
            if (isset($checkedOptions[$selectedIndex]))
            {
              $checkedOptions[$selectedIndex] = !$checkedOptions[$selectedIndex];
            }
          }
          break;
      }

      $selectedIndex = Mathf::wrap($selectedIndex, 0, $totalOptions);
      Input::printOptions(options: $promptOptions, selectedIndex: $selectedIndex);
    });

    $eventDispatcher->addListener('key:enter', function (KeyPressEvent $event) use ($eventDispatcher) {
      global $selectedOption, $promptOptions, $selectedIndex;
      $selectedOption = $promptOptions[$selectedIndex];

      Input::clearOptions(options: $promptOptions);
      ConsoleCursor::setVisibility(isVisible: true);

      $eventDispatcher->dispatch('key:stop:listening');
    });

    $listener->start();

    $selectedIndex = $GLOBALS['selectedIndex'];
    $selectedOption = $multiSelect
      ? ''
      : $options[$selectedIndex];

    ConsoleCursor::moveUp();
    ConsoleEraser::entireLine();
    printf("\r%s?%s %s: %s%s%s\n", Color::GREEN->value, Color::RESET->value, $message, Color::LIGHT_BLUE->value, $selectedOption, Color::RESET->value);

    $selectedOptions = [];
    if ($multiSelect)
    {
      $checkedOptions = $GLOBALS['checkedOptions'];

      foreach ($checkedOptions as $index => $value)
      {
        if ($value)
        {
          $selectedOptions[] = $options[$index];
        }
      }
    }

    return match (true) {
      $multiSelect => $selectedOptions,
      default => $selectedOption
    };
  }

  public static function printOptions(array $options, int $selectedIndex = 0): void
  {
    global $multiSelect, $checkedOptions;
    $totalOptions = count($options);

    if (is_null($selectedIndex))
    {
      $selectedIndex = 0;
    }

    foreach ($options as $index => $option)
    {
      Console::eraser()->entireLine();
      $prefix = ' ';

      if ($multiSelect)
      {
        $prefix = $checkedOptions[$index]
          ? sprintf("%s%s%s", Color::LIGHT_GREEN->value, '◉ ', Color::RESET->value)
          : sprintf("%s%s", Color::RESET->value, '◯ ');
      }

      $color = ($index === $selectedIndex)
        ? sprintf("%s❯%s%s ", Color::LIGHT_BLUE->value, $prefix, Color::LIGHT_BLUE->value)
        : sprintf("%s %s ", Color::RESET->value, $prefix);
      printf("%s%s%s\n", $color, $option, Color::RESET->value);
    }

    Console::cursor()->moveUpBy(numberOfLines: $totalOptions);
  }

  public static function clearOptions(array $options)
  {
    $totalOptions = count($options);
    $terminalWidth = TermInfo::windowSize()->width();

    Console::cursor()->moveDownBy(numberOfLines: $totalOptions);
    Console::eraser()->entireLine();

    for ($x = 0; $x < $totalOptions; $x++)
    {
      Console::cursor()->moveUp();
      Console::eraser()->entireLine();
    }
  }

  public static function printBlankSpaces(int $numberOfSpaces = 1): void
  {
    for ($x = 0; $x < $numberOfSpaces; $x++)
    {
      echo ' ';
    }
  }

  private static function invertBooleanArray(array $input): array
  {
    $output = $input;

    foreach ($output as $index => $entry)
    {
      if (is_bool($entry))
      {
        $output[$index] = !$entry;
      }
    }

    return $output;
  }

  private static function arrayValuesIdentical(array $input): bool
  {
    $previous = 0;
    $total = count($input);
    for ($current = 1; $current < $total; $current++)
    {
      if ($input[$previous] !== $input[$current])
      {
        return false;
      }
      $previous = $current;
    }

    return true;
  }
}