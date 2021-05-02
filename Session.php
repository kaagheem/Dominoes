<?php

namespace Kareem\Dominoes;

class Session
{
  protected const FLASH_KEY = 'flash_messages';

  public function __construct()
  {
    session_start();

    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
    foreach ($flashMessages as $key => &$value) {
      $value['removed'] = true;
    }
    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }

  /**
   * @param string $key
   * @param string $msg
   * 
   * @return void
   */
  public function setFlash(string $key, string $msg): void
  {
    $_SESSION[self::FLASH_KEY][$key] = [
      'removed' => false,
      'value' => $msg
    ];
  }

  /**
   * Get flash message
   * 
   * @param string $key
   * 
   */
  public function getFlash(string $key)
  {
    return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
  }

  public function __destruct()
  {
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
    foreach ($flashMessages as $key => &$value) {
      if ($value['removed']) {
        unset($flashMessages[$key]);
      }
    }
    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }

  /**
   * Set the value for the given key
   * 
   * @param string $key
   * @param string $value
   * 
   * @return void
   */
  public function set(string $key, string $value): void
  {
    $_SESSION[$key] = $value;
  }

  /**
   * Get the value from the given key
   * 
   * @param string $key
   * 
   * @return string|false
   */
  public function get(string $key): string|false
  {
    return $_SESSION[$key] ?? false;
  }

  /**
   * Remove the the given key
   * 
   * @param string $key
   * 
   * @return void
   */
  public function remove(string $key): void
  {
    unset($_SESSION[$key]);
  }
}
