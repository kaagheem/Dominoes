<?php

namespace App\src;

abstract class Model
{
  public const RULE_REQUIRED = 'required';
  public const RULE_EMAIL = 'email';
  public const RULE_UNIQUE = 'unique';
  public const RULE_MIN = 'min';
  public const RULE_MAX = 'max';
  public const RULE_MATCH = 'match';

  /**
   * @var array $errors
   */
  public array $errors = [];

  /**
   * Extends classes will override this method,
   * to print user friendly labels
   * 
   * @return array
   */
  public function labels(): array
  {
    return [];
  }

  /**
   * @param string $attr
   * 
   * @return string
   */
  public function getLabel(string $attr): string
  {
    return $this->labels()[$attr] ?? $attr;
  }

  /**
   * A load data function from form inputs
   * 
   * @return void
   */
  public function load(array $data): void
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }

  /**
   * Return true if the validate pass
   * 
   * @return bool
   */
  public function validate(): bool
  {
    foreach ($this->rules() as $attr => $rules) {
      $value = $this->{$attr};
      foreach ($rules as $rule) {
        $ruleName = $rule;
        if (is_array($ruleName)) {
          $ruleName = $rule[0];
        }
        if ($ruleName === self::RULE_REQUIRED && !$value) {
          $this->addErrorForRule($attr, self::RULE_REQUIRED);
        }
        if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $this->addErrorForRule($attr, self::RULE_EMAIL);
        }
        if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
          $this->addErrorForRule($attr, self::RULE_MIN, $rule);
        }
        if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
          $this->addErrorForRule($attr, self::RULE_MAX, $rule);
        }
        if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
          $rule['match'] = $this->getLabel($rule['match']);
          $this->addErrorForRule($attr, self::RULE_MATCH, $rule);
        }
        if ($ruleName === self::RULE_UNIQUE) {
          $className = $rule['class'];
          $uniqueAttribute = $rule['attribute'] ?? $attr;
          $tableName = $className::tableName();
          $stmt = Application::$app->db->pdo->prepare("
            SELECT * FROM $tableName WHERE $uniqueAttribute = :$attr;
          ");
          $stmt->bindValue(":$attr", $value);
          $stmt->execute();
          $record = $stmt->fetchObject();
          if ($record) {
            $this->addErrorForRule($uniqueAttribute, self::RULE_UNIQUE, [
              'field' => $this->getLabel($attr)
            ]);
          }
        }
      }
    }

    return empty($this->errors);
  }

  /**
   * Set error messages for rules only
   * 
   * @param string $attr
   * @param string $rule
   */
  private function addErrorForRule(string $attr, string $rule, array $params = [])
  {
    $message = $this->errorMessages()[$rule] ?? '';

    foreach ($params as $key => $value) {
      $message = str_replace("{{$key}}", $value, $message);
    }

    $this->addError($attr, $message);
  }

  /**
   * Set error messages
   * 
   * @param string $attr
   * @param string $rule
   */
  public function addError(string $attr, string $msg)
  {
    $this->errors[$attr][] = $msg;
  }

  /**
   * Define Rules Messages
   * 
   * @return array
   */
  public function errorMessages(): array
  {
    return [
      self::RULE_REQUIRED => 'This field is required',
      self::RULE_EMAIL => 'This field must be valid email address',
      self::RULE_UNIQUE => 'Record with this {field} already exists',
      self::RULE_MIN => 'Min length of this field must be {min}',
      self::RULE_MAX => 'Max length of this field must be {max}',
      self::RULE_MATCH => 'This field must be the same as {match}',
    ];
  }

  /**
   * If any errors accord to this giving $attr returns true
   * 
   * @return bool
   */
  public function hasError(string $attr): bool
  {
    return !empty($this->errors[$attr]);
  }

  /**
   * Return the invalid message or false
   * 
   * @return string|false
   */
  public function getFirstError(string $attr): string|false
  {
    return $this->errors[$attr][0] ?? false;
  }


  /**
   * A function to be implements in child class, and set its own rules
   * 
   * @return array
   */
  abstract public function rules(): array;
}
