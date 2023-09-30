<?php
/**
 * Comment converter.
 *
 * @package WPTelegram\FormatText\Converter
 */

namespace WPTelegram\FormatText\Converter;

use WPTelegram\FormatText\ElementInterface;

/**
 * Class CommentConverter
 */
class CommentConverter extends BaseConverter {

	/**
	 * {@inheritdoc}
	 */
	public function convert( ElementInterface $element ) {
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSupportedTags() {
		return [ '#comment' ];
	}
}
