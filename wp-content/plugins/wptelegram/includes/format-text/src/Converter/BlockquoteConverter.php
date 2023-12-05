<?php
/**
 * Blockquote converter.
 *
 * @package WPTelegram\FormatText\Converter
 */

namespace WPTelegram\FormatText\Converter;

use WPTelegram\FormatText\ElementInterface;

/**
 * Class BlockquoteConverter
 */
class BlockquoteConverter extends BaseConverter {

	/**
	 * {@inheritdoc}
	 */
	public function getSupportedTags() {
		return [ 'blockquote' ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function convertToMarkdown( ElementInterface $element ) {
		$value = trim( $element->getValue() );

		// If this is a v1 format, don't emit, because v1 doesn't support blockquote.
		if ( 'v1' === $this->formattingToMarkdown() ) {
			return $value;
		}

		return '>' . $value;
	}
}
