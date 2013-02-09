<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Articles Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./assets/css/articles.css" rel="stylesheet">
    <link href="./components/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet">
    <link href="./components/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="./components/bootstrap/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="" id="#">Articles</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a id="recentlink">Recent</a></li>
              <li><a id="indexlink">Archive</a></li>
              <li><a id="addlink">Add</a></li>
            </ul>
            <!-- The drop down menu -->
            <ul class="nav pull-right">
<?#="              <li><a href="/users/sign_up">Sign Up</a></li>"?>
              <li class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
                <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                  <!-- Login form here -->
                  <form id="loginForm" class="form-horizontal" action="routes/dostuff.php?action=signin" style="float:left;">
                    <fieldset>
                      <legend>Login</legend>
                      <div class="control-group">
                        <label class="control-label" for="email">Email</label>
                        <div class="controls">
                          <input type="text" id="email" name="email" maxlength="255" placeholder="email">
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for="password">Password</label>
                        <div class="controls">
                          <input type="password" id="password" name="password" maxlength="255" placeholder="password">
                        </div>
                      </div>
                      <div class="form-actions">
                        <button type="submit" class="btn btn-inverse" id="signinBtn">Submit</button>
                        <div id="loginNote"></div>
                      </div>
                    </fieldset>
                  </form>
                </div>
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container" id="main">

      <div class="container" id="login">
  
  
      </div> <!-- /container -->
      <div class="container" id="welcome">
  
        <h3>Salutations, visitor.</h3>
        <p>To see a list of recent articles, an index of articles or to add a new article, please select one of the options above.</p>
  
      </div> <!-- /container -->

    </div> <!-- /container -->

    <div class="container" id="recent">

      <h1>Recently Updated</h1>
      <table class="table table-bordered table-hover">
        <caption>Click any element to view more detail</caption>
        <thead>
          <tr>
            <th>Article</th>
            <th>Content</th>
            <th>Created</th>
            <th>Updated</th>
          </tr> 
        <thead>
        <tbody id="recentCells">
        </tbody>
      </table>
      
    </div> <!-- /container -->

    <div class="container" id="index">

      <h1>Archive</h1>
      <table class="table table-bordered table-hover">
        <caption>Click any element to view more detail</caption>
        <thead>
          <tr>
            <th class="indexTitle">Article</th>
            <th>Created</th>
            <th>Updated</th>
          </tr> 
        <thead>
        <tbody id="indexCells">
        </tbody>
      </table>
      <form id="setOffset" class="form-horizontal" action="routes/dostuff.php?action=index">
        <button name="prev" type="submit" id="prev" value="0" class="btn">Prev</button>
        <button name="next" type="submit" id="next" value="10" class="btn">Next</button>
      </form>

    </div> <!-- /container -->

    <div class="container" id="details">

      <form id="updateForm" class="form-horizontal" action="routes/dostuff.php?action=update">
        <fieldset>
          <legend>Update Article</legend>
          <div class="control-group">
            <label class="control-label" for="updateTitle">Title</label>
            <div class="controls">
              <input type="text" id="updateTitle" name="title" maxlength="255" placeholder="Title">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="updateContent">Content</label>
            <div class="controls">
              <textarea rows="6" id="updateContent" name="content"></textarea>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-inverse" id="updateBtn">Submit</button>
            <button type="submit" class="btn btn-danger" id="deleteBtn" style="float:right;">Delete</button>
            <div id="updateNote">Data submitted.</div>
          </div>
        </fieldset>
      </form>

    </div> <!-- /container -->

    <div class="container" id="add">

      <form id="addForm" class="form-horizontal" action="routes/dostuff.php?action=add">
        <fieldset>
          <legend>Add Article</legend>
          <div class="control-group">
            <label class="control-label" for="inputTitle">Title</label>
            <div class="controls">
              <input type="text" id="inputTitle" name="title" maxlength="255" placeholder="Title">
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="inputContent">Content</label>
            <div class="controls">
              <textarea rows="6" id="inputContent" name="content"></textarea>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-inverse" id="addBtn">Submit</button>
            <div id="addNote">Data submitted.</div>
          </div>
        </fieldset>
      </form>

    </div> <!-- /container -->

    <script src="./components/jquery/jquery.js"></script>
    <script type='text/javascript' src='./components/datejs/src/globalization/en-US.js'></script>
    <script type='text/javascript' src='./components/datejs/src/core.js'></script>
    <script type='text/javascript' src='./components/datejs/src/sugarpak.js'></script>
    <script type='text/javascript' src='./components/datejs/src/parser.js'></script>
    <script type='text/javascript' src='./components/datejs/src/time.js'></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-transition.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-alert.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-modal.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-dropdown.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-scrollspy.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-tab.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-tooltip.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-popover.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-button.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-collapse.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-carousel.js"></script>
    <script src="./components/bootstrap/docs/assets/js/bootstrap-typeahead.js"></script>
    <script src="./assets/js/display.js"></script>

  </body>
</html>
