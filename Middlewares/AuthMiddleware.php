<?php

namespace Kareem\Dominoes\Middlewares;

use Kareem\Dominoes\Application;
use Kareem\Dominoes\Exceptions\ForbiddenException;

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
