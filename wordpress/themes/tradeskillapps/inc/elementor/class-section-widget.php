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
	private $images;
	private $posts;

	public function __construct($data = array(), $args = null) {
		// Elementor passes $args through on registration; our own keys ride along.
		$this->slug   = isset($args['tsa_slug']) ? $args['tsa_slug'] : '';
		$this->label  = isset($args['tsa_label']) ? $args['tsa_label'] : '';
		$this->icon   = isset($args['tsa_icon']) ? $args['tsa_icon'] : 'eicon-section';
		$this->group  = isset($args['tsa_group']) ? $args['tsa_group'] : '';
		$this->fields = isset($args['tsa_fields']) ? $args['tsa_fields'] : array();
		$this->images = isset($args['tsa_images']) ? $args['tsa_images'] : array();
		$this->posts  = isset($args['tsa_posts']) ? $args['tsa_posts'] : '';
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

	/** A control name for an image path: 'hero.image' -> 'img_hero_image'. */
	private function image_key($path) {
		return 'img_' . str_replace('.', '_', $path);
	}

	protected function register_controls() {
		$this->register_text_controls();
		$this->register_image_controls();
		$this->register_posts_notice();
	}

	private function register_posts_notice() {
		if (!$this->posts) return;
		$this->start_controls_section('tsa_posts', array(
			'label' => __('Entries', 'tradeskillapps'),
		));
		$this->add_control('tsa_posts_note', array(
			'type' => \Elementor\Controls_Manager::RAW_HTML,
			'raw'  => sprintf(
				/* translators: %s is an admin menu name, e.g. "Trades" */
				esc_html__('The entries in this section are edited under %s in the main WordPress menu, where you can add, remove and reorder them. Changes appear here straight away.', 'tradeskillapps'),
				'<strong>' . esc_html($this->posts) . '</strong>'
			),
		));
		$this->end_controls_section();
	}

	private function register_image_controls() {
		if (!$this->images) return;
		$this->start_controls_section('tsa_pictures', array(
			'label' => __('Pictures', 'tradeskillapps'),
		));
		$this->add_control('tsa_pictures_intro', array(
			'type' => \Elementor\Controls_Manager::RAW_HTML,
			'raw'  => esc_html__('Choose a picture to replace the one this section ships with. Leave it alone to keep the current picture. Each picture is cropped from the centre to fit its frame, so put the subject in the middle.', 'tradeskillapps'),
			'content_classes' => 'elementor-descriptor',
		));
		foreach ($this->images as $img) {
			list($label, $path) = $img;
			$this->add_control($this->image_key($path), array(
				'label'   => $label,
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => array('url' => ''),
			));
		}
		$this->end_controls_section();
	}

	private function register_text_controls() {
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

		foreach ($this->images as $img) {
			$key = $this->image_key($img[1]);
			if (empty($settings[$key]['url'])) continue;
			$overrides[$img[1] . '.src'] = $settings[$key]['url'];
			// take the alt text from the Media Library entry, so swapping a
			// picture does not quietly leave the old description behind
			if (!empty($settings[$key]['id'])) {
				$alt = get_post_meta((int) $settings[$key]['id'], '_wp_attachment_image_alt', true);
				if ($alt !== '') $overrides[$img[1] . '.alt'] = $alt;
			}
		}

		$previous = tsa_set_overrides($overrides);

		// Inside the builder each section is one widget, and its edit handle
		// sits at the far right of a full-width block where it is easy to
		// miss - so the section says what it is and that it can be clicked.
		// Never rendered on the live site.
		if (function_exists('tsa_elementor_editing') && tsa_elementor_editing()) {
			printf(
				'<span class="tsa-edit-badge" aria-hidden="true">%s</span>',
				esc_html(sprintf(
					/* translators: %s is the name of a section, e.g. "Our approach" */
					__('%s — click to edit this section', 'tradeskillapps'),
					$this->label
				))
			);
		}

		get_template_part('template-parts/' . $this->slug);
		tsa_set_overrides($previous);
	}

	// render_plain_content() is deliberately NOT overridden: Elementor uses it
	// on save to write the widget's text into post_content, which is what
	// makes builder pages findable by WordPress search and excerpts.
}
