<?php

namespace Kareem\Dominoes\Database;

use Kareem\Dominoes\Application;
use Kareem\Dominoes\Model;
use PDOStatement;

abstract class DbModel extends Model
{
  /**
   * Database table name
   * 
   * @return string
   */
  abstract public static function tableName(): string;

  /**
   * Database table columns names
   * 
   * @return array
   */
  abstract public function attributes(): array;

  /**
   * Detremine the primary key
   * 
   * @return string
   */
  abstract public static function primaryKey(): string;

  /**
   * Database add a new row
   * 
   * @return bool
   */
  public function save(): bool
  {
    $tableName = $this->tableName();
    $attributes = $this->attributes();

    $params = array_map(fn ($p) => ":$p", $attributes);

    $sql = "
      INSERT INTO $tableName (
        " . implode(",", $attributes) . "
        ) VALUES (
        " . implode(",", $params) . "
        );
    ";

    $stmt = $this->prepare($sql);

    foreach ($attributes as $attr) {
      $stmt->bindValue(":$attr", $this->{$attr});
    }
    $stmt->execute();
    return true;
  }

  /**
   * Call prepare method from php native PDO class
   * 
   * @param string $sql
   * 
   * @return PDOStatement|false
   */
  public static function prepare(string $sql): PDOStatement|false
  {
    return Application::$app->db->pdo->prepare($sql);
  }
}
