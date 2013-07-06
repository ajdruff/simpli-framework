<?php
/**
 * Plugin Module
 *
 * Each Module in the project will extend this base Module class.
 * Modules can be treated as independent plugins. Think of them as sub-plugins.
 *
 * @author Mike Ems
 * @package Simpli
 */
class Simpli_Plugin_Module implements Simpli_Plugin_Module_Interface {

	/**
	 * Plugin object that this module extends
	 *
	 * @var Simpli_Plugin
	 */
	protected $_plugin;

	/**
	 *
	 * Initializes the module
	 * @param none
	 * @return void
	 */
	public function init() {
		throw new Exception('No init method in ' . get_class($this));
	}

	/**
	 * Set Plugin
	 *
	 * @param Simpli_Plugin $plugin
	 * @return object $this
	 * @uses Simpli_Plugin
	 */
	public function setPlugin( Simpli_Plugin $plugin ) {
		$this->_plugin = $plugin;
		return $this;
	}

	/**
	 * Get Plugin
	 *
	 * @param none
	 * @return Simpli_Plugin
	 */
	public function getPlugin() {
		if ( ! isset($this->_plugin) ) {
			die('Module ' . __CLASS__ . ' missing Plugin dependency.');
		}

		return $this->_plugin;
	}

}