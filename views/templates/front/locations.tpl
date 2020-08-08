{**
* 2007-2020 PrestaShop SA and Contributors
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file='page.tpl'}
{block name='page_content'}
{if !$stores}
<h2>{l s='No stores to display' mod='storelocations'}</h2>
{/if}
<script>
let map;
{if $stores}
function initMap() {
    var locations = [
    {foreach from=$stores item='i'}
      ['<b>{$i.name|escape:'htmlall':'UTF-8'}</b> <br/>{$i.address|escape:'htmlall':'UTF-8'}', {$i.latitude}, {$i.longitude}, 4],
    {/foreach}
    ];
    map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: {$latitude|escape:'htmlall':'UTF-8'}, lng: {$longitude|escape:'htmlall':'UTF-8'} },
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
{/if}

    <div class="row">
    <div id="left-column" class="col-xs-12 col-sm-4 col-md-3">
    {if $stores}
    {foreach from=$stores item='i'}
        <h2>{$i.name|escape:'htmlall':'UTF-8'}</h2>
        <p>{$i.address|escape:'htmlall':'UTF-8'}</p>
    {/foreach}
    {/if}
    </div>
    <div id="left-column" class="col-xs-12 col-sm-8 col-md-9">
    {if $api_key}
        <div id="map" style="width:100%; height:500px;"></div>
            <script
            src="https://maps.googleapis.com/maps/api/js?key={$api_key|escape:'htmlall':'UTF-8'}&callback=initMap&libraries=&v=weekly"
            defer
            ></script>
        </div>
    {/if}
    </div>
{/block}
