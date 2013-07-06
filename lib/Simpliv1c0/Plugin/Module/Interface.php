<?php
/**
 * Plugin Module Interface
 *
 * @author Mike Ems
 * @package Simpli
 *
 */

interface Simpliv1c0_Plugin_Module_Interface {

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
	 * @param Simpliv1c0_Plugin $plugin
	 * @return Simpliv1c0_Plugin_Module
	 * @uses Simpliv1c0_Plugin
	 */
	public function setPlugin( Simpliv1c0_Plugin $plugin );

	/**
	 * Get Plugin
	 *
	 * @param none
	 * @return Simpliv1c0_Plugin
	 */
	public function getPlugin();
}