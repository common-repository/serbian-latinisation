<?php

class SGI_SRLat_Widget extends WP_Widget
{

	private $defaults;

	public function __construct()
	{

		$widget_opts = array(
			'id_base'     => 'sgi_srlat_widget',
			'description' => __('Serbian Script selection widget', 'serbian-latinisation')
		);

		$this->defaults = array(
			'title' 	=> __('Script Selection','serbian-latinisation'),
			'cyr_title' => __('ћирилица','serbian-latinisation'),
			'lat_title' => __('latinica','serbian-latinisation'),
			'sel_type'  => 'links',
			'link_sep'  => '::'
		);

		parent::__construct(
			'sgi_srlat_widget',
			__('Serbian Script selector', 'serbian-latinisation'),
			$widget_opts
		);

	}

	public function form($instance)
	{

		$instance = wp_parse_args( (array) $instance, $this->defaults );

		printf(
			'<p>
				<label for="%s">%s</label>
				<input id="%s" name="%s" value="%s" type="text" class="widefat">
			</p>',
			$this->get_field_id( 'title' ),
			__('Title','serbian-latinisation'),
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			esc_attr( $instance['title'] )
		);

		printf(
			'<h4>%s</h4>',
			__('Link Options','serbian-latinisation')
		);

		printf(
			'<p>
				<label for="%s">%s</label>
				<input id="%s" name="%s" value="%s" type="text" class="widefat">
			</p>',
			$this->get_field_id( 'cyr_title' ),
			__('Link text - Cyrillic','serbian-latinisation'),
			$this->get_field_id( 'cyr_title' ),
			$this->get_field_name( 'cyr_title' ),
			esc_attr( $instance['cyr_title'] )
		);

		printf(
			'<p>
				<label for="%s">%s</label>
				<input id="%s" name="%s" value="%s" type="text" class="widefat">
			</p>',
			$this->get_field_id( 'lat_title' ),
			__('Link text - Latin','serbian-latinisation'),
			$this->get_field_id( 'lat_title' ),
			$this->get_field_name( 'lat_title' ),
			esc_attr( $instance['lat_title'] )
		);

		printf(
			'<h4>%s</h4>',
			__('Display Options','serbian-latinisation')
		);

		printf(
			'<p>
				<label for="%s">%s</label>
				<select id="%s" name="%s">
					<!--<option value="%s" %s>%s</value>-->
					<option value="%s" %s>%s</value>
					<option value="%s" %s>%s</value>
				</select>
			</p>',
			$this->get_field_id( 'sel_type' ),
			__('Selector style','serbian-latinisation'),
			$this->get_field_id( 'sel_type' ),
			$this->get_field_name( 'sel_type' ),
			'dropdown',
			selected($instance['sel_type'],'dropdown', false),
			__('Dropdown','serbian-latinisation'),
			'links',
			selected($instance['sel_type'],'links', false),
			__('Links','serbian-latinisation'),
			'oneline',
			selected($instance['sel_type'],'oneline', false),
			__('One line','serbian-latinisation')
		);

		printf(
			'<p>
				<label for="%s">%s</label>
				<input id="%s" name="%s" value="%s" type="text" class="">
			</p>',
			$this->get_field_id( 'link_sep' ),
			__('Separator (oneline)','link_sep'),
			$this->get_field_id( 'link_sep' ),
			$this->get_field_name( 'link_sep' ),
			esc_attr( $instance['link_sep'] )
		);




	}

	public function update($new_instance,$old_instance)
	{

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cyr_title'] = strip_tags( $new_instance['cyr_title'] );
		$instance['lat_title'] = strip_tags( $new_instance['lat_title'] );
		$instance['sel_type'] = $new_instance['sel_type'];
		$instance['link_sep'] = strip_tags( $new_instance['link_sep'] );

		return $instance;

	}

	public function widget($args,$instance)
	{

		extract( $args );
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance['title'] );

		if ( !empty( $title ) ) :
			echo $before_title . $title . $after_title;
		endif;

		sgi_srlat_selector($instance['sel_type'],$instance['cyr_title'],$instance['lat_title'],$instance['link_sep']);

		echo $after_widget;

	}

}