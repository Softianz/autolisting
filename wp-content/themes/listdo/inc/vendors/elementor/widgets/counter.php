<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes;

/**
 * Elementor counter widget.
 *
 * Elementor widget that displays stats and numbers in an escalating manner.
 *
 * @since 1.0.0
 */
class Listdo_Elementor_Counter extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve counter widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'counter';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve counter widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Apus Counter', 'listdo' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve counter widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-counter';
	}

	public function get_categories() {
        return [ 'elementor-elements' ];
    }

	/**
	 * Retrieve the list of scripts the counter widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'jquery-numerator' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'counter' ];
	}

	/**
	 * Register counter widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_counter',
			[
				'label' => esc_html__( 'Counter', 'listdo' ),
			]
		);

		$this->add_control(
            'icon',
            [
                'label' => esc_html__( 'Icon', 'listdo' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-star',
            ]
        );

		$this->add_control(
			'starting_number',
			[
				'label' => esc_html__( 'Starting Number', 'listdo' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'ending_number',
			[
				'label' => esc_html__( 'Ending Number', 'listdo' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'prefix',
			[
				'label' => esc_html__( 'Number Prefix', 'listdo' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'placeholder' => 1,
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => esc_html__( 'Number Suffix', 'listdo' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'placeholder' => esc_html__( 'Plus', 'listdo' ),
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => esc_html__( 'Animation Duration', 'listdo' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2000,
				'min' => 100,
				'step' => 100,
			]
		);

		$this->add_control(
			'thousand_separator',
			[
				'label' => esc_html__( 'Thousand Separator', 'listdo' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'listdo' ),
				'label_off' => esc_html__( 'Hide', 'listdo' ),
			]
		);

		$this->add_control(
			'thousand_separator_char',
			[
				'label' => esc_html__( 'Separator', 'listdo' ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					'thousand_separator' => 'yes',
				],
				'options' => [
					'' => 'Default',
					'.' => 'Dot',
					' ' => 'Space',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'listdo' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Cool Number', 'listdo' ),
				'placeholder' => esc_html__( 'Cool Number', 'listdo' ),
			]
		);

		$this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'listdo' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'listdo' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'listdo' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'listdo' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-counter-title, {{WRAPPER}} .elementor-counter-number-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'listdo' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_number',
			[
				'label' => esc_html__( 'Number', 'listdo' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Text Color', 'listdo' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-counter-number-wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_number',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-counter-number-wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'listdo' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'listdo' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-counter-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_title',
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .elementor-counter-title',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render counter widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="elementor-counter">
			<div class="elementor-counter-number-wrapper">
				<# if ( settings.icon ) {
					#>
					<div class="elementor-counter-icon">
						<span class="counter-icon">
							<i class="{{{ settings.icon }}}"></i>
						</span>
					</div>
					<#
				} #>
				<span class="elementor-counter-number-prefix">{{{ settings.prefix }}}</span>
				<span class="elementor-counter-number" data-duration="{{ settings.duration }}" data-to-value="{{ settings.ending_number }}" data-delimiter="{{ settings.thousand_separator ? settings.thousand_separator_char || ',' : '' }}">{{{ settings.starting_number }}}</span>
				<span class="elementor-counter-number-suffix">{{{ settings.suffix }}}</span>
			</div>
			<# if ( settings.title ) {
				#><div class="elementor-counter-title">{{{ settings.title }}}</div><#
			} #>
		</div>
		<?php
	}

	/**
	 * Render counter widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'counter', [
			'class' => 'elementor-counter-number',
			'data-duration' => $settings['duration'],
			'data-to-value' => $settings['ending_number'],
			'data-from-value' => $settings['starting_number'],
		] );

		if ( ! empty( $settings['thousand_separator'] ) ) {
			$delimiter = empty( $settings['thousand_separator_char'] ) ? ',' : $settings['thousand_separator_char'];
			$this->add_render_attribute( 'counter', 'data-delimiter', $delimiter );
		}
		?>
		<div class="elementor-counter">
			<div class="elementor-counter-number-wrapper">
				<?php if(!empty($settings['icon'])){ ?>
					<div class="elementor-counter-icon">	
						<span class="counter-icon">
							<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
						</span>
					</div>
				<?php } ?>
				<span class="elementor-counter-number-prefix"><?php echo trim($settings['prefix']); ?></span>
				<span <?php echo trim($this->get_render_attribute_string( 'counter' )); ?>><?php echo trim($settings['starting_number']); ?></span>
				<span class="elementor-counter-number-suffix"><?php echo trim($settings['suffix']); ?></span>
			</div>
			<?php if ( $settings['title'] ) : ?>
				<div class="elementor-counter-title"><?php echo trim($settings['title']); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Plugin::instance()->widgets_manager->register_widget_type( new Listdo_Elementor_Counter );
} else {
    Plugin::instance()->widgets_manager->register( new Listdo_Elementor_Counter );
}