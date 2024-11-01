<?php
/**
 * Simple Posts Order
 *
 * @package    Simple Posts Order
 * @subpackage SimplePostsOrder
/*
	Copyright (c) 2016- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$simplepostsorder = new SimplePostsOrder();

/** ==================================================
 * Main Functions
 */
class SimplePostsOrder {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.10
	 */
	public function __construct() {

		add_action( 'pre_get_posts', array( $this, 'sort_posts' ) );
		add_action( 'wp_print_styles', array( $this, 'load_styles' ) );
		add_shortcode( 'spo', array( $this, 'simplepostsorder_func' ) );

		/* Original hook */
		add_filter( 'spo_sort_links', array( $this, 'sort_links' ), 10, 2 );

	}

	/** ==================================================
	 * Main
	 *
	 * @param string $query  query.
	 * @since 1.00
	 */
	public function sort_posts( $query ) {

		if ( isset( $_GET['simplepostsorder_get'] ) && ! empty( $_GET['simplepostsorder_get'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_GET['simplepostsorder_get'] ) );
			if ( wp_verify_nonce( $nonce, 'spo_get' ) ) {
				if ( ! is_admin() && $query->is_main_query() ) {
					$simplepostsorder_option = get_option( 'simple_posts_order' );
					if ( $simplepostsorder_option['showsort'] ) {
						if ( ! empty( $_GET['sort_spo'] ) && ( 'DESC' === $_GET['sort_spo'] || 'ASC' === $_GET['sort_spo'] ) ) {
							$sort = sanitize_text_field( wp_unslash( $_GET['sort_spo'] ) );
						} else {
							$sort = 'DESC';
						}
						$orderby = null;
						if ( ! empty( $_GET['orderby_spo_1'] ) || ! empty( $_GET['orderby_spo_2'] ) || ! empty( $_GET['orderby_spo_3'] ) || ! empty( $_GET['orderby_spo_4'] ) || ! empty( $_GET['orderby_spo_5'] ) ) {
							if ( ! empty( $_GET['orderby_spo_1'] ) ) {
								$orderby .= sanitize_text_field( wp_unslash( $_GET['orderby_spo_1'] ) ) . ' ';
							}
							if ( ! empty( $_GET['orderby_spo_2'] ) ) {
								$orderby .= sanitize_text_field( wp_unslash( $_GET['orderby_spo_2'] ) ) . ' ';
							}
							if ( ! empty( $_GET['orderby_spo_3'] ) ) {
								$orderby .= sanitize_text_field( wp_unslash( $_GET['orderby_spo_3'] ) ) . ' ';
							}
							if ( ! empty( $_GET['orderby_spo_4'] ) ) {
								$orderby .= sanitize_text_field( wp_unslash( $_GET['orderby_spo_4'] ) ) . ' ';
							}
							if ( ! empty( $_GET['orderby_spo_5'] ) ) {
								$orderby .= sanitize_text_field( wp_unslash( $_GET['orderby_spo_5'] ) );
							}
							rtrim( $orderby, ' ' );
						}
					} else {
						$sort = $simplepostsorder_option['sort'];
						$orderby = $simplepostsorder_option['orderby'];
					}

					$query->set( 'order', $sort );
					$query->set( 'orderby', $orderby );
				}
			}
		}

	}

	/** ==================================================
	 * Sort links
	 *
	 * @param string $style  style.
	 * @param string $orderby  orderby.
	 * @return string $sortlinks
	 * @since 1.00
	 */
	public function sort_links( $style, $orderby ) {

		if ( isset( $_SERVER['HTTP_HOST'] ) && ! empty( $_SERVER['HTTP_HOST'] ) ) {
			$host = sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
		} else {
			return;
		}
		$uri = null;
		if ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
			$uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}

		$query = 'http://' . $host . $uri;
		if ( is_ssl() ) {
			$query = str_replace( 'http:', 'https:', $query );
		}

		if ( 'form' == $style ) {
			$arr_params = array( 'sort_spo', 'orderby_spo_1', 'orderby_spo_2', 'orderby_spo_3', 'orderby_spo_4', 'orderby_spo_5' );
			$query_string = remove_query_arg( $arr_params );
			$query_string = str_replace( '/?', '', $query_string );

			$query_html = null;
			if ( '/' <> $query_string ) {
				parse_str( $query_string, $query_strings );
				foreach ( $query_strings as $key => $value ) {
					if ( null <> $value ) {
						$query_html .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
					}
				}
			}
		}

