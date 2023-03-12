<?php
  session_start();
  if (!isset($_SESSION['username'])) {
    header("Location: session.php");
    exit;
  }

  // Connect to the SQLite database
  $db = new SQLite3('database.sqlite');

  // Get all of the images uploaded by the current user
  $username = $_SESSION['username'];
  $stmt = $db->prepare("SELECT * FROM images WHERE username = :username ORDER BY id DESC");
  $stmt->bindValue(':username', $username);
  $result = $stmt->execute();
?>

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
            <img src="thumbnails/<?php echo $image['filename']; ?>">
          </a>
          <div>
            <form action="delete.php" method="post">
              <input type="hidden" name="filename" value="<?php echo $image['filename']; ?>">
              <input style="margin-top: -38px; margin-left: 8px; font-size: 10px;" class="btn btn-danger fw-bold" type="submit" value="Delete">
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</html>
