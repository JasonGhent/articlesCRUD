<?php
include "connect.class.php";
class Article extends Connect
{
  function __construct()
  {
    parent::__construct();
  }

  function detail($id)
  {
    $query = $this->conn->prepare("SELECT * FROM `article` WHERE id = $id");
    $query->bindValue(':id', (int) $id);
    $query->execute();
    $articles = array();
    while ($row = $query->fetch(PDO::FETCH_OBJ))
    {
      $articles[] = $row;
    }
   
    return $articles;
  }

  function index($data)
  {
    try {
      $query = $this->conn->prepare('SELECT * FROM `article` ORDER BY `created` DESC LIMIT 10 OFFSET :offset');
      $query->bindValue(':offset', (int) $data['offset'], PDO::PARAM_INT);
      $query->execute();
      $articles = array();
      while ($row = $query->fetch(PDO::FETCH_ASSOC))
      {
        $articles[] = $row;
      }
    }
    catch(PDOException $e) { echo 'Error: ' . $e->getMessage(); }
    return $articles; 
  }

  function recent()
  {
    $query = $this->conn->prepare("SELECT * FROM `article` ORDER BY `updated` DESC LIMIT 5");
    $query->execute();
    $articles = array();
    while ($row = $query->fetch(PDO::FETCH_ASSOC))
    {
      $articles[] = $row;
    }
    return $articles;
  }

  function add($data)
  {
    $query = $this->conn->prepare('INSERT INTO article (title, body, created) VALUES (:title,:content,NOW())');
    $query->bindValue(':title', $data['title']);
    $query->bindValue(':content', $data['content']);
    $query->execute();
    
    return $query->rowCount();
  }

  function update($data)
  {
    $query = $this->conn->prepare('update article set title=:title, body=:content WHERE id=:id');
    $query->bindValue(':id', $data['id']);
    $query->bindValue(':title', $data['title']);
    $query->bindValue(':content', $data['content']);
    $query->execute();
    
    return $query->rowCount();
  }

  function del($data)
  {
    $query = $this->conn->prepare('DELETE FROM `article` WHERE id=:id');
    $query->bindValue(':id', $data['id']);
    $query->execute();
    
    return $query->rowCount();
  }
  
  function __destruct()
  {
    parent::__destruct();
  }
}
?>
