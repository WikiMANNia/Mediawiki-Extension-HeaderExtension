<?php
/**
 * Hooks for HeaderExtension extension
 *
 * @file
 * @ingroup Extensions
 */

namespace MediaWiki\Extension\HeaderExtension;

use MediaWiki\Hook\BeforePageDisplayHook;

// Class aliases for multi-version compatibility.
// These need to be in global scope so phan can pick up on them,
// and before any use statements that make use of the namespaced names.
if ( version_compare( MW_VERSION, '1.41', '<' ) ) {
	class_exists( 'MediaWiki\Config\Config' ) or class_alias( '\Config', '\MediaWiki\Config\Config' );
	class_exists( 'MediaWiki\Output\OutputPage' ) or class_alias( '\OutputPage', '\MediaWiki\Output\OutputPage' );
}

if ( version_compare( MW_VERSION, '1.44', '<' ) ) {
	class_exists( 'MediaWiki\Skin\Skin' ) or class_alias( '\Skin', '\MediaWiki\Skin\Skin' );
}

use MediaWiki\Config\Config;
use MediaWiki\Output\OutputPage;
use MediaWiki\Skin\Skin;

/**
 * @phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
 */
class Hooks implements BeforePageDisplayHook {

	private Config $config;

	/**
	 * @param GlobalVarConfig $config
	 */
	public function __construct(
		Config $config
	) {
		$this->config = $config;
	}

	/**
	 * Code for adding the head script to the wiki
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return void This hook must not abort, it must return no value
	 */
	public function onBeforePageDisplay( $out, $skin ) : void {

		$_array = $this->config->get( "HeaderHeadItems" );
		if ( is_array( $_array ) && ( count( $_array ) > 0 ) ) {

			$i=0;
			foreach ( $_array as $value ) {
				$out->addHeadItem( "HeaderInserts_$i", $value );
				$i++;
			}
		}

		$_array = $this->config->get( "HeaderMetaItems" );
		if ( is_array( $_array ) && ( count( $_array ) > 0 ) ) {

			foreach ( $_array as $key => $value ) {
				$out->addMeta( $key, $value );
			}
		}
	}
}
