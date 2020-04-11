<?php
namespace Gist\Wallgreens;

class ConfigurationException extends \Exception
{

  // Redefine the exception so message isn't optional
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }

}
