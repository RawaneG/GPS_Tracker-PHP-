let circle = null;
let marker = null;
let vehicleName = "";
let vehicleIcon = "";
let routingControl = null;
let animationInterval = null;
let mapboxAccessToken =
  "pk.eyJ1IjoiZXJtZXMtamlyZW4iLCJhIjoiY2xtOWJzM2JvMGd0bDNkbzU5OWZoNG03dSJ9.JG1z9fol0pJT9t4-MubdGA";
let map = L.map("map").setView([48.85341, 2.3488], 8);
let osm = L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(
  map
);

$(function () {
  $(".reportrange").daterangepicker({
    startDate: moment().subtract(29, "days"),
    endDate: moment(),
    ranges: {
      "Aujourd'hui": [moment(), moment()],
      Hier: [moment().subtract(1, "days"), moment().subtract(1, "days")],
      "7 derniers jours": [moment().subtract(6, "days"), moment()],
      "30 derniers jours": [moment().subtract(29, "days"), moment()],
      "Ce mois-ci": [moment().startOf("month"), moment().endOf("month")],
      "Mois dernier": [
        moment().subtract(1, "month").startOf("month"),
        moment().subtract(1, "month").endOf("month"),
      ],
    },
    locale: {
      toLabel: "Au",
      fromLabel: "Du",
      format: "DD MMMM YYYY",
      cancelLabel: "Annuler",
      applyLabel: "Appliquer",
      customRangeLabel: "Personnalisé",
      daysOfWeek: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
      monthNames: [
        "Janvier",
        "Février",
        "Mars",
        "Avril",
        "Mai",
        "Juin",
        "Juillet",
        "Août",
        "Septembre",
        "Octobre",
        "Novembre",
        "Décembre",
      ],
    },
  });

  $(".jsGrid").jsGrid({
    width: "100%",
    height: "auto",
    paging: true,
    pageSize: 5,
    autoload: true,
    pageButtonCount: 5,
    controller: {
      loadData: function (filter) {
        return $.ajax({
          type: "GET",
          dataType: "json",
          url: "liste_vehicules.php",
          data: filter,
        });
      },
      insertItem: function () {},
    },
    fields: [
      {
        name: "icon",
        title: "",
        headerTemplate: function () {
          return `
          <div class="header-checkbox">
            <input type="checkbox" class="mainEye">
            <input type="checkbox" class="mainFootprints">
          </div>
          `;
        },
        itemTemplate: function (value, item) {
          let $itemTemplate = $(`
          <div class="vehicle">
            <div class="checkbox-cell">
              <input type="checkbox" class="item-checkbox eye">
              <input type="checkbox" class="item-checkbox footprints">
            </div>
            <div class="vehicle-name">
                <img src="${value}" alt="${item.name}" style="width: 30px; height: auto; ">
                <div class="vehicle-details">
                  <span class="item-name">${item.name}</span>
                  <span class="item-date">${item.date}</span>
                </div>
            </div>
            <div class="vehicle-speed">${item.speed} Km/h</div>
            <div class="vehicle-properties wifi"><i class="fas fa-wifi"></i></div>
            <div class="vehicle-properties date reportrange" data-row-id="${item.id}" hidden>
              <i class="fas fa-ellipsis-vertical"></i>
            </div>
          </div>`);

          let dateRangePicker = $itemTemplate.find(".reportrange");

          dateRangePicker.daterangepicker({
            startDate: moment().subtract(29, "days"),
            endDate: moment(),
            ranges: {
              "Aujourd'hui": [moment(), moment()],
              Hier: [
                moment().subtract(1, "days"),
                moment().subtract(1, "days"),
              ],
              "7 derniers jours": [moment().subtract(6, "days"), moment()],
              "30 derniers jours": [moment().subtract(29, "days"), moment()],
              "Ce mois-ci": [
                moment().startOf("month"),
                moment().endOf("month"),
              ],
              "Mois dernier": [
                moment().subtract(1, "month").startOf("month"),
                moment().subtract(1, "month").endOf("month"),
              ],
            },
            locale: {
              toLabel: "Au",
              fromLabel: "Du",
              format: "DD MMMM YYYY",
              cancelLabel: "Annuler",
              applyLabel: "Appliquer",
              customRangeLabel: "Personnalisé",
              daysOfWeek: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
              monthNames: [
                "Janvier",
                "Février",
                "Mars",
                "Avril",
                "Mai",
                "Juin",
                "Juillet",
                "Août",
                "Septembre",
                "Octobre",
                "Novembre",
                "Décembre",
              ],
            },
          });

          $itemTemplate.find(".eye").change(function () {
            if ($(this).is(":checked")) {
              cleanMarker();
              let itemDatePicker = $(this)
                .closest(".vehicle")
                .find(".reportrange");
              itemDatePicker.removeAttr("hidden");
              let itemNameElement = $(this)
                .closest(".vehicle")
                .find(".item-name");
              let itemName = itemNameElement.text();
              vehicleName = itemName;
              $.ajax({
                type: "GET",
                dataType: "json",
                url: "historique.php",
                data: { name: itemName },
                success: function (data) {
                  renderFetchedData(data);
                },
              });
            } else {
              let itemDatePicker = $(this)
                .closest(".vehicle")
                .find(".reportrange");
              itemDatePicker.attr("hidden", "true");
              window.location.href = "spinner.php";
            }
          });

          dateRangePicker.on("apply.daterangepicker", function (ev, picker) {
            let selectedStartDate = picker.startDate.format("DD MMMM YYYY");
            let selectedEndDate = picker.endDate.format("DD MMMM YYYY");

            $.ajax({
              type: "GET",
              dataType: "json",
              url: "daterange.php",
              data: {
                name: vehicleName,
                startDate: selectedStartDate,
                endDate: selectedEndDate,
              },
              success: function (data) {
                cleanMarker();
                renderFetchedData(data);
              },
            });
          });

          return $itemTemplate;
        },
      },
    ],
  });

  const searchInput = $(".search-input");
  searchInput.on("input", function () {
    const query = $(this).val();
    filterGridData(query);
  });

  function filterGridData(query = "") {
    let jsGridInstance = $(".jsGrid").data("JSGrid");
    jsGridInstance.loadData({ searchText: query });
  }

  let mainEye = document.querySelector(".mainEye");
  let mainFootprints = document.querySelector(".mainFootprints");

  checkAll(mainEye, ".eye");
  checkAll(mainFootprints, ".footprints");

  function checkAll(mainCheckbox, checkboxClass) {
    mainCheckbox.addEventListener("change", () => {
      cleanMarker();
      let checkboxes = document.querySelectorAll(checkboxClass);
      checkboxes.forEach((e) => (e.checked = mainCheckbox.checked));
      if (mainCheckbox.checked === true) {
        $.ajax({
          type: "GET",
          dataType: "json",
          url: "liste_vehicules.php",
          success: function (data) {
            renderAllVehicles(data);
          },
        });
      } else {
        window.location.href = "spinner.php";
      }
    });
  }
});

