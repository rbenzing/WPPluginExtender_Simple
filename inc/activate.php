<?php
/**
 * @package WPPluginExtender
 */

class WPPluginExtenderActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}