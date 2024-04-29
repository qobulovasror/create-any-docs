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

require('../config/db.php');

function getDocItems($link, $id){
    $query = "SELECT * FROM doc_pages WHERE doc_id=$id";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
    return $data;
}
function getDocTitle($link, $id){
    $query = "SELECT name FROM docs WHERE id=$id";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
    return $data;
}


$curDocID = "";
if(empty($_GET["docId"])){
    if(!empty($_POST["formType"]) && !empty($_POST["title"]) && !empty($_POST["body"]) ){
        $title = $_POST['title'];
        $body = $_POST['body'];
        $doc_id = $_POST["docId"];
        
        // str_replace("'", "\'", $title);
        // str_replace("'", "\'", $body);
        if($_POST["formType"]=="addDocPart"){
            $query = "INSERT INTO `doc_pages` (`doc_id`, `title`, `body`) VALUES ('$doc_id', '$title', '$body')";
            mysqli_query($link, $query) or die(mysqli_error($link));
        }else{
            // $query = "UPDATE `doc_pages` SET `title`='$title',`body`='$body' WHERE id=docPartID";
            // mysqli_query($link, $query) or die(mysqli_error($link));
        }
        $curDocID = $_POST['docId'];
    }else{
        // redrect("../index.php");   
    }
}else{
    $curDocID = $_GET['docId'];
}

$docParts = getDocItems($link, $curDocID);
$docTitle = getDocTitle($link, $curDocID);

// $title = "";
// $titleID = "";
// $setTitleErr = "";
// if(!empty($_POST["title"]) && !empty($_POST["formStatus"])){
//     $title = $_POST["title"];
//     str_replace("'", "\'", $title);
//     $formStatus = $_POST["formStatus"];
//     if($formStatus=="create"){
//         $userID = $_SESSION["id"];
//         $query = "INSERT INTO docs SET author_id='$userID', name='$title'";
//         if (mysqli_query($link, $query)) {
//             $titleID = mysqli_insert_id($link);
//         } else {
//             echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
//         }
//     }else{
//         $query = "UPDATE docs SET `name`='$title' WHERE id='$formStatus'";
//         mysqli_query($link, $query) or die(mysqli_error($link));
//     }
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Online qo'llanma yaratish</title>
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
                    <input type="hidden" value="<?php echo ($title)? $titleID: 'create';?>" name="formStatus">
                    <div class="modal-header">
                        <h5 class="modal-title">Qo'llanma sarlovhasini kiriting</h5>
                    </div>
                    <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="title" placeholder="Php..." id="setTitleInputID" minlength="1" required>
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
                    <input type="text" class="form-control" name="title" placeholder="For operatori..." id="addThemeInputID" required>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="closeAddThemeWinBtn">Qo'shish</button>
                </div>
            </div>
        </div>
    </div>

    <div class="navbar bg-body-tertiary w-100">
        <div class="container">
            <div class="row d-flex justify-content-between w-100 pt-3">
                <div class="col" style="height: 50px">
                    <a href="/" class="navbar-brand">
                        <h2>Online docs</h2>
                    </a>
                </div>
                <div class="col">
                    <h4 class="text-center"><?php echo end($docTitle)["name"]?></h4>
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
                <div class="card p-3 col-4 me-1">
                    <div class="d-flex justify-content-between">
                        <h4>Qo'llanma mavzulari</h4>
                        <a title="Add new sub doc" href="/templates/create.php?docId=<?=$_GET['docId']?>" class="btn btn-primary" id="addThemeBtn"><i class="bi bi-plus-circle" style="font-size: 18px"></i></a>
                    </div>
                    <div class="d-flex mt-2"  style="overflow: auto; max-height: 75vh">
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomi</th>
                                <th scope="col">Amal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tr = "";
                                foreach($docParts as $key => $value){
                                    $tr .= '<tr>';
                                    $tr .= '<th scope="row">'.($key+1).'</th>';
                                    $tr .= '<td>'.$value["title"].'</td>';
                                    $tr .= '<td class="d-flex">';
                                    $tr .= '<a href="/templates/read.php?docId='.$value["doc_id"].'&page='.$value['id'].'" class="btn btn-primary btn-sm me-1"><i class="bi bi-eye" style="font-size: 15px"></i></a>
                                    <button type="button" class="btn btn-primary btn-sm me-1"><i class="bi bi-pencil-square" style="font-size: 15px"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash3" style="font-size: 15px"></i></button>';
                                    $tr .= '</td>';
                                    $tr .= '</tr>';
                                }
                                    echo $tr;
                                ?>
                            </tbody>
                        </table>
                        
                    </div>
                </div>
                <form class="card col-7 p-2" style="height: max-content" action="/templates/create.php" method="post">
                    <h3>Mavzu qo'shish</h3>
                    <input type="hidden" name="formType" value="addDocPart"/>
                    <input type="hidden" name="docId" value="<?= $_GET['docId']?>"/>
                    <div class="mb-3">
                        <label for="title">Mavzu nomi</label>
                        <input type="text" class="form-control" name="title" placeholder="for operatori ..." id="title" />
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Write content here ..." name="body" id="floatingTextarea2" style="height: 300px"></textarea>
                        <label for="floatingTextarea2">Mavzu matni</label>
                    </div>
                    <button type="submit" class="btn btn-primary my-2">Qo'shish</button>
                </form>
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


        document.addEventListener("DOMContentLoaded", function() {
            const oldCreatedTitle = <?php if($title){echo('"'.$title.'"');}else{echo 'false';} ?>;
            // if(!oldCreatedTitle) {
            //     setTitle.style.display = "block";
            // }else{
            //     doc_title.innerHTML = oldCreatedTitle;
            // }
        })

        doc_title.addEventListener("click", function() {
            setTitle.style.display = "block";
            if(doc_title.innerHTML){
                setTitleInputID.value = doc_title.innerText;
            }
        })

        setTitleBtn.addEventListener('click', ()=>{
            if(setTitleInputID.value.length > 0 ){
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

        addThemeBtn.addEventListener('click', function(){
            addThemeWindow.style.display = 'block'
        })
        closeAddThemeWinBtn.addEventListener('click', function(){
            addThemeWindow.style.display = 'none'
        })

        

    </script>

</body>

</html>