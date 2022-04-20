<?php

    include('bd.php');
    $ip =  $_SERVER['REMOTE_ADDR'];
    $errorMsg = false;
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "SELECT * FROM links WHERE newLink = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                header('Location: '.$row['longLink']);
            }
        }else{
            $errorMsg = true;
        }
    }
    if(isset($_POST['link'])){
        $errorMsg = false;
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

        <?php
            if($errorMsg){
                echo "
                <div class='alert alert-danger'  role='alert'>
                    Ce lien est incorrect ou a été supprimé
                </div>              
                ";
            }
        ?>
        <div class="row">
            <div class="d-flex col-9 flex-column align-middle">
                <form method="post" action="#">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Lien à raccourcir</label>
                        <input name="link" type="text" class="form-control" placeholder="https://google.fr">
                    </div>
                    <button type="submit" class="btn btn-primary">Raccourcir</button>
                </form>    
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
            </div>
            <div class="col-3">
            <?php 
                $sql = "SELECT * FROM links WHERE ip = '$ip'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<h2>Historique</h2>";
                    while ($row = $result->fetch_assoc()) {
                        echo '
                            <div class=" d-flex justify-content-center align-items-center">
                                <li class="list-group-item w-100 ">localhost/url-short?id='.$row['newLink'].'                             
                                <div class="small">'.$row['longLink'].'</div>
                                </li>    
                                <a href= "?delete='.$row['id'].'" /><button type="button" class="btn btn-danger ">X</button></a>
                            </div>
                        ';
                    }    
                } 
                ?>
            </div>
        </div>
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