		if ( ! empty( $_GET['sort_spo'] ) && ( 'DESC' === $_GET['sort_spo'] || 'ASC' === $_GET['sort_spo'] ) ) {
			$sort = sanitize_text_field( wp_unslash( $_GET['sort_spo'] ) );
		} else {
			$sort = 'DESC';
		}

		$sortnamedes = __( 'Des', 'simple-posts-order' );
		$sortnameasc = __( 'Asc', 'simple-posts-order' );

		$a_html = null;
		if ( 'DESC' === $sort ) {
			/* des */
			if ( 'form' == $style ) {
				$a_html = $sortnameasc . '<span class="dashicons dashicons-arrow-up"></span>';
			} else {
				$a_html = $sortnameasc;
			}
			$order = 'ASC';
		} else if ( 'ASC' === $sort ) {
			/* asc */
			if ( 'form' == $style ) {
				$a_html = $sortnamedes . '<span class="dashicons dashicons-arrow-down"></span>';
			} else {
				$a_html = $sortnamedes;
			}
			$order = 'DESC';
		}
		$query_arg_text = array( 'sort_spo' => $order ); /* for text */

		if ( empty( $orderby ) ) {
			if ( ! empty( $_GET['orderby_spo_1'] ) || ! empty( $_GET['orderby_spo_2'] ) || ! empty( $_GET['orderby_spo_3'] ) || ! empty( $_GET['orderby_spo_4'] ) || ! empty( $_GET['orderby_spo_5'] ) ) {
				if ( ! empty( $_GET['orderby_spo_1'] ) ) {
					$cheked_orderby1 = 'checked="checked"';
					$query_arg_text['orderby_spo_1'] = sanitize_text_field( wp_unslash( $_GET['orderby_spo_1'] ) );
				} else {
					$cheked_orderby1 = null;
				}
				if ( ! empty( $_GET['orderby_spo_2'] ) ) {
					$cheked_orderby2 = 'checked="checked"';
					$query_arg_text['orderby_spo_2'] = sanitize_text_field( wp_unslash( $_GET['orderby_spo_2'] ) );
				} else {
					$cheked_orderby2 = null;
				}
				if ( ! empty( $_GET['orderby_spo_3'] ) ) {
					$cheked_orderby3 = 'checked="checked"';
					$query_arg_text['orderby_spo_3'] = sanitize_text_field( wp_unslash( $_GET['orderby_spo_3'] ) );
				} else {
					$cheked_orderby3 = null;
				}
				if ( ! empty( $_GET['orderby_spo_4'] ) ) {
					$cheked_orderby4 = 'checked="checked"';
					$query_arg_text['orderby_spo_4'] = sanitize_text_field( wp_unslash( $_GET['orderby_spo_4'] ) );
				} else {
					$cheked_orderby4 = null;
				}
				if ( ! empty( $_GET['orderby_spo_5'] ) ) {
					$cheked_orderby5 = 'checked="checked"';
					$query_arg_text['orderby_spo_5'] = sanitize_text_field( wp_unslash( $_GET['orderby_spo_5'] ) );
				} else {
					$cheked_orderby5 = null;
				}
			} else { /* read admin orderby */
				$simplepostsorder_option = get_option( 'simple_posts_order' );
				$orderby_admin = $simplepostsorder_option['orderby'];
				if ( strpos( $orderby_admin, 'author' ) !== false ) {
					$cheked_orderby1 = 'checked="checked"';
					$query_arg_text['orderby_spo_1'] = 'author';
				} else {
					$cheked_orderby1 = null;
				}
				if ( strpos( $orderby_admin, 'title' ) !== false ) {
					$cheked_orderby2 = 'checked="checked"';
					$query_arg_text['orderby_spo_2'] = 'title';
				} else {
					$cheked_orderby2 = null;
				}
				if ( strpos( $orderby_admin, 'date' ) !== false ) {
					$cheked_orderby3 = 'checked="checked"';
					$query_arg_text['orderby_spo_3'] = 'date';
				} else {
					$cheked_orderby3 = null;
				}
				if ( strpos( $orderby_admin, 'modified' ) !== false ) {
					$cheked_orderby4 = 'checked="checked"';
					$query_arg_text['orderby_spo_4'] = 'modified';
				} else {
					$cheked_orderby4 = null;
				}
				if ( strpos( $orderby_admin, 'comment_count' ) !== false ) {
					$cheked_orderby5 = 'checked="checked"';
					$query_arg_text['orderby_spo_5'] = 'comment_count';
				} else {
					$cheked_orderby5 = null;
				}
			}
		} else { /* orderby of shortcode */
			if ( strpos( $orderby, 'author' ) !== false ) {
				$cheked_orderby1 = 'checked="checked"';
				$query_arg_text['orderby_spo_1'] = 'author';
			} else {
				$cheked_orderby1 = null;
			}
			if ( strpos( $orderby, 'title' ) !== false ) {
				$cheked_orderby2 = 'checked="checked"';
				$query_arg_text['orderby_spo_2'] = 'title';
			} else {
				$cheked_orderby2 = null;
			}
			if ( strpos( $orderby, 'date' ) !== false ) {
				$cheked_orderby3 = 'checked="checked"';
				$query_arg_text['orderby_spo_3'] = 'date';
			} else {
				$cheked_orderby3 = null;
			}
			if ( strpos( $orderby, 'modified' ) !== false ) {
				$cheked_orderby4 = 'checked="checked"';
				$query_arg_text['orderby_spo_4'] = 'modified';
			} else {
				$cheked_orderby4 = null;
			}
			if ( strpos( $orderby, 'comment_count' ) !== false ) {
				$cheked_orderby5 = 'checked="checked"';
				$query_arg_text['orderby_spo_5'] = 'comment_count';
			} else {
				$cheked_orderby5 = null;
			}
		}

