<?php

namespace App\Controllers;

use Kareem\Dominoes\Controller;
use Kareem\Dominoes\Middlewares\AuthMiddleware;

class SiteController extends Controller
{
  public function __construct()
  {
    $this->registerMiddleware(new AuthMiddleware(['dashboard', 'profile']));
  }

  /**
   * Welcome view
   * 
   * @return string
   */
  public function welcome()
  {
    $this->setLayout('guest');
    return $this->render('welcome');
  }

  /**
   * Dashboard view
   * 
   * @return string
   */
  public function dashboard()
  {
    return $this->render('dashboard');
  }

  /**
   * Render profile view
   * 
   * @return string
   */
  public function profile(): string
  {
    return $this->render('profile');
  }
}
