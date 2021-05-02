<?php

namespace App\src\Form;

class TextareaField extends BaseField
{
  /**
   * 
   */
  public function renderInput(): string
  {
    return sprintf(
      '<textarea name="%s" class="%s">%s</textarea>',
      $this->attr,
      $this->model->hasError($this->attr) ? 'is-invalid' : '', // class
      $this->model->{$this->attr}, // value
    );
  }
}
