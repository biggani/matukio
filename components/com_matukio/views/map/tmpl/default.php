<?php
/**
 * @author Daniel Dimitrov
 * @date: 29.03.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
Jhtml::_('behavior.framework');
$api = 'http://maps.googleapis.com/maps/api/js?sensor=false';
$document = JFactory::getDocument();
$document->addScript($api);
$script = "window.addEvent('domready', function() {

geocoder = new google.maps.Geocoder();
        var myOptions = {
            zoom:8,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById('map_canvas'),
            myOptions);
        var address = '" . preg_replace("#\n|\r#", ' ', str_replace('<br />', ',', $this->event->gmaploc)) . "';
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });

            var infowindow = new google.maps.InfoWindow({
                content: address
            });

            google.maps.event.addListener(marker, 'click', function() {
              infowindow.open(map,marker);
            });

          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });

});";

$document->addScriptDeclaration($script);
?>

<div id="map_canvas" style="width:450px; height:300px"></div>
