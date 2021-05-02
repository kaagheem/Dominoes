<?php

namespace Kareem\Dominoes\Exceptions;

use Exception;

class NotFoundException extends Exception
{
  protected $code = 404;
  protected $message = 'Page not found!';
}