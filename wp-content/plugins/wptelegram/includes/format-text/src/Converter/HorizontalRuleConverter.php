<?php
/**
 * Horizontal rule converter.
 *
 * @package WPTelegram\FormatText\Converter
 */

namespace WPTelegram\FormatText\Converter;

use WPTelegram\FormatText\ElementInterface;

/**
 * Class HorizontalRuleConverter
 */
class HorizontalRuleConverter extends BaseConverter {

	/**
	 * {@inheritdoc}
	 */
	public function convert( ElementInterface $element ) {

		$output = "\n-------------\n\n";

		if ( $this->formattingToMarkdown() ) {
			$output = $this->escapeMarkdownChars( $output );
		}

		return $output;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSupportedTags() {
		return [ 'hr' ];
	}
}
