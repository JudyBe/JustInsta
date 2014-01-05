<?php namespace JustInsta\Exceptions;

class WrongResponseException extends \Exception {
  protected $message = "Wrong response from server";
}