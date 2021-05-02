<?php

namespace Kareem\Dominoes;

use Kareem\Dominoes\Database\Database;
use Exception;

class Application
{
  const EVENT_BEFORE_REQUEST = 'beforeRequest';
  const EVENT_AFTER_REQUEST = 'afterRequest';

  /**
   * @var array $events
   */
  public array $events = [];

  /**
   * Root path
   * 
   * @var string $ROOT_DIR
   */
  public static string $ROOT_DIR;

  /**
   * @var Application $app
   */
  public static Application $app;

  /**
   * @var string $layout
   */
  public string $layout = 'guest';

  /**
   * @var string $userClass
   */
  public string $userClass;

  /**
   * @var $route
   */
  public Route $route;

  /**
   * @var $request
   */
  public Request $request;

  /**
   * @var $response
   */
  public Response $response;

  /**
   * @var $session
   */
  public Session $session;

  /**
   * @var $view
   */
  public View $view;

  /**
   * @var $db
   */
  public Database $db;

  /**
   * @var $controller
   */
  public ?Controller $controller = null;

  /**
   * @var $user
   */
  public ?UserModel $user = null;

  /**
   * 
   */
  public function __construct(string $rootPath, array $config)
  {
    self::$ROOT_DIR = $rootPath;
    self::$app = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->session = new Session();
    $this->route = new Route($this->request, $this->response);
    $this->view = new View();
    $this->db = new Database($config['db']);
    $this->userClass = $config['user'];

    $primaryVal = $this->session->get('user');
    if ($primaryVal) {
      $primaryKey = $this->userClass::primaryKey();
      $this->user = $this->userClass::findOne([$primaryKey => $primaryVal]);
    }
  }

  /**
   * Output
   * 
   * @return void
   */
  public function run(): void
  {
    $this->triggerEvents(self::EVENT_BEFORE_REQUEST);

    try {
      echo $this->route->resovle();
    } catch (Exception $ex) {
      $this->response->setStatusCode(403);
      echo $this->view->renderView('error', [
        'exception' => $ex
      ]);
    }
  }

  /**
   * Set the value of controller
   *
   * @param  $controller
   *
   * @return  self
   */
  public function setController(Controller $controller): void
  {
    $this->controller = $controller;
  }

  /**
   * Login User
   * 
   * @param UserModel $user
   * 
   * @return bool
   */
  public function login(UserModel $user): bool
  {
    $this->user = $user;
    $primaryKey = $user->primaryKey();
    $primaryVal = $user->{$primaryKey};
    $this->session->set('user', $primaryVal);
    return true;
  }

  /**
   * Logout the authenticated user
   * 
   * @return void
   */
  public function logout(): void
  {
    $this->user = null;
    $this->session->remove('user');
  }

  /**
   * Return true if there is no user auth
   * 
   * @return bool
   */
  public static function isGuest(): bool
  {
    return !self::$app->user;
  }

  /**
   * Assign given callback and event
   * 
   * @param string $event
   * @param mixed $callback
   * 
   * @return void
   */
  public function on(string $event, mixed $callback): void
  {
    $this->events[$event][] = $callback;
  }

  /**
   * Fire the callbacks for the given event
   * 
   * @param string $event
   * 
   * @return void
   */
  public function triggerEvents(string $event): void
  {
    $callbacks = $this->events[$event] ?? [];

    foreach ($callbacks as $value) {
      call_user_func($value);
    }
  }
}
