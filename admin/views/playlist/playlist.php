<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: playlist model file.
Version: 2.3.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
?>
<?php
$page = '';
if (isset($_GET['pagenum'])){
        $page = '&pagenum=' . $_GET['pagenum'];}
?>
<script type="text/javascript">
    // When the document is ready set up our sortable with it's inherant function(s)
    var dragdr = jQuery.noConflict();
    var videoid = new Array();
    dragdr(document).ready(function() {
        dragdr("#test-list").sortable({
            handle : '.handle',
            update : function () {
                var order = dragdr('#test-list').sortable('serialize');
                orderid= order.split("listItem[]=");
                for(i=1;i<orderid.length;i++)
                {
                    videoid[i]=orderid[i].replace('&',"");
                    oid= "ordertd_"+videoid[i];
                }
                dragdr.post("<?php echo get_bloginfo('url'); ?>/wp-admin/admin-ajax.php?action=playlistsortorder<?php echo $page; ?>",order);
            }
        });
    });
</script>
<div class="apptha_gallery">

    <!--   MENU OPTIONS STARTS  --->
    <h2 class="nav-tab-wrapper">
        <a href="?page=video" class="nav-tab"><?php _e('All Videos', 'video_gallery'); ?></a>
        <a href="?page=playlist" class="nav-tab nav-tab-active"><?php _e('Categories', 'video_gallery'); ?></a>
        <a href="?page=videoads" class="nav-tab"><?php _e('Video Ads', 'video_gallery'); ?></a>
        <a href="?page=hdflvvideosharesettings" class="nav-tab"><?php _e('Settings', 'video_gallery'); ?></a>
    </h2>
    <!--  MENU OPTIONS ENDS --->
    <div class="wrap">
        <h2 class="option_title">
            <?php echo "<img src='" . APPTHA_VGALLERY_BASEURL . "/images/manage_list.png' alt='move' width='30'/>"; ?>
            <?php _e('Manage Categories', 'video_gallery'); ?>
            <a class="button-primary" href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=newplaylist" ><?php _e('Add Category', 'video_gallery'); ?></a>
        </h2>

<?php if ($displayMsg): ?>
            <div class="updated below-h2">
                <p>
<?php echo $displayMsg; ?>
                    </p>
                </div>
