<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

//error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../classes/article.class.php";

###
# Routing
###
  switch($_GET['action'])
  { 
    case 'add':
      routeAdd();
      break;
    case 'recent':
      routeRecent();
      break;
    case 'index':
      routeIndex();
      break;
    case 'update':
      routeUpdate();
      break;
    case 'delete':
      routeDelete();
      break;
    case 'detail':
      routeDetail();
      break;
  }

  function routeDelete()
  {
    $article = new Article();
    if(isset($_POST))
    {
      $id = $_POST['id'];
      if(isset($id))
      {
        $data = array(
		'id'		=> $id,
        );
        $article->del($data);
      }
    }
  }

  function routeUpdate()
  {
    $article = new Article();
    if(isset($_POST))
    {
      $id = $_POST['id'];
      $title = $_POST['title'];
      $content = $_POST['content'];
      if(isset($title) && isset($content))
      {

        $data = array(
		'id'		=> $id,
            	'title'		=> $title,
            	'content'	=> $content
        );

        $article->update($data);
      }
    }
  }

  function routeAdd()
  {
    $article = new Article();
    if(isset($_POST))
    {
      if(isset($_POST['title'])) { $title = $_POST['title']; } else { print "<b>title</b> is not set.<br />"; }
      if(isset($_POST['content'])) { $content = $_POST['content']; } else { print "<b>content</b> is not set.<br />"; }
      if(isset($title) && isset($content))
      {
        $data = array(
            	'title'		=> $title,
            	'content'	=> $content
        );

        $article->add($data);
      }
    }
  }

  function routeDetail()
  {
    $article = new Article();
    if(isset($_POST))
    {
      $id = isset($_POST['id']) ? $_POST['id'] : "";
      $articles = $article->detail($id);
      print json_encode($articles);
    }
  }

  function routeRecent()
  {
    $article = new Article();
    if(isset($_POST))
    {
      $articles = $article->recent();
      print json_encode($articles);
    }
  }

  function routeIndex()
  {
    $article = new Article();
    if(isset($_POST))
    {
      $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
      if(isset($offset))
      {

        $data = array(
            	'offset'	=> $offset
        );

        $articles = $article->index($data);
        print json_encode($articles);
      }
    }
  }
?>
