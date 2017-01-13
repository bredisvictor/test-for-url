<?php 

    session_start();
    
    $tournaments = $_SESSION['tournaments'];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>API search Tournaments</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> 
    <script src="../main.js"></script>
</head>
<body>
    
    <header>
       <div class="logoCover">
            <div class="mainLogo">
                <div class="logoImage"></div>
            </div>
        </div>
        <div class="mainHeader">Tournaments</div>
        <div class="mainImage"></div>
    </header>
    <section class="body">
       <div class="insider">
            <span class="eventsSpan">Events</span>
            <div class="eventsCover"><!--
               <?php 
            
            foreach($tournaments as &$val){ ?>
               
                --><div class="event">
                    <span class="eventImage"></span>
                    <span class="eventName"><?php echo $val; ?></span>
                </div><!--
                
                <?php } ?>
            --></div>
       </div>
    </section>
</body>
</html>
