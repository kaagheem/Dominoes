<?php

namespace Kareem\Dominoes;

use Kareem\Dominoes\Database\DbModel;

abstract class UserModel extends DbModel
{
  /**
   * Get user full name
   * 
   * @return string
   */
  abstract public function getDisplayName(): string;
}