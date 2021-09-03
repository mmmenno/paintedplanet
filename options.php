<?

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
