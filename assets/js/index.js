let options = {
    method: 'GET',
    headers: {}
};


var map = L.map('map').setView([45.55, 9.2], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

window.addEventListener('load', function() {
    // Your document is loaded.
    var fetchInterval = 5000; // 5 seconds.

    // Invoke the request every 5 seconds.
    setInterval(function(){fetchStatus(map)}, fetchInterval);
});

//fetchStatus(map);

const myChart = new Chart(
    document.getElementById('myChart'), {
        type: 'line',
        //data: data,
        options: {
            animation: false,
            parsing: true,
        },

        plugins: {
            decimation: {
                enabled: true,
                algorithm: 'min-max',
            },
            interaction: {
                mode: "nearest",
                axis: "x",
                intersect: false,
            },

        }
    }
);

function fetchStatus(map) {

    console.log(Date() + " fetching...");
    fetch('VisualizationController/data', options)
        .then(response => response.json())
        .then(body => {

            var DateTime = luxon.DateTime;
            for (var i = body.length - 1; i >= 0; i--) {
                body[i].ts = DateTime.fromFormat(
                    body[i].ts.date.replace('.000000', '') + ' ' + body[i].ts.timezone,
                    'yyyy-MM-dd HH:mm:ss z'
                ).toFormat('f');
            }


            const labels = pluck(body, 'ts');

            const data = {
                labels: labels,
                datasets: [{
                    label: 'Light',
                    backgroundColor: 'rgb(219, 22, 47)',
                    borderColor: 'rgb(239, 42, 67)',
                    data: pluck(body, 'light'),
                    borderWidth: 1,
                    radius: 0,
                }, {
                    label: 'Humidity',
                    backgroundColor: 'rgb(59,31,43)',
                    borderColor: 'rgb(79,51,63)',
                    data: pluck(body, 'hum'),
                    borderWidth: 1,
                    radius: 0,
                }, {
                    label: 'External Humidity',
                    backgroundColor: 'rgb(219,223,172)',
                    borderColor: 'rgb(239,243,192)',
                    data: pluck(body, 'e_hum'),
                    borderWidth: 1,
                    radius: 0,
                }, {
                    label: 'Temperature',
                    backgroundColor: 'rgb(95,117,142)',
                    borderColor: 'rgb(115,137,162)',
                    data: pluck(body, 'tmp'),
                    borderWidth: 1,
                    radius: 0,
                }, {
                    label: 'External Temperature',
                    backgroundColor: 'rgb(56,57,97)',
                    borderColor: 'rgb(76,77,117)',
                    data: pluck(body, 'e_tmp'),
                    borderWidth: 1,
                    radius: 0,
                }, {
                    label: 'Pollution (PPM)',
                    backgroundColor: 'rgb(209, 81, 45)',
                    borderColor: 'rgb(229, 101, 65)',
                    data: pluck(body, 'ppm'),
                    borderWidth: 1,
                    radius: 0,
                }]
            };

            const myChart = Chart.getChart('myChart');
            myChart.data = data;

            myChart.update();

            // map clear and readd markers

            var markers = L.markerClusterGroup();
            var markerArray = [];

            /* delete method */
            map.eachLayer(function(layer) {
                if (layer instanceof L.MarkerClusterGroup) {
                    map.removeLayer(layer);
                }
            });

            body.forEach(function(item) {
                if (!arrayEquals([item.lat, item.lng],[0, 0])) {
                    var marker = L.marker([item.lat, item.lng], {
                        icon: L.divIcon({
                            iconUrl: 'assets/img/dot.png',

                            iconSize: [10, 10], // size of the icon
                            iconAnchor: [0, 0], // point of the icon which will correspond to marker's location
                            popupAnchor: [5, 5] // point from which the popup should open relative to the iconAnchor

                        })
                    });

                    markers.addLayer(marker);
                    markerArray.push(marker);
                }
            });

            map.addLayer(markers);

            var group = new L.featureGroup(markerArray);
            map.fitBounds(group.getBounds());

        });
}

function arrayEquals(a, b) {
    return Array.isArray(a) &&
        Array.isArray(b) &&
        a.length === b.length &&
        a.every((val, index) => val === b[index]);
}


function pluck(array, key) {
    return array.map(function(obj) {
        return obj[key];
    });
}