<?php

namespace Kareem\Dominoes\Form;

use Kareem\Dominoes\Model;

class InputField extends BaseField
{
  /**
   * 
   */
  public function __construct(Model $model, string $attr)
  {
    $this->type = self::TYPE_TEXT;
    parent::__construct($model, $attr);
  }

  /**
   * @inheritDoc
   */
  public function renderInput(): string
  {
    return sprintf(
      '<input type="%s" name="%s" value="%s" class=" %s ">',
      $this->type, // type
      $this->attr, // name
      $this->model->{$this->attr}, // value
      $this->model->hasError($this->attr) ? 'is-invalid' : '', // class
    );
  }

  /**
   * Set input field type to be password,
   * Chain method approach
   * 
   * @return self
   */
  public function passwordField(): self
  {
    $this->type = self::TYPE_PASSWORD;
    return $this;
  }

  /**
   * Set input field type to be email,
   * Chain method approach
   * 
   * @return self
   */
  public function emailField(): self
  {
    $this->type = self::TYPE_EMAIL;
    return $this;
  }
}
