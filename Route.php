<?php

namespace App\src;

use App\src\Exceptions\NotFoundException;

class Route
{
  /**
   * @var $request
   */
  public Request $request;

  /**
   * @var $response
   */
  public Response $response;

  /**
   * @var array $routes
   */
  protected array $routes = [];

  /**
   * 
   */
  public function __construct(Request $request, Response $response)
  {
    $this->request = $request;
    $this->response = $response;
  }

  /**
   * Set callback to get method in the routes array
   * 
   * @param string $path
   * @param mixed $callback
   */
  public function get(string $path, mixed $callback): void
  {
    $this->routes['get'][$path] = $callback;
  }

  /**
   * Set callback to post method in the routes array
   * 
   * @param string $path
   * @param mixed $callback
   */
  public function post(string $path, mixed $callback): void
  {
    $this->routes['post'][$path] = $callback;
  }

  /**
   * Resolve route callback
   * 
   * @return mixed
   */
  public function resovle(): mixed
  {
    $path = $this->request->path();
    $method = $this->request->method();
    $callback = $this->routes[$method][$path] ?? false;

    if (!$callback) {
      throw new NotFoundException();
    }
    // execute the callback
    if (is_string($callback)) {
      return Application::$app->view->renderView($callback);
    }
    if (is_array($callback)) {
      $controller = new $callback[0];
      Application::$app->controller = $controller;
      $controller->action = $callback[1];
      $callback[0] = $controller;
      foreach ($controller->getMiddlewares() as $middleware) {
        $middleware->execute();
      }
    }
    /**
     * call_user_func is for calling functions whose name you don't know 
     * ahead of time but it is much less efficient 
     * since the program has to lookup the function at runtime.
     */
    return call_user_func($callback, $this->request, $this->response);
  }
}
