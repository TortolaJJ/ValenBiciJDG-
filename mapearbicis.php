<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Mapa de Estaciones Valenbisi JDG</title>
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
 <style>
  #map { height: 600px; width: 100%; margin-top: 20px; }
  body { margin: 0; font-family: Arial, sans-serif; text-align: center; background-color:rgb(193, 56, 56); }
  h1 { color: green; font-size: 24px; margin-top: 20px; }
 </style>
</head>
<body>
 <h1>Mapeo de Bicicletas en Valencia</h1>
 <div id="map"></div>
 <script>
  var map = L.map('map').setView([39.47, -0.37], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
   attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  function getMarkerColor(available) {
   if (available < 5) {
    return 'red';
   } else if (available >= 5 && available < 10) {
    return 'orange';
   } else if (available >= 10 && available < 20) {
    return 'yellow';
   } else {
    return 'green';
   }
  }

  fetch('data.json')
   .then(response => {
    if (!response.ok) {
     throw new Error(`Error al cargar data.json: ${response.statusText}`);
    }
    return response.json();
   })
   .then(data => {
    Object.values(data).forEach(station => {
     const { lat, lon, address, available, free, total } = station;
     if (lat && lon) {
      const size = 0.001; // Ajusta el tamaño del cuadrado
      const bounds = [
       [lat - size, lon - size],
       [lat + size, lon + size]
      ];
      L.rectangle(bounds, {
       color: getMarkerColor(available),
       weight: 1,
       fillOpacity: 0.8
      })
      .addTo(map)
      .bindPopup(`
       <strong>${address}</strong><br>
       <b>Disponibles:</b> ${available}<br>
       <b>Libres:</b> ${free}<br>
       <b>Total:</b> ${total}
      `);
     }
    });
   })
   .catch(error => {
    console.error('Error cargando los datos:', error);
   });
 </script>
</body>
</html>
