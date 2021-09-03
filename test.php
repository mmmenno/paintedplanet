<?

//include("functions.php");

$sparql = "
SELECT ?country ?countryLabel (COUNT(?i) AS ?nr) WHERE {
  ?i wdt:P31 wd:Q3305213 .
  ?i wdt:P136 wd:Q191163 .
  ?i wdt:P180 ?beeldtaf .
  ?beeldtaf wdt:P625 ?coords .
  ?beeldtaf wdt:P17 ?country .
  MINUS { ?country wdt:P576 ?end }
  ?i wdt:P18 ?afb .
  SERVICE wikibase:label { bd:serviceParam wikibase:language \"en\". }
}
GROUP BY ?country ?countryLabel
ORDER BY ASC(?countryLabel)
";

$endpoint = 'https://query.wikidata.org/sparql';

$json = getSparqlResults($endpoint,$sparql);
$data = json_decode($json,true);

print_r($data);

die;

$options = "";

foreach ($data['results']['bindings'] as $k => $v) {

    $qnr = str_replace("http://www.wikidata.org/entity/","",$v['country']['value']);

    if($qcountry==$qnr){
        $options .= "<option selected=\"s\" value=\"" . $qnr . "\">";
        $options .= $v['countryLabel']['value'] . " (" . $v['nr']['value'] . ")</option>\n";
        $countryname = $v['countryLabel']['value'];
    }else{
        $options .= "<option value=\"" . $qnr . "\">";
        $options .= $v['countryLabel']['value'] . " (" . $v['nr']['value'] . ")</option>\n";
    }

}


function getSparqlResults($endpoint,$query){


    // params
    $url = $endpoint . '?query=' . urlencode($query) . "&format=json";
    /*

    $urlhash = hash("md5",$url);
    $datafile = __DIR__ . "/data/" . $urlhash . ".json";
    $maxcachetime = 60*60*24*7;

    // get cached data if recent
    if(file_exists($datafile)){
        $mtime = filemtime($datafile);
        $timediff = time() - $mtime;
        if($timediff < $maxcachetime){
            $json = file_get_contents($datafile);
            return $json;
        }
    }
    */

    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch,CURLOPT_USERAGENT,'RotterdamsPubliek');
    $headers = [
        'Accept: application/sparql-results+json'
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec ($ch);
    curl_close ($ch);

    //var_dump($response);

    /*
    // if valid results were returned, save file
    $data = json_decode($response,true);
    if(isset($data['results'])){
        file_put_contents($datafile, $response);
    }
    */

    
    
    return $response;
}