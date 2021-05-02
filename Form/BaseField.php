<?php

namespace Kareem\Dominoes\Form;

use Kareem\Dominoes\Model;

abstract class BaseField
{
  public const TYPE_TEXT = 'text';
  public const TYPE_EMAIL = 'email';
  public const TYPE_PASSWORD = 'password';
  public const TYPE_NUMBER = 'number';

  /**
   * @var Model $model
   */
  public Model $model;

  /**
   * @var string $attr
   */
  public string $attr;

  /**
   * @var string $type
   */
  public string $type;

  /**
   * 
   */
  public function __construct(Model $model, string $attr)
  {
    $this->model = $model;
    $this->attr = $attr;
  }

  /**
   * Dynamic input field to be rendered
   * 
   * @return string
   */
  abstract public function renderInput(): string;

  /**
   * The basic idea is that the main purpose of this object is to create
   * a string (HTML), so the use of __toString in this case is convenient.
   */
  public function __toString()
  {
    return sprintf(
      '
    <div class="form-group">
      <label>%s</label>
      %s
      <div class="error-message">%s</div>
    </div>
    ',
      $this->model->getLabel($this->attr), // label
      $this->renderInput(), // dynamic input field
      $this->model->getFirstError($this->attr) // message
    );
  }
}
