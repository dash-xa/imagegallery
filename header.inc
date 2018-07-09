<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Image Gallery
  </title>

  <!-- Included Libraries: -->
	<!-- 	jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
  <!-- Fancybox -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.js"></script>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!-- Custom scripts written by yours truly -->
  <link rel="stylesheet" href="style.css">
  <script src="script.js"></script>

</head>
<body>
  <!--Navbar-->
  <!-- Navigation -->
  <div class="container" style="width:100%; background-color:#C0C0C0">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle glyphicon glyphicon-menu-hamburger" data-toggle="collapse" data-target=".navbar-main-collapse">
	  <i class="fa fa-bars">
      </i>
      </button>
	  <ul>
      <li>
        <a style="float:left" class="navbar-brand page-scroll" href="index.php">Home
        </a>
      </li></ul>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
      <ul class="nav navbar-nav">
        <!-- Link to upload image form -->
        <li>
          <a href="index.php?form=true">Upload Image
          </a>
        </li>
        <!-- If user is editor show display settings -->
        <?php
          if (isset($_SESSION['isEditor']) && $_SESSION['isEditor'] == true)
            echo '
            <!-- display modes(public, private, all) -->
            <li class="dropdown">
            <a class="dropdown-toggle"  data-toggle="dropdown" href="#" aria-expanded="false">Display
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
            <li>
            <form action="index.php" method="post" class="navbar-form navbar-nav ">
            <button type="submit" name="access" value="all" class="btn btn-default">all</button>
            </form>
            </a>
            </li>
            <li>
            <form action="index.php" method="post"
            class="navbar-form navbar-nav ">
            <button type="submit" name="access" value="public" class="btn btn-default">Public</button>
            </form>
            </li>
            <li>
            <form action="index.php" method="post"
            class="navbar-form navbar-nav ">
            <button type="submit" name="access" value="private" class="btn btn-default">Private</button>
            </form>
            </li>
            </ul>';
          ?>
        <!-- Sorting options -->
        <li class="dropdown">
          <a class="dropdown-toggle"  data-toggle="dropdown" href="#" aria-expanded="false">Sort
          <span class="caret">
          </span>
          </a>
          <ul class="dropdown-menu">
            <li>
              <a href="index.php?sort=firstname">First Name
              </a>
            </li>
						<li>
              <a href="index.php?sort=lastname">Last Name
              </a>
            </li>
						<li> 
              <a href="index.php?sort=date">Date
              </a>
            </li>
          </ul>
        </li>
        <!--Seachbar and submit button-->
        <li>
          <input type="text" id="search" name="search" class="form-control searchplacing" placeholder="Search" style="position:relative; top:8px" required>
        </li>
        <li>
			<button type="submit" id="submitsearch" value="submit" class="btn btn-default" style="position:relative; top:7px">
				Search
			</button>
        </li>
        <!-- If user is editor show authorize images and edit views -->
        <?php
          if (isset($_SESSION['isEditor']) && $_SESSION['isEditor'] == true)
            include 'moderatorbuttons.inc';
          // print logout or login depending on current status
          if (isset($_SESSION['isEditor']) && $_SESSION['isEditor'] == true)
            echo '<li><a href="index.php?isEditor='.'false">Logout</a></li>';
          else 
            echo '<li><a href="index.php?isEditor='.'true">Login</a></li>'; 
        ?>
      </ul>
    </div>
  </div>
  <!-- main container -->
  <div id="container-fluid" style="padding-left:15%; padding-right:15%; padding-top:5%">
  <div class="row">
  <div class="col-sm-12 main">
  <!-- other files determine what to show here -->