<?php
/**
 * Manage Maps
 *
 * @package Maps
 */
  $form = new WPGMP_Template();
  $form->show_header();
if ( class_exists( 'FlipperCode_List_Table_Helper' ) and ! class_exists( 'Wpgmp_Maps_Table' ) ) {

	/**
	 * Display maps manager.
	 */
	class Wpgmp_Maps_Table extends FlipperCode_List_Table_Helper {
		/**
		 * Intialize manage category table.
		 *
		 * @param array $tableinfo Table's properties.
		 */
		public function __construct( $tableinfo ) {
			parent::__construct( $tableinfo ); }
		/**
		 * Output for Shortcode column.
		 *
		 * @param array $item Map Row.
		 */
		public function column_shortcodes( $item ) {
			
			$tooltip = "<div class='fc-tooltip'><a href='javascript:void(0);' data-toggle='tooltip' title='Copy Shortcode To Clipboard!' data-clipboard-text='[put_wpgm id=" . $item->map_id . "]' class='copy_to_clipboard'><img src='" . WPGMP_IMAGES . "copy-to-clipboard.png'></a>
				<span class='fc-tooltiptext fc-tooltip-top'>Shortcode has been copied to clipboard.</span>
				</div>";

			echo '<b>[put_wpgm id=' . $item->map_id . ']</b>&nbsp;&nbsp;'. $tooltip; 

		}
		/**
		 * Clone of the map.
		 *
		 * @param  integer $item Map ID.
		 */
		public function copy() {
			$map_id       = intval( $_GET['map_id'] );
			$modelFactory = new WPGMP_Model();
			$map_obj      = $modelFactory->create_object( 'map' );
			$map          = $map_obj->copy( $map_id );
			$this->prepare_items();
			$this->listing();
		}

	}

	global $wpdb;
	$columns   = array(
		'map_title'      => esc_html__( 'Map Title', 'wpgmp-google-map' ),
		'map_width'      => esc_html__( 'Map Width', 'wpgmp-google-map' ),
		'map_height'     => esc_html__( 'Map Height', 'wpgmp-google-map' ),
		'map_zoom_level' => esc_html__( 'Zoom Level', 'wpgmp-google-map' ),
		'map_type'       => esc_html__( 'Map Type', 'wpgmp-google-map' ),
		'shortcodes'     => esc_html__( 'Map Shortcode', 'wpgmp-google-map' )
	);
	$sortable  = array( 'map_title', 'map_width', 'map_height', 'map_zoom_level', 'map_type' );
	$tableinfo = array(
		'table'                   => $wpdb->prefix . 'create_map',
		'textdomain'              => 'wpgmp-google-map',
		'singular_label'          => esc_html__( 'map', 'wpgmp-google-map' ),
		'plural_label'            => esc_html__( 'maps', 'wpgmp-google-map' ),
		'admin_listing_page_name' => 'wpgmp_manage_map',
		'admin_add_page_name'     => 'wpgmp_form_map',
		'primary_col'             => 'map_id',
		'columns'                 => $columns,
		'sortable'                => $sortable,
		'per_page'                => 20,
		'actions'                 => array( 'edit', 'delete', 'copy' ),
		'bulk_actions'            => array( 'delete' => esc_html__( 'Delete', 'wpgmp-google-map' ) ),
		'col_showing_links'       => 'map_title',
		'searchExclude'           => array( 'shortcodes' ),
		'translation' => array(
			'manage_heading'      => esc_html__( 'Manage Maps', 'wpgmp-google-map' ),
			'add_button'          => esc_html__( 'Add Map', 'wpgmp-google-map' ),
			'delete_msg'          => esc_html__( 'Map was deleted successfully.', 'wpgmp-google-map' ),
			'insert_msg'          => esc_html__( 'Map was added successfully.', 'wpgmp-google-map' ),
			'update_msg'          => esc_html__( 'Map was updated successfully.', 'wpgmp-google-map' ),
			'search_text'         => esc_html__( 'Search', 'wpgmp-google-map' ),
		),
	);
	$obj       = new Wpgmp_Maps_Table( $tableinfo );
}
