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
      background-image:    url(https://upload.wikimedia.org/wikipedia/commons/7/76/Princess_Alice%2C_Grand_Duchess_of_Hesse_%5E_by_Rhine%2C_consort_of_Ludwig_IV%2C_Grand_Duke_of_Hesse_%5E_by_Rhine_%281843-78%29_-_Lochnagar_from_the_Sluggan_-_RCIN_403863_-_Royal_Collection.jpg?width=1000);
      margin-top: 0;  
      background-size:     cover;                      /* <------ */
      background-repeat:   no-repeat;
      background-position: center center; 
      background-attachment: fixed; 
    
    }
    @media only screen and (max-width: 900px) {
      background-size: auto 100%;
    }
    
    ul{
      padding-left: 16px;
    }
  </style>
</head>
<body>




<div id="intro">
  <h1><a href="index.php">The Painted Planet</a></h1>

  <p>This website shows landscape paintings from Wikidata, as <a href="https://w.wiki/3$M4">queried</a> from the Wikidata sparql endpoint.</p>


  <p>The paintings give an idea of what places once looked like (to the painter). Maybe they will provide a pleasant stop during an armchair travelling trip. Maybe they will add something to your 'sense of place' of a place.</p>

  <p>And maybe they will make you ponder <a href="https://rewildingeurope.com/what-is-rewilding-2/">large-scale rewilding</a> for a moment.</p>
  
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

  <p><a target="_blank" href="https://w.wiki/3$Xy">This query</a> selects random landscape paintings without a 'depicts' statement</p>
  
</div>



<div id="bias">
  <h1>Distribution</h1>

  <p>As you <a title="link to query" href="https://w.wiki/3$NF">can see</a>, current data is not distributed equally over countries. Possibly, this is partly explained by the popularity of painting as an artform in the Western world.</p>

  <img style="width: 100%" src="art/bubbles.jpg" />

  <p>When adding, you might want to consider adding depictions of places in countries that lack in pictorial presence.</p>
  
</div>

<br />
<br />




</body>
</html>