<?php
                endif;
                $orderField = filter_input(INPUT_GET, 'order');
                $direction = isset($orderField) ? $orderField : false;
                $reverse_direction = ($direction == 'DESC' ? 'ASC' : 'DESC');
                if (isset($_REQUEST["playlistsearchbtn"])) {
 ?>
                    <div  class="updated below-h2">
            <?php
                    $url = get_bloginfo('url') . '/wp-admin/admin.php?page=playlist';
                    $searchmsg = filter_input(INPUT_POST, 'PlaylistssearchQuery');
                    if (count($gridPlaylist)) {
                        echo count($gridPlaylist) . "   "._e('Search Result(s) for', 'video_gallery')." '" . $searchMsg . "'.&nbsp&nbsp&nbsp<a href='$url' >"._e('Back to Category List', 'video_gallery')."</a>";
                    } else {
                        echo " "._e('No Search Result(s) for', 'video_gallery')." '" . $searchMsg . "'.&nbsp&nbsp&nbsp<a href='$url' >"._e('Back to Category List', 'video_gallery')."</a>";
                    }
            ?> </div> <?php } ?>
            <form name="Playlists" action="" method="post" onsubmit="return Playlistsearch();">
                <p class="search-box">
                    <input type="text"  name="PlaylistssearchQuery" id="PlaylistssearchQuery" value="<?php if (isset($searchmsg))
                    echo $searchmsg; ?>">
                    <input type="hidden"  name="page" value="Playlists">
                    <input type="submit" name="playlistsearchbtn"  class="button" value="<?php _e('Search Categories', 'video_gallery'); ?>"></p>
            </form>
            <form  name="Playlistsfrm" action="" method="post" onsubmit="return PlaylistdeleteIds()">
                <div class="alignleft actions bulk-actions">
                    <select name="playlistactionup" id="playlistactionup">
                        <option value="-1" selected="selected">
                            <?php _e('Bulk Actions', 'video_gallery'); ?>
                        </option>
                        <option value="playlistdelete">
                            <?php _e('Delete', 'video_gallery'); ?>
                        </option>
                    </select>
                    <input type="submit" name="playlistapply"  class="button-secondary action" value="<?php _e('Apply', 'video_gallery'); ?>">
                </div>
 <?php
                    $limit = 20;
                    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
                    $total = $Playlist_count;
                    $num_of_pages = ceil($total / $limit);
                    $page_links = paginate_links(array(
                                'base' => add_query_arg('pagenum', '%#%'),
                                'format' => '',
                                'prev_text' => __('&laquo;', 'aag'),
                                'next_text' => __('&raquo;', 'aag'),
                                'total' => $num_of_pages,
                                'current' => $pagenum
                            ));

                    if ($page_links) {
                        echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
                    }
            ?>
                <div style="float:right ; font-weight: bold;" ><?php if (isset($pagelist))
                    echo $pagelist; ?></div>
                <div style="clear: both;"></div>
                <table class="wp-list-table widefat fixed tags" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col"  class="manage-column column-cb check-column" style="">
                                <input type="checkbox" name="" >
                            </th>
                            <th width="3%" scope="col"  style="">
                                   <span>
                                    <?php _e('', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></th>
                            <th scope="col"  class="manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=playlist&orderby=id&order=<?php echo $reverse_direction; ?>">
                                    <span><?php _e('ID', 'video_gallery'); ?></span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col"  class="manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=playlist&orderby=title&order=<?php echo $reverse_direction; ?>">
                                    <span><?php _e('Title', 'video_gallery'); ?></span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" class="manage-column column-Expiry sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=playlist&orderby=publish&order=<?php echo $reverse_direction; ?>"><span><?php _e('Publish', 'video_gallery'); ?></span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" class="manage-column column-sortorder sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=playlist&orderby=sorder&order=<?php echo $reverse_direction; ?>"><span><?php _e('Order', 'video_gallery'); ?></span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="test-list" class="list:post"> <input type=hidden id=playlistid2 name=playlistid2 value="1"  >
                    <div name=txtHint ></div>
<?php
                $class = '';
                foreach ($gridPlaylist as $playlistView) {
                    $class = ( $class == 'class="alternate"' ) ? '' : 'class="alternate"';
?>
                <tr id="listItem_<?php echo $playlistView->pid; ?>" <?php echo $class; ?> >
                    <th scope="row" class="check-column">
                        <input type="checkbox" name="pid[]" value="<?php echo $playlistView->pid ?>">
                    </th>
                    <td>
                        <span class="hasTip content" title="<?php _e('Click and Drag', 'video_gallery'); ?>" style="padding: 6px;">
                            <img src="<?php echo APPTHA_VGALLERY_BASEURL . 'images/arrow.png'; ?>" alt="move"
                                 width="16" height="16" class="handle" />
                        </span>
                    </td>
                    <td class="id-column">
                        <a title="Edit <?php echo $playlistView->playlist_name; ?>" href="<?php echo $_SERVER["PHP_SELF"]; ?>?page=newplaylist&playlistId=<?php echo $playlistView->pid; ?>" ><?php echo $playlistView->pid; ?></a><div class="row-actions">
                    </td>
                    <td class="title-column">
                        <a title="Edit <?php echo $playlistView->playlist_name; ?>" class="row-title" href="<?php echo $_SERVER["PHP_SELF"]; ?>?page=newplaylist&playlistId=<?php echo $playlistView->pid; ?>" ><?php echo $playlistView->playlist_name; ?></a>
                    </td>
                    <td class="pub-column Expiry column-Expiry">
                        <?php
                        $status = 1;
                        $image = "deactivate.jpg";
                        $publish = __('Click here to Activate', 'video_gallery');
                        if ($playlistView->is_publish == 1) {
                            $status = 0;
                            $image = "activate.jpg";
                            $publish = __('Click here to Deactivate', 'video_gallery');
                        }
                        ?>
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=playlist<?php if(isset($_GET['pagenum'])) echo '&pagenum='.$_GET['pagenum']; ?>&playlistId=<?php echo $playlistView->pid; ?>&status=<?php echo $status; ?>">   <img src="<?php echo APPTHA_VGALLERY_BASEURL . 'images/' . $image ?>" title="<?php echo $publish; ?>"   /> </a>
                            </td>
                            <td class="order-column Expiry column-Expiry">
                <?php echo $playlistView->playlist_order; ?>
                            </td>
                        </tr>
                <?php
                    }

                    if (isset($_REQUEST["searchplaylistsbtn"])) {
                ?> <tr class="no-items"><td class="colspanchange" colspan="5">No Category found.</td></tr> <?php }
                    if (count($gridPlaylist) == 0) { ?>
                        <tr class="no-items"><td class="colspanchange" colspan="5">No Category found.</td></tr> <?php
                    }
                ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="col"  class="manage-column column-cb check-column" style="">
                                <input type="checkbox" name="" >
                            </th>
                            <th width="3%" scope="col"  style="">
                                   <span>
                                    <?php _e('', 'video_gallery'); ?> </span><span class="sorting-indicator"></span></th>
                            <th scope="col"  class="manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=playlist&orderby=id&order=<?php echo $reverse_direction; ?>">
                                    <span><?php _e('ID', 'video_gallery'); ?></span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col"  class="manage-column column-name sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=playlist&orderby=title&order=<?php echo $reverse_direction; ?>">
                                    <span><?php _e('Title', 'video_gallery'); ?></span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                            <th scope="col" class="manage-column column-Expiry sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=playlist&orderby=publish&order=<?php echo $reverse_direction; ?>"><span><?php _e('Publish', 'video_gallery'); ?></span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                             <th scope="col" class="manage-column column-sortorder sortable desc" style="">
                                <a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=playlist&orderby=sorder&order=<?php echo $reverse_direction; ?>"><span><?php _e('Order', 'video_gallery'); ?></span>
                                    <span class="sorting-indicator"></span>
                                </a>
                            </th>
                        </tr>
                    </tfoot>
                </table>
                <div style="clear: both;"></div>
                <div class="alignleft actions" style="margin-top:10px;">
                    <select name="playlistactiondown" id="playlistactiondown">
                        <option value="-1" selected="selected">
                            <?php _e('Bulk Actions', 'video_gallery'); ?>
                        </option>    
                        <option value="playlistdelete">
                            <?php _e('Delete', 'video_gallery'); ?>
                        </option>
                    </select>
                    <input type="submit" name="playlistapply"  class="button-secondary action" value="Apply">
                </div>
            <?php
                    if ($page_links) {
                        echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
                    }
            ?>
        </form>
    </div>
</div>