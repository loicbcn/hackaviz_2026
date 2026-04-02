<?php include("genere_classements.php"); 
$annees = [2013,2018,2021,2022,2023];
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Hackaviz 2026</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div class="container">
        <div id="contyears" class="" data-layout="reel" data-gap="xl" data-scroll="center">
        <?php //foreach($annees as $an) :?>
        <?php for($an =$annees[0]; $an <= $annees[count($annees) - 1]; $an++) {
                if(in_array($an, $annees)) {
        ?>
                <div class="item" id="item_<?php echo $an; ?>">
                    <h2><?php echo $an; ?></h2>
                    <?php echo getclstfromyear($an); ?>
                    <h2><?php echo $an; ?></h2>
                </div>
        <?php } else { ?>
                <div class="item empty" id="item_<?php echo $an; ?>">
                    <h2><?php echo $an; ?></h2> 
                </div>
        <?php }
        } ?>
        <?php //endforeach; ?>
        </div>
    </div>
    <!-- <div class="lst_clst" data-layout="reel" data-gap="m" data-scroll="center">
        <?php foreach($annees as $an) : break; ?>

                <div class="item">
                <?php echo getclstfromyear($an); ?>
                </div>

        <?php endforeach; ?>
    </div> -->
<script>
    const annees = <?php echo json_encode($annees); ?>;
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
			  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
			  crossorigin="anonymous"></script>
<script src="assets/script.js"></script>
</body>
</html>