let arrow = document.querySelector(".fa-location-arrow");
arrow.addEventListener("click", () =>
  navigator.geolocation.getCurrentPosition(getPosition)
);

function cleanMarker() {
  marker !== null ? map.removeLayer(marker) : (marker = null);
  circle !== null ? map.removeLayer(circle) : (circle = null);
  routingControl !== null ? map.removeControl(routingControl) : null;
}

function renderLatLng(data) {
  let roadCoordinates = data.map((element) => [
    element.latitude,
    element.longitude,
  ]);
  return roadCoordinates;
}

function renderAllVehicles(vehicules) {
  vehicules.map((vehicule) => {
    $.ajax({
      type: "GET",
      dataType: "json",
      url: "historique.php",
      data: { name: vehicule.name },
      success: function (data) {
        let donneesVehicule = data;
        let historique = renderLatLng(data);
        let lastPosition = historique[historique.length - 1];
        $.ajax({
          url: `https://api.mapbox.com/geocoding/v5/mapbox.places/${lastPosition[1]},${lastPosition[0]}.json?access_token=pk.eyJ1IjoiZXJtZXMtamlyZW4iLCJhIjoiY2xtOWJzM2JvMGd0bDNkbzU5OWZoNG03dSJ9.JG1z9fol0pJT9t4-MubdGA`,
          dataType: "json",
          success: function (geocodeData) {
            let formattedAddress = geocodeData.features[0].place_name;

            let vehicle = L.icon({
              iconUrl: `${vehicule.icon}`,
              iconSize: [50, 50],
              iconAnchor: [25, 25],
            });

            L.marker(lastPosition, {
              icon: vehicle,
              iconAngle: 0,
            }).addTo(map).bindPopup(`
              <h4>Nom du véhicule : ${vehicule.name}</h4>
              <h4>Date : ${
                donneesVehicule[donneesVehicule.length - 1].date
              }</h4>
              <h4>Vitesse : ${
                donneesVehicule[donneesVehicule.length - 1].speed
              } Km/h</h4>
              <h4>Dernière position : ${formattedAddress}</h4>
            `);

            map.setView(lastPosition);
          },
        });
      },
    });
  });
}

