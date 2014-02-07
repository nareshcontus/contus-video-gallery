<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Video More page controller file.
  Version: 2.6
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

include_once( $frontModelPath . 'videomore.php' );								## including ContusVideo model file for get database information.
if ( class_exists( 'ContusMoreController' ) != true ) {

	class ContusMoreController extends ContusMore {

		public function __construct() {											## contructor starts
			parent::__construct();
		}																		## contructor ends

		function settings_data() {												## getting settings data function starts
			return $this->get_settingsdata();
		}																		## getting settings data function ends

		function more_pageid() {												## getting more page ID function starts
			return $this->get_more_pageid();
		}																		## getting more page ID function ends

		function video_count() {												## getting Video count function starts
			return $this->get_video_count();
		}																		## getting Video count function ends

		function home_thumbdata( $thumImageorder, $where, $pagenum, $dataLimit ) { ##  HOME PAGE FEATURED VIDEOS STARTS
			return $this->get_thumdata( $thumImageorder, $where, $pagenum, $dataLimit );
		}

		function home_categoriesthumbdata( $pagenum, $dataLimit ) {				##  HOME PAGE FEATURED VIDEOS STARTS
			return $this->get_categoriesthumdata( $pagenum, $dataLimit );
		}

		function home_searchthumbdata( $thumImageorder, $pagenum, $dataLimit ) {	##  HOME PAGE FEATURED VIDEOS STARTS
			return $this->get_searchthumbdata( $thumImageorder, $pagenum, $dataLimit );
		}

		function countof_videos( $playid, $userid, $thumImageorder, $where ) {	##  HOME PAGE FEATURED VIDEOS STARTS
			return $this->get_countof_videos( $playid, $userid, $thumImageorder, $where );
		}

		function countof_videocategories() {									##  HOME PAGE FEATURED VIDEOS STARTS
			return $this->get_countof_videocategories();
		}

		function countof_videosearch( $thumImageorder ) {							##  HOME PAGE FEATURED VIDEOS STARTS
			return $this->get_countof_videosearch( $thumImageorder );
		}

	}																			## class over
} else {
	echo 'class contusMore already exists';
}
include_once( $frontViewPath . 'videomore.php' );									## including ContusVideo model file for get database information.
?>