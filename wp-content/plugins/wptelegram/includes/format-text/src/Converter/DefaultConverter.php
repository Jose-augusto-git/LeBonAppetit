<?php
/**
 * Default converter.
 *
 * @package WPTelegram\FormatText\Converter
 */

namespace WPTelegram\FormatText\Converter;

/**
 * Class DefaultConverter
 */
class DefaultConverter extends BaseConverter {

	const DEFAULT_CONVERTER = '_default';

	/**
	 * {@inheritdoc}
	 */
	public function getSupportedTags() {
		return [ self::DEFAULT_CONVERTER ];
	}
}
