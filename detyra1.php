<?php

if(!file_exists($argv[1])){
    die ("File doesn't exist");
}

foreach (explode("\n", file_get_contents($argv[1])) as $row) {

    if (empty($row)) break;
    $row = json_decode($row, true);

    $binResults = file_get_contents('https://lookup.binlist.net/' .$row["bin"]);
    if (!$binResults)
        die('error!');
    $r = json_decode($binResults);
    $isEu = isEu($r->country->alpha2);

    $rate = @json_decode(file_get_contents('https://api.exchangeratesapi.io/latest'), true)['rates'][$row["currency"]];
    if ($row["currency"] == 'EUR' or $rate == 0) {
        $amntFixed = $row["amount"];
    }
    if ($row["currency"] != 'EUR' or $rate > 0) {
        $amntFixed =$row["amount"] / $rate;
    }

    echo $amntFixed * ($isEu == 'yes' ? 0.01 : 0.02);
    print "\n";
}

function isEu($c) {
    $result = false;
    switch($c) {
        case 'AT':
        case 'BE':
        case 'BG':
        case 'CY':
        case 'CZ':
        case 'DE':
        case 'DK':
        case 'EE':
        case 'ES':
        case 'FI':
        case 'FR':
        case 'GR':
        case 'HR':
        case 'HU':
        case 'IE':
        case 'IT':
        case 'LT':
        case 'LU':
        case 'LV':
        case 'MT':
        case 'NL':
        case 'PO':
        case 'PT':
        case 'RO':
        case 'SE':
        case 'SI':
        case 'SK':
            $result = 'yes';
            return $result;
        default:
            $result = 'no';
    }
    return $result;
}
?>