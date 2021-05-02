<?php

namespace Kareem\Dominoes\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
  protected $message = 'You don\'t have permission to acces this page';
  protected $code = 403;
}