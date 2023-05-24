<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: session.php");
  exit;
}

// Create "images" and "thumbnails" folders if they don't exist
if (!is_dir('images')) {
  mkdir('images');
}

if (!is_dir('thumbnails')) {
  mkdir('thumbnails');
}

// Connect to the SQLite database
$db = new SQLite3('database.sqlite');
$db->exec("CREATE TABLE IF NOT EXISTS images (id INTEGER PRIMARY KEY AUTOINCREMENT, filename TEXT, username TEXT, title TEXT, description TEXT)");

// Get all of the images from the database
$result = $db->query("SELECT * FROM images ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ArtCODE LITE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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

    .btn1 {
        padding: 10px;
        margin: 10px; 
        border: 8px solid #eee;
        border-radius: 15px;
        color: gray;
        font-weight: 700;
        padding: 8px 20px;
    }

  </style>
  </head>
  <body>
    <?php include 'header.php'; ?>
    <div class="images">
      <?php while ($image = $result->fetchArray()): ?>
        <a href="view.php?id=<?php echo $image['id']; ?>">
          <img class="lazy-load" data-src="thumbnails/<?php echo $image['filename']; ?>">
        </a>
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
