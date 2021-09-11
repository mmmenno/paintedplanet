<?php

include("functions.php");


if(isset($_GET['country'])){
  $qcountry = $_GET['country'];
}else{
  $qcountry = "Q55";
}

if(isset($_GET['scape']) && $_GET['scape'] == "city"){
  $scape = "city";
  $otherscape = "land";
  $markercolor = "#5354ea";
}else{
  $scape = "land";
  $otherscape = "city";
  $markercolor = "#f60000";
}


$geojsonfile = __DIR__ . "/geojson-" . $scape . "/" . $qcountry . ".geojson";
if(!file_exists($geojsonfile) || isset($_GET['uncache'])){
  include("geojson.php");
}elseif (time()-filemtime($geojsonfile) > 60*60*24*7) {
  include("geojson.php");
}


include("options.php");


?><!DOCTYPE html>
<html>
<head>
  
<title>painted planet - <?= $countryname ?></title>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css" integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw==" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet.js" integrity="sha512-mNqn2Wg7tSToJhvHcqfzLMU6J4mkOImSPTxVZAdo+lcPlk+GhZmYgACEe0x35K7YzW1zJ7XyJV/TT1MrdXvMcA==" crossorigin=""></script>
  <link rel="stylesheet" href="styles.css" />

  <script defer data-domain="hicsuntleones.nl" src="https://plausible.io/js/plausible.js"></script>

  <style type="text/css">
    body{
      background-color: #000;
    }
  </style>
  
</head>
<body>


<div id="overlaycontent">
  <div id="bigpic"></div>
  <div id="bigpictxt"></div>
</div>


<div id="bigmap"></div>


<div id="legenda">
  <h1>The Painted Planet</h1>

  <p id="introline"><?= ucfirst($scape) ?>scapes from Wikidata, depicting things with coordinates, in ...</p>

  <form>
    <select name="country">
      <option value="Q55">choose country</option>
      <?php echo $options ?>
    </select>

    <input type="hidden" name="scape" value="<?= $scape ?>">
  </form>

  <p>Any paintings missing? Please read the <a href="howto.php">how-to add paintings to the map</a> section.</p>

  <p>Switch to <a href="?country=<?= $qcountry ?>&scape=<?= $otherscape ?>"><?= $otherscape ?>scapes</a>.</p>
  
</div>

<div id="location">
  <h1></h1>

  <div id="pics"></div>
  
</div>


<div id="smallscreen">
  <p>All data from Wikidata. Read more on the data used and how to add paintings to the map <a href="howto.php">here</a>.</p>
</div>



<div id="overlay"></div>






<script>
  $(document).ready(function() {

    $('form select').change(function(){
      $("form").submit();
    });

    createMap();
    refreshMap();

    $('#overlay').click(function(){
      $('#bigpic').empty();
      $('#bigpictxt').empty();
      $('#overlaycontent').hide();
      $('#overlay').hide();
    });
    $('#overlaycontent').click(function(){
      $('#bigpic').empty();
      $('#bigpictxt').empty();
      $('#overlaycontent').hide();
      $('#overlay').hide();
    })
  });

  function createMap(){
    center = [52.381016, 4.637126];
    zoomlevel = 6;
    
    map = L.map('bigmap', {
          center: center,
          zoom: zoomlevel,
          minZoom: 1,
          maxZoom: 19,
          scrollWheelZoom: true,
          zoomControl: false
      });

    L.control.zoom({
        position: 'bottomleft'
    }).addTo(map);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_labels_under/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
      subdomains: 'abcd',
      maxZoom: 19
    }).addTo(map);
  }

  function refreshMap(){

    $.ajax({
          type: 'GET',
          url: 'geojson-<?= $scape ?>/<?= $qcountry ?>.geojson',
          dataType: 'json',
          success: function(jsonData) {
            if (typeof streets !== 'undefined') {
              map.removeLayer(streets);
            }

            streets = L.geoJson(null, {
              pointToLayer: function (feature, latlng) {                    
                  return new L.CircleMarker(latlng, {
                      color: "<?= $markercolor ?>",
                      radius:4,
                      weight: 0,
                      opacity: 0.8,
                      fillOpacity: 0.8
                  });
              },
              style: function(feature) {
                return {
                    radius: getSize(feature.properties),
                    clickable: true
                };
              },
              onEachFeature: function(feature, layer) {
                layer.on({
                    click: whenClicked
                  });
                }
              }).addTo(map);

              streets.addData(jsonData).bringToFront();

              var totalPins = streets.getLayers().length;
              
              console.log(totalPins);

              if(totalPins > 0){
                map.fitBounds(streets.getBounds());
              }else{
                $('#introline').html('No results in selected country');
              }
              //$('#straatinfo').html('');
          },
          error: function() {
              console.log('Error loading data');
          }
      });
  }

  function getSize(props) {

    var j = props['cnt'];
    return  j > 4 ? 10 :
            j > 3 ? 9 :
            j > 2 ? 8 :
            j > 1 ? 7 :
            j > 0 ? 6 :
            6;
  }

function whenClicked(){
  $("#location").show();

  var props = $(this)[0].feature.properties;
  console.log(props);
  
  $("#location h1").html('<a target="_blank" href="http://www.wikidata.org/entity/' + props['wdid'] + '">' + props['label'] + '</a>');

  $("#pics").empty();

  $.each(props.paintings, function( key, value ) {

    var pic = $("<img />").attr("src",value['img'] + '?width=400');
    pic.click(function(){
      console.log(value);
      if($(window).width() < 900) {
        window.open(value['img']);
        return false;
      }else{
        $("#bigpic").empty();
        var bigpic = $("<img />").attr("src",value['img'] + '?width=1000');
        //bigpic.css("height","50%");
        $("#bigpic").append(bigpic);
        var bigpictxt = $("<p></p>").text(value['maker'] + ", " + value['title'] + ", " + value['date']);
        $("#bigpictxt").append(bigpictxt);
        $("#overlay").show();
        $("#overlaycontent").show();
      }
    });

    $("#pics").append(pic);

    var link = $("<a></a>").html('<img src="https://upload.wikimedia.org/wikipedia/commons/f/ff/Wikidata-logo.svg" style="width:24px; margin-right:6px;" />');
    link.attr("href","http://www.wikidata.org/entity/" + key);
    //link.attr("target","_blank");

    var txt = $("<p></p>").text(value['title']);

    txt.prepend(link);

    $("#pics").append(txt);

  });


    
    
}

</script>



</body>
</html>
