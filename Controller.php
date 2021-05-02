<?php

namespace Kareem\Dominoes;

use Kareem\Dominoes\Application;
use Kareem\Dominoes\Middlewares\BaseMiddleware;

class Controller
{
  /**
   * @var Kareem\Dominoes\Middlewares\BaseMiddleware[] $middlewares
   */
  protected array $middlewares = [];

  /**
   * @var string $action
   */
  public string $action = '';

  /**
   * Set default layout to be app layout
   * 
   * @var string $layout
   */
  public string $layout = 'app';

  /**
   * A function to minimize the call
   * 
   * @param string $view
   * @param array  $params
   * 
   * @return string
   */
  public function render(string $view, array $params = []): string
  {
    return Application::$app->view->renderView($view, $params);
  }

  /**
   * Set layout to override the app layout
   * 
   * @param string $layout
   * 
   * @return void
   */
  public function setLayout(string $layout): void
  {
    $this->layout = $layout;
  }

  /**
   * Set $middlewares
   * 
   * @param BaseMiddleware $baseMiddleware
   * 
   * @return void
   */
  public function registerMiddleware(BaseMiddleware $baseMiddleware): void
  {
    $this->middlewares[] = $baseMiddleware;
  }

  /**
   * Get $middlewares
   *
   * @return  Kareem\Dominoes\Middlewares\BaseMiddleware[]
   */
  public function getMiddlewares()
  {
    return $this->middlewares;
  }
}
