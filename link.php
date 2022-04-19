<?php
$n = 10;
$newLink = "";
$link = $_POST['link'];
include('bd.php');

    if(isset($link)){
        $randId = getRandomString($n);
        $sql = "INSERT INTO links (longLink, newLink) VALUES ('$link', '$randId')";
        $conn->query($sql);
        $sql = "SELECT * from links WHERE newLink = '$randId'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $newLink = "localhost/url-short?id=".$row['newLink'];
        }
    }else{
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
        <div class="row">
            <div class="col">
                <input name="link" id="link" type="text" class="form-control" value="<?=$newLink?>" readonly>
            </div>
            <div class="col">
                <button onClick="copy('link')" type="button" class="btn btn-success">Copier</button>
                <a href="http://<?=$newLink?>"><button type="button" class="btn btn-primary">Acceder</button></a>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    function copy(id)
    {
    var r = document.createRange();
    r.selectNode(document.getElementById(id));
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(r);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
    }
</script>
