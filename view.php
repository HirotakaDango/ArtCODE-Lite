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
    <?php include ('header.php'); ?>
    <div class="container mt-2">
      <div style="margin-top: 6px;">
        <?php if ($post) { ?> <!-- Fixed variable name -->
          <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item fw-bold"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active fw-bold" aria-current="page"><?php echo $post['title']; ?></li>
            </ol>
          </nav>
          <div class="image-row">
            <div class="image-col-6">
              <div class="image-card">
                <img class="rounded object-fit-cover" style="width: 100%; height: 100%;" src="thumbnails/<?php echo $post['filename']; ?>" alt="<?php echo $post['title']; ?>">
              </div>
            </div>
            <div class="cool-6">
              <div class="image-card container">
                <h2 class="fw-bold"><?php echo $post['title']; ?></h2>
                <div class="container">
                  <p class="fw-bold mt-2"><?php echo $post['description']; ?></p>
                </div>
                <a class="btn btn-sm btn-primary mb-5 fw-bold rounded-4" href="images/<?php echo $post['filename']; ?>" download><i class="bi bi-download"></i> download image</a>
              </div>
            </div>
          </div>
        <?php } else { ?>
          <p>No post found with the given ID.</p>
        <?php } ?>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
  </body>
</html>
