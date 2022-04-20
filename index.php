<?php

include('bd.php');
$ip =  $_SERVER['REMOTE_ADDR'];
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "SELECT * FROM links WHERE newLink = '$id'";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            header('Location: '.$row['longLink']);
        }  
    }

    if(isset($_POST['link'])){
        $n = 6;
        $link = $_POST['link'];
        $randId = getRandomString($n);
        $sql = "INSERT INTO links (longLink, newLink, ip) VALUES ('$link', '$randId', '$ip')";
        $conn->query($sql);

        $newLink = "";
        $sql = "SELECT * from links WHERE newLink = '$randId'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $newLink =$row['newLink'];
        }
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>URL Shortener</h1>
        <div class="d-flex flex-column align-middle">
            <form method="post" action="#">
                <div class="form-group">
                    <label for="exampleInputEmail1">Lien à raccourcir</label>
                    <input name="link" type="text" class="form-control" placeholder="https://google.fr">
                </div>
                <button type="submit" class="btn btn-primary">Raccourcir</button>
            </form>          
        </div>

        <?php
            if(isset($_POST['link'])){
                echo '
                <div class="row mt-5">
                    <div class="col-md-9">
                        <input name="link" id="link" type="text" class="form-control" value=http://localhost/url-short?id='.$newLink.' readonly>
                    </div>
                    <div class="col">
                        <button onClick="copy(`link`)"x type="button" class="btn btn-success">Copier</button>
                        <a target="_blank"  href=http://localhost/url-short?id='.$newLink.'><button type="button" class="btn btn-primary">Acceder</button></a>
                    </div>
                </div>    
                ';
            }
        ?>

        <?php 
            $sql = "SELECT * FROM links WHERE ip = '$ip'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<h2>Historique</h2>";
                while ($row = $result->fetch_assoc()) {
                    echo '
                        <div class="col-12 d-flex">
                            <li class="list-group-item">localhost/url-short?id='.$row['newLink'].'                             
                            <button type="button" class="btn btn-danger align-self-end">Danger</button>
                            </li>    
                        </div>
                    ';
                }    
              } 
        ?>
    </div>
</body>
</html>


<script>
    function copy(id)
    {
        console.log(id)
    var r = document.createRange();
    r.selectNode(id);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(r);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
    }
</script>
