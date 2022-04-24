<?php

    include('bd.php');
    include('phpqrcode/qrlib.php');

    
    
    $ip =  $_SERVER['REMOTE_ADDR'];
    $errorMsg = false;

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $sql = "SELECT * FROM links WHERE newLink = '$id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                header('Location: '.$row['longLink']);
                $val = $row['views']+1;
                $sql = "UPDATE links SET views = $val WHERE newLink = '$id'";
                $result = $conn->query($sql);
            }
        }else{
            $errorMsg = true;
        }
    }
    if(isset($_POST['link'])){
        $errorMsg = false;
        $n = 4;
        $link = $_POST['link'];
        $pattern= "~(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))~";
        $result =  preg_match($pattern, $link);
        if($result){
            $codeContents = $link;    
            $fileName = '1.'.md5($codeContents).'.png';;
            $pngAbsoluteFilePath ="qr/".$fileName;
            $urlRelativeFilePath = $fileName;
            QRcode::png($codeContents, $pngAbsoluteFilePath);   

            $randId = getRandomString($n);
            $sql = "INSERT INTO links (longLink, newLink, ip, views, qr) VALUES ('$link', '$randId', '$ip', 0, '$pngAbsoluteFilePath')";
            $conn->query($sql);
    
            $newLink = "";
            $sql = "SELECT * from links WHERE newLink = '$randId'";
            $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $newLink =$row['newLink'];
                }  
        }else{
            $errorMsg = true;
        }
      
    }

    if(isset($_GET['delete'])){
        delete($_GET['delete']);
    }

    function delete($id){
        include('bd.php');
        $sql = "DELETE from links WHERE id = '$id' ";
        $conn->query($sql);
        header('Location: index.php');
    }


    function getRandomString($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
        }
        unset($_POST);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
</head>
<body>
    <div class="app">
        <div class="container">
            <nav class="navbar">
                    <img src="img/img.png" />
                    <div class="left-btn">
                        <a href="#raccourcir">Raccourcir un lien</a>
                        <a href="#historique">Historique de mes liens</a>
                    </div>
            </nav>
            <div class="top" id="raccourcir">
                <div class="form-short">
                    <p class="title-top">Raccourcisseur d'URL</p>

                    <form action="#" method="post" class="form">
                        <div class="flex">
                            <input type="text" class="link" name="link" placeholder="Entrez une URL">
                            <?php
                            echo $errorMsg;
                                if($errorMsg){
                                    echo '
                                        <div class="error">
                                            <p>Ce lien est incorrect    </p>
                                        </div>    
                                    ';
                                }
                            ?>
                        </div>
                        <input type="submit" class="submit-link" value="Raccourcir">
                    </form>
                </div>
                <div class="left-icons">
                    <img src="img/landing.png" />
                </div>
            </div>

            <div class="bottom" id="historique">
                <div class="title">Historique de vos liens</div>
                <div class="links-list">

                <?php 
                $sql = "SELECT * FROM links WHERE ip = '$ip' ORDER BY id DESC ";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $link = $_SERVER['SERVER_NAME'].'?id='.$row['newLink'];
                        echo '
                        <div class="link-info">
                            <div class="links">
                                <div class="buttons">
                                        <div class="qr-show"id="'.$row['id'].'" style="display:none">
                                            <img src="'.$row['qr'].'" />
                                         </div>
                                        <div class="views">
                                            <p>'.$row['views'].'</p>
                                            <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                            <path d="M160 256C160 185.3 217.3 128 288 128C358.7 128 416 185.3 416 256C416 326.7 358.7 384 288 384C217.3 384 160 326.7 160 256zM288 336C332.2 336 368 300.2 368 256C368 211.8 332.2 176 288 176C287.3 176 286.7 176 285.1 176C287.3 181.1 288 186.5 288 192C288 227.3 259.3 256 224 256C218.5 256 213.1 255.3 208 253.1C208 254.7 208 255.3 208 255.1C208 300.2 243.8 336 288 336L288 336zM95.42 112.6C142.5 68.84 207.2 32 288 32C368.8 32 433.5 68.84 480.6 112.6C527.4 156 558.7 207.1 573.5 243.7C576.8 251.6 576.8 260.4 573.5 268.3C558.7 304 527.4 355.1 480.6 399.4C433.5 443.2 368.8 480 288 480C207.2 480 142.5 443.2 95.42 399.4C48.62 355.1 17.34 304 2.461 268.3C-.8205 260.4-.8205 251.6 2.461 243.7C17.34 207.1 48.62 156 95.42 112.6V112.6zM288 80C222.8 80 169.2 109.6 128.1 147.7C89.6 183.5 63.02 225.1 49.44 256C63.02 286 89.6 328.5 128.1 364.3C169.2 402.4 222.8 432 288 432C353.2 432 406.8 402.4 447.9 364.3C486.4 328.5 512.1 286 526.6 256C512.1 225.1 486.4 183.5 447.9 147.7C406.8 109.6 353.2 80 288 80V80z"/></svg>
                                        </div>
                                        <div class="copy" onClick="copy(`'.$link.'`)">
                                            <svg fill="white" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                <path d="M384 96L384 0h-112c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48H464c26.51 0 48-21.49 48-48V128h-95.1C398.4 128 384 113.6 384 96zM416 0v96h96L416 0zM192 352V128h-144c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48h192c26.51 0 48-21.49 48-48L288 416h-32C220.7 416 192 387.3 192 352z"/></svg>
                                        </div>
                                        <div class="qr">
                                            <svg fill="white" onClick="showQr('.$row['id'].')" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                            <path d="M144 32C170.5 32 192 53.49 192 80V176C192 202.5 170.5 224 144 224H48C21.49 224 0 202.5 0 176V80C0 53.49 21.49 32 48 32H144zM128 96H64V160H128V96zM144 288C170.5 288 192 309.5 192 336V432C192 458.5 170.5 480 144 480H48C21.49 480 0 458.5 0 432V336C0 309.5 21.49 288 48 288H144zM128 352H64V416H128V352zM256 80C256 53.49 277.5 32 304 32H400C426.5 32 448 53.49 448 80V176C448 202.5 426.5 224 400 224H304C277.5 224 256 202.5 256 176V80zM320 160H384V96H320V160zM352 448H384V480H352V448zM448 480H416V448H448V480zM416 288H448V416H352V384H320V480H256V288H352V320H416V288z"/></svg>
                                        </div>
                                        <div class="delete">
                                            <a href= "?delete='.$row['id'].'">
                                                <svg fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                <path d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM394.8 466.1C393.2 492.3 372.3 512 346.9 512H101.1C75.75 512 54.77 492.3 53.19 466.1L31.1 128H416L394.8 466.1z"/></svg>
                                            </a>
                                        </div>
                                </div>
                                <p>'.$_SERVER['SERVER_NAME'].'?id='.$row['newLink'].'</p>
                            </div>
                                <p class="old-link">'.$row['longLink'].'</p>
                        </div>
                        ';
                    }    
                } 
                ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


<script>
    function copy(text)
    {
        navigator.clipboard.writeText(text);
    }
    function showQr(id){
        $(`#${id}`).fadeToggle()
    }

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
