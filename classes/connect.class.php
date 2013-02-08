<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

class Connect
{
  private $username = '';
  private $password = '';

  function __construct()
  {
    $this->conn = new PDO('mysql:host=localhost;dbname=articles', $this->username, $this->password);

    // PDO debugging
    # $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    // Depreacated method of connecting
    # $this->conn = new mysqli('localhost', 'root', 'password', 'articles') or die();
  }

  function __destruct()
  {
    $this->conn = null;
  }
}
?>
