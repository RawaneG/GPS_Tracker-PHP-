<?php
require 'helper.php';
session_start();
(!isset($_SESSION['username'])) ?  header("Location: index.php") : null;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <title>GPS Tracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Jquery and JSGRID dependencies -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
  <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
  <!-- DATEPICKER DEPENDENCY -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <!-- Leaflet CDN -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <!-- Leaflet Location Controller CDN dependency -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
  <!-- Leaflet Routine Machine CDN dependency -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
  <!-- Other CDNS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- CSS LINK -->
  <link rel="stylesheet" href="css/map.css" />
</head>

<body>
  <div id="map"></div>

  <div class="identity">
    <h3>Connecté : <?= $_SESSION['name'] ?></h3>
    <a href="deconnexion.php" class="logout">
      <i class="fas fa-sign-in"></i>
    </a>
  </div>

  <input id="hamburger" class="hamburger" type="checkbox" />
  <label class="hamburger" for="hamburger">
    <i></i>
  </label>

  <div class="warpper">
    <input class="radio" id="one" name="group" type="radio" checked />
    <input class="radio" id="two" name="group" type="radio" />
    <input class="radio" id="three" name="group" type="radio" />
    <input class="radio" id="four" name="group" type="radio" />

    <div class="tabs">
      <label class="tab" id="one-tab" for="one">Traceurs</label>
      <label class="tab" id="two-tab" for="two">Evenements</label>
      <label class="tab" id="three-tab" for="three">Adresses</label>
      <label class="tab" id="four-tab" for="four">Historique</label>
    </div>

    <div class="tools">
      <div class="search-container">
        <input type="text" name="search" placeholder="Search..." class="search-input" />
        <a href="#" class="search-btn">
          <i class="fas fa-search"></i>
        </a>
      </div>

      <i class="fa-solid fa-retweet"></i>
      <i class="fa-solid fa-share-nodes"></i>
      <i class="fa-solid fa-location-arrow"></i>
    </div>

    <div class="panels">
      <div class="panel" id="one-panel">
        <div class="jsGrid-header">
          <div class="jsGrid-header-icons">
            <i class="fas fa-eye"></i>
            <i class="fas fa-shoe-prints"></i>
          </div>
          <h3 class="jsGrid-header-title">Traceur</h3>
        </div>
        <div class="jsGrid"></div>
      </div>
      <div class="panel" id="two-panel">
        <p>Contenu 2</p>
      </div>
      <div class="panel" id="three-panel">
        <p>Contenu 3</p>
      </div>
      <div class="panel" id="four-panel">
        <div class="jsGrid-header">
          <h3 class="historic">Historique du véhicule</h3>
        </div>
        <div class="historic-body"></div>
      </div>
    </div>
  </div>

  <!-- Leaflet CDN dependency -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <!-- Leaflet Location Controller CDN dependency -->
  <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
  <!-- Leaflet Routing Machine CDN dependency -->
  <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
  <!-- Moment Dependency -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" type="text/css" href="https://momentjs.com/downloads/moment-with-locales.min.js" />
  <!-- Other Dependencies -->
  <script type="text/javascript" src="js/script.js"></script>
</body>

</html>