<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://atakanau.blogspot.com/2022/10/auto-gallery-image-sync-wp-plugin.html
 * @since      1.0.0
 *
 * @package    Auto_Gallery_And_Image_Sync
 * @subpackage Auto_Gallery_And_Image_Sync/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Auto_Gallery_And_Image_Sync
 * @subpackage Auto_Gallery_And_Image_Sync/admin
 * @author     Your Name <email@example.com>
 */
/**
 * Adding WP List table class if it's not available.
 */
if ( ! class_exists( \WP_List_Table::class ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Auto_Gallery_And_Image_Sync_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Auto_Gallery_And_Image_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Auto_Gallery_And_Image_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$page = esc_attr( filter_input( INPUT_GET, 'page' ) );
		if ( $this->plugin_name == $page ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/auto-gallery-image-sync-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Auto_Gallery_And_Image_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Auto_Gallery_And_Image_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$page = esc_attr( filter_input( INPUT_GET, 'page' ) );
		if ( $this->plugin_name == $page ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/auto-gallery-image-sync-admin.js', array( 'jquery' ), $this->version, false );
		}

	}

	/**
	 * Register admin page.
	 * .
	 *
	 * @since    1.0.0
	 */
	public function init_options_page() {
		// add_action( 'admin_menu', $this, 'options_menu' );
		add_submenu_page(
			'tools.php', // parent page slug
			__('Automatic Gallery And Featured Image Sync',$this->plugin_name), // page <title>Title</title>
			__('Sync images',$this->plugin_name), // link text
			'manage_options', // user capabilities
			$this->plugin_name, // page slug
			array( $this, 'load_admin_page_content' ), // Calls function to require the partial
		);
		
	}

	/**
	 *
	 * @since    1.0.0
	 */
	public function options_menu() {
	}

	public function load_admin_page_content() {
		$agisync_atakanau = new sync_list_table_atakanau();
		$vals = array( 'plugin_name' => $this->plugin_name, 'version' => $this->version );
		$agisync_atakanau->set_vals($vals);
		require_once plugin_dir_path( __FILE__ ). 'partials/'.$this->plugin_name.'-admin-display.php';
	}
}

/**
 * Class sync_list_table_atakanau.
 *
 * @since 0.1.0
 * @package Admin_Table_Tut
 * @see WP_List_Table
 */
class sync_list_table_atakanau extends \WP_List_Table {

	/**
	 * Const to declare number of posts to show per page in the table.
	 */
	private $posts_per_page;

	/**
	 * Property to store post types
	 *
	 * @var  array Array of post types
	 */
	private $allowed_post_types;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Display messages.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array
	 */
	public $messages;

	/**
	 * Set common variables.
	 *
	 * @return void
	 */
	public function set_vals($new_val_arr) {
		$this->plugin_name = $new_val_arr['plugin_name'];
		$this->version = $new_val_arr['version'];
	}

	/**
	 * Draft_List_Table constructor.
	 */
	public function __construct() {

		parent::__construct(
			array(
				'singular' => 'Item',
				'plural'   => 'Items',
				'ajax'     => false,
			)
		);

		$this->allowed_post_types = $this->allowed_post_types();
		$this->posts_per_page = hexdec('a');
		$this->messages = array();

	}

	/**
	 * Retrieve post types to be shown in the table.
	 *
	 * @return array Allowed post types in an array.
	 */
	private function allowed_post_types() {
		$post_types = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );

		return $post_types;
	}

	/**
	 * Convert slug string to human readable.
	 *
	 * @param string $title String to transform human readable.
	 *
	 * @return string Human readable of the input string.
	 */
	private function human_readable( $title ) {
		return ucwords( get_post_type_object( $title )->labels->singular_name );
	}

	/**
	 * A map method return all allowed post types to human readable format.
	 *
	 * @return array Array of allowed post types in human readable format.
	 */
	private function allowed_post_types_readable() {
		$formatted = array_map(
			array( $this, 'human_readable' ),
			$this->allowed_post_types
		);

		return $formatted;
	}

	/**
	 * Return instances post object.
	 *
	 * @return WP_Query Custom query object with passed arguments.
	 */
	protected function get_posts_object() {
		$post_types = $this->allowed_post_types;

		$post_args = array(
			'post_type'      => $post_types,
			// 'post_status'    => array( 'draft' ),
			'posts_per_page' => $this->posts_per_page,
		);

		$paged = filter_input( INPUT_GET, 'paged', FILTER_VALIDATE_INT );

		if ( $paged ) {
			$post_args['paged'] = $paged;
		}

		$post_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );

		if ( $post_type ) {
			$post_args['post_type'] = $post_type;
			/* 
				if ( $post_type == 'product' ){
				$post_args['meta_query']	= array(
						array(
							// 'key'	=> '_product_image_gallery'
							'key'	=> '_sku'
						)
					);
			}
			*/
		}

		$orderby = sanitize_sql_orderby( filter_input( INPUT_GET, 'orderby' ) );
		$order   = esc_sql( filter_input( INPUT_GET, 'order' ) );

		if ( empty( $orderby ) ) {
			$orderby = 'date';
		}

		if ( empty( $order ) ) {
			$order = 'DESC';
		}

		$post_args['orderby'] = $orderby;
		$post_args['order']   = $order;

		$search = esc_sql( filter_input( INPUT_GET, 's' ) );
		if ( ! empty( $search ) ) {
			$post_args['s'] = $search;
		}

		return new \WP_Query( $post_args );
	}

	/**
	 * Display text for when there are no items.
	 */
	public function no_items() {
		esc_html_e( __( 'No posts found.',$this->plugin_name ) );
	}

	/**
	 * The Default columns
	 *
	 * @param  array  $item        The Item being displayed.
	 * @param  string $column_name The column we're currently in.
	 * @return string              The Content to display
	 */
	public function column_default( $item, $column_name ) {
		$result = '';
		switch ( $column_name ) {
			case 'status':
				$result = $item['status'];
				break;

			case 'date':
				$t_time    = get_the_time( 'Y/m/d g:i:s a', $item['id'] );
				$time      = get_post_timestamp( $item['id'] );
				$time_diff = time() - $time;

				if ( $time && $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
					/* translators: %s: Human-readable time difference. */
					$h_time = sprintf( __( '%s ago', 'admin-table-tut-main' ), human_time_diff( $time ) );
				} else {
					$h_time = get_the_time( 'Y/m/d', $item['id'] );
				}

				$result = '<span title="' . $t_time . '">' . apply_filters( 'post_date_column_time', $h_time, $item['id'], 'date', 'list' ) . '</span>';
				break;

			case 'syncid':
				$result = $item['syncid'];
				break;

			case 'syncsku':
				$result = $item['syncsku'];
				break;

			case 'type':
				$result = $item['type'];
				break;
			case 'present':
				$result = $item['present'];
				break;
		}

		return $result;
	}

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'		=> '<input type="checkbox"/>',
			'title'		=> __( 'Title', $this->plugin_name ),
			'type'		=> __( 'Type', $this->plugin_name ) . ' (' . __( 'Id', $this->plugin_name ) . ')' ,
			'date'		=> __( 'Date', $this->plugin_name ),
			'status'	=> __( 'Publish', $this->plugin_name ),
			'present'	=> __( 'Present', $this->plugin_name ),
			'syncid'	=> __( 'Match with ID', $this->plugin_name ),
			'syncsku'	=> __( 'Match with SKU', $this->plugin_name ),
		);
	}

	/**
	 * Return title column.
	 *
	 * @param  array $item Item data.
	 * @return string
	 */
	public function column_title( $item ) {
		/* 
		$edit_url    = get_edit_post_link( $item['id'] );
		$post_link   = get_permalink( $item['id'] );
		$delete_link = get_delete_post_link( $item['id'] );

		$output = '<strong>';

		// translators: %s: Post Title
		$output .= '<a class="row-title" href="' . esc_url( $edit_url ) . '" aria-label="' . sprintf( __( '%s (Edit)', 'admin-table-tut-main' ), $item['title'] ) . '">' . esc_html( $item['title'] ) . '</a>';
		$output .= _post_states( get_post( $item['id'] ), false );
		$output .= '</strong>';

		// Get actions.
		$actions = array(
			'edit'  => '<a href="' . esc_url( $edit_url ) . '">' . __( 'Edit', 'admin-table-tut-main' ) . '</a>',
			'trash' => '<a href="' . esc_url( $delete_link ) . '" class="submitdelete">' . __( 'Trash', 'admin-table-tut-main' ) . '</a>',
			'view'  => '<a href="' . esc_url( $post_link ) . '">' . __( 'View', 'admin-table-tut-main' ) . '</a>',
		);

		$row_actions = array();

		foreach ( $actions as $action => $link ) {
			$row_actions[] = '<span class="' . esc_attr( $action ) . '">' . $link . '</span>';
		}

		$output .= '<div class="row-actions">' . implode( ' | ', $row_actions ) . '</div>';
		*/
		$output = esc_html( $item['title'] );

		return $output;
	}

	/**
	 * Column cb.
	 *
	 * @param  array $item Item data.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s_id[]" value="%2$s" />',
			esc_attr( $this->_args['singular'] ),
			esc_attr( $item['id'] )
		);
	}

	/**
	 * Prepare the data for the WP List Table
	 *
	 * @return void
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$sortable              = $this->get_sortable_columns();
		$hidden                = array();
		$primary               = 'title';
		$this->_column_headers = array( $columns, $hidden, $sortable, $primary );
		$data                  = array();

		#region https://developer.wordpress.org/resource/dashicons/
		$my_icons = array(
			'none'=> '',
			'product'=> '<span class="dashicons dashicons-archive"></span>',
			'post'=> '<span class="dashicons dashicons-admin-post"></span>',
			'page'=> '<span class="dashicons dashicons-text-page"></span>',
			'image'=> '<span class="dashicons dashicons-format-image"></span>',
			'images'=> '<span class="dashicons dashicons-images-alt"></span>',
			'ok'=> '<span class="dashicons dashicons-yes-alt"></span>',
			'dismiss'=> '<span class="dashicons dashicons-dismiss"></span>',
			'external'=> '<span class="dashicons dashicons-external"></span>',
			'question'=> '<span class="dashicons dashicons-editor-help"></span>',
			'marker'=> '<span class="dashicons dashicons-marker"></span>',
		);
		#endregion

		$this->process_bulk_action();

		$get_posts_obj = $this->get_posts_object();

		if ( $get_posts_obj->have_posts() ) {

			while ( $get_posts_obj->have_posts() ) {

				$get_posts_obj->the_post();
				$post_id = get_the_ID();
				$my_post_type = get_post_type();
				$image_link= ' <a class="" target="_blank" href="'.get_the_post_thumbnail_url(null).'">'.$my_icons["external"].'</a>';
				
				$cell_present = has_post_thumbnail()?get_the_post_thumbnail(null,array(15,15)).$image_link:$my_icons["image"].$my_icons["dismiss"];
				#region post id
				$cell_matched_id = '';
				$title = '';
				$found = $this->get_matched_images($post_id);
				// $cell_matched_id = print_r($found ,true);
				$img_name_featured = $post_id . '-1';
				
				if( in_array( $img_name_featured, $found ) ){	// post has matched the featured image
					$cell_matched_id = $my_icons["image"];
					$title = $img_name_featured;
					
					if( ( $key = array_search($img_name_featured, $found)) !== false ){
						unset($found[$key]);
					}
					
					if( $my_post_type == 'product' && count($found) ){	// product has matched gallery images
						$title .= ' + ' . implode(', ', $found);
						// $cell_matched_id .= $my_icons["images"] . " " . count($found) ;
						$cell_matched_id .= ' + ' . $my_icons["images"] . ' ' . count($found);
					}
					if($title)
						$cell_matched_id = '<span title="'.$title.'">'.$cell_matched_id.'</span>';
				}
				#endregion

				#region product sku
				$cell_matched_sku = '';
				if( $my_post_type == 'product' ){
					$sku = get_post_meta(get_the_ID(),'_sku');
					
					if( count($sku) ){	// product has stock keeping unit
						$sku = $sku[0];
						$found = $this->get_matched_images($sku);
						// $cell_matched_sku = print_r($this->get_matched_images($sku) ,true);
						$img_name_featured = $sku . '-1';
						
						if( in_array( $img_name_featured, $found ) ){	// product has the featured image
							$cell_matched_sku = $my_icons["image"];
							$title = $img_name_featured;
							
							if( ( $key = array_search($img_name_featured, $found)) !== false ){
								unset($found[$key]);
							}
							
							if( count($found) ){	// product has gallery images
								$title .= ' + ' . implode(', ', $found);
								$cell_matched_sku .= " + " . $my_icons["images"] . " " . count($found);
							}
							if($title)
								$cell_matched_sku = '<span title="'.$title.'">'.$cell_matched_sku.'</span>';
						}
						
					}
					else{	// product has not stock keeping unit
						$cell_matched_sku = $my_icons["question"];
					}
					
					$gallery = $this->get_gallery_count($post_id);
					$cell_present .= ($gallery ? ' + ' : ' / ') . $my_icons["images"] . ($gallery ? ' ' . $gallery : $my_icons["dismiss"]);
				}
				$post_param = ($my_post_type=="page"?"page_id":"p").'=%1$s'.(get_post_status()!="publish"?"&post_type=".$my_post_type."&preview=true":"");
				$post_link = ' <a href="../?'.$post_param.'" target="_blank"><span style="color:silver">(#%1$s)</span></a>';
				// $post_link = ' <a href="../?p=%1$s'.(get_post_status()!="publish"?"&post_type=".$my_post_type."&preview=true":"").'" target="_blank"><span style="color:silver">(#%1$s)</span></a>';
				#endregion

				$data[ get_the_ID() ] = array(
					'id'		=> get_the_ID(),
					'title'		=> get_the_title(),
					'type'		=> $my_icons[$my_post_type]." ".ucwords( get_post_type_object( $my_post_type )->labels->singular_name ).sprintf($post_link,$post_id,$post_id),
					'date'		=> get_post_datetime(),
					'status'	=> $my_icons[get_post_status()=="publish"?"ok":"marker"],
					'present'	=> $cell_present,
					'syncid'	=> $cell_matched_id, // get_the_author(),
					'syncsku'	=> $cell_matched_sku
				);
			}
			wp_reset_postdata();
		}

		$this->items = $data;

		$this->set_pagination_args(
			array(
				'total_items' => $get_posts_obj->found_posts,
				'per_page'    => $get_posts_obj->post_count,
				'total_pages' => $get_posts_obj->max_num_pages,
			)
		);
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array(
			'sync_id' => __( 'Sync with Id',$this->plugin_name ),
			'sync_sku' => __( 'Sync with SKU (Pro)',$this->plugin_name ),
		);
	}

	/**
	 * Search for images in media library whose name matches key prefix.
	 *
	 * @return array matched image names
	 */
	public function get_matched_images($img_name_prefix) {

		global $wpdb;
		
		$result = array();
		// $result[] = $wpdb->remove_placeholder_escape($wpdb->prepare( $sql_text , "image%" ) );
		$images = $wpdb->get_results( $wpdb->prepare(
			"SELECT post_name FROM $wpdb->posts WHERE " // ID, 
			."`post_type` = 'attachment' "
			."AND post_mime_type LIKE %s "
			."AND post_name REGEXP '^(%1s-)[[:digit:]]+$' "
			."ORDER BY `post_name` ASC"
			, "image%", $img_name_prefix ) ); // , ARRAY_N
		if( count( $images ) != 0 ){
			foreach( $images as $image ){
				$result[] = $image->post_name;
			}
		}
		return $result;
	}
	/**
	 * Search the image gallery used in the product.
	 *
	 * @return int
	 */
	public function get_gallery_count($post_id) {

		global $wpdb;
		
		$result = 0;

		$images = $wpdb->get_results( $wpdb->prepare(
			"SELECT meta_value FROM $wpdb->postmeta WHERE "
			."`post_id` = %d "
			."AND `meta_key` = '_product_image_gallery' "
			, $post_id ) ); // , ARRAY_N
		if( count( $images ) != 0 ){
			$result = count( explode(',', $images[0]->meta_value) );
		}
		return $result;
	}

	/**
	 * Get bulk actions.
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		if ( 'sync_id' === $this->current_action() ) {
			$post_ids = filter_input( INPUT_GET, 'item_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$total_fi = 0;
			$text = '';
			// print_r($post_ids);
			if( gettype($post_ids) == "array" ){
				$totals = $this->bulk_sync_by_id($post_ids);
				if($totals[0])
					$text .= " " . sprintf( _n( "%d media file synced as featured image.", "%d media files synced as featured image."
						, $totals[0], $this->plugin_name ), number_format( $totals[0] ) );
				else
					$text .= " " . __( 'Featured image',$this->plugin_name ) . ": " . __( 'Synchronization could not be processed because no match was found.',$this->plugin_name );
				if($totals[1])
					$text .= " " . sprintf( _n( "Image gallery has been added to %d product.", "Image gallery has been added to %d products."
						, $totals[1], $this->plugin_name ), number_format( $totals[1] ) );
				else
					$text .= " " . __( 'Image gallery',$this->plugin_name ) . ": " . __( 'Synchronization could not be processed because no match was found.',$this->plugin_name );
				$this->messages[] = array( 'type' => 'success', 'text' => $text );
			}else{
				$this->messages[] = array( 'type' => 'error', 'text' => __( 'None selected.',$this->plugin_name ) );
			}
		}
		else if ( 'sync_sku' === $this->current_action() ) {
			$post_ids = filter_input( INPUT_GET, 'item_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$total_fi = 0;
			$text = '';
			// print_r($post_ids);
			if( gettype($post_ids) == "array" ){
				$totals = $this->bulk_sync_by_sku($post_ids);
				if($totals[0])
					$text .= " " . sprintf( _n( "%d media file synced as featured image.", "%d media files synced as featured image."
						, $totals[0], $this->plugin_name ), number_format( $totals[0] ) );
				else
					$text .= " " . __( 'Featured image',$this->plugin_name ) . ": " . __( 'Synchronization could not be processed because no match was found.',$this->plugin_name );
				if($totals[1])
					$text .= " " . sprintf( _n( "Image gallery has been added to %d product.", "Image gallery has been added to %d products."
						, $totals[1], $this->plugin_name ), number_format( $totals[1] ) );
				else
					$text .= " " . __( 'Image gallery',$this->plugin_name ) . ": " . __( 'Synchronization could not be processed because no match was found.',$this->plugin_name );
				$this->messages[] = array( 'type' => 'success', 'text' => $text );
			}else{
				$this->messages[] = array( 'type' => 'error', 'text' => __( 'None selected.',$this->plugin_name ) );
			}
		}
		else if ( 'trash' === $this->current_action() ) {
			$post_ids = filter_input( INPUT_GET, 'item_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( is_array( $post_ids ) ) {
				$post_ids = array_map( 'intval', $post_ids );

				if ( count( $post_ids ) ) {
					// array_map( 'wp_trash_post', $post_ids );
				}
			}
		}
	}

	/**
	 * Database actions
	 *
	 * @param array $id_arr target post ids.
	 *
	 * @return array index 0: inserted featured image count, index 1: inserted gallery count for woocommerce products
	 */
	protected function bulk_sync_by_id( $id_arr ){
		global $wpdb;
		
		$result = array(0,0);

		$result[0] = $wpdb->query( $wpdb->prepare( 
			"INSERT INTO $wpdb->postmeta (`post_id`, `meta_key`, `meta_value`) "
			."SELECT `item`.`ID` AS `post_id`, '_thumbnail_id' AS `meta_key`, `imgSync`.`ID` AS `meta_value` "
			."FROM $wpdb->posts AS `item` "
			."LEFT JOIN $wpdb->posts AS `imgSync` ON `imgSync`.`post_name`=CONCAT(`item`.`ID`, '-1') AND `imgSync`.`post_type` = 'attachment' AND `imgSync`.`post_mime_type` LIKE %s "
			."LEFT JOIN $wpdb->postmeta AS `img1` ON `img1`.`meta_key` = '_thumbnail_id' AND `img1`.`post_id` = `item`.`ID` "
			."WHERE `imgSync`.`post_name` IS NOT NULL AND `img1`.`meta_value` IS NULL AND `item`.`ID` IN(%1s) "
			 , "image%", implode(',',$id_arr)
		) );

		$result[1] = $wpdb->query( $wpdb->prepare(
			"INSERT INTO $wpdb->postmeta (`post_id`, `meta_key`, `meta_value`) "
			."SELECT `item`.`ID`,'_product_image_gallery' AS meta_key,GROUP_CONCAT(`imgSync`.`ID` ORDER BY `imgSync`.`ID` ASC SEPARATOR ',') AS meta_value "
			."FROM $wpdb->posts AS `item` "
			."LEFT JOIN $wpdb->posts AS `imgSync` ON "
			."`imgSync`.`post_name` REGEXP CONCAT('^(', `item`.`ID`, '-)[[:digit:]]+$') AND `imgSync`.`post_type` = 'attachment' AND `imgSync`.`post_mime_type` LIKE %s "
			."LEFT JOIN $wpdb->postmeta AS `imgG` ON "
			."`imgG`.`meta_key` = '_product_image_gallery' AND `imgG`.`post_id` = `item`.`ID` "
			."WHERE `item`.`post_type` = 'product' "
			."AND `imgSync`.`post_name` != CONCAT(`item`.`ID`, '-1') "
			."AND `imgSync`.`ID` IS NOT NULL "
			."AND `imgG`.`meta_value` IS NULL "
			."AND `item`.`ID` IN(%1s) "
			."GROUP BY `item`.`ID` "
			, "image%", implode(',',$id_arr) 
		) );
		return $result;
	}
	
	protected function bulk_sync_by_sku( $id_arr ){
		$result = array(0,0);
		return $result;
	}
	
	/**
	 * Generates the table navigation above or below the table
	 *
	 * @param string $which Position of the navigation, either top or bottom.
	 *
	 * @return void
	 */
	protected function display_tablenav( $which ) { ?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">

			<?php if ( $this->has_items() ) : ?>
			<div class="alignleft actions bulkactions">
				<?php $this->bulk_actions( $which ); ?>
			</div>
			<?php endif;
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>

			<br class="clear" />
		</div><?php }

	/**
	 * Overriden method to add dropdown filters column type.
	 *
	 * @param string $which Position of the navigation, either top or bottom.
	 *
	 * @return void
	 */
	protected function extra_tablenav( $which ) {

		if ( 'top' === $which ) {
			$drafts_dropdown_arg = array(
				'options'   => array( '' => __( 'All',$this->plugin_name ) ) + $this->allowed_post_types_readable(),
				'container' => array(
					'class' => 'alignleft actions',
				),
				'label'     => array(
					'class'      => 'screen-reader-text',
					'inner_text' => __( __( 'Filter by Post Type',$this->plugin_name ), 'admin-table-tut-main' ),
				),
				'select'    => array(
					'name'     => 'type',
					'id'       => 'filter-by-type',
					'selected' => filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING ),
				),
			);

			$this->html_dropdown( $drafts_dropdown_arg );

			submit_button( __( __( 'Filter',$this->plugin_name ), 'admin-table-tut-main' ), 'secondary', 'action', false );

		}
	}

	/**
	 * Navigation dropdown HTML generator
	 *
	 * @param array $args Argument array to generate dropdown.
	 *
	 * @return void
	 */
	private function html_dropdown( $args ) {
		?>

		<div class="<?php echo( esc_attr( $args['container']['class'] ) ); ?>">
			<label
				for="<?php echo( esc_attr( $args['select']['id'] ) ); ?>"
				class="<?php echo( esc_attr( $args['label']['class'] ) ); ?>">
			</label>
			<select
				name="<?php echo( esc_attr( $args['select']['name'] ) ); ?>"
				id="<?php echo( esc_attr( $args['select']['id'] ) ); ?>">
				<?php
				foreach ( $args['options'] as $id => $title ) {
					?>
					<option
					<?php if ( $args['select']['selected'] === $id ) { ?>
						selected="selected"
					<?php } ?>
					value="<?php echo( esc_attr( $id ) ); ?>">
					<?php echo esc_html( \ucwords( $title ) ); ?>
					</option>
					<?php
				}
				?>
			</select>
		</div>

		<?php
	}

	/**
	 * Include the columns which can be sortable.
	 *
	 * @return Array $sortable_columns Return array of sortable columns.
	 */
	public function get_sortable_columns() {

		return array(
			'title'  => array( 'title', false ),
			'type'   => array( 'type', false ),
			'date'   => array( 'date', false ),
			// 'status'   => array( 'status', false ),
			// 'present'  => array( 'present', false ),
			// 'syncid' => array( 'author', false ),
			// 'syncsku'=> array( 'syncsku', false ),
		);
	}
}

