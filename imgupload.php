<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: session.php");
  exit;
}

// Connect to the SQLite database
$db = new SQLite3('database.sqlite');

// Check if an image was uploaded
if (isset($_FILES['image'])) {
  $image = $_FILES['image'];

  // Check if the image is valid
  if ($image['error'] == 0) {
    // Generate a unique file name
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;

    // Save the original image
    move_uploaded_file($image['tmp_name'], 'images/' . $filename);

    // Determine the image type and generate the thumbnail
    switch ($ext) {
      case 'jpg':
      case 'jpeg':
        $source = imagecreatefromjpeg('images/' . $filename);
        break;
      case 'png':
        $source = imagecreatefrompng('images/' . $filename);
        break;
      case 'gif':
        $source = imagecreatefromgif('images/' . $filename);
        break;
      default:
        echo "Error: Unsupported image format.";
        exit;
    }

    // Calculate the new dimensions based on the aspect ratio of the original image
    $source_width = imagesx($source);
    $source_height = imagesy($source);
    $source_ratio = $source_width / $source_height;
    $thumbnail_width = 300;
    $thumbnail_height = round($thumbnail_width / $source_ratio);

    // Calculate the target dimensions while maintaining the original aspect ratio
    if ($source_ratio > 1) {
      $thumbnail_height = round($thumbnail_width / $source_ratio);
    } else {
      $thumbnail_width = round($thumbnail_height * $source_ratio);
    }

    $thumbnail = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
    imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $source_width, $source_height);

    switch ($ext) {
      case 'jpg':
      case 'jpeg':
        imagejpeg($thumbnail, 'thumbnails/' . $filename);
        break;
      case 'png':
        imagepng($thumbnail, 'thumbnails/' . $filename);
        break;
      case 'gif':
        imagegif($thumbnail, 'thumbnails/' . $filename);
        break;
      default:
        echo "Error: Unsupported image format.";
        exit;
    }

    // Retrieve the title and price values from the form submission
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Add the image to the database
    $username = $_SESSION['username'];
    $stmt = $db->prepare("INSERT INTO images (username, filename, title, description) VALUES (:username, :filename, :title, :description)");
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':filename', $filename);
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':description', nl2br($description));
    $stmt->execute();

    header("Location: index.php");
    exit;
  } else {
    echo "Error uploading image.";
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  </head>
  <body>
    <?php include 'header.php'; ?>
    <div class="container">
      <h2 class="text-center fw-bold mt-2">UPLOAD IMAGE</h2>
      <form method="post" enctype="multipart/form-data">
        <?php if (isset($_GET['error'])): ?>
              <p><?php echo $_GET['error']; ?></p>
        <?php endif ?>
        <div class="row featurette">
          <div class="col-md-7 order-md-2">
            <img class="d-block border border-2 object-fit-cover rounded mb-2" id="file-ip-1-preview" style="height: 480px; width: 100%;">
          </div>
          <div class="col-md-5 order-md-1">
            <input class="form-control mb-2" type="file" name="image" type="file" id="file-ip-1" accept="image/*" onchange="showPreview(event);">
            <input class="form-control mb-2" type="text" placeholder="title" id="title" name="title">
            <textarea class="form-control mb-2" type="text" placeholder="description" id="description" style="height: 200px;" name="description"></textarea>
            <button class="btn btn-primary fw-bold w-100" type="submit">upload</button>
          </div>
        </div>
      </form>
    </div>
    <script>
      function showPreview(event){
        if(event.target.files.length > 0){
          var src = URL.createObjectURL(event.target.files[0]);
          var preview = document.getElementById("file-ip-1-preview");
          preview.src = src;
          preview.style.display = "block";
        }
      }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
  </body>
</html>
