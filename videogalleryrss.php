<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: RSS Feed file for Videos.
  Version: 2.6
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

require_once( dirname( __FILE__ ) . '/hdflv-config.php' );
header( 'Content-Type: ' . feed_content_type( 'rss-http' ) . '; charset=' . get_option( 'blog_charset' ), true );
echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';
$dir		= dirname( plugin_basename( __FILE__ ) );
$dirExp		= explode( '/', $dir );
$dirPage	= $dirExp[0];
$image_path = str_replace( 'plugins/' . $dirPage . '/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL );
$_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;	 ## declare image path
$type		= filter_input( INPUT_GET, 'type' );
$where		= $tag_name = '';
$dataLimit	= 1000;
$contusOBJ	= new ContusVideoController(  );

switch ( $type ) {
	case 'popular':													 ## GETTING POPULAR VIDEOS STARTS
	default:
		$thumImageorder = 'w.hitcount DESC';
		$TypeOFvideos			= $contusOBJ->home_thumbdata( $thumImageorder, $where, $dataLimit );
		break;													  ## GETTING POPULAR VIDEOS ENDS

	case 'recent':
		$thumImageorder = 'w.vid DESC';
		$TypeOFvideos			= $contusOBJ->home_thumbdata( $thumImageorder, $where, $dataLimit );
		break;

	case 'featured':
		$thumImageorder = 'w.ordering ASC';
		$where			= 'AND w.featured=1';
		$TypeOFvideos			= $contusOBJ->home_thumbdata( $thumImageorder, $where, $dataLimit );
		break;
	case 'category':
		$thumImageorder = intval( filter_input( INPUT_GET, 'playid' ) );
		$TypeOFvideos			= $contusOBJ->home_catthumbdata( $thumImageorder, $dataLimit );
		break;
}
?>
<rss version="2.0"
	 xmlns:content="http://purl.org/rss/1.0/modules/content/"
	 xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	 xmlns:dc="http://purl.org/dc/elements/1.1/"
	 xmlns:atom="http://www.w3.org/2005/Atom"
	 xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	 xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	 <?php do_action( 'rss2_ns' ); ?>
	 >
	<channel>
		<title><?php bloginfo_rss( 'name' );
		wp_title_rss(); ?></title>
		<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
		<link><?php bloginfo_rss( 'url' ) ?></link>
		<description><?php bloginfo_rss( 'description' ) ?></description>
		<lastBuildDate><?php echo balanceTags( mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ) ); ?></lastBuildDate>
		<language><?php bloginfo_rss( 'language' ); ?></language>
		<sy:updatePeriod><?php echo balanceTags( apply_filters( 'rss_update_period', 'hourly' ) ); ?></sy:updatePeriod>
		<sy:updateFrequency><?php echo balanceTags( apply_filters( 'rss_update_frequency', '1' ) ); ?></sy:updateFrequency>
		<?php do_action( 'rss2_head' ); ?>
<?php
if ( count( $TypeOFvideos ) > 0 ) {
	foreach ( $TypeOFvideos as $media ) :

		$file_type	= $media->file_type;
		$videoUrl		= $media->file;
	if ( ! empty( $media->tags_name ) ) {
		$tag_name = $media->tags_name;
	}
		$views					= $media->hitcount;
		$opimage			= $media->opimage;
		$image					= $media->image;
		$post_date	= $media->post_date;
		## Get thumb image detail
	if ( $image == '' ) {
		$image = $_imagePath . 'nothumbimage.jpg';
	} else {
		if ( $file_type == 2 ) {
			$image = $image_path . $image;
		}
	}
	## Get preview image detail
	if ( $opimage == '' ) {
		$opimage = $_imagePath . 'noimage.jpg';
	} else {
		if ( $file_type == 2 ) {
			$opimage = $image_path . $opimage;
		}
	}
	## Get video url detail
	if ( $videoUrl != '' ) {
		if ( $file_type == 2 ) {
			$videoUrl = $image_path . $videoUrl;
		}
	}
		?>
		<item>
			<title><?php echo balanceTags( get_the_title( $media->ID ) ); ?></title>
			<videoId><?php echo balanceTags( $media->vid ); ?></videoId>
			<videoUrl><?php echo balanceTags( $videoUrl ); ?></videoUrl>
			<thumbImage><?php echo balanceTags( $image ); ?></thumbImage>
			<previewImage><?php echo balanceTags( $opimage ); ?></previewImage>
			<views><?php echo balanceTags( $views ); ?></views>
			<createdDate><?php echo balanceTags( $post_date ); ?></createdDate>
			<description><![CDATA[<?php echo balanceTags( $media->description ); ?>]]></description>
			<tags><![CDATA[<?php echo balanceTags( $tag_name ); ?>]]></tags>
			<guid><?php echo balanceTags( get_permalink( $media->ID ) ); ?></guid>
		</item>
	<?php
	endforeach;
}
?>
	</channel>
</rss>