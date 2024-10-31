<?php
/**
 * Slider Widget.
 *
 * @since 1.0
 */
namespace RT_Easy_Builder;
use Elementor\Controls_Manager;

class Post_Thumbnail extends Widgets{

	protected $slug = 'rt-post-thumbnail';
	protected $directory_name = 'post-thumbnail';
	protected $title = 'Post Thumbnail';
	protected $icon = 'fa fa-newspaper-o';

	public function __construct( $data = [], $args = null ) {

		parent::__construct( $data, $args );

		add_action( 'wp_enqueue_scripts', [ $this, 'register_dependencies' ], 9999 );
	}

	public function register_dependencies(){
		
		$builder = Builder::get_instance();
		$script  = $builder->get_module( 'script-handler' );

		// Register styles
		$script->register(array(
			'handler' => $this->slug,
			'file'    => $this->get_assets_uri( 'assets/style.css' )
		));
	}

	public function get_style_depends() {
		return array(
			$this->slug
		);
	}

	public function get_content_fields(){

		$categories = get_categories( array(
		    'orderby' => 'name',
		    'order'   => 'ASC'
		));

		$options = array();
		foreach( $categories as $category ){
			$options[ $category->term_id ] = $category->name;
		}

		$fields = array(
			'post_thumbnail_content_settings' => array(
				'label'  => esc_html__( 'General Settings', 'rt-easy-builder' ),
				'fields' => array(
					'category' => array(
						'label'   => esc_html__( 'Select Category', 'rt-easy-builder' ),
						'type'    => 'select',
						'default' => 1,
						'options' => $options
					),
					'per_row' => array(
						'label'   => esc_html__( 'Per Row', 'rt-easy-builder' ),
						'type'    => 'number',
						'default' => 3,
						'min'     => 1,
						'max'     => 3
					),
					'per_page' => array(
						'label'   => esc_html__( 'Per Page', 'rt-easy-builder' ),
						'type'    => 'number',
						'default' => 3,
						'min'     => 1,
						'max'     => 3
					),
					'show_title' => array(
						'label'   => esc_html__( 'Show Title', 'rt-easy-builder' ),
						'type'    => 'switcher',
						'default' => 'yes'
					),
					'show_thumbnail' => array(
						'label'   => esc_html__( 'Show Thumbnail', 'rt-easy-builder' ),
						'type'    => 'switcher',
						'default' => 'yes'
					),
					'show_date' => array(
						'label'   => esc_html__( 'Show Date', 'rt-easy-builder' ),
						'type'    => 'switcher',
						'default' => 'yes'
					),
					'show_excerpt' => array(
						'label'   => esc_html__( 'Show Excerpt', 'rt-easy-builder' ),
						'type'    => 'switcher',
						'default' => 'yes'
					)
				)
			),
		);

		return $fields;
	}

