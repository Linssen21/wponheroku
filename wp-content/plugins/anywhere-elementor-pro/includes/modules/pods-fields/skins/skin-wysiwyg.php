<?php

namespace Aepro\Modules\PodsFields\Skins;

use Aepro\Modules\PodsFields;
use Aepro\Classes\PodsMaster;
use Aepro\Base\Widget_Base;
use Elementor\Controls_Manager;


class Skin_Wysiwyg extends Skin_Text_Area {

	public function get_id() {
		return 'wysiwyg';
	}

	public function get_title() {
		return __( 'WYSIWYG', 'ae-pro' );
	}
	//phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
	protected function _register_controls_actions() {

		parent::_register_controls_actions();
		add_action( 'elementor/element/ae-pods/general/after_section_end', [ $this, 'add_unfold_section' ] );
		add_action( 'elementor/element/ae-pods/general/after_section_end', [ $this, 'manage_controls' ] );
	}

	public function manage_controls() {

		$this->remove_control( 'prefix' );
		$this->remove_control( 'suffix' );
		$this->remove_control( 'links_to' );
	}

	public function render() {

		$settings = $this->parent->get_settings();

		$field_args = [
			'field_name' => $settings['field_name'],
			'field_type' => $settings['field_type'],

		];

		if ( $settings['pods_option_name'] !== '' ) {
			$field_args['pods_option_name'] = $settings['pods_option_name'];
		}

		$unfold      = $this->get_instance_value( 'enable_unfold' );
		$placeholder = $this->get_instance_value( 'placeholder' );
		$text        = PodsMaster::instance()->get_field_value( $field_args );

		if ( ( $text === '' || is_null( $text ) ) && ( $placeholder !== '' && ! is_null( $placeholder ) ) ) {
			$text   = $placeholder;
			$unfold = '';
		}

		$html_tag = $this->get_instance_value( 'html_tag' );

		$this->parent->add_render_attribute( 'title-class', 'class', 'ae-acf-content-wrapper' );

		$this->parent->add_render_attribute( 'wrapper-class', 'class', 'ae-acf-wrapper' );
		$this->parent->add_render_attribute( 'wrapper-class', 'class', 'ae-acf-unfold-' . $settings[ $this->get_control_id( 'enable_unfold' ) ] );

		if ( $text === '' ) {
			$this->parent->add_render_attribute( 'wrapper-class', 'class', 'ae-hide' );
		}

		// Process Content
		$text = $this->process_content( $text );

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'wrapper-class' ); ?>>
		<?php
			echo sprintf( '<%1$s itemprop="name" %2$s>%3$s</%1$s>', $html_tag, $this->parent->get_render_attribute_string( 'title-class' ), $text );

		if ( $unfold === 'yes' ) {
			$this->getFoldUnfoldButtonHtml();
		}

		?>
		</div>
		<?php
	}

}
