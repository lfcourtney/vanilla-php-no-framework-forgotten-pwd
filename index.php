<?php 
    include_once 'header.php'
?>

    <h1 id="index-text">Welcome, <?php if(isset($_SESSION['usersId'])){
        echo explode(" ", $_SESSION['usersName'])[0];
    }else{
        echo 'Guest';
    } 
    ?> </h1>
    

<?php 
    include_once 'footer.php'
?>