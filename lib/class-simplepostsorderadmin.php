<?php
/**
 * Simple Posts Order
 *
 * @package    SimplePostsOrder
 * @subpackage SimplePostsOrder Management screen
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

$simplepostsorderadmin = new SimplePostsOrderAdmin();

/** ==================================================
 * Management screen
 */
class SimplePostsOrderAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.10
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_settings' ) );

		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );

	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'simple-posts-order/simplepostsorder.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=SimplePostsOrder' ) . '">' . __( 'Settings' ) . '</a>';
		}
			return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_menu() {
		add_options_page( 'Simple Posts Order Options', 'Simple Posts Order', 'manage_options', 'SimplePostsOrder', array( $this, 'plugin_options' ) );
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$scriptname = admin_url( 'options-general.php?page=SimplePostsOrder' );

		$simplepostsorder_option = get_option( 'simple_posts_order' );

		?>

		<div class="wrap">
		<h2>Simple Posts Order</h2>

			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'simple-posts-order' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>

			<details style="margin-bottom: 5px;" open>
			<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><?php esc_html_e( 'Settings' ); ?></summary>
				<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
				<?php wp_nonce_field( 'spo_set', 'simplepostsorder_settings' ); ?>

				<div class="submit">
					<?php submit_button( __( 'Save Changes' ), 'large', 'Simplepostsorder_set_Save', false ); ?>
					<?php submit_button( __( 'Default' ), 'large', 'Default', false ); ?>
				</div>

				<div style="display: block; padding:5px 5px;">
					<h3><?php esc_html_e( 'Order and Display', 'simple-posts-order' ); ?></h3>
					<div style="display: block; padding:5px 20px;">
					<input type="radio" name="simplepostsorder_showsort" value="" <?php checked( '', $simplepostsorder_option['showsort'] ); ?> />
					<?php esc_html_e( 'Administrator to decide the order.', 'simple-posts-order' ); ?>
						<div style="display: block; padding:5px 35px;">
						<?php esc_html_e( 'Order by', 'simple-posts-order' ); ?>
						<div style="display: block; padding:5px 40px;">
						<div><input type="checkbox" name="simplepostsorder_orderby_1" value="author" 
						<?php
						if ( strpos( $simplepostsorder_option['orderby'], 'author' ) !== false ) {
							echo 'checked="checked"';}
						?>
						> <?php esc_html_e( 'Author' ); ?></div>
						<div><input type="checkbox" name="simplepostsorder_orderby_2" value="title" 
						<?php
						if ( strpos( $simplepostsorder_option['orderby'], 'title' ) !== false ) {
							echo 'checked="checked"';}
						?>
						> <?php esc_html_e( 'Title' ); ?></div>
						<div><input type="checkbox" name="simplepostsorder_orderby_3" value="date" 
						<?php
						if ( strpos( $simplepostsorder_option['orderby'], 'date' ) !== false ) {
							echo 'checked="checked"';}
						?>
						> <?php esc_html_e( 'Date' ); ?></div>
						<div><input type="checkbox" name="simplepostsorder_orderby_4" value="modified" 
						<?php
						if ( strpos( $simplepostsorder_option['orderby'], 'modified' ) !== false ) {
							echo 'checked="checked"';}
						?>
						> <?php esc_html_e( 'Last updated' ); ?></div>
						<div><input type="checkbox" name="simplepostsorder_orderby_5" value="comment_count" 
						<?php
						if ( strpos( $simplepostsorder_option['orderby'], 'comment_count' ) !== false ) {
							echo 'checked="checked"';}
						?>
						> <?php esc_html_e( 'Number of comments', 'simple-posts-order' ); ?></div>
						</div>
						<?php esc_html_e( 'Order' ); ?>
						<select id="simplepostsorder_sort" name="simplepostsorder_sort">
							<option value="DESC" 
							<?php
							if ( 'DESC' == $simplepostsorder_option['sort'] ) {
								echo 'selected="selected"';}
							?>
							><?php esc_html_e( 'Des', 'simple-posts-order' ); ?></option>
							<option value="ASC" 
							<?php
							if ( 'ASC' == $simplepostsorder_option['sort'] ) {
								echo 'selected="selected"';}
							?>
							><?php esc_html_e( 'Asc', 'simple-posts-order' ); ?></option>
						</select>
						</div>
					</div>
					<div style="display: block; padding:5px 20px;">
						<input type="radio" name="simplepostsorder_showsort" value="1" <?php checked( '1', $simplepostsorder_option['showsort'] ); ?> />
						<?php esc_html_e( 'Users to decide the order.', 'simple-posts-order' ); ?>
					</div>
					<div style="display: block; padding:5px 35px;">
						<?php esc_html_e( 'Style of Sort Link', 'simple-posts-order' ); ?> : 
						<select id="simplepostsorder_style" name="simplepostsorder_style">
							<option value="form" 
							<?php
							if ( 'form' == $simplepostsorder_option['style'] ) {
								echo 'selected="selected"';}
							?>
							>form</option>
							<option value="text" 
							<?php
							if ( 'text' == $simplepostsorder_option['style'] ) {
								echo 'selected="selected"';}
							?>
							>text</option>
						</select>
					</div>
					<div style="display: block; padding:5px 40px;">
					<?php
					$form_or_text_1_html = '<code>form</code>';
					$form_or_text_2_html = '<code>text</code>';
					$form_or_text_3_html = '<code>' . __( 'Order by', 'simple-posts-order' ) . '</code>';
					/* translators: Style form */
					$form_or_text_4 = sprintf( __( 'In the case of style %1$s, the %2$s will be the initial value is what the administrator has decided. The user can change the value.', 'simple-posts-order' ), $form_or_text_1_html, $form_or_text_3_html );
					/* translators: Style text orderby */
					$form_or_text_5 = sprintf( __( 'In the case of style %1$s, the %2$s will be those that the administrator has decided. The user can not change the value. However, you can specify a value in the shortcode.', 'simple-posts-order' ), $form_or_text_2_html, $form_or_text_3_html );
					?>
					<div><li><?php echo wp_kses_post( $form_or_text_4 ); ?></li></div>
					<div><li><?php echo wp_kses_post( $form_or_text_5 ); ?></li></div>
					</div>
				</div>

				<?php submit_button( __( 'Save Changes' ), 'large', 'Simplepostsorder_set_Save', true ); ?>

				</form>
			</details>

			<details style="margin-bottom: 5px;">
			<summary style="cursor: pointer; padding: 10px; border: 1px solid #ddd; background: #f4f4f4; color: #000;"><?php esc_html_e( 'How to use', 'simple-posts-order' ); ?></summary>
				<h3><?php esc_html_e( 'Set the widget', 'simple-posts-order' ); ?></h3>
				<?php
				$classic_widget_html = '<a href="' . admin_url( 'widgets.php' ) . '" style="text-decoration: none; word-break: break-all;">' . __( 'Widgets' ) . '[' . __( 'Posts Sort', 'simple-posts-order' ) . ']</a>';
				$block_widget_html = '<a href="' . admin_url( 'widgets.php' ) . '" style="text-decoration: none; word-break: break-all;">' . __( 'Shortcode', 'simple-posts-order' ) . '[spo]</a>';
				?>
				<div style="padding: 5px 20px; font-weight: bold;">
				<?php
				/* translators: Widget html */
				echo wp_kses_post( sprintf( __( 'Classic widget : Please set the %1$s.', 'simple-posts-order' ), $classic_widget_html ) );
				?>
				</div>
				<div style="padding: 5px 20px; font-weight: bold;">
				<?php
				/* translators: Widget html */
				echo wp_kses_post( sprintf( __( 'Block widget : Please set the %1$s.', 'simple-posts-order' ), $block_widget_html ) );
				?>
				</div>

				<h3><?php esc_html_e( 'Set up a shortcode to the template of the theme', 'simple-posts-order' ); ?></h3>

				<div style="padding: 5px 20px; font-weight: bold;"><?php esc_html_e( 'Example', 'simple-posts-order' ); ?></div>
				<div style="padding: 5px 35px;"><code>&lt;?php echo do_shortcode('[spo]'); ?&gt</code></div>
				<div style="padding: 5px 35px;"><code>&lt;?php echo do_shortcode('[spo style="text" orderby="title date"]'); ?&gt</code></div>

				<div style="padding: 5px 20px; font-weight: bold;"><?php esc_html_e( 'Description of each attribute', 'simple-posts-order' ); ?></div>

				<?php
				$styles1_html = '<code>form</code>';
				$styles2_html = '<code>text</code>';
				?>
				<div style="padding: 5px 35px;"><?php esc_html_e( 'Style of Sort Link', 'simple-posts-order' ); ?> : <code>style</code> <?php esc_html_e( 'Default' ); ?><code>style="form"</code></div>
				<div style="padding: 5px 50px;">
				<?php
				/* translators: Style */
				echo wp_kses_post( sprintf( __( 'Specify an %1$s form display or %2$s text display.', 'simple-posts-order' ), $styles1_html, $styles2_html ) );
				?>
				</div>
				<div style="padding: 5px 35px;"><?php esc_html_e( 'Order by', 'simple-posts-order' ); ?> : <code>orderby</code> 
				<?php esc_html_e( 'If blank read the value of the settings.', 'simple-posts-order' ); ?>
				</div>
				<div style="padding: 5px 50px;"><?php esc_html_e( 'One or more specified.', 'simple-posts-order' ); ?></div>
				<div style="padding: 5px 70px;">
				<li><code>author</code> : <?php esc_html_e( 'Order by author.', 'simple-posts-order' ); ?></li>
				<li><code>title</code> : <?php esc_html_e( 'Order by title.', 'simple-posts-order' ); ?></li>
				<li><code>date</code> : <?php esc_html_e( 'Order by date.', 'simple-posts-order' ); ?></li>
				<li><code>modified</code> : <?php esc_html_e( 'Order by last modified date.', 'simple-posts-order' ); ?></li>
				<li><code>comment_count</code> : <?php esc_html_e( 'Order by number of comments.', 'simple-posts-order' ); ?></li>
				</div>

				<div style="padding: 5px 20px; font-weight: bold;">
				<?php esc_html_e( 'Attribute value of short codes can also be specified in the settings. Attribute value of the short code takes precedence.', 'simple-posts-order' ); ?>
				</div>
			</details>

		</div>
		<?php

	}

	/** ==================================================
	 * Credit
	 *
	 * @since 1.00
	 */
	private function credit() {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( wp_normalize_path( $plugin_path ) );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __( 'Version:' ) . ' ' . $plugin_ver_num;
		/* translators: FAQ Link & Slug */
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'simple-posts-order' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'simple-posts-order' );

		?>
		<span style="font-weight: bold;">
		<div>
		<?php echo esc_html( $plugin_version ); ?> | 
		<a style="text-decoration: none;" href="<?php echo esc_url( $faq ); ?>" target="_blank" rel="noopener noreferrer">FAQ</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $support ); ?>" target="_blank" rel="noopener noreferrer">Support Forums</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $review ); ?>" target="_blank" rel="noopener noreferrer">Reviews</a>
		</div>
		<div>
		<a style="text-decoration: none;" href="<?php echo esc_url( $translate ); ?>" target="_blank" rel="noopener noreferrer">
		<?php
		/* translators: Plugin translation link */
		echo esc_html( sprintf( __( 'Translations for %s' ), $plugin_name ) );
		?>
		</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-video-alt3"></span></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'simple-posts-order' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php

	}

	/** ==================================================
	 * Update wp_options table.
	 *
	 * @since 1.00
	 */
	private function options_updated() {

		if ( isset( $_POST['Default'] ) && ! empty( $_POST['Default'] ) ) {
			if ( check_admin_referer( 'spo_set', 'simplepostsorder_settings' ) ) {
				$simple_posts_order_reset_tbl = array(
					'sort' => 'DESC',
					'orderby' => 'date',
					'showsort' => true,
					'style' => 'form',
				);
				update_option( 'simple_posts_order', $simple_posts_order_reset_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( __( 'Settings' ) . ' --> ' . __( 'Default' ) . ' --> ' . __( 'Changes saved.' ) ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['Simplepostsorder_set_Save'] ) && ! empty( $_POST['Simplepostsorder_set_Save'] ) ) {
			if ( check_admin_referer( 'spo_set', 'simplepostsorder_settings' ) ) {
				$simple_posts_order_tbl = get_option( 'simple_posts_order' );
				if ( ! empty( $_POST['simplepostsorder_sort'] ) ) {
					$simple_posts_order_tbl['sort'] = sanitize_text_field( wp_unslash( $_POST['simplepostsorder_sort'] ) );
				}
				$orderby = null;
				if ( ! empty( $_POST['simplepostsorder_orderby_1'] ) || ! empty( $_POST['simplepostsorder_orderby_2'] ) || ! empty( $_POST['simplepostsorder_orderby_3'] ) || ! empty( $_POST['simplepostsorder_orderby_4'] ) || ! empty( $_POST['simplepostsorder_orderby_5'] ) ) {
					if ( ! empty( $_POST['simplepostsorder_orderby_1'] ) ) {
						$orderby .= sanitize_text_field( wp_unslash( $_POST['simplepostsorder_orderby_1'] ) ) . ' ';
					}
					if ( ! empty( $_POST['simplepostsorder_orderby_2'] ) ) {
						$orderby .= sanitize_text_field( wp_unslash( $_POST['simplepostsorder_orderby_2'] ) ) . ' ';
					}
					if ( ! empty( $_POST['simplepostsorder_orderby_3'] ) ) {
						$orderby .= sanitize_text_field( wp_unslash( $_POST['simplepostsorder_orderby_3'] ) ) . ' ';
					}
					if ( ! empty( $_POST['simplepostsorder_orderby_4'] ) ) {
						$orderby .= sanitize_text_field( wp_unslash( $_POST['simplepostsorder_orderby_4'] ) ) . ' ';
					}
					if ( ! empty( $_POST['simplepostsorder_orderby_5'] ) ) {
						$orderby .= sanitize_text_field( wp_unslash( $_POST['simplepostsorder_orderby_5'] ) );
					}
					rtrim( $orderby, ' ' );
					$simple_posts_order_tbl['orderby'] = $orderby;
				} else {
					$simple_posts_order_tbl['orderby'] = 'date';
				}
				if ( ! empty( $_POST['simplepostsorder_showsort'] ) ) {
					$simple_posts_order_tbl['showsort'] = intval( $_POST['simplepostsorder_showsort'] );
				} else {
					$simple_posts_order_tbl['showsort'] = false;
				}
				if ( ! empty( $_POST['simplepostsorder_style'] ) ) {
					$simple_posts_order_tbl['style'] = sanitize_text_field( wp_unslash( $_POST['simplepostsorder_style'] ) );
				}
				update_option( 'simple_posts_order', $simple_posts_order_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( __( 'Settings' ) . ' --> ' . __( 'Changes saved.' ) ) . '</li></ul></div>';
			}
		}

	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 1.00
	 */
	public function register_settings() {

		if ( ! get_option( 'simple_posts_order' ) ) {
			$simple_posts_order_tbl = array(
				'sort' => 'DESC',
				'orderby' => 'date',
				'showsort' => true,
				'style' => 'form',
			);
			update_option( 'simple_posts_order', $simple_posts_order_tbl );
		}

	}

}


