<?php
//url/index.php/home/method/paras
class Route
{
  protected $currentController = 'Home';
  protected $currentMethod = 'index';
  protected $params = [];
  public function __construct()
  {
    //lay URL
    $url = $this->getUrl();
    unset($url[0]);
    unset($url[1]);
    unset($url[2]);
    unset($url[3]);
    unset($url[4]);
    unset($url[5]);

    // Look in controllers for first value
    if (isset($url[6])) {
      if (file_exists("controllers/" . ucwords($url[6]) . '.php')) {
        // If exists, set as controller
        $this->currentController = ucwords($url[6]);
        // Unset 0 Index
        unset($url[6]);
      }
    }
    // Require the controller
    require_once "controllers/" . $this->currentController . '.php';

    // Instantiate controller class
    $this->currentController = new $this->currentController;

    // Check for second part of url
    if (isset($url[7])) {
      // Check to see if method exists in controller
      if (method_exists($this->currentController, $url[7])) {
        $this->currentMethod = $url[7];
        // Unset 1 index
        unset($url[7]);
      }
    }

    // Lay danh sahc tham so
    $this->params = $url ? array_values($url) : [];

    // goi ham voi tham so la mang tham so
    call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
  }

  public function getUrl()
  {
    $url = rtrim($_SERVER['REQUEST_URI'], '/');

    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url); //duoc mang cac phan url,index.php,controller,method/paras
    return $url;
  }
}
