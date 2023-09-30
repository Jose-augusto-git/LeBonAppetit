<?php
/**
 * List block converter.
 *
 * @package WPTelegram\FormatText\Converter
 */

namespace WPTelegram\FormatText\Converter;

use WPTelegram\FormatText\ElementInterface;

/**
 * Class ListBlockConverter
 */
class ListBlockConverter extends BaseConverter {

	/**
	 * {@inheritdoc}
	 */
	public function convert( ElementInterface $element ) {
		return "\n" . $element->getValue() . "\n";
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSupportedTags() {
		return [ 'ol', 'ul' ];
	}
}
