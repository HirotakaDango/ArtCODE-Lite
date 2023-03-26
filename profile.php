<?php
  session_start();
  if (!isset($_SESSION['username'])) {
    header("Location: session.php");
    exit;
  }

  // Connect to the SQLite database
  $db = new SQLite3('database.sqlite');

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
    <div class="images">
      <?php while ($image = $result->fetchArray()): ?>
        <div class="image-container">
          <a href="images/<?php echo $image['filename']; ?>">
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
