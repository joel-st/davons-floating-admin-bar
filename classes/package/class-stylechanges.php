<?php

namespace Davon\FloatingAdminBar\Package;

class StyleChanges {

	public function run() {

		add_action( 'get_header', [ $this, 'remove_admin_bar_styles' ] );
		add_action( 'wp_head', [ $this, 'custom_admin_bar_styles' ] );

	}

	/**
	 * Removes admin bar styles
	 *
	 * @since    1.0.0
	 */
	public function remove_admin_bar_styles() {
		remove_action( 'wp_head', '_admin_bar_bump_cb' );
	}

	/**
	 * Adds custom admin bar styles
	 *
	 * @since    1.0.0
	 */
	public function custom_admin_bar_styles() {
		$admin_bar_styles = '<style>
			#wpadminbar {
				top: -30px;
				height: 40px;
				background: none;
				transition: top 0.1s ease-out;
			}

			#wpadminbar:hover {
				top: 0;
			}

			#wp-toolbar {
				background: #23282d;
				height: 32px;
			}

			#wpadminbar .ab-top-secondary {
				position: absolute;
				right: 0;
			}

			@media screen and (max-width: 782px) {
				html #wpadminbar {
					top: -46px;
					height: 50px;
				}

				html #wp-toolbar {
					height: 46px;
				}
			}
		</style>';
		if ( is_user_logged_in() ) {
			echo $this->minify_css( $admin_bar_styles );
		}
	}

	/**
	 * This function takes a css-string and compresses it, removing
	 * unneccessary whitespace, colons, removing unneccessary px/em
	 * declarations etc.
	 *
	 * @since 1.0.0
	 */
	public function minify_css( $css ) {
		// some of the following functions to minimize the css-output are directly taken
		// from the awesome CSS JS Booster: https://github.com/Schepp/CSS-JS-Booster
		// all credits to Christian Schaefer: http://twitter.com/derSchepp
		// remove comments
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		// backup values within single or double quotes
		preg_match_all( '/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER );
		for ( $i = 0; $i < count( $hit[1] ); $i++ ) {
			$css = str_replace( $hit[1][ $i ], '##########' . $i . '##########', $css );
		}
		// remove traling semicolon of selector's last property
		$css = preg_replace( '/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css );
		// remove any whitespace between semicolon and property-name
		$css = preg_replace( '/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css );
		// remove any whitespace surrounding property-colon
		$css = preg_replace( '/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css );
		// remove any whitespace surrounding selector-comma
		$css = preg_replace( '/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css );
		// remove any whitespace surrounding opening parenthesis
		$css = preg_replace( '/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css );
		// remove any whitespace between numbers and units
		$css = preg_replace( '/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css );
		// shorten zero-values
		$css = preg_replace( '/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css );
		// constrain multiple whitespaces
		$css = preg_replace( '/\p{Zs}+/ims', ' ', $css );
		// remove newlines
		$css = str_replace( array( "\r\n", "\r", "\n" ), '', $css );
		// Restore backupped values within single or double quotes
		for ( $i = 0; $i < count( $hit[1] ); $i++ ) {
			$css = str_replace( '##########' . $i . '##########', $hit[1][ $i ], $css );
		}
		return $css;
	}

}
