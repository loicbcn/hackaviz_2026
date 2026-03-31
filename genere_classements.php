

<?php
$data = json_decode(file_get_contents("data/transfo/classements.json"), true);


function getclstfromyear($year) {
    global $data;
    $result = [];
    foreach($data as $clst){
        if($clst["annee"] == $year){
            $bienetre = number_format($clst["valeur"], 2, ',', ' ');
            $dette = $clst["dette_tx_pib"] ? number_format($clst["dette_tx_pib"], 2, ',', ' ') : 'N/A';
            $euros_per_hab = number_format($clst["euros_per_hab"], 0, ',', ' ');
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
