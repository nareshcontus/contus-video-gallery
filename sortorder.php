<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Video hitcount file.
  Version: 2.3.1
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */
require_once( dirname(__FILE__) . '/hdflv-config.php');
$listitem       = $_POST['listItem'];
$ids            = implode(',', $listitem);
$sql            = 'UPDATE `' . $wpdb->prefix . 'hdflvvideoshare` SET `ordering` = CASE vid ';
if (isset($_GET['pagenum'])){
       $page = $_GET['pagenum'];
       $page = (20*($page-1));
   }
foreach($listitem as $key => $value){
   $listitems[$key+$page]=$value;
}
foreach ($listitems as $position => $item) {
    $sql       .= sprintf("WHEN %d THEN %d ", $item, $position);
}
$sql           .= ' END WHERE vid IN (' . $ids . ')';
$wpdb->query($sql);
exit();
?>