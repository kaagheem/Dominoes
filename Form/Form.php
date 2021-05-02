<?php

namespace App\src\Form;

use App\src\Model;

class Form
{
  /**
   * Begin of form tag and its properties
   * 
   * @param string $action
   * @param string $method
   * 
   * @return Form
   */
  public static function begin(string $action, string $method): Form
  {
    echo sprintf('<form action="%s" method="%s">', $action, $method);

    return new Form();
  }

  /**
   * End of form tag
   * 
   * @return void
   */
  public static function end(): void
  {
    echo '<form/>';
  }

  /**
   * We need the same model to get same data if submit fails
   * 
   * @param Modle  $model
   * @param string $attr
   * 
   * @return InputField
   */
  public function field(Model $model, string $attr): InputField
  {
    return new InputField($model, $attr);
  }
}