function reverseGeocode(lat, lng) {
  const apiUrl = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lng},${lat}.json?access_token=${mapboxAccessToken}`;

  return new Promise((resolve, reject) => {
    fetch(apiUrl)
      .then((response) => response.json())
      .then((data) => {
        if (data.features && data.features.length > 0) {
          const address = data.features[0].place_name;
          marker.getPopup().setContent(`
            <p>Nom du véhicule : ${vehicleName}</p>
            <p>Adresse : ${address}</p>
          `);
          resolve(address);
        } else {
          console.error("Aucune addresse n'a été trouvé avec ces coordonnées.");
          reject("No address found");
        }
      })
      .catch((error) => {
        console.error(
          "Erreur obtenue en utilisant le reverse Geocoding: ",
          error
        );
        reject(error);
      });
  });
}

function renderFetchedData(data) {
  $.ajax({
    type: "GET",
    dataType: "json",
    url: "liste_vehicules.php",
    data: vehicleName,
    success: (vehicles) => {
      cleanMarker();

      vehicles.map((vehicle) =>
        vehicle.name === vehicleName ? (vehicleIcon = vehicle.icon) : null
      );

      let roadCoordinates = renderLatLng(data);

      let vehicle = L.icon({
        iconUrl: `./${vehicleIcon}`,
        iconSize: [50, 50],
        iconAnchor: [25, 25],
      });

      marker = L.marker(roadCoordinates[0], {
        icon: vehicle,
        iconAngle: 0,
      })
        .addTo(map)
        .bindPopup();

      const waypoints = roadCoordinates.map((coord) =>
        L.latLng(coord[0], coord[1])
      );

      routingControl = L.Routing.control({
        waypoints: waypoints,
      }).addTo(map);

      routingControl.on("routesfound", async (e) => {
        let currentSpeed = 0;
        let previousCoord = null;
        let previousAddress = "";
        let route = e.routes[0];

        let routeCoordinates = route.coordinates;
        let historicBody = document.querySelector(".historic-body");

        for (let index = 0; index < routeCoordinates.length; index++) {
          let coord = routeCoordinates[index];
          if (previousCoord) {
            data[index] !== undefined
              ? (currentSpeed = data[index].speed)
              : null;
            let distance = map.distance(previousCoord, coord);
            let address = await reverseGeocode(coord.lat, coord.lng);
            if (address !== previousAddress) {
              let table = document.createElement("table");
              let hr = document.createElement("hr");
              let timeInSeconds =
                distance / currentSpeed === Infinity
                  ? 0
                  : distance / currentSpeed;
              let hours = Math.floor(timeInSeconds / 3600);
              let minutes = Math.floor((timeInSeconds % 3600) / 60);
              let seconds = Math.floor(timeInSeconds % 60);

              table.classList.add("jsGrid-historic");
              table.innerHTML = `
              <tr>
                <td><b>Adresse</b></td>
                  <tr>
                    <td>
                      De : ${previousAddress}
                    </td>
                  </tr>
                  <tr>
                    <td>
                      A : ${address}
                    </td>
                  </tr>
              </tr>
              <tr>
                <td><b>Distance</b></td>
                  <tr>
                    <td>${distance.toFixed(0)} mètres</td>
                  </tr>
              </tr>
              <tr>
                <td><b>Temps</b></td>
                <tr>
                  <td>${hours}h ${minutes}m ${seconds}s</td>
                </tr>
              </tr>
              `;
              historicBody.appendChild(table);
              historicBody.appendChild(hr);
            }
            previousAddress = address;
          }

          setTimeout(() => {
            marker.setLatLng([coord.lat, coord.lng]);
            reverseGeocode(coord.lat, coord.lng);
          }, 150 * index);

          previousCoord = coord;
        }
      });

      map.eachLayer((layer) => {
        if (layer instanceof L.Marker && layer !== marker) {
          map.removeLayer(layer);
        }
      });

      routingControl.route();
    },
  });
}

function getPosition(position) {
  let lat = position.coords.latitude;
  let long = position.coords.longitude;
  let accurary = position.coords.accuracy;
  cleanMarker();
  marker = L.marker([lat, long, accurary]);
  circle = L.circle([lat, long], { radius: accurary });
  let featureGroup = L.featureGroup([marker, circle]).addTo(map);
  map.fitBounds(featureGroup.getBounds());
}
