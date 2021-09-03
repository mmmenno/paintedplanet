<?php

ini_set('memory_limit', '1024M');

$sparql = "
SELECT ?i ?iLabel ?creatorLabel ?createdate ?img ?depicts ?depictsLabel ?coords WHERE {
  ?i wdt:P31 wd:Q3305213 .
  ?i wdt:P136 wd:Q191163 .
  ?i wdt:P180 ?depicts .
  OPTIONAL{
    ?i wdt:P170 ?creator .
  }
  OPTIONAL{
    ?i wdt:P571 ?createdate .
  }
  ?depicts wdt:P17 wd:" . $qcountry . " .
  ?depicts wdt:P625 ?coords .
  ?i wdt:P18 ?img .
  SERVICE wikibase:label { bd:serviceParam wikibase:language \"en,nl\". }
}
limit 2000
";

$endpoint = 'https://query.wikidata.org/sparql';

$json = getSparqlResults($endpoint,$sparql);
$data = json_decode($json,true);




//print_r($data);

//die;

$locations = array();

foreach ($data['results']['bindings'] as $k => $v) {

	$qid = str_replace("http://www.wikidata.org/entity/","",$v['depicts']['value']);

	if(!isset($locations[$qid])){

		$locations[$qid] = array(
			"coords" => $v['coords']['value'],
			"label" => $v['depictsLabel']['value']
		);

	}

	$paintingid = str_replace("http://www.wikidata.org/entity/","",$v['i']['value']);
	$locations[$qid]['paintings'][$paintingid] = array(
		"title" => $v['iLabel']['value'],
		"img" => $v['img']['value'],
		"maker" => $v['creatorLabel']['value'],
		"date" => substr($v['createdate']['value'],0,4)
	);

}

//print_r($locations);
//die;

$fc = array("type"=>"FeatureCollection", "features"=>array());

$beenthere = array();

foreach ($locations as $k => $v) {

	$loc = array("type"=>"Feature");
	$props = array(
		"wdid" => $k,
		"label" => $v['label'],
		"paintings" => $v['paintings'],
		"cnt" => count($v['paintings'])
	);
	
	
	$coords = str_replace(array("Point(",")"), "", $v['coords']);
	$latlon = explode(" ", $coords);
	$loc['geometry'] = array("type"=>"Point","coordinates"=>array((double)$latlon[0],(double)$latlon[1]));
	
	$loc['properties'] = $props;
	$fc['features'][] = $loc;

}

$json = json_encode($fc);

file_put_contents("geojson/" . $qcountry . '.geojson', $json);













