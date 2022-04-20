<?php

if(isset($_GET['id'])){
        include('bd.php');
        $id = $_GET['id'];
        $sql = "SELECT * FROM links WHERE newLink = '$id'";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo $row['longLink'];
            header('Location: '.$row['longLink']);
        }  
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
            <form method="post" action="link.php">
                <div class="form-group">
                    <label for="exampleInputEmail1">Lien à raccourcir</label>
                    <input name="link" type="text" class="form-control" placeholder="https://google.fr">
                </div>
                <button type="submit" class="btn btn-primary">Raccourcir</button>
            </form>          
        </div>
    </div>
</body>
</html>