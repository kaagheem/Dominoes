<?php

namespace Kareem\Dominoes\Database;

use Kareem\Dominoes\Application;
use PDO;


class Database
{
  /**
   * @var PDO $pdo
   * 
   * Consider PDO as a built in class that comes packaged with PHP
   * to make it very easier for you to interact with your database.
   */
  public PDO $pdo;

  public function __construct(array $config)
  {
    $dsn = $config['dsn'];
    $user = $config['user'];
    $password = $config['password'];

    $this->pdo = new PDO($dsn, $user, $password);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
   * 
   */
  public function applyMigrations()
  {
    $this->createMigrationsTable();
    $appliedMigrations = $this->getAppliedMigrations();

    $newMigrations = [];

    $files = scandir(Application::$ROOT_DIR . '/migrations');

    $toApplyMigrations = array_diff($files, $appliedMigrations);

    foreach ($toApplyMigrations as $migration) {
      if ($migration === '.' || $migration === '..') {
        continue;
      }

      require_once Application::$ROOT_DIR . '/migrations/' . $migration;

      // pathinfo() returns the filename without extension
      $className = pathinfo($migration, PATHINFO_FILENAME);
      // create new instance of this filename cuz its same as class name
      $instance = new $className();
      $this->log('applying migration ' . $migration);
      $instance->up();
      $this->log('applied migration ' . $migration);
      $newMigrations[] = $migration;
    }

    if (!empty($newMigrations)) {
      $this->saveMigration($newMigrations);
    } else {
      $this->log('All Migrations are Applied');
    }
  }

  /**
   * This method accept a bunch of file names and iterate over,
   * Save the file name into migrations table
   * 
   * @param array $newMigrations
   * 
   * @return void
   */
  public function saveMigration(array $newMigrations): void
  {
    $migrations = implode(',', array_map(fn ($m) => "('$m')", $newMigrations));

    $stmt = $this->pdo->prepare("
      INSERT INTO migrations (migration) VALUES $migrations
    ");

    $stmt->execute();
  }

  /**
   * Create migrations table
   * 
   * @return void
   */
  public function createMigrationsTable(): void
  {
    $this->pdo->exec("
      CREATE TABLE IF NOT EXISTS migrations (
        id INT unsigned not null AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=INNODB;
    ");
  }

  /**
   * Execute a prepared statement
   * 
   * @return array
   */
  public function getAppliedMigrations(): array
  {
    $stmt = $this->pdo->prepare("
      SELECT migration FROM migrations;
    ");

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  /**
   * Print current date and time and the message
   * 
   * @param string $msg
   * 
   * @return void
   */
  public function log(string $msg): void
  {
    echo '[' . date('Y-m-d H:i:s') . '] - ' . $msg . PHP_EOL;
  }
}
