

<?php
$data = json_decode(file_get_contents("data/transfo/classements.json"), true);

function getclstfromyear($year) { 
    global $data;
    $result = [];
    foreach($data as $clst){
        if($clst["annee"] != $year){
            continue;
        }
        $bienetre = number_format($clst["satisfaction"], 2, ',', ' ');
        $dette = $clst["dette_tx_pib"] ? number_format($clst["dette_tx_pib"], 2, ',', ' ') : 'N/A';
        $euros_per_hab = number_format($clst["depense_par_hab"], 0, ',', ' ');
        $pays = $clst["pays"];
        $img = "<img class='drapeau' src='data/drapeaux/{$clst['cde_pays']}.svg' alt='{$pays}'>";
        $result[] = '<div class="card"><div class="card-title"><h3>'. $img . $pays . $img .'</h3></div>
        <div class="card-body">
            <div class="satisfaction">'. getsquaresvg(round($clst["satisfaction"]), '#2fdb04', 10, 10, 'white', 1) .'
            <div class="card-row"><span class="card-label">Bien être</span><span class="card-value">'. $bienetre .'</span></div>
            <div class="card-row"><span class="card-label">€/habitant</span><span class="card-value">'. $euros_per_hab .'</span></div>
            <div class="card-row"><span class="card-label">Dette</span><span class="card-value">'. $dette .'</span></div>
        </div></div></div>';
    }
    return implode("", $result);
}

function getsquaresvg($number, $color, $width = 20, $height = 20, $stroke = 'none', $stroke_width = 0) {
    $svgs = [];
    for($i = 0; $i < $number; $i++){
        $svgs[] = '<svg width="' . $width . '" height="' . $height . '"><rect width="' . $width . '" height="' . $height . '" fill="' . $color . '" stroke="' . $stroke . '" stroke-width="' . $stroke_width . '"/></svg>';
    }
    return implode("", $svgs);
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
