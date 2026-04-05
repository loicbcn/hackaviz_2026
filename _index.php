<?php include("genere_classements.php"); 
$annees = [2013,2018,2021,2022,2023];
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Hackaviz 2026</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div id="lk_legend">🛈 Légende & informations 🛈</div>
    <header>
        <h1>Évolution de la satisfaction à l'égard de la vie ressentie dans divers pays européens</h1>
        <div id="headeryears" class="years" data-layout="switcher" data-gap="xl" data-scroll="center">
            <?php for($an =$annees[0]; $an <= $annees[count($annees) - 1]; $an++) {
                if(in_array($an, $annees)) { ?>
                    <div class="item"><h2><?php echo $an; ?></h2></div>
            <?php } else { ?>
                <div class="item empty"><h2><?php echo $an; ?></h2></div>
            <?php } 
            } ?>
        </div>
    </header>
    <div class="container" id="bigchart">
        <div id="contyears" class="" data-layout="switcher" data-gap="xl" data-scroll="center">
        <?php //foreach($annees as $an) :?>
        <?php for($an =$annees[0]; $an <= $annees[count($annees) - 1]; $an++) {
                if(in_array($an, $annees)) {
        ?>
                <div class="item hidden" id="item_<?php echo $an; ?>">&nbsp;
                    <h2><?php echo $an; ?></h2>
                    <?php echo getclstfromyear($an); ?>
                    <!--<h2><?php echo $an; ?></h2>-->
                </div>
        <?php } else { ?>
                <div class="item empty hidden" id="item_<?php echo $an; ?>">&nbsp;
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
<footer>
Pour conclure, aucun indicateur ne semble expliquer à lui seul les raisons de la satisfaction à l'égard de la vie ressentie dans ces 15 pays.<br>
Il semble apparaît que l'argent que dédient les états aux prestations sociales jouent un rôle conséquent.<br>
La Finlande et l'Autriche, qui monopolisent les 2 premières places ont en commun des dettes et impôts élevées et de gros budgets prestations sociales.
            <div style="text-align:right; margin: 10px 5px 5px;">Pour le hackaviz 2026 de <a href="https://toulouse-dataviz.fr/">Toulouse DataViz</a></div>
</footer>
<div id="modal" class="modal" style="height: initial; max-height: 95%; max-width: 95%; overflow-y: auto; overflow-x: hidden;">
</div>
<div id="modallegende" class="modal" style="height: initial; max-height: 95%; max-width: 95%;">
    <div id="legende">
        <h2>Légende & informations</h2>
        <div data-layout="stack" data-gap="m">
            <div class="item">
                <div data-layout="cluster" data-align="center" data-gap="m">
                    <div class="item">
                <?php echo getsquaresvg(4.5, '#2fdb04', 10, 10, 'white', 1); ?>
                    </div>
                    <div class="item">Satisfaction à l'égard de la vie (de 0 à 10)</div>
                </div>          
            </div>
            <div class="item">
                <div data-layout="cluster" data-align="center" data-gap="m">
                    <div class="item">
                <?php echo getcirclesvg('rgba(0,0,0,0.5)', 40, $stroke = 'none', $stroke_width = 0, $text='..', $textfill = 'black'); ?>
                    </div>
                    <div class="item">Classement du pays pour une année donnée en fonction de sa dette par habitant</div>
                </div>
            </div>
            <div class="item">
                <div data-layout="cluster" data-align="center" data-gap="m">
                    <div class="item">
                        <?php echo getcirclesvg('rgba(255,0,0,0.5)', 40, $stroke = 'none', $stroke_width = 0, $text='..', $textfill = 'black'); ?>
                    </div>
                    <div class="item">Classement du pays pour une année donnée en fonction de ses impôts par habitant (impôts sur le revenu + taxe sur les produits)</div>
                </div>
            </div>
            <div class="item">
                <div data-layout="cluster" data-align="center" data-gap="m">
                    <div class="item">
                    <?php echo getcirclesvg('rgba(0,0,255,0.5)', 40, $stroke = 'none', $stroke_width = 0, $text='..', $textfill = 'black'); ?>
                    </div>
                    <div class="item">Classement du pays pour une année donnée en fonction du montant des prestations sociales par habitant</div>

                </div>
            </div>
            <div class="item">
                !!! les chiffres dans les cercles indiquent un classement, la surface des cercles indique une quantité, visible en infobulle au survol du cercle.
            </div>
        </div>
        <div style="margin-top:20px;font-weight:bold;">
            Les données présentées sont celles des années pour lesquelles la variable "Satisfaction à l'égard de la vie" étaient disponibles<br>
            Les pays sont cliquables, ce qui donne accès à plus d'informations
        </div>
    </div>
</div>
<script>
    const annees = <?php echo json_encode($annees); ?>;
    const data_detail = <?php echo json_encode($data_detail); ?>;
    const data_clst = <?php echo json_encode($data); ?>;
</script>
<script src="assets/jquery-3.7.1.min.js"></script>
<script src="assets/highcharts.js"></script>
<script src="assets/jquery.modal.min.js"></script>
<script src="assets/script.js"></script>
</body>
</html>