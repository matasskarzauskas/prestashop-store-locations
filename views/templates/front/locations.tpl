{extends file='page.tpl'}
{block name='page_content'}
<script>
let map;
function initMap() {
    var locations = [
    {foreach from=$stores item='i'}
      ['<b>{$i.name}</b> <br/>{$i.address}', {$i.latitude}, {$i.longitude}, 4],
    {/foreach}
    ];
    map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: {$latitude}, lng: {$longitude} },
    zoom: 8
  });
  var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
}
</script>
    <div class="row">
    <div id="left-column" class="col-xs-12 col-sm-4 col-md-3">
    {foreach from=$stores item='i'}
        <h2>{$i.name}</h2>
        <p>{$i.address}</p>
    {/foreach}
    </div>
    <div id="left-column" class="col-xs-12 col-sm-8 col-md-9">
        <div id="map" style="width:100%; height:500px;"></div>
            <script
            src="https://maps.googleapis.com/maps/api/js?key={$api_key}&callback=initMap&libraries=&v=weekly"
            defer
            ></script>
        </div>
    </div>
{/block}
