<?php
function redrect($url)
{
    header("Location:$url");
}
session_start();
if (empty($_SESSION["auth"]) && empty($_SESSION["id"])) {
    redrect("../index.php");
}

if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    session_unset();
    unset($_SESSION["auth"]);
    // redrect('index.php');
    header("Location: ../index.php");
    exit();
}
$userID = $_SESSION["id"];

require ('../config/db.php');


function getOwnDocs($link, $userID){
    $query = "SELECT
    docs.id,
    docs.name,
    docs.status,
    COUNT(doc_pages.id) AS counts
FROM
    docs
LEFT JOIN doc_pages ON docs.id = doc_pages.doc_id and docs.author_id=$userID
GROUP BY
    docs.id;";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row)
        ;
    return $data;
}


if (!empty($_POST["title"]) && !empty($_POST["formStatus"])) {
    $title = $_POST["title"];
    str_replace("'", "\'", $title);
    $formStatus = $_POST["formStatus"];
    if ($formStatus == "create") {
        
        $query = "INSERT INTO docs SET author_id='$userID', name='$title'";
        if (mysqli_query($link, $query)) {
            $titleID = mysqli_insert_id($link);
        } else {
            echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
        }
    } else {
        $query = "UPDATE docs SET `name`='$title' WHERE id='$formStatus'";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}

//for edit doc 
if(!empty($_POST["name"]) && !empty($_POST["status"]) && !empty($_POST["formType"])){
    $title = $_POST["name"];
    $status = $_POST["status"];
    $id = $_POST['formType'];
    str_replace("'", "\'", $title);
    $query = "UPDATE `docs` SET `name`='$title', `status`='$status' WHERE `id`='$id'";
    mysqli_query($link, $query) or die(mysqli_error($link));
}


$myDocs = getOwnDocs($link, $userID);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mening qo'llanmalarim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>

<body>
    <div class="modal" tabindex="-1" id="setTitle">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/templates/create.php" method="post">
                    <input type="hidden" value="<?php echo ($title) ? $titleID : 'create'; ?>" name="formStatus">
                    <div class="modal-header">
                        <h5 class="modal-title">Qo'llanma sarlovhasini kiriting</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="title" placeholder="Php..."
                                id="setTitleInputID" minlength="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="setTitleBtn">Kiritish</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="addThemeWindow">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Qo'llanmaga mavzu qo'shish</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="title" placeholder="For operatori..."
                            id="addThemeInputID" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="closeAddThemeWinBtn">Qo'shish</button>
                </div>
            </div>
        </div>
    </div>

    <!-- navbar -->
    <div class="navbar bg-body-tertiary w-100">
        <div class="container">
            <div class="row d-flex justify-content-between w-100 pt-3">
                <div class="col" style="height: 50px">
                    <a href="/" class="navbar-brand">
                        <h2>Online docs</h2>
                    </a>
                </div>
                <div class="col d-flex justify-content-end" style="height: 50px">
                    <div class="d-flex">
                        <a href="/?logout=1" class="btn btn-danger" style="height: 40px">Chiqish</a>
                    </div>
                </div>
            </div>
        </div>
        <hr class="bg-secondary-subtle m-0" />
    </div>

    <div class="doc-title">
        <div class="container">
            <div class="row d-flex justify-content-between mt-3">
                <div class="card p-2 col-7 me-1">
                    <h3 class="text-center">Mening qo'llanmalarim</h3>
                    <div class="d-flex" style="flex-direction: column; justify-content: space-between;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nomi</th>
                                    <th scope="col">Mavzular soni</th>
                                    <th scope="col">Holati</th>
                                    <th scope="col">Amal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $tr = "<tr>";
                                    foreach($myDocs as $key => $value){
                                        $tr .= "<th scope='row'>".($key+1)."</th>" ; 
                                        $tr .= "<td>".$value["name"]."</td>" ; 
                                        $tr .= "<td>".$value["counts"]."</td>" ; 
                                        if($value['status']){
                                            $tr .= "<td>Chop qilingan</td>" ; 
                                        }else{
                                            $tr .= "<td>Chop qilinmagan</td>" ; 
                                        }
                                        $tr .= '<td class="d-flex">
                                        <a type="button" href="/templates/read.php?docId='.$value["id"].'" title="View doc" class="btn btn-primary btn-sm me-1"><i
                                                class="bi bi-eye" style="font-size: 15px"></i></a>
                                        <a type="button" href="/templates/docList.php?editDoc='.$value["id"].'" title="edit doc" class="btn btn-success btn-sm me-1"><i class="bi bi-pencil-square"
                                                style="font-size: 15px"></i></a>
                                        <a type="button" href="/templates/create.php?editDocId='.$value["id"].'" title="edit items" class="btn btn-info btn-sm me-1"><i class="bi bi-list-ul"
                                                style="font-size: 15px"></i></a>
                                        <a type="button" title="delete doc" class="btn btn-danger btn-sm"><i class="bi bi-trash3"
                                                style="font-size: 15px"></i></a></td>' ; 
                                        $tr .= "</tr>" ; 
                                    }
                                    echo $tr;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php 
                    if(!empty($_GET["editDoc"])):

                        $editDoc = array_filter($myDocs, function($ar) {
                            return ($ar['id'] == $_GET["editDoc"]);
                        });
                        $editDoc = end($editDoc);
                ?>
                    <form class="card col-4 px-2 py-3" action="/templates/docList.php" method="post" style="height: max-content">
                        <h3 class="text-center">Qo'llanmani tahrirlash</h3>
                        <input type="hidden" name="formType" value='<?=$editDoc["id"]?>'>
                        <div class="mb-3">
                            <label for="tutorialName" class="form-label">Qo'llanma nomi</label>
                            <input type="text" class="form-control" name="name" id="tutorialName" placeholder="Javascript..."
                                minlength="1" required value='<?php echo $editDoc["name"]; ?>' />
                        </div>
                        <div class="mb-3">
                            <label for="tutorialStatus" class="form-label">Qo'llanma holati</label>
                            <select class="form-select" id="tutorialStatus" name="status">
                                <option value="0" <?php if(!$editDoc["status"]) echo "selected";?> >Chop qilmaslik</option>
                                <option value="1" <?php if($editDoc["status"]) echo "selected";?>>Chop qilish</option>
                            </select>
                        </div>
                        <button class="btn btn-success">O'zgartrish</button>
                        <!-- <a class="btn btn-primary mt-3" href="/templates/create.php?editDocId=<?=$editDoc["id"]?>">Qo'llanma mavzularini o'zgartrish</a> -->
                        <a class="btn btn-primary mt-3" href="/templates/docList.php">Yangi qo'llanma yaratish</a>
                    </form>
                <?php 
                    else:
                ?>
                <form class="card col-4 px-2 py-3" action="/templates/create.php" method="post" style="height: max-content">
                    <h3 class="text-center">Yangi qo'llanma yaratish</h3>
                    <div class="mb-3">
                        <label for="tutorialName" class="form-label">Qo'llanma nomini kiriting</label>
                        <input type="text" class="form-control" name="name" id="tutorialName" placeholder="Javascript..."
                            minlength="1" required />
                    </div>
                    <button class="btn btn-success">Yaratish</button>
                </form>
                <?php
                    endif;
                ?>
                
            </div>
        </div>
    </div>


    <script>
        const setTitle = document.getElementById('setTitle')
        const setTitleBtn = document.getElementById('setTitleBtn')
        const doc_title = document.getElementById('doc_title')
        const setTitleInputID = document.getElementById('setTitleInputID')

        const addThemeWindow = document.getElementById('addThemeWindow')
        const addThemeBtn = document.getElementById('addThemeBtn')
        const addThemeInputID = document.getElementById('addThemeInputID')
        const closeAddThemeWinBtn = document.getElementById('closeAddThemeWinBtn')


        // document.addEventListener("DOMContentLoaded", function() {
        //     const oldCreatedTitle = <?php if ($title) {
            echo ('"' . $title . '"');
        } else {
            echo 'false';
        } ?>;
        //     if(!oldCreatedTitle) {
        //         setTitle.style.display = "block";
        //     }else{
        //         doc_title.innerHTML = oldCreatedTitle;
        //     }
        // })

        // doc_title.addEventListener("click", function() {
        //     setTitle.style.display = "block";
        //     if(doc_title.innerHTML){
        //         setTitleInputID.value = doc_title.innerText;
        //     }
        // })

        setTitleBtn.addEventListener('click', () => {
            if (setTitleInputID.value.length > 0) {
                setTitle.style.display = "none";
                doc_title.innerHTML = setTitleInputID.value;

                // const dataToSend = {
                //     key1: 'value1',
                //     key2: 'value2'
                // };

                // // Make a POST request using fetch
                // fetch('/templates/createBack.php', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json'
                //     },
                //     body: JSON.stringify(dataToSend)
                // })
                // .then(response => {
                //     console.log(response);
                //     if (!response.ok) {
                //         throw new Error('Network response was not ok');
                //     }
                //     return response.json(); // Parse the JSON from the response
                // })
                // .then(data => {
                //     console.log('Response from server:', data);
                // })
                // .catch(error => {
                //     console.error('Error:', error);
                // });

            }
        })

        // addThemeBtn.addEventListener('click', function(){
        //     addThemeWindow.style.display = 'block'
        // })
        // closeAddThemeWinBtn.addEventListener('click', function(){
        //     addThemeWindow.style.display = 'none'
        // })



    </script>

</body>

</html>