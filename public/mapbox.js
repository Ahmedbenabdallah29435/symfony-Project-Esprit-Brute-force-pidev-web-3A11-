
const apiKey = "AAPK8f27da23013b4b43b967a2b4cf4fed55MwFkbld8ACq5Y70fgYUK4aLGkBkSdvXT5zEXvakLLEam6hJfa4BHfnL6LbFTkJxf";

const basemapEnum = "ArcGIS:Navigation";
const coordinates = document.getElementById('coordinates');
const latitude = document.getElementById('latitude');
const test= document.getElementById('test');
const map = new mapboxgl.Map({
container: "map",
style: `https://basemaps-api.arcgis.com/arcgis/rest/services/styles/${basemapEnum}?type=style&token=${apiKey}`,
zoom: 6,

center: [9.541625307219647, 35.660282489135156], // starting position ([lng, lat] for Mombasa, Kenya)

});

const marker = new mapboxgl.Marker({
draggable: true
})
.setLngLat([9.871215150968851, 37.26823489806061])
.addTo(map);

function onDragEnd() {
const lngLat = marker.getLngLat();
coordinates.innerHTML = `${lngLat.lng}`;
latitude.innerHTML = `${lngLat.lat}`;

const coords = lngLat.toArray();

const authentication = new arcgisRest.ApiKey({
  key: apiKey
});

var label ='' ;
arcgisRest
  .reverseGeocode(coords, {
    authentication
  })
  .then((result) => {

    const lngLat = [result.location.x, result.location.y];

    const label = `${result.address.LongLabel}<br>${lngLat[0].toLocaleString()}, ${lngLat[1].toLocaleString()}`;

    new mapboxgl.Popup().setLngLat(lngLat).setHTML(label).addTo(map);

    // const lngLat = [result.location.x, result.location.y];  
    //  label = `${result.address.LongLabel}<br>${lngLat[0].toLocaleString()}, ${lngLat[1].toLocaleString()}`;

    

    // label = result.address.
    //           LongLabel;
    //           console.log(label);
              test.innerHTML = label ;
  })
   
  .catch((error) => {
    alert("There was a problem using the reverse geocode service. See the console for details.");
    console.error(error);
  });
 

}

marker.on('dragend', onDragEnd);

