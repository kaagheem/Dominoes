<?php

namespace App\src;

class View
{
  /**
   * @var string $title
   */
  public string $title = '';

  /**
   * Placeholder the content with layout and view
   * 
   * @param string $view
   * @param array $params
   * 
   * @return string
   */
  public function renderView(string $view, array $params = []): string
  {
    $viewContent = $this->renderOnlyView($view, $params);
    $layoutContent = $this->layoutContent();
    return str_replace('{{content}}', $viewContent, $layoutContent);
  }

  /**
   * Get and render the layout content
   * 
   * @param string $layout
   * 
   * @return string
   */
  protected function layoutContent(): string
  {
    $layout = Application::$app->layout;
    if (Application::$app->controller) {
      $layout = Application::$app->controller->layout;
    }

    // start output caching
    /**
     * Think of ob_start() as saying
     * "Start remembering everything that would normally be outputted,
     *  but don't quite do anything with it yet."
     */
    ob_start();
    include_once Application::$ROOT_DIR . "./views/layouts/$layout.php";
    /**
     * ob_get_clean(), which basically gives you whatever has been "saved"
     * to the buffer since it was turned on with ob_start()
     */
    return ob_get_clean();
  }

  /**
   * Get and render the view content
   * 
   * @param string $view
   * @param array $params
   * 
   * @return string
   */
  protected function renderOnlyView(string $view, array $params = []): string
  {
    // Params will be pass to the view automatic by the include or require
    foreach ($params as $key => $value) {
      // dynamic variable
      $$key = $value;
    }

    ob_start();
    include_once Application::$ROOT_DIR . "./views/$view.php";
    return ob_get_clean();
  }
}
