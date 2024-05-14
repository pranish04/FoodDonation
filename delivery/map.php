<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Map Location</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

  <style>
    #map {
      height: 600px;
      border: 2px solid #ccc;
      border-radius: 5px;
      margin-right: 320px;
    }

    .panel {
      position: fixed;
      top: 0;
      right: 0;
      width: 300px;
      height: 100%;
      background-color: #fff;
      overflow-y: auto;
      box-shadow: -1px 0px 10px rgba(0, 0, 0, 0.5);
    }

    .panel-header {
      text-align: center;
      padding: 10px;
      background-color: #f57c51;
      color: white;
      font-weight: bold;
    }

    .panel-content {
      padding: 20px;
    }

    .search-container {
      margin-top: 20px;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    #pickupInput,
    #deliveryInput {
      padding: 10px;
      font-size: 16px;
      border: 2px solid #ccc;
      border-radius: 5px;
      width: 80%;
      margin-bottom: 10px;
    }

    #searchButton {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #f57c51;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 50%;
    }

    #searchButton:hover {
      background-color: #eb8e6d;
    }
  </style>
</head>

<body>
  <div class="container">
    <div id="map"></div>
    <div class="panel" id="infoPanel">
      <div class="panel-header">Place Details</div>
      <div class="panel-content" id="infoContent">
        <div class="search-container">
          pickup from:<input type="text" id="pickupInput" value="<?php echo $_GET['pickup'] ?? ''; ?>" />
          delivery to:<input type="text" id="deliveryInput" value="<?php echo $_GET['delivery'] ?? ''; ?>" />
          <button id="searchButton">Search</button>
        </div>
      </div>
    </div>

    <div id="pathDetails">

  </div>

  <script>
    var map = L.map("map");
    var userMarker = L.marker([0, 0]).addTo(map);
    var routingControl;
    var zoomed = false;

    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    navigator.geolocation.watchPosition(success, error);

    function success(pos) {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;

      if (userMarker) {
        map.removeLayer(userMarker);
      }

      userMarker = L.marker([lat, lng]).addTo(map);

      if (!zoomed) {
        map.setView([lat, lng], 13);
        zoomed = true;
      }

      if (routingControl) {
        map.removeControl(routingControl);
      }
    }

    function error(err) {
      if (err === 1) {
        alert("Please allow geolocation access");
      } else {
        alert("Cannot get the current location");
      }
    }

    document.getElementById("searchButton").addEventListener("click", function () {
      const pickupLocation = document.getElementById("pickupInput").value;
      const deliveryLocation = document.getElementById("deliveryInput").value;

      if (!pickupLocation || !deliveryLocation) {
        alert("Please enter both pickup and delivery locations");
        return;
      }

      geocode(pickupLocation, function (pickupCoords) {
        geocode(deliveryLocation, function (deliveryCoords) {
          if (routingControl) {
            map.removeControl(routingControl);
          }

          routingControl = L.Routing.control({
            waypoints: [
              L.latLng(userMarker.getLatLng()),
              L.latLng(pickupCoords),
              L.latLng(deliveryCoords),
            ],
            routeWhileDragging: true,
            lineOptions: {
              styles: [
                { color: "#FF0000", opacity: 0.7, weight: 5 },
                { color: "#00FF00", opacity: 0.7, weight: 5 },
              ],
            },
          }).addTo(map);

          map.setView(L.latLng(pickupCoords), 13);

          document.getElementById("pathDetails").innerHTML = "<strong>Path Details:</strong> Follow the route highlighted on the map.";
        });
      });
    });

    function geocode(address, callback) {
      fetch(
        `https://nominatim.openstreetmap.org/search?format=json&q=${address}`
      )
        .then((response) => response.json())
        .then((data) => {
          if (data && data.length > 0) {
            const result = data[0];
            const lat = parseFloat(result.lat);
            const lon = parseFloat(result.lon);
            callback([lat, lon]);
          } else {
            alert("Location not found.");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    }
  </script>
</body>

</html>
