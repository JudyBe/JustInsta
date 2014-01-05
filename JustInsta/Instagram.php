<?php namespace JustInsta;

class Instagram {

  /**
   * @var JustInsta\Curl
   */
  protected $curl;

  /**
   * @var string
   */
  protected $error;

  protected $ajax_login_url = "https://instagram.com/accounts/login/ajax/";

  public function __construct(CurlService $curl = null)
  {
    if (is_null($curl))
      die('Curl class is broken :(' . PHP_EOL);

    $this->curl = $curl;
    
  }

  /**
   * 
   * @param string $login
   * @param string $password
   * @return mixed
   */
  public function login($login, $password)
  {
    try
    {
      // Setup
      $this->curl->setUrl($this->ajax_login_url);

      return $this->curl->makeLoginRequest($login, $password);
    }
    catch (\Exception $e)
    {
      $this->error = $e->getMessage();
      return false;
    }
  }

  public function getErrorMessage()
  {
    return $this->error;
  }

}