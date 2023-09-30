<?php
/**
 * Environment.
 *
 * @package WPTelegram\FormatText
 */

namespace WPTelegram\FormatText;

use WPTelegram\FormatText\Converter\CodeConverter;
use WPTelegram\FormatText\Converter\CommentConverter;
use WPTelegram\FormatText\Converter\ConverterInterface;
use WPTelegram\FormatText\Converter\DefaultConverter;
use WPTelegram\FormatText\Converter\EmphasisConverter;
use WPTelegram\FormatText\Converter\HorizontalRuleConverter;
use WPTelegram\FormatText\Converter\ImageConverter;
use WPTelegram\FormatText\Converter\LinkConverter;
use WPTelegram\FormatText\Converter\ListBlockConverter;
use WPTelegram\FormatText\Converter\ListItemConverter;
use WPTelegram\FormatText\Converter\PreformattedConverter;
use WPTelegram\FormatText\Converter\SpoilerConverter;
use WPTelegram\FormatText\Converter\TableConverter;
use WPTelegram\FormatText\Converter\TextConverter;

/**
 * Class Environment
 */
final class Environment {

	/**
	 * Configuration.
	 *
	 * @var Configuration
	 */
	protected $config;

	/**
	 * Converters.
	 *
	 * @var ConverterInterface[]
	 */
	protected $converters = [];

	/**
	 * Environment constructor.
	 *
	 * @param array<string, mixed> $config Configuration.
	 */
	public function __construct( array $config = [] ) {
		$this->config = new Configuration( $config );
		$this->addConverter( new DefaultConverter() );
	}

	/**
	 * Get configuration.
	 *
	 * @return Configuration
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * Add converter.
	 *
	 * @param ConverterInterface $converter Converter.
	 *
	 * @return void
	 */
	public function addConverter( ConverterInterface $converter ) {
		$converter->setConfig( $this->config );

		foreach ( $converter->getSupportedTags() as $tag ) {
			$this->converters[ $tag ] = $converter;
		}
	}

	/**
	 * Get converter by tag.
	 *
	 * @param string $tag Tag.
	 *
	 * @return ConverterInterface
	 */
	public function getConverterByTag( string $tag ) {
		if ( isset( $this->converters[ $tag ] ) ) {
			return $this->converters[ $tag ];
		}

		return $this->converters[ DefaultConverter::DEFAULT_CONVERTER ];
	}

	/**
	 * Create default environment.
	 *
	 * @param array<string, mixed> $config Configuration.
	 *
	 * @return Environment
	 */
	public static function createDefaultEnvironment( array $config = [] ) {
		$environment = new static( $config );

		$environment->addConverter( new CodeConverter() );
		$environment->addConverter( new CommentConverter() );
		$environment->addConverter( new EmphasisConverter() );
		$environment->addConverter( new HorizontalRuleConverter() );
		$environment->addConverter( new LinkConverter() );
		$environment->addConverter( new ImageConverter() );
		$environment->addConverter( new ListBlockConverter() );
		$environment->addConverter( new ListItemConverter() );
		$environment->addConverter( new PreformattedConverter() );
		$environment->addConverter( new SpoilerConverter() );
		$environment->addConverter( new TableConverter() );
		$environment->addConverter( new TextConverter() );

		return $environment;
	}
}
