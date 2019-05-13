<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    var availableTags = [
      "Hamburg",
      "Berlin",
      "München",
      "Köln",
      "Frankfurt (Main)",
      "Stuttgart",
      "Düsseldorf",
      "Dortmund",
      "Essen",
      "Leipzig",
      "Bremen",
      "Dresden",
      "Hannover",
      "Nürnberg",
      "Duisburg",
      "Bochum",
      "Wuppertal",
      "Bielefeld",
      "Bonn",
      "Münster",
      "Karlsruhe",
      "Mannheim",
      "Augsburg",
      "Wiesbaden",
      "Mönchengladbach",
      "Gelsenkirchen",
      "Braunschweig",
      "Kiel",
      "Chemnitz",
      "Aachen"
    ];
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  } );
  </script>