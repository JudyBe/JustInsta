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

  protected $base_url = "https://instagram.com/accounts/login/ajax/";

  public function __construct(CurlService $curl = null)
  {
    if (is_null($curl))
      die('Curl class is broken :(' . PHP_EOL);

    $this->curl = $curl;

    // Setup
    $this->curl->setUrl($this->base_url);
    
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