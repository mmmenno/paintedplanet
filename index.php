<?php

include("functions.php");


if(isset($_GET['country'])){
  $qcountry = $_GET['country'];
}else{
  $qcountry = "Q55";
}

if(!file_exists(__DIR__ . "/geojson/" . $qcountry . ".geojson") || isset($_GET['uncache'])){
  include("geojson.php");
}


include("options.php");


?><!DOCTYPE html>
<html>
<head>
  
<title>Landscapism in <?= $countrynaam ?></title>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css" integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw==" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet.js" integrity="sha512-mNqn2Wg7tSToJhvHcqfzLMU6J4mkOImSPTxVZAdo+lcPlk+GhZmYgACEe0x35K7YzW1zJ7XyJV/TT1MrdXvMcA==" crossorigin=""></script>
  <link rel="stylesheet" href="styles.css" />

  
</head>
<body>


<div id="overlaycontent">
  <div id="bigpic"></div>
  <div id="bigpictxt"></div>
</div>


<div id="bigmap"></div>


<div id="legenda">
  <h1>The Painted Planet</h1>

  <p>Landscapes from Wikidata, depicting things with coordinates in ...</p>

  <form>
    <select name="country">
      <?php echo $options ?>
    </select>
  </form>

  <p>Missing paintings? Pleas read our <a href="">how-to add paintings to the map</a> section.</p>
  
</div>

<div id="location">
  <h1></h1>

  <div id="pics"></div>
  
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
    zoomlevel = 16;
    
    map = L.map('bigmap', {
          center: center,
          zoom: zoomlevel,
          minZoom: 1,
          maxZoom: 20,
          scrollWheelZoom: true,
          zoomControl: false
      });

    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

    L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png', {
  maxZoom: 20,
  attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
  }).addTo(map);
  }

  function refreshMap(){

    $.ajax({
          type: 'GET',
          url: 'geojson/<?= $qcountry ?>.geojson',
          dataType: 'json',
          success: function(jsonData) {
            if (typeof streets !== 'undefined') {
              map.removeLayer(streets);
            }

            streets = L.geoJson(null, {
              pointToLayer: function (feature, latlng) {                    
                  return new L.CircleMarker(latlng, {
                      color: "#FC2211",
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
          
              map.fitBounds(streets.getBounds());
              //$('#straatinfo').html('');
          },
          error: function() {
              console.log('Error loading data');
          }
      });
  }

  function getSize(props) {

    var j = props['cnt'];
    return  j > 4 ? 8 :
            j > 3 ? 7 :
            j > 2 ? 6 :
            j > 1 ? 5 :
            j > 0 ? 4 :
            4;
  }

function whenClicked(){
  $("#location").show();

  var props = $(this)[0].feature.properties;
  console.log(props);
  
  $("#location h1").html('<a target="_blank" href="http://www.wikidata.org/entity/' + props['wdid'] + '">' + props['label'] + '</a>');

  $("#pics").empty();

  $.each(props.paintings, function( key, value ) {

    var pic = $("<img />").attr("src",value['img'] + '?width=300');
    pic.click(function(){
      console.log(value);
      $("#bigpic").empty();
      var bigpic = $("<img />").attr("src",value['img'] + '?width=1000');
      //bigpic.css("height","50%");
      $("#bigpic").append(bigpic);
      var bigpictxt = $("<p></p>").text(value['title']);
      $("#bigpictxt").append(bigpictxt);
      $("#overlay").show();
      $("#overlaycontent").show();
    });

    $("#pics").append(pic);

    var link = $("<a></a>").html('<img src="https://upload.wikimedia.org/wikipedia/commons/f/ff/Wikidata-logo.svg" style="width:20px;" /> ');
    link.attr("href","http://www.wikidata.org/entity/" + key);
    link.attr("target","_blank");

    var txt = $("<p></p>").text(value['title']);

    txt.prepend(link);

    $("#pics").append(txt);

  });


    
    
}

</script>



</body>
</html>
