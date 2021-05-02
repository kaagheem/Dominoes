<?php

namespace App\src;

use App\src\Database\DbModel;

abstract class UserModel extends DbModel
{
  /**
   * Get user full name
   * 
   * @return string
   */
  abstract public function getDisplayName(): string;
}