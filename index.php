<?php
function redrect($url)
{
  header("Location:$url");
}
session_start();

if (isset($_GET['logout'])) {
  $_SESSION = array();
  session_destroy();
  session_unset();
  unset($_SESSION["auth"]);
  // redrect('index.php');
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Online qo'llanma</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body class="bg-light">
  <?php include 'components/navbar.php'; ?>
  <div class="container">
    <h3 class="text-center my-3">Qo'llanmalar</h3>
    <?php include 'components/docs.php'; ?>
  </div>
  <?php include 'components/footer.php'; ?>
</body>

</html>