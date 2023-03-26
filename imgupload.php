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
    $thumbnail_width = 100;
    $thumbnail_height = round($thumbnail_width / $source_ratio);

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

    // Add the image to the database
    $username = $_SESSION['username'];
    $stmt = $db->prepare("INSERT INTO images (username, filename) VALUES (:username, :filename)");
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':filename', $filename);
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
    <section class="gallery-links">
        <div class="wrapper">
            <h2 style="font-family: sans-serif; float: center; color: gray; font-weight: 800;">UPLOAD IMAGE</h2>

            <img id="file-ip-1-preview" style="height: 400px; border-radius: 15px; width: 95%; margin-bottom: 15px; margin-top: 20px;">
            <div class="gallery-upload">
                <form method="post" enctype="multipart/form-data">
                       
                  <?php if (isset($_GET['error'])): ?>
                        <p><?php echo $_GET['error']; ?></p>
                  <?php endif ?>
                    </br>
                    <div class="upload-btn-wrapper">
                        <label for="file-ip-1" hidden>upload</label>
                        <button class="btn1">browse</button>
                        <input type="file" name="image" type="file" id="file-ip-1" accept="image/*" onchange="showPreview(event);">
                    </div>
                    <div class="upload-btn-wrapper">
                        <input type="submit" 
                               name="submit" 
                               value="upload" 
                               class="btn1">  
                    </div>
                </form>
            </div>
        </div>
    </section>
<style>
input[type=text] {
  padding:10px;
  border: 2px solid #eee;
  width: 90%;
  margin: auto;
  margin-bottom: 10px;
  border-radius: 15px;
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

.gallery-links {
    text-align: center;
}

.gallery-upload {
    text-align: center;
}

img {
    text-align: center;
    object-fit: cover;
    margin: auto;
    border: 4px solid #e6e5e3;
}

.center {
    text-align: center;
}

.btn {
    border: 2px solid #eee;
    color: gray;
    background-color: #eee;
    padding: 8px 20px;
    border-radius: 15px;
    font-size: 20px;
    font-weight: bold;
}

.upload-btn-wrapper input[type=file] {
    font-size: 100px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
}

.upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
}

</style>

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
