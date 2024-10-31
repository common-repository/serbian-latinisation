<?php

class SGI_SRLat_Admin_Utils
{

	private $opts;
	
	public function __construct()
	{

		//Options init
		$srlat_opts = get_option(
			'sgi_srlat_opts',
			array(
				'core' => array(
					'script'		 => 'cir',
					'fix_permalinks' => true,
					'fix_media'		 => true,
					'fix_search'	 => true,
				),
		));

		$this->opts = $srlat_opts;

		add_filter('sanitize_file_name', array(&$this, 'sanitize_file_name'),50,2);
		add_filter('posts_search', array(&$this,'fix_search'),20,2);

	}

	public function sanitize_file_name($filename)
	{

		if (!$this->opts['core']['fix_media'])
			return $filename;

		$filename = SGI_Translit_Core::cir_to_cut_lat($filename);

		return $filename;

	}

	public function fix_search($search,$query)
	{

		if (!$this->opts['core']['fix_search'])
			return $search;

		if ( !$query->is_main_query()) :

			return $search;

		endif;

		if ( is_admin() && ($_GET['s'] == '' || !isset($_GET['s'])) ) 
			return $search;
		
		if (is_admin()) :

			global $pagenow;

			if ($pagenow != 'edit.php') return $search;

		endif;	

		if ($_GET['s'] == '')
			return $search;

		if (!is_search())
			return $search;

		$search = sgi_sql_search($search);

		return $search;

	}

}