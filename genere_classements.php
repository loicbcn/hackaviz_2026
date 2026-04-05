<?php
$data = json_decode(file_get_contents("data/transfo/classements.json"), true);
$data_detail = json_decode(file_get_contents("data/transfo/alldatas.json"), true);

$cls_dette_hab = json_decode(file_get_contents("data/transfo/clst_dette_par_hab.json"), true);
$cls_protecsocial_hab = json_decode(file_get_contents("data/transfo/clst_protection_sociale.json"), true);
$cls_impots_hab = json_decode(file_get_contents("data/transfo/clst_impots_par_hab.json"), true);

function getclstfromyear($year) { 
    global $data;
    $result = [];
    foreach($data as $clst){
        if($clst["annee"] != $year){
            continue;
        }
        $classt = $clst["clst"]<10 ? '0'.$clst["clst"] : $clst["clst"];
        $bienetre = number_format($clst["satisfaction"], 2, '.', ' ');
        $dette = $clst["dette_tx_pib"] ? number_format($clst["dette_tx_pib"], 2, '.', ' ') : 'N/A';
        $dette_hab = $clst["dette_par_hab"] ? number_format($clst["dette_par_hab"], 0, '.', ' ') : 'N/A';
        $euros_per_hab = number_format($clst["depense_par_hab"], 0, '.', ' ');
        $impots_per_hab = number_format($clst["impot_hab"], 0, '.', ' ');
        $pays = $clst["pays"];
        $img = "<img class='drapeau' src='data/drapeaux/{$clst['cde_pays']}.svg' alt='{$pays}'>";
        $result[] = '<div id="card_'. $clst["cde_pays"] .'_'. $clst["annee"] .'" class="card">
                    <span data-year="'. $clst["annee"] .'" class="clst">'. $classt .'</span>
                    <div class="satisfaction"><strong id="satis_'. $clst["cde_pays"] .'_'. $clst["annee"] .'">'. $bienetre .'</strong>&nbsp'. getsquaresvg($clst["satisfaction"], '#2fdb04', 10, 10, 'white', 1) .'</div>
        <!--<div data-layout="repel" style="margin-top:5px;">-->
            <div class="card-title item">
                <h3>'. $img . $pays .'</h3>
            </div>
            <!--<div class="item">
                <div class="card-row">'. getcirclesvg("rgba(0,0,0,0.8)", $dette, 'none', 0 ) .'</div>    
            </div>
        </div>-->
        <div class="trois_cols">
            <div class="elem"><span title="Dette: '. $dette_hab .' Euros/habitant ... '. $dette .' % du PIB">'. getclstdette($clst['cde_pays'], $clst['annee']) . '</span></div>
            <div class="elem"><span title="Impôts: '. $impots_per_hab .' Euros/habitant">'. getclstimpots($clst['cde_pays'], $clst['annee']) . '</span></div>
            <div class="elem">'. getclstprotec($clst['cde_pays'], $clst['annee']) . '</div>
        </div>
        </div>';
    }
    return implode("", $result);
}

function getclstdette($cdepays, $annee){
    global $cls_dette_hab;
    
    foreach($cls_dette_hab as $c){
        if($c["cde_pays"] == $cdepays && $c["annee"] == $annee){
            return getcirclesvg('rgba(0,0,0,0.5)', 4*$c['dette_par_hab']/1000, $stroke = 'none', $stroke_width = 0, $c["clst"],'black');
        }
    }
}

function getclstimpots($cdepays, $annee){
    global $cls_impots_hab;
    
    foreach($cls_impots_hab as $c){
        if($c["cde_pays"] == $cdepays && $c["annee"] == $annee){
            return getcirclesvg('rgba(255,0,0,0.5)', 4*$c['impot_hab']/1000, $stroke = 'none', $stroke_width = 0, $c["clst"],'black');
        }
    }
}

function getclstprotec($cdepays, $annee){
    global $cls_protecsocial_hab;
    
    foreach($cls_protecsocial_hab as $c){
        if($c["cde_pays"] == $cdepays && $c["annee"] == $annee){
            $dep = number_format($c["depense_par_hab"], 0, '.', ' ');
            return '<span title="Protec. sociale: '. $dep .' Euros/habitant">'.getcirclesvg('rgba(0,0,255,0.5)', 4*$c['depense_par_hab']/1000, $stroke = 'none', $stroke_width = 0, $c["clst"],'black').'</span>';
        }
    }
}


function getsquaresvg($number, $color, $width = 20, $height = 20, $stroke = 'none', $stroke_width = 0) {
    $svgs = [];
    $partie_entiere = floor($number);
    if($number - $partie_entiere > 0){
        $partial_width = ($number - $partie_entiere) * $width;
        $svgs[] = '<svg width="' . $partial_width . '" height="' . $height . '"><rect width="' . $partial_width . '" height="' . $height . '" fill="' . $color . '" stroke="' . $stroke . '" stroke-width="' . $stroke_width . '"/></svg>';
    }

    for($i = 0; $i < $partie_entiere; $i++){
        $svgs[] = '<svg width="' . $width . '" height="' . $height . '"><rect width="' . $width . '" height="' . $height . '" fill="' . $color . '" stroke="' . $stroke . '" stroke-width="' . $stroke_width . '"/></svg>';
    }

    return implode("", $svgs);
}

function getcirclesvg($color, $diameter, $stroke = 'none', $stroke_width = 0, $text=null, $textfill = 'white') {
    $text_svg = $text ? $text : round($diameter);
    //$diameter = $diameter*50/100;
    $diam = 5*sqrt($diameter / pi()) * 2;
    $svg= '<svg width="' . $diam . '" height="' . $diam . '">
        <circle cx="' . ($diam / 2) . '" cy="' . ($diam / 2) . '" r="' . ($diam / 2) . '" fill="' . $color . '" stroke="' . $stroke . '" stroke-width="' . $stroke_width . '"/>
         <text font-weight="bold" font-family="consolas, monospace" x="50%" y="50%" text-anchor="middle" fill="' . $textfill . '" dy=".3em">'. $text_svg .'</text>
        </svg>';
    

    return $svg;
}


function getclstfromyeartable($year) {
    global $data;
    $result = [];
    foreach($data as $clst){
        if($clst["annee"] == $year){
            $bienetre = number_format($clst["satisfaction"], 2, ',', ' ');
            $dette = $clst["dette_tx_pib"] ? number_format($clst["dette_tx_pib"], 2, ',', ' ') : 'N/A';
            $euros_per_hab = number_format($clst["depense_par_hab"], 0, ',', ' ');
            $pays = $clst["cde_pays"] == 'SVK' ? 'Slovaquie' : $clst["pays"];
            $img = "<img class='drapeau' src='data/drapeaux/{$clst['cde_pays']}.svg' alt='{$pays}'>";

            $result[] = "<tr>
                <td>{$clst['clst']}</td>
                <td>{$img}{$pays}</td>
                <td class='num'>{$bienetre}</td>
                <td class='num'>{$euros_per_hab}</td>
                <td class='num'>{$dette}</td>
            </tr>";
        }

    }
    return '<table class="clst"><thead><tr><th>Pos</th><th>Pays</th><th>Bien être</th><th>€/habitant</th><th>Dette</th></tr></thead><tbody>'. 
            implode("", $result).'</tbody></table>'; 
}
?>
