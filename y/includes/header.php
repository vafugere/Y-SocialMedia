<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <div class="navigation">
      <div class="left-group">
        <a class="navbar-brand" href="index.php"><img alt="Y Logo" src="images/y_logo.png" class="logo"></a>

        <form class="form-inline my-2 my-lg-0" method="get" action="search.php">
          <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>

      <div class="right-group">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php User::displayProfilePic($con, $userId); ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="#" id="edit_photo">Change Profile Photo</a>
              <a class="dropdown-item" href="logout.php">Logout</a>
            </div>
          </li>
        </ul>
      </div>

    </div>
  </div> 
</nav>

<script src="js/upload_photo.js"></script>

