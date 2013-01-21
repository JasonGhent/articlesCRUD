<?php
class Connect
{
  function __construct()
  {
    $this->conn = new mysqli('localhost', '', '', '') or die();
  }
  function __destruct()
  {
    $this->conn->close();
  }
}
?>
