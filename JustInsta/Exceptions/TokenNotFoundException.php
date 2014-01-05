<?php namespace JustInsta\Exceptions;

class TokenNotFoundException extends \Exception {
  protected $message = "CSRF token not found";
}