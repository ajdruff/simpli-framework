<?php
/**
 * Plugin Module Interface
 *
 * @author Mike Ems
 * @package Simpli
 *
 */

interface Simpli_Plugin_Module_Interface {

	/**
	 * Initializes the module
	 *
	 * @param none
	 * @return void
	 */
	public function init();

	/**
	 * Set Plugin
	 *
	 * @param Simpli_Plugin $plugin
	 * @return Simpli_Plugin_Module
	 * @uses Simpli_Plugin
	 */
	public function setPlugin( Simpli_Plugin $plugin );

	/**
	 * Get Plugin
	 *
	 * @param none
	 * @return Simpli_Plugin
	 */
	public function getPlugin();
}