<!DOCTYPE html>
<html>
<head>

  
  
  <title>Landscapism how-to?</title>

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
      background-image:    url(https://upload.wikimedia.org/wikipedia/commons/0/0d/Volga_lagoon.jpg?width=1000);
      margin-top: 0;  
      background-size:     cover;                      /* <------ */
      background-repeat:   no-repeat;
      background-position: center center;  
    }
    #main{
      position: fixed;
      left: 360px;
      top: 30px;
      width: 250px;
      background-color: #000;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
      max-height: 85%;
      overflow-y: scroll;
    }
    #bias{
      position: fixed;
      left: 690px;
      top: 30px;
      width: 250px;
      background-color: #000;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
      max-height: 85%;
      overflow-y: scroll;
    }
    ul{
      padding-left: 16px;
    }
  </style>
</head>
<body>




<div id="legenda">
  <h1><a href="index.php">The Painted Planet</a></h1>

  <p>This website shows landscape paintings from Wikidata, as <a href="https://w.wiki/3$M4">queried</a> from the Wikidata sparql endpoint.</p>


  <p>The landscapes show what places once looked like (to the painter). Maybe they'll provide pleasant stops during an armchair travelling trip. Maybe they'll add something to your 'sense of place' of a place.</p>

  <p>Maybe they'll spark a wish to <a href="https://rewildingeurope.com/what-is-rewilding-2/">rewild</a> parts of the planet.</p>
  
</div>



<div id="main">
  <h1>How to add paintings to the map?</h1>

  <p>To appear on the map, a Wikidata item should be a <a href="https://www.wikidata.org/wiki/Q3305213">painting (Q3305213)</a> with the following properties:</p>

  <ul>
    <li>'genre' (P136), and the value must be 'landscape art' (Q191163)</li>
    <li>'depicts' (P180), and the value must be something with a 'coordinate location' (P625) and a 'country' (P17)</li>
    <li>the country should still exist and thus not have an 'end' (P576)</li>
    <li>obviously, an 'image' (P18)</li>
  </ul>


  <p>This website caches data, but everything is refreshed once a week.</p>
  
</div>



<div id="bias">
  <h1>Distribution</h1>

  <p>As you <a title="link to query" href="https://w.wiki/3$NF">can see</a>, current data is not distributed equally over countries. Possibly, this is partly explained by the popularity of paintings in the Western world.</p>

  <img style="width: 100%" src="art/bubbles.jpg" />

  <p>Still, when adding, you might want to consider adding depictions of places in countries that lack in pictorial presence.</p>
  
</div>






https://w.wiki/3$NF

</body>
</html>
