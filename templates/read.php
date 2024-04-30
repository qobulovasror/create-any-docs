<?php
function redrect($url)
{
    header("Location:$url");
}
session_start();

require ("../config/db.php");

if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    session_unset();
    unset($_SESSION["auth"]);
    // redrect('index.php');
    header("Location: index.php");
    exit();
}

function getDoc($link, $doc_id){
    $query = "SELECT * FROM `doc_pages` WHERE `doc_id`=" . $doc_id . " ORDER BY id;";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
    return $data;
}
function getDocTitle($link, $doc_id){
    $query = "SELECT name FROM `docs` WHERE `id`=" . $doc_id . ";";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
    return $data;
}

$doc_id = "";
$page = 0;
$page_content = "";
$page_content_title = "";

if (!empty($_GET['docId'])) {
    $doc_id = $_GET['docId'];
} else {
    redrect('../index.php');
}
if (!empty($_GET['page'])) {
    $page = $_GET['page'];
}

$curDoc = getDoc($link, $doc_id);
$curDocTitle = getDocTitle($link, $doc_id);
$curDocTitle = end($curDocTitle)["name"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Online qo'llanma</title>
    <link rel="shortcut icon" href="../assets/imgs/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/read.css" />
    <script src="../assets/js/showdown.js"></script>
</head>

<body>
    <div class="navbar bg-body-tertiary w-100">
        <div class="container">
            <div class="row d-flex justify-content-between w-100 pt-3">
                <div class="col" style="height: 50px">
                    <a href="/" class="navbar-brand">
                        <h2>Online docs</h2>
                    </a>
                </div>
                <div class="col">
                    <h4 class="text-center"><?=$curDocTitle?></h4>
                </div>
                <div class="col d-flex justify-content-end" style="height: 50px">
                <?php if (empty($_SESSION["auth"])){ ?>
                <a href="../templates/login.php" type="button" class="btn btn-primary" style="height: 40px">  
                    Kirish va Yaratish
                </a>
                <?php } else{ ?>
                    <div class="d-flex">
                        <a href="/templates/docList.php" class="btn btn-primary me-2"  style="height: 40px">Mening qo'llanmalarim</a>
                        <a href="/?logout=1" class="btn btn-danger"  style="height: 40px">Chiqish</a>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
        <hr class="bg-secondary-subtle m-0" />
    </div>

    <div class="d-flex">
        <div class="left-nav pt-3">
            <h3 class="text-center"><?=$curDocTitle?></h3>
            <div class="list-group  list-group-flush">
                <?php
                $list = "";
                foreach ($curDoc as $key => $value) {
                    $list .= '<a href="/templates/read.php?docId=' . $doc_id . '&page=' . $value['id'];
                    if($page == 0){
                        if($key==0){
                            $page_content = $value['body'];
                            $page_content_title = $value['title'];
                            $list .= '" class="list-group-item list-group-item-action active" aria-current="true">';
                        }else{
                            $list .= '" class="list-group-item list-group-item-action" aria-current="true">';
                        }
                    }else{
                        if ($page == $value['id']) {
                            $list .= '" class="list-group-item list-group-item-action active" aria-current="true">';
                            $page_content = $value['body'];
                            $page_content_title = $value['title'];
                        } else {
                            $list .= '" class="list-group-item list-group-item-action" aria-current="true">';
                        }

                    }
                    $list .= $value['title'];
                    $list .= '</a>';
                }
                echo $list;
                ?>
            </div>
        </div>

        <div class="right-side">
            <div class="container-2  py-3">
                <?php
                    echo "<h2 class='text-center'>".$page_content_title."</h2>";
                ?>
                <p id="doc_body"></p>
            </div>
        </div>
    </div>

    <script>
        const title = `<?php echo $page_content_title; ?>`
        const data = `<?php echo $page_content; ?>`
        const converter = new showdown.Converter();
        const doc_body = document.getElementById("doc_body");

        document.addEventListener('DOMContentLoaded', (event) => {
            let html = converter.makeHtml(data);
            doc_body.innerHTML = html;
        });
    </script>

</body>

</html>