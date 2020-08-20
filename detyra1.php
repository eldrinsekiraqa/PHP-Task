<?php

if(!file_exists($argv[1])){
    die ("File doesn't exist");
}

$rows = explode("\n", file_get_contents($argv[1]));

if(count($rows)==0){
    die("Now rows to check");
}

foreach ($rows as $row) {

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

    echo $amntFixed * ($isEu ? 0.01 : 0.02);
    print "\n";
}

function isEu($c) {
    $currencies  = ['AT','BE','BG','CY','CZ','DE','DK','EE','ES','FI','FR','FR','GR','HR','HU','IE','IT','LT','LU','LV','MT','NL','PO','PT','RO','SE','SI','SK'];
   return in_array($c, $currencies);
        
    }

?>