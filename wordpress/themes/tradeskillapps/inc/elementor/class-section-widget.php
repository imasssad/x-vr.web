<?php
/**
 * One Elementor widget per section of the site.
 *
 * Rather than seventeen near-identical classes, there is one class told which
 * section it is. It renders the same template part the home page uses, so a
 * section can never drift between the two: fix it once and both change.
 *
 * Each widget carries the section's shipped wording as its defaults, so
 * dropping one onto a page gives a finished block straight away. Anything
 * typed into the widget applies to that placement only, and emptying a field
 * falls back to the site-wide wording from the Customizer.
 */
defined('ABSPATH') || exit;

class TSA_Section_Widget extends \Elementor\Widget_Base {

	private $slug;
	private $label;
	private $icon;
	private $group;
	private $fields;

	public function __construct($data = array(), $args = null) {
		// Elementor passes $args through on registration; our own keys ride along.
		$this->slug   = isset($args['tsa_slug']) ? $args['tsa_slug'] : '';
		$this->label  = isset($args['tsa_label']) ? $args['tsa_label'] : '';
		$this->icon   = isset($args['tsa_icon']) ? $args['tsa_icon'] : 'eicon-section';
		$this->group  = isset($args['tsa_group']) ? $args['tsa_group'] : '';
		$this->fields = isset($args['tsa_fields']) ? $args['tsa_fields'] : array();
		parent::__construct($data, $args);
	}

	public function get_name()       { return 'tsa-' . $this->slug; }
	public function get_title()      { return $this->label; }
	public function get_icon()       { return $this->icon; }
	public function get_categories() { return array('tradeskillapps'); }
	public function get_keywords()   { return array('tradeskillapps', 'section', $this->slug); }

	/** The section styles itself; Elementor should not wrap it in a content width. */
	public function get_html_wrapper_class() {
		return 'elementor-widget elementor-widget-' . $this->get_name() . ' tsa-section-widget';
	}

	protected function register_controls() {
		if (!$this->fields) {
			$this->start_controls_section('tsa_about', array(
				'label' => __('Section', 'tradeskillapps'),
			));
			$this->add_control('tsa_note', array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => sprintf(
					/* translators: the name of a section of the site */
					esc_html__('This places the "%s" section exactly as it appears on the home page. Its content is edited in Appearance > Customize.', 'tradeskillapps'),
					esc_html($this->label)
				),
			));
			$this->end_controls_section();
			return;
		}

		$this->start_controls_section('tsa_content', array(
			'label' => __('Wording', 'tradeskillapps'),
		));

		$this->add_control('tsa_intro', array(
			'type' => \Elementor\Controls_Manager::RAW_HTML,
			'raw'  => esc_html__('Leave a box empty to use the site-wide wording from the Customizer. Anything typed here applies to this placement only.', 'tradeskillapps'),
			'content_classes' => 'elementor-descriptor',
		));

		$labels = array(
			'eyebrow'    => __('Small label above the headline', 'tradeskillapps'),
			'headline'   => __('Headline', 'tradeskillapps'),
			'headlineEm' => __('Headline - highlighted part', 'tradeskillapps'),
			'quote'      => __('Quote', 'tradeskillapps'),
			'quoteEm'    => __('Quote - highlighted part', 'tradeskillapps'),
			'lede'       => __('Intro paragraph', 'tradeskillapps'),
			'body'       => __('Body text', 'tradeskillapps'),
			'note'       => __('Note', 'tradeskillapps'),
		);
		$multiline = array('lede', 'body', 'note', 'quote');

		foreach ($this->fields as $field) {
			$path = $this->group . '.' . $field;
			$this->add_control('f_' . $field, array(
				'label'       => isset($labels[$field]) ? $labels[$field] : ucfirst($field),
				'type'        => in_array($field, $multiline, true)
					? \Elementor\Controls_Manager::TEXTAREA
					: \Elementor\Controls_Manager::TEXT,
				'rows'        => 3,
				'default'     => '',
				'placeholder' => tsa_default($path),
				'label_block' => true,
			));
		}

		$this->end_controls_section();
	}

	protected function render() {
		$settings  = $this->get_settings_for_display();
		$overrides = array();
		foreach ($this->fields as $field) {
			$key = 'f_' . $field;
			if (!empty($settings[$key])) {
				$overrides[$this->group . '.' . $field] = $settings[$key];
			}
		}

		$previous = tsa_set_overrides($overrides);
		get_template_part('template-parts/' . $this->slug);
		tsa_set_overrides($previous);
	}

	// render_plain_content() is deliberately NOT overridden: Elementor uses it
	// on save to write the widget's text into post_content, which is what
	// makes builder pages findable by WordPress search and excerpts.
}
