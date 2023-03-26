    <nav class="navbar fixed-top navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container-fluid">
        <button class="navbar-toggler1 d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
          <svg fill="#d4d3cf" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 72 72" width="22px" height="22px"><path d="M56 48c2.209 0 4 1.791 4 4 0 2.209-1.791 4-4 4-1.202 0-38.798 0-40 0-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4C17.202 48 54.798 48 56 48zM56 32c2.209 0 4 1.791 4 4 0 2.209-1.791 4-4 4-1.202 0-38.798 0-40 0-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4C17.202 32 54.798 32 56 32zM56 16c2.209 0 4 1.791 4 4 0 2.209-1.791 4-4 4-1.202 0-38.798 0-40 0-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4C17.202 16 54.798 16 56 16z"/></svg>
        </button> 
        <a class="navbar-brand text-secondary fw-bold" href="index.php">
          ArtCODE
        </a>
        <div class="offcanvas offcanvas-start w-50" tabindex="-1" id="navbar" aria-labelledby="navbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title text-secondary" id="navbarLabel">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-bold">
              <li class="nav-item">
                <a class="nav-link nav-center <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active' ?>" href="index.php">
                  <i class="bi bi-house-fill fs-5"></i>
                  <span class="d-md-none d-lg-inline ms-2">Home</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-center <?php if(basename($_SERVER['PHP_SELF']) == 'imgupload.php') echo 'active' ?>" href="imgupload.php">
                  <i class="bi bi-cloud-arrow-up-fill fs-5"></i>
                  <span class="d-md-none d-lg-inline ms-2">Uploads</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-center <?php if(basename($_SERVER['PHP_SELF']) == 'profile.php') echo 'active' ?>" href="profile.php">
                  <i class="bi bi-person-circle fs-5"></i>
                  <span class="d-md-none d-lg-inline ms-2">Profile</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-center" href="logout.php">
                  <i class="bi bi-door-open-fill fs-5"></i>
                  <span class="d-md-none d-lg-inline ms-2">Logout</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <br><br>
    <style>
      @media (min-width: 768px) {
        .navbar-nav {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
        }
      
        .nav-center {
          margin-left: 15px;
          margin-right: 15px;
        }
      }
      
      @media (max-width: 767px) {
        .navbar-brand {
          position: static;
          display: block;
          text-align: center;
          margin: auto;
          transform: none;
        }

        .navbar-brand {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          font-size: 18px;
        }
      }
    
      .navbar {
        height: 45px;
      }
      
      .navbar-brand {
        font-size: 18px;
      }

      @media (min-width: 992px) {
        .navbar-toggler1 {
          display: none;
        }
      }
    
      .navbar-toggler1 {
        background-color: #ededed;
        border: none;
        font-size: 8px;
        margin-top: -2px;
        margin-left: 8px;
        border-radius: 5px;
        padding: 6px;
        transition: background-color 0.3s ease; 
      }

      .navbar-toggler1:hover {
        background-color: rgba(0,0,0,0.2);
      } 
    </style>