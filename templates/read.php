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
    <link rel="stylesheet" href="../assets/css/read.css" />
</head>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="d-flex">
        <div class="left-nav pt-3">
            <h3 class="text-center">Python</h3>
            <div class="list-group  list-group-flush">
                <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                    The current link item
                </a>
                <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
            </div>
        </div>

        <div class="right-side">
            <div class="container-2  py-3">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae rem optio inventore assumenda sint,
                    dicta eum libero dolore neque earum. Optio quaerat molestias eum ipsum asperiores est libero, at
                    adipisci ut minus iure facilis ratione soluta accusamus nulla laudantium esse possimus debitis dolor
                    deserunt officiis veritatis sint fugiat saepe? Dolore, sequi repellendus! Numquam, dolorum aliquid
                    cum ullam, quam itaque perspiciatis vitae alias veniam dicta tempore commodi. Harum voluptates,
                    repellat rerum, assumenda minus maxime ea est animi magnam nesciunt optio aut saepe fugiat at
                    pariatur asperiores! Exercitationem ullam velit vel quam expedita, nisi nobis dolores excepturi nam
                    quasi asperiores sequi numquam.</p>
            </div>
        </div>
    </div>
    
</body>

</html>