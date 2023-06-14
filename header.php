    <nav class="navbar fixed-top navbar-expand-md navbar-expand-lg bg-light bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">ArtCODE Lite</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link fw-bold <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active' ?>" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold <?php if(basename($_SERVER['PHP_SELF']) == 'imgupload.php') echo 'active' ?>" href="imgupload.php">Upload</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold <?php if(basename($_SERVER['PHP_SELF']) == 'profile.php') echo 'active' ?>" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <br><br><br>