<?php

class SGI_SRLat_Cyr_Shortcode
{

	private $shortcodes;

	private $script;

	public static $instance = null;

    public static function get_instance()
    {

        if ( self::$instance == null) :
        	self::$instance = new SGI_SRLat_Cyr_Shortcode();
    	endif;

    	return self::$instance;

    }

	public function __construct()
	{

		global $srlat_script;

		$this->script = $srlat_script;
		$this->shortcodes = array();

		add_shortcode('srlat_cyr', array(&$this, 'do_shortcode') );

	}

	public function do_shortcode($atts, $content)
	{

		if ($this->script == 'cir')
			return $content;

		$uuid = uniqid();

		$this->shortcodes[$uuid] = $content;

		return $uuid;

	}

	public function get_shortcodes()
	{

		return $this->shortcodes;

	}

}