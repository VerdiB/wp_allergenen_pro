<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Allergens_Dietary_Pro_Allergen_Queries' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/DB/allergen.php';
}

if ( ! class_exists( 'Allergens_Dietary_Pro_Form' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_form.php';
}

if ( ! class_exists( 'Allergens_Dietary_Pro_Notices' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/notice/notice.php';
}

if ( ! class_exists( 'Allergens_Dietary_Show_Allergens' ) ) {
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/tables/allergen_show_allergen.php';
}

/**
 * @class Allergens_Dietary_Pro_Show_Allergens
 * @brief Class that shows the allergens
 * the user can see the already created allergies
 * @author Ictoria
 * @date 24-9-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Pro_Show_Allergens extends Allergens_Dietary_Show_Allergens {

	// Page is statisch zodat er maar 1 is, en de zelfde waarde blijft.

	protected function __construct() {
		parent::__construct();

		// if ( ! empty( static::$message ) ) {
		// 	$type   = Notice_Types::INFO;
		// 	$notice = Allergens_Dietary_Pro_Notices::getInstance();
		// 	$notice->display_admin_notice( $type, static::$message );
		// }

		// $notice = Allergens_Dietary_Pro_Notices::getInstance();
		// $notice->display_admin_notice(Notice_Types::WARNING, __('is great success', 'allergens-dietary-pro'));
	}

	protected $table_action_options = array( 'change_status', 'delete', 'quick_edit' );

	public function handle_row_actions( $item, $column_name, $primary ) {
		if ( $primary !== $column_name ) {
			return '';
		}
		$valid_actions = $this->table_action_options;

		$action_links = array();
		foreach ( $valid_actions as $action ) {
			$action_links[ $action ] = $this->build_action_url( $action, $item );
		}

		return $this->row_actions( $action_links );
	}


	protected function build_action_url( $action, $item ) {
		// loop-build actions for quick actions.
		$color      = 'blue';
		$disabled   = '';
		$is_default = Allergens_Dietary_Pro_Allergen_Queries::getInstance();
		if ( esc_attr( $action ) === 'delete' || esc_attr( $action ) === 'quick_edit' ) {
			$is_default = Allergens_Dietary_Pro_Allergen_Queries::getInstance()->is_default_allergen( $item['allergy_name'] );
		}

		( esc_attr( $action ) === 'delete' ) ? $color = 'red' : $color = 'blue';

		if ( $is_default ) {
			$disabled = 'none';
		} else {
			$disabled = 'auto';
		}

		/*
		While using quick_edit you always need to add a file.
		This can't be solved, because you can't put a value into
		a file input*/
		if ( esc_attr( $action ) !== 'quick_edit' && esc_attr( $action ) !== 'change_status' ) {
			return $is_default ? '<a style="color: grey;">' . ucfirst( str_replace( '_', ' ', $action ) ) . '</a>' : sprintf(
				'<a style="color: ' . $color . ';" href="?page=%s' . ( static::$_page > 0 ? '&paged=' . strval( static::$_page ) : '' ) . '&item=%s&action=%s&_wpnonce=%s">%s</a>',
				esc_attr( $_REQUEST['page'] ),
				esc_attr( $item['allergy_name'] ),
				esc_attr( $action ),
				wp_create_nonce( 'allergens_' . $action ),
				ucfirst( str_replace( '_', ' ', $action ) ),
			);
		} elseif ( esc_attr( $action ) === 'change_status' ) {
			return sprintf(
				'<a style="color: ' . $color . ';" href="?page=%s' . ( static::$_page > 0 ? '&paged=' . strval( static::$_page ) : '' ) . '&item=%s&action=%s&_wpnonce=%s">%s</a>',
				esc_attr( $_REQUEST['page'] ),
				esc_attr( $item['allergy_name'] ),
				esc_attr( $action ),
				wp_create_nonce( 'allergens_' . $action ),
				ucfirst( str_replace( '_', ' ', $action ) ),
			);
		} else {
			Allergens_Dietary_Pro_Form::setFormType( FormType::ALLERGENS );
			Allergens_Dietary_Pro_Form::getInstance( true )->showForm( esc_attr( $item['allergy_name'] ) );

			return $is_default ? '<a style="color: grey;">' . ucfirst( str_replace( '_', ' ', $action ) ) . '</a>' : sprintf(
				'<a class="%s" id="%s" style="color: ' . $color . '; pointer-events: %s;" href="#&item=%s">%s</a>',
				esc_attr( $action ),
				esc_attr( $item['allergy_name'] ),
				$disabled,
				esc_attr( $item['allergy_name'] ),
				ucfirst( str_replace( '_', ' ', $action ) ),
			);
		}
	}

	public function get_bulk_actions() {
		$actions                  = array();
		$actions['change_status'] = __( 'Change status', 'allergens-dietary-pro' );
		$actions['delete']        = __( 'Delete', 'allergens-dietary-pro' );
		return $actions;
	}

	public function prepare_items() {
		$data = Allergens_Dietary_Pro_Allergen_Queries::getItems();

		$this->_column_headers = array( $this->get_columns(), array(), array() );

		if ( ! empty( $this->search_query ) ) {
			$this->items = array_filter(
				$data,
				function ( $item ) {
					return stripos( $item['allergy_name'], $this->search_query ) !== false;
				}
			);
		} else {
			$this->items = $data;
		}

		$total_items = count( $this->items );

		$per_page     = $this->get_items_per_page( 'my_list_table_per_page', $this->get_items_per_pages() );
		$current_page = $this->get_pagenum();

		// Fetch data for the current page
		$this->items = array_slice( $this->items, ( $current_page - 1 ) * $per_page, $per_page );

		// Set pagination args
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}

	public function process_quick_action() {
		if ( isset( $_GET['action'] ) && isset( $_GET['item'] ) ) {
			$item   = sanitize_text_field( $_GET['item'] );
			$action = sanitize_text_field( $_GET['action'] );
			$nonce  = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

			// Verify nonce based on action
			if ( $action === 'change_status' && ! wp_verify_nonce( $nonce, 'allergens_change_status' ) ) {
				wp_die( 'Security check failed for changing status!' );
			} elseif ( $action === 'delete' && ! wp_verify_nonce( $nonce, 'allergens_delete' ) ) {
				wp_die( 'Security check failed for deletion!' );
			} elseif ( $action === 'quick_edit' && ! wp_verify_nonce( $nonce, 'allergens_delete' ) ) {
				wp_die( 'Security check failed for quick edit!' );
			}

			// Perform action based on case
			switch ( $action ) {
				case 'change_status':
                    error_log('case of change status');
					self::$message = __( 'Status changed', 'allergens-dietary-pro' );
					Allergens_Dietary_Pro_Allergen_Queries::getInstance()->singleActivationUpdate( );
					return self::$message;
				break;
				case 'delete':
					static::$message = __( 'Allergen deleted', 'allergens-dietary-pro' );
					Allergens_Dietary_Pro_Allergen_Queries::getInstance()->delete_allergen_by_name( $item, static::$_page, static::$message );
					return static::$message;
				break;
			}
		}
	}

	public function process_bulk_action( $data ) {
		// Check if nonce is set and not empty
		if ( isset( $_GET['_wpnonce'] ) && ! empty( $_GET['_wpnonce'] ) ) {
			$nonce  = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$action = $this->current_action();
			foreach ( $this->table_action_options as $bulk_action ) {
				if ( $action === $bulk_action ) {
					$nonce_action = 'bulk_' . $bulk_action;
					break;
				}
			}
			// Verify the nonce with the correct action
			if ( ! wp_verify_nonce( $nonce, $nonce_action ) ) {
				wp_die( 'Invalid token.' );
			}
		}

		if ( ! isset( $data['item'] ) ) {
			return;
		}

		$action = $this->current_action();
		switch ( $action ) {
			case 'change_status':
				$this->message = __( 'Allergen deleted', 'allergens-dietary-pro' );
				Allergens_Dietary_Pro_Allergen_Queries::getInstance()->activationUpdate( $data, $this->message );
				return $this->message;
				break;
			case 'delete':
				foreach ( $data['item'] as $allergy_name ) {
					$this->message = __( 'Allergen deleted', 'allergens-dietary-pro' );
					Allergens_Dietary_Pro_Allergen_Queries::getInstance()->delete_allergen_by_name( $allergy_name, $this->_page, $this->message );
					return $this->message;
				}
				break;
		}
	}
}

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

	// if ( isset( $_GET['messaged'] ) ) {
	// 	$type   = Notice_Types::INFO;
	// 	$notice = Allergens_Dietary_Pro_Notices::getInstance();
	// 	$notice->display_admin_notice( $type, htmlspecialchars( $_GET['messaged'] ) );
	// }

	if ( isset( $_POST['action'] ) ) {
		if ( $_POST['action'] = -1 ) {
			Allergens_Dietary_Pro_Form::setFormType( FormType::ALLERGENS );
			Allergens_Dietary_Pro_Form::getInstance()->submitUpdate();
		}
	}

	if ( isset( $_POST['action'] ) && isset( $_POST['post'] ) ) {
		$process_action = sanitize_text_field( $_POST['action'] );
		$process_item   = array_map( 'sanitize_text_field', $_POST['post'] );
		$process_data   = array(
			'action' => $process_action,
			'item'   => $process_item,
		);
		Allergens_Dietary_Pro_Show_Allergens::getInstance()->process_bulk_action( $process_data );
	}
	if ( isset( $_POST['search'] ) ) {
		$search_query        = sanitize_text_field( $_POST['search'] );
		$table               = Allergens_Dietary_Pro_Show_Allergens::getInstance();
		$table->search_query = $search_query;
		$table->prepare_items();
	}
} elseif ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
	if ( isset( $_GET['quick_edit'] ) ) {
		$table = Allergens_Dietary_Pro_Show_Allergens::getInstance();

		$table->process_quick_action();
	} else {

		if ( isset( $_GET['messaged'] ) ) {
			$type   = Notice_Types::INFO;
			$notice = Allergens_Dietary_Pro_Notices::getInstance();
			$notice->display_admin_notice( $type, htmlspecialchars( $_GET['messaged'] ) );
		}
		$table = Allergens_Dietary_Pro_Show_Allergens::getInstance();

		$table->process_quick_action();
	}
}
