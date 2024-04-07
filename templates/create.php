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
    <div class="modal " tabindex="-1" id="setTitle">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Qo'llanma sarlovhasi</h5>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" name="title" placeholder="Php..." id="setTitleInputID" required>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="setTitleBtn">Save changes</button>
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
                <div class="col" style="height: 50px">
                    <h3 class="p-1 text-center" style="cursor: text" id="doc_title"></h3>
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


    <script>
        const setTitle = document.getElementById('setTitle')
        const setTitleBtn = document.getElementById('setTitleBtn')
        const doc_title = document.getElementById('doc_title')
        const setTitleInputID = document.getElementById('setTitleInputID')

        document.addEventListener("DOMContentLoaded", function() {
            setTitle.style.display = "block";
        })

        doc_title.addEventListener("click", function() {
            setTitle.style.display = "block";
        })

        setTitleBtn.addEventListener('click', ()=>{
            if(setTitleInputID.value.length > 0 ){
                setTitle.style.display = "none";
                doc_title.innerHTML = setTitleInputID.value;
            }
        })
    </script>

</body>

</html>