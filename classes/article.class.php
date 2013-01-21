<?php
include "./classes/connect.class.php";
class Article extends Connect
{
  function __construct()
  {
    parent::__construct();
  }

  function sanitize($input)
  {
    $input = trim($input);

    if(get_magic_quotes_gpc())
    {
        $input = stripslashes($input);
    } 

    $input = mysql_real_escape_string($input);
    $input = htmlentities($input);

    return $input;
  }

  function detail($id)
  {
    $id = $this->sanitize($id);
    $result = $this->conn->query("SELECT * FROM `article` WHERE id = $id");
    if ($result) 
    {
      $articles = array();
      while ($row = $result->fetch_assoc())
      {
        $articles[] = $row;
      }
    }
    if(!empty($articles)) { return $articles; }
  }

  function index($data)
  {
    $data['offset'] = $this->sanitize($data['offset']);
    $result = $this->conn->query("SELECT * FROM `article` ORDER BY `created` DESC LIMIT 10 OFFSET ".$data['offset']);
    if ($result) 
    {
      $articles = array();
      while ($row = $result->fetch_assoc())
      {
        $articles[] = $row;
      }
    }
    if(!empty($articles)) { return $articles; }
  }

  function recent()
  {
    $result = $this->conn->query("SELECT * FROM `article` ORDER BY `updated` DESC LIMIT 5");
    if ($result) 
    {
      $articles = array();
      while ($row = $result->fetch_assoc())
      {
        $articles[] = $row;
      }
    }
    if(!empty($articles)) { return $articles; }
  }

  function add($data)
  {
    $data['title'] = $this->sanitize($data['title']);
    $data['content'] = $this->sanitize($data['content']);
    $result = $this->conn->query("INSERT INTO article (title, body, created) VALUES ('".$data['title']."','".$data['content']."',NOW())");
    
    return $result;
  }

  function update($data)
  {
    $data['title'] = $this->sanitize($data['title']);
    $data['content'] = $this->sanitize($data['content']);
    $result = $this->conn->query("update article set title='".$data['title']."', body='".$data['content']."' WHERE id=".$data['id']);
    
    return $result;
  }

  function del($data)
  {
    $result = $this->conn->query("DELETE FROM article WHERE id = '".$data['id']."'");
    
    return $result;
  }
  
  function __destruct()
  {
    parent::__destruct();
  }
}
?>
