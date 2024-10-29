<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://atakanau.blogspot.com/2022/10/auto-gallery-image-sync-wp-plugin.html
 * @since      1.0.0
 *
 * @package    Auto_Gallery_And_Image_Sync
 * @subpackage Auto_Gallery_And_Image_Sync/admin/partials
 */

$aau_agisync_support_link = "https://atakanau.blogspot.com/2022/10/auto-gallery-image-sync-wp-plugin.html";
			$agisync_atakanau->prepare_items();
?>

	<div class="wrap">
		<div class="notice notice-info is-dismissible"><p>
<table class="" width="100%">
	<tbody>
		<tr valign="top">
			<td>
<i class="dashicons dashicons-admin-home"></i> 
<?php _e('Visit my blog for documentation, support and feedback. The ads on the website may help me earn some tip. ;)', $this->plugin_name); ?>
			</td>
			<td>
<a href="https://atakanau.blogspot.com/2022/10/auto-gallery-image-sync-wp-plugin.html" target="_blank">atakanau.blogspot.com</a>
			</td>
		</tr>
		<tr valign="top">
			<td>
<i class="dashicons dashicons-wordpress-alt dashicons-wordpress"></i> 
<?php _e('Please help me continue development by giving the plugin a 5 star.', $this->plugin_name); ?>
			</td>
			<td>
<a target="_blank" href="https://wordpress.org/support/plugin/auto-gallery-image-sync/reviews/?filter=5#new-post">
<?php _e(translate('Rate',$this->plugin_name)); ?> ★★★★★</a>
			</td>
		</tr>
		<tr valign="top">
			<td>
<i class="dashicons dashicons-share"></i> 
<?php _e('Share plugin', $this->plugin_name); ?>:
			</td>
			<td>
<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo str_replace(':','%3A',$aau_agisync_support_link); ?>" target="_blank"><i class="dashicons dashicons-facebook"></i> Facebook</a> 
<a href="https://twitter.com/home?status=<?php echo str_replace(' ','%20',__('Automatic Gallery And Featured Image Sync', $this->plugin_name) ); ?>%20-%20<?php echo str_replace(' ','%20',__('Automatically sync posts (or WooCommerce Product) and media images as featured image and gallery.', $this->plugin_name) ); ?>%20%0A<?php echo str_replace(':','%3A',$aau_agisync_support_link); ?>" target="_blank"><i class="dashicons dashicons-twitter"></i> Twitter</a> 
<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo str_replace(':','%3A',$aau_agisync_support_link); ?>&title=<?php echo str_replace(' ','%20',__('Automatic Gallery And Featured Image Sync', $this->plugin_name) ); ?>&summary=&source=" target="_blank"><i class="dashicons dashicons-linkedin"></i> LinkedIn</a>
			</td>
		</tr>
	</tbody>
</table>
		</p></div>

		<div class="notice notice-warning is-dismissible"><p><?php echo __( 'Please backup your database first. The operation is irreversible!',$this->plugin_name ); ?></p></div>
	<?php foreach ( $agisync_atakanau->messages as $message ) {?>
		<div class="notice notice-<?php echo esc_html($message['type']); ?> is-dismissible"><p><?php echo esc_html($message['text']); ?></p></div>
	<?php
	} ?>
		<h2><?php esc_html_e( 'Automatic Gallery And Featured Image Sync',$this->plugin_name ); ?></h2>
		<form id="auto-gallery-image-sync" method="get">
			<input type="hidden" name="page" value="auto-gallery-image-sync" />

			<?php
			$agisync_atakanau->search_box( __( 'Search',$this->plugin_name ), 'search' );
			$agisync_atakanau->display();
			?>
		</form>
	</div>