	public function get_style_fields(){
		$fields = array(
			'general_style' => array(
				'label' => esc_html__( 'General', 'rt-easy-builder' ),
				'fields' => array(
					'padding' => array(
						'label' => esc_html__( 'Padding', 'rt-easy-builder' ),
						'type'  => 'dimensions',
						'default' => array( 'top' => '15', 'right' => '15', 'bottom' => '15', 'left' => '15' ),
						'selectors' => array(
							'{{WRAPPER}} .rt-blog-thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
						)
					),
					'background' => array(
						'label'     => esc_html__( 'Background', 'rt-easy-builder' ),
						'type'      => 'color',
						'default'   => '#eeeeee',
						'selectors' => array(
							'{{WRAPPER}} .rt-blog-thumbnail-inner' => 'background-color: {{VALUE}};'
						)
					),
					'border-radius' => array(
						'label' => esc_html__( 'Border Radius', 'rt-easy-builder' ),
						'type'  => 'dimensions',
						'default' => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),
						'selectors' => array(
							'{{WRAPPER}} .rt-blog-thumbnail-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
						)
					)
				)
			),
			'title_style' => array(
				'label'  => esc_html__( 'Title', 'rt-easy-builder' ),
				'fields' => array(
					'typography' => array(
						'label'    => esc_html__( 'Typography', 'rt-easy-builder' ),
						'type'     => 'typography',
						'selector' => '{{WRAPPER}} .rt-blog-thumbnail-title'
					),
					'title_color' => array(
						'type' => 'tabs',
						'fields' => array(
							'title_normal_color' => array(
								'label' => esc_html__( 'Normal', 'rt-easy-builder' ),
								'fields' => array(
									'color' => array(
										'label'     => esc_html__( 'Color', 'rt-easy-builder' ),
										'type'      => 'color',
										'default'   => '#333333',
										'selectors' => array(
											'{{WRAPPER}} .rt-blog-thumbnail-title a' => 'color: {{VALUE}};',
										)
									)
								)
							),
							'title_hover_color' => array(
								'label' => esc_html__( 'Hover', 'rt-easy-builder' ),
								'fields' => array(
									'color' => array(
										'label'     => esc_html__( 'Color', 'rt-easy-builder' ),
										'type'      => 'color',
										'default'   => '#333333',
										'selectors' => array(
											'{{WRAPPER}} .rt-blog-thumbnail-title a:hover' => 'color: {{VALUE}};',
										)
									)
								)
							)
						)
					),
					'margin' => array(
						'label'      => esc_html__( 'Margin', 'rt-easy-builder' ),
						'type'       => 'dimensions',
						'size_units' => array( 'px' ),
						'default'    => array( 'top' => '20', 'right' => '0', 'bottom' => '20', 'left' => '0', 'isLinked' => false ),
						'selectors'  => array(
							'{{WRAPPER}} .rt-blog-thumbnail-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						)
					)
				)
			),
			'meta_style' => array(
				'label'  => esc_html__( 'Meta', 'rt-easy-builder' ),
				'fields' => array(
					'typography' => array(
						'label'    => esc_html__( 'Typography', 'rt-easy-builder' ),
						'type'     => 'typography',
						'selector' => '{{WRAPPER}} .rt-blog-thumbnail-date a',
					),
					'meta_color' => array(
						'type' => 'tabs',
						'fields' => array(
							'meta_normal_color' => array(
								'label' => esc_html__( 'Normal', 'rt-easy-builder' ),
								'fields' => array(
									'color' => array(
										'label'     => esc_html__( 'Color', 'rt-easy-builder' ),
										'type'      => 'color',
										'default'   => '#333333',
										'selectors' => array(
											'{{WRAPPER}} .rt-blog-thumbnail-date a' => 'color: {{VALUE}};'
										)
									)
								)
							),
							'meta_hover_color' => array(
								'label' => esc_html__( 'Hover', 'rt-easy-builder' ),
								'fields' => array(
									'color' => array(
										'label'     => esc_html__( 'Color', 'rt-easy-builder' ),
										'type'      => 'color',
										'default'   => '#333333',
										'selectors' => array(
											'{{WRAPPER}} .rt-blog-thumbnail-date a:hover' => 'color: {{VALUE}};'
										)
									)
								)
							)
						)
					),
					'margin' => array(
						'label'      => esc_html__( 'Margin', 'rt-easy-builder' ),
						'type'       => 'dimensions',
						'size_units' => array( 'px' ),
						'default'    => array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0' ),
						'selectors'  => array(
							'{{WRAPPER}} .rt-blog-thumbnail-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						)
					)
				)
			),
			'excerpt_style' => array(
				'label'  => esc_html__( 'Excerpt', 'rt-easy-builder' ),
				'fields' => array(
					'typography' => array(
						'label'    => esc_html__( 'Typography', 'rt-easy-builder' ),
						'type'     => 'typography',
						'selector' => '{{WRAPPER}} .rt-blog-thumbnail-content',
					),
					'color' => array(
						'label'     => esc_html__( 'Color', 'rt-easy-builder' ),
						'type'      => 'color',
						'default'   => '#333333',
						'selectors' => array(
							'{{WRAPPER}} .rt-blog-thumbnail-content' => 'color: {{VALUE}};',
						)
					),
					'margin' => array(
						'label'      => esc_html__( 'Margin', 'rt-easy-builder' ),
						'type'       => 'dimensions',
						'size_units' => array( 'px' ),
						'default'    => array( 'top' => '20', 'right' => '0', 'bottom' => '20', 'left' => '0', 'isLinked' => false ),
						'selectors'  => array(
							'{{WRAPPER}} .rt-blog-thumbnail-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						)
					)
				)
			)
		);

		return $fields;
	}
}