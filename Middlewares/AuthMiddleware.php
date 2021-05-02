<?php

namespace App\src\Middlewares;

use App\src\Application;
use App\src\Exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
  /**
   * 
   */
  public array $actions = [];

  public function __construct(array $actions = [])
  {
    $this->actions = $actions;
  }

  /**
   * @inheritDoc
   */
  public function execute()
  {
    if (Application::isGuest()) {
      if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
        throw new ForbiddenException();
      }
    }
  }
}
