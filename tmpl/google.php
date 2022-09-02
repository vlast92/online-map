<?php
/**
 * @package    online-map
 *
 * @author     Vlast <vlasteg@mail.ru>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @var object $module
 * @var int $map_height
 * @var string $center_lat
 * @var string $center_lng
 * @var int $map_zoom
 * @var int $map_zoom_marker_click
 * @var string $marker_image
 * @var string $info_window
 * @var array $map_controls
 * @var array $places
 * @var object $document
 * @var string $moduleclass_sfx
 * @var string $module_path
 */

$module_id = 'google-map-' . $module->id . '-' . rand(1, 99999);

$script = "
        document.addEventListener(\"DOMContentLoaded\", function(){
            
           document.getElementById('" . $module_id . "').style.height = '" . $map_height . "px';
           document.getElementById('" . $module_id . "').innerHTML = '<div class=\"map-loader\"><div>Загрузка карты. Подождите...</div><div><img src=\"" . $module_path . "/assets/images/Preloader.gif\"/></div></div>';
        });
           window.addEventListener('load', function(){
                    document.getElementById('" . $module_id . "').innerHTML = '';
                    
                    var googleMap_" . $module->id . " = new GoogleMap({
                        id: '" . $module_id . "',
                        centerCoord: {lat: " . $center_lat . ", lng: " . $center_lng . "},
                        zoom: " . $map_zoom . ",
                        mapZoomMarkerClick: " . $map_zoom_marker_click . ",
                        marker: {
                            animation: 'google.maps.Animation.DROP'";
if ($marker_image)
{
	$script .= " ,
                icon: '/" . $marker_image . "'";
}
$script .= "                        },
                        info_window: " . $info_window . ",
                        zoomControl: " . $map_controls["zoom"] . ",
                        mapTypeControl: " . $map_controls["mapType"] . ",
                        streetViewControl: " . $map_controls["streetView"] . ",
                        fullscreenControl: " . $map_controls["fullscreen"] . "
                    });
                    ";
if (!empty($places))
{
	$counter      = 1;
	$places_count = count($places);
	$script       .= "googleMap_" . $module->id . ".addPlace([";

	foreach ($places as $place)
	{
		$script .= "
            {
              name: '" . $place["name"] . "',
              position: {lat: " . $place["lat"] . ", lng: " . $place["lng"] . "}";
		if (!empty($place["info_window_content"]))
		{
			$infoWindowContent = '<div class=\"markerContent g-grid\">';

			if ($place['info_window_image']) $infoWindowContent .= '<div class="image"><img src="/' . $place['info_window_image'] . '" alt="" /></div>';

			$infoWindowContent .= '<div class="content">';
			$infoWindowContent .= '<div class="title">' . nl2br($place["name"]) . '</div>';
			$infoWindowContent .= '<div class="body">' . nl2br($place["info_window_content"]) . '</div>';
			$infoWindowContent .= '</div>';

			$infoWindowContent .= '</div>';
			$script            .= ",
              infoWindowContent: '" . str_replace("\r\n", "", $infoWindowContent) . "'";
		}
		$script .= "
            }
        ";
		if ($counter++ != $places_count) $script .= ',';
	}

	$script .= "]);";
}

if ($map_controls["custom"])
{
	$script .= "googleMap_" . $module->id . ".addControl({
                        class: 'controls',
                        position: 'LEFT_BOTTOM',
                        content: '<p>Левый клик по маркеру - приблизить</p><p>Правый клик по маркеру - отдалить</p>'
                    });
    ";
}

$script .= "
    });
";

$document->addScriptDeclaration($script);
?>
<div id="<?= $module_id ?>" class="online-map <?= $moduleclass_sfx ?>"></div>
