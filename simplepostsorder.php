<?php
/**
 * Plugin Name: Simple Posts Order
 * Plugin URI:  https://wordpress.org/plugins/simple-posts-order/
 * Description: Sort the posts order.
 * Version:     1.16
 * Author:      Katsushi Kawamori
 * Author URI:  https://riverforest-wp.info/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-posts-order
 *
 * @package Simple Posts Order
 */

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

if ( ! class_exists( 'SimplePostsOrderAdmin' ) ) {
	require_once( dirname( __FILE__ ) . '/lib/class-simplepostsorderadmin.php' );
}
if ( ! class_exists( 'SimplePostsOrderWidgetItem' ) ) {
	require_once( dirname( __FILE__ ) . '/lib/class-simplepostsorderwidgetitem.php' );
}
if ( ! class_exists( 'SimplePostsOrder' ) ) {
	require_once( dirname( __FILE__ ) . '/lib/class-simplepostsorder.php' );
}


