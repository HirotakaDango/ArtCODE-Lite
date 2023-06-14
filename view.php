<?php
session_start();
$db = new PDO('sqlite:database.sqlite');
if (!isset($_SESSION['username'])) {
  header('Location: session.php');
  exit(); // Added exit() to stop execution after redirection
}

$username = $_SESSION['username'];
$id = $_GET['id'];

// Query to fetch a single row by ID
$query = "SELECT * FROM images WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindParam(':id', $id);
$statement->execute();
$post = $statement->fetch();

// Query to check if there are more than 1 post by the same user
$user_posts_query = "SELECT id FROM images WHERE username = :username";
$user_posts_statement = $db->prepare($user_posts_query);
$user_posts_statement->bindParam(':username', $username);
$user_posts_statement->execute();
$user_posts = $user_posts_statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo $post['title']; ?></title>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  </head>
  <body>
    <div class="container-fluid mt-3">
      <div style="margin-top: 6px;">
        <?php if ($post) { ?> <!-- Fixed variable name -->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-chevron p-3 bg-body-tertiary rounded-3">
              <li class="breadcrumb-item">
                <a class="link-body-emphasis fw-bold text-decoration-none" href="index.php">
                  <i class="bi bi-house-fill"></i>
                  <span>Home</span>
                </a>
              </li>
              <li class="breadcrumb-item active fw-bold"><?php echo $post['username']; ?></li>
              <li class="breadcrumb-item active fw-bold" aria-current="page"><?php echo $post['title']; ?></li>
            </ol>
          </nav>
          <div class="row featurette">
            <div class="col-md-5 order-md-1 mb-2">
              <a class="d-block" href="images/<?php echo $post['filename']; ?>"><img class="rounded object-fit-cover img-thumbnail" style="width: 100%; height: 100%;" src="thumbnails/<?php echo $post['filename']; ?>" alt="<?php echo $post['title']; ?>"></a>
            </div>
            <div class="col-md-7 order-md-2"> 
              <div class="card container w-100">
                <h2 class="fw-bold text-center mt-2"><?php echo $post['title']; ?></h2>
                <p class="fw-bold mt-2"><small><?php echo $post['description']; ?></small></p>
                <?php
                  // Get image size in megabytes
                  $post_size = round(filesize('images/' . $post['filename']) / (1024 * 1024), 2);
                  
                  // Get image dimensions
                  list($width, $height) = getimagesize('images/' . $post['filename']);
                  
                  // Display image information
                  echo "<p class='text-start fw-semibold'><small>Image data size: " . $post_size . " MB</small></p>";
                  echo "<p class='text-start fw-semibold'><small>Image dimensions: " . $width . "x" . $height . "</small></p>";
                ?>
                <div class="btn-group w-100 mt-2 mb-3">
                  <a class="btn btn-primary fw-bold rounded-start-pill" href="images/<?php echo $post['filename']; ?>" download><i class="bi bi-download"></i> download</a>
                  <button class="btn btn-primary fw-bold rounded-end-pill" onclick="sharePage()"><i class="bi bi-share-fill"></i> share</button>
                </div>
              </div>
            </div>
          </div>
        <?php } else { ?>
          <p>No post found with the given ID.</p>
        <?php } ?>
      </div>
    </div>
    <br>
    <script>
      function sharePage() {
        if (navigator.share) {
          navigator.share({
            title: document.title,
            url: window.location.href
          }).then(() => {
            console.log('Page shared successfully.');
          }).catch((error) => {
            console.error('Error sharing page:', error);
          });
        } else {
          console.log('Web Share API not supported.');
        }
      }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
  </body>
</html>