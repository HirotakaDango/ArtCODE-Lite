<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: session.php");
  exit;
}

// Connect to the SQLite database
$db = new SQLite3('database.sqlite');

// Update user information
if (isset($_POST['update'])) {
  $username = $_SESSION['username'];
  $password = $_POST['password'];
  $bio = $_POST['bio'];
  $address = $_POST['address'];
  $birth = $_POST['birth'];
  $phone_number = $_POST['phone_number'];

  $stmt = $db->prepare("UPDATE users SET password = :password, bio = :bio, address = :address, birth = :birth, phone_number = :phone_number WHERE username = :username");
  $stmt->bindValue(':username', $username);
  $stmt->bindValue(':password', $password);
  $stmt->bindValue(':bio', $bio);
  $stmt->bindValue(':address', $address);
  $stmt->bindValue(':birth', $birth);
  $stmt->bindValue(':phone_number', $phone_number);
  $stmt->execute();

  header("Location: profile.php");
  exit;
}

if (isset($_POST['filename'])) {
  // Get the filename of the image to delete
  $filename = $_POST['filename'];

  // Delete the image from the database
  $stmt = $db->prepare("DELETE FROM images WHERE filename = :filename");
  $stmt->bindValue(':filename', $filename);
  $stmt->execute();

  // Delete the original image and thumbnail
  unlink('images/' . $filename);
  unlink('thumbnails/' . $filename);

  header("Location: profile.php");
  exit;
} else {
  // Get all of the images uploaded by the current user
  $username = $_SESSION['username'];
  $stmt = $db->prepare("SELECT * FROM images WHERE username = :username ORDER BY id DESC");
  $stmt->bindValue(':username', $username);
  $result = $stmt->execute();
}

// Get user information
$username = $_SESSION['username'];
$stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
$stmt->bindValue(':username', $username);
$userResult = $stmt->execute();
$user = $userResult->fetchArray();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <style>
      .images {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        grid-gap: 2px;
        justify-content: center;
        margin-right: 3px;
        margin-left: 3px;
      }
 
      .images a {
        display: block;
        border-radius: 4px;
        overflow: hidden;
        border: 2px solid #ccc;
      }
 
      .images img {
        width: 100%;
        height: 150px;
        object-fit: cover;
      }
    </style>
  </head>
  <body>
    <?php include 'header.php'; ?>
    <div class="modal modal-sheet position-static d-block bg-body-secondary p-2" tabindex="-1" role="dialog" id="modalSheet">
      <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
          <div class="modal-body py-0">
            <div class="fw-bold">
              <p class="text-center mt-3"><i class="bi bi-person-circle display-2"></i></p>
              <h2 class="text-center"><?php echo $user['username']; ?></h2>
              <p class="text-start">Description:</p>
              <div class="caontainer">
                <p class="text-start"><?php echo $user['bio']; ?></p>
              </div>
              <p class="text-start">Address: <?php echo $user['address']; ?></p>
              <p class="text-start">Birth: <?php echo $user['birth']; ?></p>
              <p class="text-start">Phone Number: <?php echo $user['phone_number']; ?></p>
              <div class="d-grid gap-2 col-6 mx-auto">
                <a class="btn btn-primary mb-3 fw-bold" data-bs-toggle="modal" data-bs-target="#setting">
                  <i class="bi bi-pencil-fill"></i> edit profile
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="images">
      <?php while ($image = $result->fetchArray()): ?>
        <div class="image-container">
          <a href="view.php?id=<?php echo $image['id']; ?>">
            <img class="lazy-load" data-src="thumbnails/<?php echo $image['filename']; ?>">
          </a>
          <div>
            <form action="profile.php" method="post">
              <input type="hidden" name="filename" value="<?php echo $image['filename']; ?>">
              <input style="margin-top: -74px; margin-left: 8px; font-size: 10px;" class="btn btn-danger fw-bold" type="submit" onclick="return confirm('Are you sure?')" value="Delete">
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="setting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Settings</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <form action="profile.php" method="post">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" id="username" class="form-control" value="<?php echo $user['username']; ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" id="password" name="password" class="form-control" value="<?php echo $user['password']; ?>">
            </div>
            <div class="mb-3">
              <label for="bio" class="form-label">Bio</label>
              <textarea id="bio" name="bio" class="form-control"><?php echo $user['bio']; ?></textarea>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <input type="text" id="address" name="address" class="form-control" value="<?php echo $user['address']; ?>">
            </div>
            <div class="mb-3">
              <label for="birth" class="form-label">Birth</label>
              <input type="date" id="birth" name="birth" class="form-control" value="<?php echo $user['birth']; ?>">
            </div>
            <div class="mb-3">
              <label for="phone_number" class="form-label">Phone Number</label>
              <input type="tel" id="phone_number" name="phone_number" class="form-control" value="<?php echo $user['phone_number']; ?>">
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
          </form>
          </div>
        </div>
      </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
          let lazyloadImages;
          if("IntersectionObserver" in window) {
            lazyloadImages = document.querySelectorAll(".lazy-load");
            let imageObserver = new IntersectionObserver(function(entries, observer) {
              entries.forEach(function(entry) {
                if(entry.isIntersecting) {
                  let image = entry.target;
                  image.src = image.dataset.src;
                  image.classList.remove("lazy-load");
                  imageObserver.unobserve(image);
                }
              });
            });
            lazyloadImages.forEach(function(image) {
              imageObserver.observe(image);
            });
          } else {
            let lazyloadThrottleTimeout;
            lazyloadImages = document.querySelectorAll(".lazy-load");

            function lazyload() {
              if(lazyloadThrottleTimeout) {
                clearTimeout(lazyloadThrottleTimeout);
              }
              lazyloadThrottleTimeout = setTimeout(function() {
                let scrollTop = window.pageYOffset;
                lazyloadImages.forEach(function(img) {
                  if(img.offsetTop < (window.innerHeight + scrollTop)) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy-load');
                  }
                });
                if(lazyloadImages.length == 0) {
                  document.removeEventListener("scroll", lazyload);
                  window.removeEventListener("resize", lazyload);
                  window.removeEventListener("orientationChange", lazyload);
                }
              }, 20);
            }
            document.addEventListener("scroll", lazyload);
            window.addEventListener("resize", lazyload);
            window.addEventListener("orientationChange", lazyload);
          }
        })
    </script> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
  </body>
</html>
