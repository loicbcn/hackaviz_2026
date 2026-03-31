<?php include("genere_classements.php"); 
$annees = [2013,2018,2021,2022,2023];
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Hackaviz 2026</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="lst_clst" data-layout="reel" data-gap="m" data-scroll="center">
        <?php foreach($annees as $an) : ?>

                <div class="item">
                <?php echo getclstfromyear($an); ?>
                </div>

        <?php endforeach; ?>
    </div>
</body>
</html>