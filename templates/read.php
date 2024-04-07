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

$doc_id = "";
$page = 1;
$page_content = "";

if (!empty($_GET['docId'])) {
    $doc_id = $_GET['docId'];
} else {
    redrect('../index.php');
}
if (!empty($_GET['page'])) {
    $page = $_GET['page'];
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
    <script src="../assets/js/showdown.js"></script>
</head>

<body>
    <?php include '../components/navbar.php'; ?>

    <?php
    $query = "SELECT * FROM `doc_pages` WHERE `doc_id`=" . $doc_id . " ORDER BY id;";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row)
        ;
    ?>
    <div class="d-flex">
        <div class="left-nav pt-3">
            <h3 class="text-center">Python</h3>
            <div class="list-group  list-group-flush">
                <?php
                $list = "";
                foreach ($data as $value) {
                    $list .= '<a href="/templates/read.php?docId='.$doc_id. '&page='. $value['id'] ;
                    if($page== $value['id']) {
                        $list .= '" class="list-group-item list-group-item-action active" aria-current="true">' ;
                        $page_content =  $value['body'];
                    }else{
                        $list .= '" class="list-group-item list-group-item-action" aria-current="true">' ;
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
                ?>
                <p id="doc_body"></p>
            </div>
        </div>
    </div>

    <script>
        const data = `<?php echo $page_content; ?>`
        const converter = new showdown.Converter();
        const doc_body = document.getElementById("doc_body");

        document.addEventListener('DOMContentLoaded', (event) => {
            let html = converter.makeHtml(data);
            doc_body.innerHTML=html;
        });
    </script>

</body>

</html>