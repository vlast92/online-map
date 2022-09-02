<?php
/**
 * @package    online-map
 *
 * @author     Vlast <vlasteg@mail.ru>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Helper\ModuleHelper;

defined('_JEXEC') or die;

/** @var object $params */

$document    = JFactory::getDocument();
$module_path = "/modules/mod_online_map";
$module_absolut_path = __DIR__;

$api                   = $params->get("map_api");
$key                   = $params->get("google_key");
$map_height            = $params->get("map_height", 500);
$map_zoom              = $params->get("map_zoom", 15);
$map_zoom_marker_click = $params->get("map_zoom_marker_click", 16);

$marker_image          = $params->get("marker_image", false);
$marker_image_width    = $params->get("marker_image_width", false);
$marker_image_height   = $params->get("marker_image_height", false);
$marker_image_offset_x = $params->get("marker_image_offset_x", 0);
$marker_image_offset_y = $params->get("marker_image_offset_y", 0);

$info_window        = $params->get("info_window");
$center_lat         = $params->get("lat");
$center_lng         = $params->get("lng");
$zoom_control_wheel = $params->get("zoom_control_wheel");
$map_controls       = array(
	"custom"              => $params->get("custom_control"),
	"zoom"                => $params->get("zoom_control"),
	"mapType"             => $params->get("mapType_control"),
	"streetView"          => $params->get("streetView_control"),
	"fullscreen"          => $params->get("fullscreen_control"),
	"geolocation_control" => $params->get("geolocation_control"),
	"search_control"      => $params->get("search_control"),
	"route_control"       => $params->get("route_control"),
	"traffic_control"     => $params->get("traffic_control"),
	"ruler_control"       => $params->get("ruler_control"),
);
$places             = json_decode(json_encode($params->get("markers", false)), true);
$moduleclass_sfx    = htmlspecialchars($params->get('moduleclass_sfx'));

$document->addStyleSheet("$module_path/assets/css/styles.min.css?v=" . filemtime($module_absolut_path . "/assets/css/styles.min.css"));

switch ($api)
{
	case 'google':
		$document->addScript("https://maps.googleapis.com/maps/api/js?key=$key", true, true);
		$document->addScript("$module_path/assets/js/google.min.js?v=" . filemtime($module_absolut_path . "/assets/js/google.min.js"));
		require ModuleHelper::getLayoutPath('mod_online_map', 'google');
		break;
	case 'yandex':
		$document->addScript("https://api-maps.yandex.ru/2.1/?apikey=$key&lang=ru_RU", true, true);
		$document->addScript("$module_path/assets/js/yandex.min.js?v=" . filemtime($module_absolut_path . "/assets/js/yandex.min.js"));
		require ModuleHelper::getLayoutPath('mod_online_map', 'yandex');
		break;
}
