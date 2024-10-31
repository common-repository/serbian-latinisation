<?php

class SGI_SRLat_Frontend
{

	private $cookie_lang;
	
	private $opts;

	public static $instance;

	public static function get_instance()
	{

		if (self::$instance === null)
			self::$instance = new self();

		return self::$instance;

	}

	protected function __construct()
	{

		$in_serbian = $this->multilanguage_check();
		
		$srlat_opts = get_option(
			'sgi_srlat_opts',
			[
				'core' => [
					'script'		 => 'cir',
					'fix_permalinks' => (get_option('WPLANG') == 'sr_RS') ? false : true,
					'fix_media'		 => true,
					'fix_search'	 => true,
				],
				'wpml' => [
					'extend_lang'	 => true
				]
			]
		);

		$this->opts = $srlat_opts;

		global $srlat_script;

		$srlat_script = $this->cookie_lang = $this->get_script();

		if ( ($this->cookie_lang == 'lat') && $in_serbian ) :

			add_action('wp_head', array(&$this,'buffer_start'), 99);
			add_action('wp_footer', array(&$this,'buffer_end'), 99);
			
			add_action('rss_head', array(&$this,'buffer_start'), 99);		
			add_action('rss_footer', array(&$this,'buffer_end'), 99);		

			add_action('atom_head', array(&$this,'buffer_start'), 99);		
			add_action('atom_footer', array(&$this,'buffer_end'), 99);		
			
			add_action('rdf_head', array(&$this,'buffer_start'), 99);		
			add_action('rdf_footer', array(&$this,'buffer_end'), 99);		
			
			add_action('rss2_head', array(&$this,'buffer_start'), 99);		
			add_action('rss2_footer', array(&$this,'buffer_end'), 99);	

			add_filter('gettext', array(&$this, 'convert_script'), 9);
			add_filter('gettext_with_context', array(&$this, 'convert_script'), 9);
			add_filter('ngettext', array(&$this, 'convert_script'), 9);
			add_filter('ngettext_with_context', array(&$this, 'convert_script'), 9);

			if (current_theme_supports( 'title-tag' )) :

				add_filter('wp_title',array (&$this,'convert_title'),200,3);

			else :

				add_filter('pre_get_document_title',array (&$this,'convert_title'),200,3);
				add_filter('document_title_parts',array (&$this,'convert_title_parts'),200,3);

			endif;

			
			

		endif;

		add_filter('posts_search', array(&$this,'fix_search'),20,2);

		if (is_wpml_active()) :

			add_filter('icl_ls_languages', array(&$this,'change_wpml_language_list'),50,1);

		elseif (function_exists('pll_the_languages')) :

			add_filter('init', [&$this, 'change_pll_language_list'], 100);

		elseif (function_exists('qtranxf_getLanguage')) :

			add_action('init',[&$this, 'change_qtx_language_list'],20);

		endif;

		self::$instance = $this;

	}

	private function multilanguage_check()
	{

		$in_serbian = true;

		if (function_exists('pll_the_languages')) :

			$cur_lang = pll_current_language('locale');

			if ($cur_lang == 'sr_RS') :

				$in_serbian = true;

			else : 

				$in_serbian = false;

			endif;


		elseif (function_exists('qtranxf_getLanguage')) :

			$cur_lang = qtranxf_getLanguage();

			if ($cur_lang == 'sr') :

				$in_serbian = true;

			else : 

				$in_serbian = false;

			endif; 

		elseif (is_wpml_active()) :

			$in_serbian = (ICL_LANGUAGE_CODE == 'sr') ? true : false;

		endif;

		return $in_serbian;

	}

	public function get_cookie_lang()
	{
		return $this->cookie_lang;
	}

	private function get_script()
	{
		if (!isset($_REQUEST['sr_pismo'])) :

			return $this->check_cookie();

		else :

			$lng = $_REQUEST['sr_pismo'];

			//$lng = ( ($lng != 'cir') || ($lng != 'lat') ) ? 'lat' : $lng;

			setcookie("sgi_pismo", $lng, strtotime("+3 months"), "/");

			return $lng;

		endif;

	}

	private function check_cookie()
	{

		if (isset($_COOKIE['sgi_pismo'])) :

			$lng = $_COOKIE['sgi_pismo'];
			//$lng = ( ($lng != 'cir') || ($lng != 'lat') ) ? 'lat' : $lng;

			return $lng;

		else :

			//echo 'ovde smo';

			setcookie("sgi_pismo", $this->opts['core']['script'], strtotime("+3 months"), "/");

			return $this->opts['core']['script'];

		endif;

	}

	public function change_wpml_language_list($active_languages)
	{

		if (!$this->opts['wpml']['extend_lang'])
			return $active_languages;

		$new_languages = $active_languages;

		$sr_cir = $sr_lat = $active_languages['sr'];

		$sr_cir['native_name'] = 'српски (ћир)';
		$sr_cir['translated_name'] = $sr_cir['translated_name'].' (cyr)';
		$sr_cir['url'] = $sr_lat['url'].'?sr_pismo=cir';

		$sr_lat['native_name'] = 'српски (lat)';
		$sr_lat['translated_name'] = $sr_lat['translated_name'].' (lat)';
		$sr_lat['url'] = $sr_lat['url'].'?sr_pismo=lat';

		unset($active_languages['sr']);

		foreach ($active_languages as $lang_code => $lang_data) :

			$new_languages[$lang_code] = $lang_data;

		endforeach;

		if ($this->cookie_lang == 'cir') :

			$new_languages['sr_lat'] = $sr_lat;
			$new_languages['sr'] = $sr_cir;

		else :

			$new_languages['sr'] = $sr_lat;
			$new_languages['sr_cir'] = $sr_cir;

		endif;

		return $new_languages;
	}

	public function change_pll_language_list()
	{

	}

	public function change_qtx_language_list()
	{
		
		global $q_config;
		$languages = $q_config['enabled_languages'];

		$q_config['enabled_languages'][] = 'sr@lat';

	}

	public function fix_search($search,$query)
	{

		if (!$this->opts['core']['fix_search'])
			return $search;

		if ( !$query->is_main_query()) :

			return $search;

		endif;

		if ($_GET['s'] == '')
			return $search;

		if (!is_search())
			return $search;

		$search = sgi_sql_search($search);

		return $search;

	}

	public function buffer_start()
	{

		ob_start();

	}

	public function buffer_end()
	{

		//ob_end_flush();
		$output = ob_get_clean();
		$output = $this->convert_script($output);

		$cir_shortcode = SGI_SRLat_Cyr_Shortcode::get_instance();
		$shortcodes = $cir_shortcode->get_shortcodes();

		echo strtr($output, $shortcodes);

	}

	public function convert_title($title,$sep = '',$location = '')
	{

		return $this->convert_script($title);

	}

	public function convert_title_parts($title)
	{	

		$newtitle = [];

		foreach ($title as $part => $value) :

			$newtitle[$part] = $this->convert_script($value);

		endforeach;

		return $newtitle;

	}

	public function convert_script($text)
	{

		return SGI_Translit_Core::cir_to_lat($text);

	}



}