		$label1 = __( 'Order by', 'simple-posts-order' );
		$label2 = __( 'Order', 'simple-posts-order' );

		$checklabel1 = __( 'Author' );
		$checklabel2 = __( 'Title' );
		$checklabel3 = __( 'Date', 'simple-posts-order' );
		$checklabel4 = __( 'Last updated' );
		$checklabel5 = __( 'Number of comments', 'simple-posts-order' );

		$nonce = wp_nonce_field( 'spo_get', 'simplepostsorder_get' );

		if ( 'form' == $style ) {
			$sortlinks = <<<SORTLINKS

<form method="get" action = "$query" >
$nonce
$query_html
<input type="hidden" name="sort_spo" value="$order">
$label1
<div style="display: block; padding:5px 5px;">
<div><input type="checkbox" name="orderby_spo_1" value="author" $cheked_orderby1> $checklabel1</div>
<div><input type="checkbox" name="orderby_spo_2" value="title" $cheked_orderby2> $checklabel2</div>
<div><input type="checkbox" name="orderby_spo_3" value="date" $cheked_orderby3> $checklabel3</div>
<div><input type="checkbox" name="orderby_spo_4" value="modified" $cheked_orderby4> $checklabel4</div>
<div><input type="checkbox" name="orderby_spo_5" value="comment_count" $cheked_orderby5> $checklabel5</div>
</div>
<div>$label2</div>
<div style="display: block; padding:5px 5px;">
<button type="submit">$a_html</button>
</div>
</form>
SORTLINKS;
		} else {
			$new_query = add_query_arg( $query_arg_text, $query );
			$sortlinks = '<a href="' . $new_query . '" title="' . $a_html . '">' . $a_html . '</a>';
		}

		return $sortlinks;

	}

	/** ==================================================
	 * Short code
	 *
	 * @param array  $atts  atts.
	 * @param string $html  html.
	 * @return string $html
	 * @since 1.00
	 */
	public function simplepostsorder_func( $atts, $html = null ) {

		$a = shortcode_atts(
			array(
				'style' => '',
				'orderby' => '',
			),
			$atts
		);
		$style   = $a['style'];
		$orderby = $a['orderby'];

		$simplepostsorder_option = get_option( 'simple_posts_order' );

		if ( $simplepostsorder_option['showsort'] ) {
			if ( empty( $style ) ) {
				$style = $simplepostsorder_option['style'];
			}
			$sortlinks = $this->sort_links( $style, $orderby );
			$html .= $sortlinks;
		}

		return $html;

	}

	/** ==================================================
	 * Load Dashicons
	 *
	 * @since 1.00
	 */
	public function load_styles() {
		wp_enqueue_style( 'dashicons' );
	}

}


