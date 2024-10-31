<?php

/**
 * @package SGI\SRLat
 */


/**
 * 
 */
class SGI_SRLat_Admin_Core
{

	private $version;

	private $opts;

	public function __construct()
	{

		//Version check
		if ($srlat_ver = get_option('sgi_srlat_ver')) :

			if (version_compare(SGI_SRLAT_VERSION,$srlat_ver,'>')) :

				update_option('sgi_srlat_ver', SGI_SRLAT_VERSION);

			endif;

			$this->version = SGI_SRLAT_VERSION;

		else :

			$srlat_ver = SGI_SRLAT_VERSION;
			add_option('sgi_srlat_ver',$srlat_ver,'no');

		endif;

		//Options init
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
				],
				'polylang' => [
					'extend_lang'	 => true
				],
				'qtx'  => [
					'extend_lang'	 => true
				]
			]
		);

		$this->opts = $srlat_opts;

		add_action('admin_init', [&$this, 'register_settings']);
        add_action('admin_menu', [&$this, 'add_settings_menu']);

        add_filter('plugin_action_links_'.SGI_SRLAT_BASENAME, array(&$this,'add_settings_link'));

        add_action('admin_notices', [&$this, 'deprecate_plugin']);

	}

    public function deprecate_plugin()
    {
        printf(
            '<div class="notice notice-warning">
                <p>
                <strong>%s</strong> %s <br>
                %s <a href="%s">SrbTransLatin</a>
                </p>
            </div>',
            __('THIS PLUGIN IS DEPRECATED!!! It will not be developed any more, and it will be removed from the WP repo soon.', 'serbian-latinisation'),
            __('Please download and install SrbTransLatin 2.0.2', 'serbian-latinisation'),
            __('You can download the plugin on the following link', 'serbian-latinisation'),
            admin_url('plugin-install.php?s=srbtranslatin&tab=search&type=term')
        );
    }

	public function add_settings_link($links)
	{

		$links[] = sprintf(
			'<a href="%s">%s</a>',
			admin_url('options-general.php?page=sgi-sr-latinization'),
			__('Settings','serbian-latinisation')
		);

		return $links;

	}

	public function add_settings_menu()
	{

		add_submenu_page(
            'options-general.php',
            'Transliteration',
            'Transliteration',
            'manage_options',
            'sgi-sr-latinization',
            array(&$this, 'settings_callback')
        );

	}

	public function settings_callback()
	{

		printf (
			'<div class="wrap"><h1>%s</h1>',
			__('Serbian Transliteration Settings','serbian-latinisation')
		);

        echo '<form method="POST" action="options.php">';

        settings_fields('sgi_srlat_settings');

        do_settings_sections('sgi-sr-latinization');

        submit_button();

        echo "</form>";

        echo '</div>';

	}

	public function register_settings()
	{

		register_setting(
            'sgi_srlat_settings',
            'sgi_srlat_opts',
            array(&$this, 'sanitize_opts')
        );

		//Core section
        add_settings_section(
            'sgi_srlat_core',
            __('Core settings','serbian-latinisation'),
            array(&$this, 'core_section_callback'),
            'sgi-sr-latinization'
        );

        add_settings_field(
            'sgi_srlat_opts_script',
            __('Default script', 'serbian-latinisation'),
            array(&$this, 'script_callback'),
            'sgi-sr-latinization',
            'sgi_srlat_core',
            $this->opts['core']['script']
        );

        add_settings_field(
            'sgi_srlat_opts_permalink',
            __('Fix permalinks', 'serbian-latinisation'),
            array(&$this, 'permalink_callback'),
            'sgi-sr-latinization',
            'sgi_srlat_core',
            $this->opts['core']['fix_permalinks']
        );

        add_settings_field(
            'sgi_srlat_opts_permalink',
            __('Fix permalinks', 'serbian-latinisation'),
            array(&$this, 'permalink_callback'),
            'sgi-sr-latinization',
            'sgi_srlat_core',
            $this->opts['core']['fix_permalinks']
        );

        add_settings_field(
            'sgi_srlat_opts_media',
            __('Fix media names', 'serbian-latinisation'),
            array(&$this, 'media_callback'),
            'sgi-sr-latinization',
            'sgi_srlat_core',
            $this->opts['core']['fix_media']
        );

        add_settings_field(
            'sgi_srlat_opts_search',
            __('Fix Search', 'serbian-latinisation'),
            array(&$this, 'search_callback'),
            'sgi-sr-latinization',
            'sgi_srlat_core',
            $this->opts['core']['fix_search']
        );

        //WPML section

        if (is_wpml_active()) :
        
	        add_settings_section(
	            'sgi_srlat_wpml',
	            __('WPML settings','serbian-latinisation'),
	            array(&$this, 'wpml_section_callback'),
	            'sgi-sr-latinization'
	        );

		    add_settings_field(
	            'sgi_srlat_opts_wpml',
	            __('Extend WPML languages', 'serbian-latinisation'),
	            array(&$this, 'wpml_lang_callback'),
	            'sgi-sr-latinization',
	            'sgi_srlat_wpml',
	            $this->opts['wpml']['extend_lang']
	        );


	    endif;

	    //Polylang Section

	    if (function_exists('pll_the_languages')) :

	    	/*

	    	add_settings_section(
	            'sgi_srlat_pll',
	            __('Polylang settings','serbian-latinisation'),
	            array(&$this, 'pll_section_callback'),
	            'sgi-sr-latinization'
	        );

	    	add_settings_field(
	            'sgi_srlat_opts_polylang',
	            __('Extend Polylang languages', 'serbian-latinisation'),
	            array(&$this, 'pll_lang_callback'),
	            'sgi-sr-latinization',
	            'sgi_srlat_pll',
	            $this->opts['polylang']['extend_lang']
	        );

	        */

	    endif;

	    // qTranslateX section

	    if (function_exists('qtranxf_getLanguage')) :

	    	add_settings_section(
	            'sgi_srlat_qtx',
	            __('qTranslate X settings','serbian-latinisation'),
	            array(&$this, 'qtx_section_callback'),
	            'sgi-sr-latinization'
	        );

	    	add_settings_field(
	            'sgi_srlat_opts_qtx',
	            __('Extend qTranslate X languages', 'serbian-latinisation'),
	            array(&$this, 'qtx_lang_callback'),
	            'sgi-sr-latinization',
	            'sgi_srlat_qtx',
	            $this->opts['qtx']['extend_lang']
	        );


	    endif;


	}

	public function core_section_callback()
	{
		
		printf(
			'<p>%s</p>',
			__(
				'Core plugin settings control main functionality of the plugin',
				'serbian-latinisation'
			)
		);
	}

	public function script_callback($default_script)
	{
		$scripts = array(
			'cir' => __('Cyrillic','serbian-latinisation'),
			'lat' => __('Latin','serbian-latinisation')
		);

		echo '<select name="sgi_srlat_opts[core][script]">';

		foreach ($scripts as $script => $name) :

			printf(
				'<option value="%s" %s>%s</option>',
				$script,
				selected($default_script, $script, false),
				$name
			);


		endforeach;

		echo '</select>';

		printf('<p class="description">%s</p>
			',
			__('Default script used for the website if user did not select a script','serbian-latinisation')
		);

	}

	public function permalink_callback($fix_permalinks)
	{

		$wplang = get_option('WPLANG');
		$helper_text = __('Transliterate cyrillic permalinks to latin script','serbian-latinisation');

		$disabled = ( ($wplang == 'sr_RS') || ($wplang == 'bs_BA') ) ? 'disabled' : '';

		if ($disabled == 'disabled') :

			$helper_text .= sprintf(
				'<br><strong>%s</strong>',
				__('This option is currently disabled because your current locale is set to sr_RS which will automatically change permalnks','serbian-latinisation')
			);

		endif;

		printf(
			'<label for="sgi_srlat_opts[core][fix_permalinks]">
				<input type="checkbox" name="sgi_srlat_opts[core][fix_permalinks]" %s %s> %s
			</label>
			<p class="description">%s</p>
			',
			checked(true, $fix_permalinks, false),
			$disabled,
			__('Fix permalinks','serbian-latinisation'),
			$helper_text
		);
	}

	public function media_callback($fix_media)
	{

		printf(
			'<label for="sgi_srlat_opts[core][fix_media]">
				<input type="checkbox" name="sgi_srlat_opts[core][fix_media]" %s> %s
			</label>
			<p class="description">%s</p>
			',
			checked(true, $fix_media, false),
			__('Fix media names','serbian-latinisation'),
			__('Transliterate cyrillic filenames to latin','serbian-latinisation')
		);

	}

	public function search_callback($fix_search)
	{

		printf(
			'<label for="sgi_srlat_opts[core][fix_search]">
				<input type="checkbox" name="sgi_srlat_opts[core][fix_search]" %s> %s
			</label>
			<p class="description">%s</p>
			',
			checked(true, $fix_search, false),
			__('Fix Search','serbian-latinisation'),
			__('Enable searching for cyrillic titles using latin script','serbian-latinisation')
		);

	}

	public function wpml_section_callback()
	{
		
		printf(
			'<p>%s</p>',
			__(
				'WPML settings control WPML integration options',
				'serbian-latinisation'
			)
		);
	}

	public function wpml_lang_callback($extend_lang)
	{

		printf(
			'<label for="sgi_srlat_opts[wpml][extend_lang]">
				<input type="checkbox" name="sgi_srlat_opts[wpml][extend_lang]" %s> %s
			</label>
			<p class="description">%s</p>
			',
			checked(true, $extend_lang, false),
			__('Extend language menu','serbian-latinisation'),
			__('Add Serbian (Cyrillic) / Serbian (Latin) to all WPML language selectors','serbian-latinisation')
		);

	}

	public function pll_section_callback()
	{
		
		printf(
			'<p>%s</p>',
			__(
				'Polylang settings control Polylang integration options',
				'serbian-latinisation'
			)
		);
	}

	public function pll_lang_callback($extend_lang)
	{

		printf(
			'<label for="sgi_srlat_opts[polylang][extend_lang]">
				<input type="checkbox" name="sgi_srlat_opts[polylang][extend_lang]" %s> %s
			</label>
			<p class="description">%s</p>
			',
			checked(true, $extend_lang, false),
			__('Extend language menu','serbian-latinisation'),
			__('Add Serbian (Cyrillic) / Serbian (Latin) to all Polylang language selectors','serbian-latinisation')
		);

	}

	public function qtx_section_callback()
	{
		
		printf(
			'<p>%s</p>',
			__(
				'qTranslate X settings control qTranslate X integration options',
				'serbian-latinisation'
			)
		);
	}

	public function qtx_lang_callback($extend_lang)
	{

		printf(
			'<label for="sgi_srlat_opts[qtx][extend_lang]">
				<input type="checkbox" name="sgi_srlat_opts[qtx][extend_lang]" %s> %s
			</label>
			<p class="description">%s<br><strong>%s</strong></p>
			',
			checked(true, $extend_lang, false),
			__('Extend language menu','serbian-latinisation'),
			__('Try to add Serbian (Cyrillic) / Serbian (Latin) to all qTranslate language selectors','serbian-latinisation'),
			__('Depending on your qTranslate X version and configured options this may not work.','serbian-latinisation')
		);

	}

	public function sanitize_opts($opts)
	{

		$core_checkboxes = ['fix_permalinks','fix_media','fix_search'];
		$core_opts = $opts['core'];


		foreach($core_checkboxes as $option) :

			if (isset($core_opts[$option])) :

				$opts['core'][$option] = true;

			else : 

				$opts['core'][$option] = false;

			endif;

		endforeach;

		if ($opts['wpml']['extend_lang'] == 'on') :

			$opts['wpml']['extend_lang'] = true;

		else :

			$opts['wpml']['extend_lang'] = false;

		endif;

		if ($opts['polylang']['extend_lang'] == 'on') :

			$opts['polylang']['extend_lang'] = true;
			delete_transient('pll_Languages_list');

		else :

			$opts['polylang']['extend_lang'] = false;
			delete_transient('pll_Languages_list');

		endif;

		if ($opts['qtx']['extend_lang'] == 'on') :

			$opts['qtx']['extend_lang'] = true;

		else :

			$opts['qtx']['extend_lang'] = false;

		endif;

		return $opts;

	}






}