<?php

namespace App\src;

class Request
{
  /**
   * Get the path where user tries to reach
   * 
   * @return string $path
   */
  public function path(): string
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $pos = strpos($path, '?');

    if (!$pos) {
      return $path;
    }

    return substr($path, 0, $pos);
  }

  /**
   * Get the method where user tries to make
   * 
   * @return string
   */
  public function method(): string
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  /**
   * Returns true if the method is get
   * 
   * @return bool
   */
  public function isGet(): bool
  {
    return $this->method() === 'get';
  }

  /**
   * Returns true if the method is post
   * 
   * @return bool
   */
  public function isPost(): bool
  {
    return $this->method() === 'post';
  }

  /**
   * Sanitize input data and put it into array
   * 
   * @return array
   */
  public function body(): array
  {
    $body = [];
    
    // if ($this->method() === 'get') {
    //   foreach ($_GET as $key => $value) {
    //     $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
    //   }
    // }

    if ($this->isPost()) {
      foreach ($_POST as $key => $value) {
        $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
      }
    }

    return $body;
  }
}
