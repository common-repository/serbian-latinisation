<?php

function is_wpml_active()
{

	return (defined('ICL_LANGUAGE_CODE') && class_exists('SitePress')) ? true : false;

}

function sgi_sql_search($search)
{

	global $wpdb;

	$search_term = $_GET['s'];

	if (preg_match('/\s/',$search_term)) :

		$search_term = rtrim($search_term);
		$search_expl = explode(' ', $search_term);

		$search = 'AND ('; $first = true;

		foreach ($search_expl as $word ) :

			if (!$first) :

				$search .= ' AND ';
				
			endif;
			$first = false;

			$lat_word = SGI_Translit_Core::lat_to_cir($word);

			$search .= sprintf(
				"(%s.post_title LIKE '%%%s%%' OR %s.post_title LIKE '%%%s%%')",
				$wpdb->posts,
				$word,
				$wpdb->posts,
				$lat_word
			);

		endforeach;

		$search .= ')';

	else :

		$search_term = ' \'%'.$_GET['s'].'%\'';
		$lat_search_term = SGI_Translit_Core::lat_to_cir($search_term);

		$search = " AND ( ({$wpdb->posts}.post_title LIKE ${lat_search_term} OR {$wpdb->posts}.post_title LIKE ${search_term}) ) ";

	endif;

	//var_dump($search);

	return $search;

}

function sgi_srlat_selector($selector_type,$cyr_title,$lat_title,$separator)
{

	global $wp;
	$current_url = home_url(add_query_arg(array(),$wp->request));

	if (!class_exists('SGI_SRLat_Frontend')) :

		require_once (SGI_SRLAT_PATH.'lib/frontend/frontend-core.php');

	endif;

	$srlat_frontend = SGI_SRLat_Frontend::get_instance();
	$current_script = $srlat_frontend->get_cookie_lang();

	$cyr_link = add_query_arg('sr_pismo', 'cir', $current_url);
	$lat_link = add_query_arg('sr_pismo', 'lat', $current_url);

	switch ($selector_type) :

		case 'links' :
			printf(
				'<a href="%s" style="display:block;margin-bottom:5px;">%s%s%s</a>
				<a href="%s" style="display:block;">%s%s%s</a>',
				$cyr_link,
				($current_script == 'cir') ? '<strong>' : '',
				$cyr_title,
				($current_script == 'cir') ? '</strong>' : '',
				$lat_link,
				($current_script == 'lat') ? '<strong>' : '',
				$lat_title,
				($current_script == 'lat') ? '</strong>' : ''
			);
			break;

		case 'dropdown' :
			break;

		case 'oneline' :
			printf(
				'<a href="%s">
					%s%s%s
				</a>
				<span style="display:inline-block;margin:0 10px;">%s</span>
				<a href="%s">
					%s%s%s
				</a>',
				$cyr_link,
				($current_script == 'cir') ? '<strong>' : '',
				$cyr_title,
				($current_script == 'cir') ? '</strong>' : '',
				$separator,
				$lat_link,
				($current_script == 'lat') ? '<strong>' : '',
				$lat_title,
				($current_script == 'lat') ? '</strong>' : ''
			);
			break;

	endswitch;

}