<?php namespace JustInsta;

use \Curl;
use JustInsta\Exceptions\TokenNotFoundException;
use JustInsta\Exceptions\WrongResponseException;
use JustInsta\Exceptions\WrongUsernameOrPasswordException;

class CurlService {

  /**
   * @var Curl
   */
  protected $curl;

  /**
   * @var string
   */
  protected $url;

  public function __construct()
  {
    $this->curl = new Curl;
  }

  /**
   * 
   * @param string $url
   */
  public function setUrl($url)
  {
    $this->url = $url;
  }

  /**
   * Setup
   * 
   * @param string $url
   * @return
   */
  protected function setup()
  {
    $ua = new UserAgent;

    // Set up User-Agent
    $this->curl->useragent = $ua->getRandomUserAgent();

    // Cookie file
    $this->curl->cookie_file = tempnam('/tmp', 'insta_');

    // Referer
    $this->curl->referer = $this->url;

    // SSL
    $this->curl->options['CURLOPT_SSL_VERIFYPEER'] = 0;
    $this->curl->options['CURLOPT_SSL_VERIFYHOST'] = 0;
  }

  /**
   * 
   * @param string $login
   * @param string $password
   * @return mixed
   */
  public function makeLoginRequest($login, $password)
  {
    // Setup for new request
    $this->setup();

    // First, get CSRF Token
    $csrf = $this->getCSRFToken();

    if ( ! $csrf)
      throw new TokenNotFoundException("CSRF token not found");

    // Add Cookie with "csrftoken"
    $this->addHeader('cookie', "csrftoken={$csrf}");
    
    $postData = [
      'username'            => $login,
      'password'            => $password,
      'csrfmiddlewaretoken' => $csrf,
    ];

    $response = $this->curl->post($this->url, $postData);
    $body = $response->body;
    $json = json_decode($body);

    if (json_last_error() !== JSON_ERROR_NONE)
      throw new WrongResponseException("Wrong response from server");
    
    if ( ! $json->authenticated)
      throw new WrongUsernameOrPasswordException("Wrong Username or Password");

    return true;
      
  }

  /**
   * Add some heades to request
   *
   * @param string $name Header name
   * @param string $value Header value
   * @return
   */
  protected function addHeader($name, $value)
  {
    $this->curl->options[$name] = $value;
  }

  /**
   * Get CSRF Token from server
   * return token on success, or false on failure
   * 
   * @return mixed
   */
  protected function getCSRFToken()
  {
    $body = $this->curl->get($this->url)->body;
    preg_match("/window\._csrf_token = '(.*)';/", $body, $found);

    if (empty($found))
      return false;

    return $found[1];
  }

}