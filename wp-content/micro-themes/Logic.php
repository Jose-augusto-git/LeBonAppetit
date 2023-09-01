<?php

namespace Microthemer; // use a shorter namespace to make is easier for users to reference custom MT functions

/*
 * Logic
 *
 * Evaluate a PHP syntax conditional expression as a text string without using eval()
 * Supports a handful of WP functions and: ||, or, &&, and, (, ), !, =
 * Test String: is_page('test') and is_page("some-slug") && ! has_category() or has_tag() || is_date() === 23 or is_date() !== 'My string'
 * Test Regex: (?:(!)?\s*([a-z_]+)\('?"?(.*?)'?"?\))|(and|&&)|(or|\|\|)|([=!]{2,3})|(\d+)|(?:['"]{1}(.+?)['"]{1})
 */

class Logic {

	protected $debug = false;
	protected $test = false;
	public static $cache = array(
		'conditions' => array(),
	);
	protected $statementCount = 0;

	// parenthesis parsing variables
	protected $stack = null;
	protected $current = null;
	protected $string = null;
	protected $position = null;
	protected $buffer_start = null;
	protected $length;

	// Regex patterns for reading logic
	protected $patterns = array(
		"andOrSurrSpace" => "/\s+\b(and|AND|or|OR)\b\s+/",
		"functionName" => "(!)?\s*[a-zA-Z_\\\\]+",
		"comparison" => "/\s*(?<comparison><=|<|>|>=|!==?|===?)\s*/",
		"expressions" => array(
			"(?:(?<negation>!)?\s*(?<functionName>[a-zA-Z_\\\\]+)\((?<parameter>.*?)\))",
			"(?:[$]_?(?<global>GET|POST|GLOBALS)\['?\"?(?<key>.*?)'?\"?\])",
			"(?<string>['\"].+?['\"])",
			"(?<boolean>true|false|null|TRUE|FALSE|NULL)",
			"(?<number>\d+)",

		)
	);

	// PHP functions the user is allowed to use in the logic
	protected $allowedFunctions = array(

		'get_post_type',

		'has_action',
		'has_block',
		'has_category',
		'has_filter',
		'has_meta',
		'has_post_format',
		'has_tag',
		//'has_term', // covered by other functions and maybe too broad for asset assignment

		'is_404',
		'is_admin',
		'is_archive',
		'is_author',
		'is_category',
		'is_date',
		'is_front_page',
		'is_home',
		'is_page',
		'is_post_type_archive',
		'is_search',
		'is_single',
		'is_singular',
		'is_super_admin',
		'is_tag',
		'is_tax',
		'is_login',
		'is_user_logged_in',


		// custom namespaced Microthemer functions
		'\\'.__NAMESPACE__.'\has_template',
		'\\'.__NAMESPACE__.'\is_active',
		'\\'.__NAMESPACE__.'\is_admin_page',
		'\\'.__NAMESPACE__.'\is_public',
		'\\'.__NAMESPACE__.'\is_public_or_admin',
		'\\'.__NAMESPACE__.'\match_url_path',
		'\\'.__NAMESPACE__.'\query_admin_screen',
		'\\'.__NAMESPACE__.'\user_has_role',

		// native PHP
		'isset',

	);

	protected $allowedSuperglobals = array(
		'$_GET',
		//'$_POST',
		//'$GLOBALS' //
	);

	function __construct(){
		// maybe allow user defined whitelist of functions here
	}

	protected function debug($message, $data = false, $die = false){

		if ($this->debug){

			$output = $message;

			if ($data){
				$output.= ':<pre>' . print_r($data, 1) . '</pre>';
			}

			if (!$data){
				$output.= '<br /><br />';
			}


			if ($die){
				wp_die($output);
			} else {
				echo $output;
			}
		}

	}

	// normalise &&, || for simpler regex and logical comparisons
	protected function normaliseAndOr($string){

		return str_replace(
			array("&&", "||"),
			array("and", "or"),
			$string
		);
	}

	// replace is_page(2) with is_page^^2^^ so that parenthesis in function doesn't create a new group
	protected function addCarets($string){

		return preg_replace(
			"/(".$this->patterns['functionName'].")\((.*?)\)/s",
			'$1^^$3^^',
			$string
		);
	}

	protected function removeCarets($string){

		return preg_replace(
			"/(".$this->patterns['functionName'].")\^\^(.*?)\^\^/s",
			'$1($3)',
			$string
		);
	}

	protected function splitStatements($value){

		return preg_split(
			$this->patterns["andOrSurrSpace"],
			trim($value),
			-1,
			PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE
		);
	}

	protected function push(){

		if ($this->buffer_start !== null) {

			// extract string from buffer start to current position
			$buffer = substr($this->string, $this->buffer_start, $this->position - $this->buffer_start);

			// clean buffer
			$this->buffer_start = null;

			// throw token into current scope
			$statementsArray = $this->splitStatements(
				$this->removeCarets(
					$buffer
				)
			);

			if (count($statementsArray)){
				$this->current = array_merge($this->current, $statementsArray);
			}
		}
	}

	// Tease apart parenthesis groups
	protected function parseStatements($string){
		return $this->parse($string);
	}

	// walk over a multidimensional array recursively, applying a callback on non-array values
	protected function traverseStatements(&$array, $callback, $level = 0){

		$result = false;

		foreach ($array as $index => &$value){

			// if we are on a parenthesis group, get the result of the group
			if (is_array($value)){

				$result = $this->traverseStatements($value, $callback, (++$level));

				$this->debug('Group result ', array(
					'result' => $result,
					'group' => $value
				));
			}

			// get the result of the individual statement
			else {

				// simply move onto the next statement if we're on and/or
				if ($value === 'and' || $value === 'or' || $value === 'AND' || $value === 'OR'){
					continue;
				}

				// check result
				$result = $this->evaluateStatement($value);
				$resultString = $result
					? 'true'
					: ($result === null ? 'null' : 'false');

				// now that we have processed the logical statement, add some debug info
				$array[$index].= ' ['.$resultString.']';

				$this->debug('Statement result ('.$value.'): '.$result);
			}

			// look for the following and/or and possibly return early
			$nextIndex = $index + 1;
			$nextStatement = isset($array[$nextIndex]) ? $array[$nextIndex] : false;

			if (
				!$nextStatement ||
				($result && ($nextStatement === 'or' || $nextStatement === 'OR')) ||
				(!$result && ($nextStatement === 'and' || $nextStatement === 'AND'))
			){

				// mark final result
				if (!is_array($array[$index])){
					$array[$index].= '[result]';
				}


				return $result;
			}

			// true result
			/*if ($result){

				// if the next statement is an 'or',
				// we can safely return the TRUE result
				if ($nextStatement === 'or' || $nextStatement === 'OR'){
					$this->debug('Return early as true and next is OR ' . json_encode($value));
					// now that we have processed the logical statement, add some debug info
					$array[$index].= '[result]';
					return $result;
				}

			}

			// false result
			else {

				// if the next statement is an 'and',
				// we can safely return the FALSE result
				if ($nextStatement === 'and' || $nextStatement === 'AND'){
					$this->debug('Return early as false and next is AND ' . json_encode($value));
					$array[$index].= '[result]';
					return $result;
				}
			}*/

		}

		return $result;
	}

	protected function parseStatement($string){

		preg_match(
			"/" . implode('|', $this->patterns['expressions']) . "/s",
			$string,
			$matches
			//PREG_PATTERN_ORDER
		);

		return $matches;
	}

	protected function statementResult($parsedStatement){

		$this->debug('Statement parsed in callback', $parsedStatement);
		
		$result = false;

		// query any GET/POST values
		$global = isset($parsedStatement['global']) ? $parsedStatement['global'] : false;
		if ($global){

			$key = $parsedStatement['key'];

			if (!$key){
				return false;
			}

			if ($global == 'GET'){
				$result = isset($_GET[$key]) ? $_GET[$key] : false;
			} elseif ($global == 'POST'){
				$result = isset($_POST[$key]) ? $_POST[$key] : false;
			} elseif ($global == 'GLOBALS'){
				$result = isset($GLOBALS[$key]) ? $GLOBALS[$key] : false;
			}

		}

		// query any allowed function results
		$functionName = isset($parsedStatement['functionName']) ? $parsedStatement['functionName'] : false;
		if ($functionName){

			// bail if the function isn't allowed, or doesn't exist
			if (
				!in_array($functionName, $this->allowedFunctions) || !function_exists($functionName)
			    //(!function_exists($functionName) && !function_exists( 'Microthemer\\' .$functionName))
			){
				$this->debug('Disallowed or does not exist:', [
					'$functionName' => $functionName,
					'not allowed' => !in_array($functionName, $this->allowedFunctions),
					'does not exist' => !function_exists($functionName)
				]);
				return null;
			}

			$parameter = isset($parsedStatement['parameter']) ? $parsedStatement['parameter'] : '';
			$parameters = $parameter
				? preg_split("/\s*,\s*/", $parameter)
				: array();

			$this->debug('Parameter Strings', $parameters);


			// native PHP functions cannot be called with call_user_func_array (as not user function)
			if ($functionName === 'isset'){

				// we have a parameter
				if (isset($parameters[0])){

					$parsedParameter = $this->parseStatement($parameters[0]);
					$globalParameter = isset($parsedParameter['global']) ? $parsedParameter['global'] : false;

					// we have a global parameter
					if ($globalParameter){

						$key = $parsedParameter['key'];

						if (!$key){
							return false;
						}

						if ($globalParameter == 'GET'){
							$result = isset($_GET[$key]);
						} elseif ($globalParameter == 'POST'){
							$result = isset($_POST[$key]);
						} elseif ($globalParameter == 'GLOBALS'){
							$result = isset($GLOBALS[$key]);
						}
					}
				}

				// no parameter, so false
				else {
					$result = null;
				}
			}

			// run function
			else {

				// convert parameter strings to PHP result
				foreach ($parameters as $i => $parameterString){

					$parsedParameter = $this->parseStatement($parameterString);

					if (!$parsedParameter){
						$this->debug('Cannot parse $parameterString: ' . $parameterString);
					} else {
						$parameters[$i] = $this->statementResult($parsedParameter);
					}
				}

				$this->debug('Parameters converted', $parameters);

				$result = call_user_func_array(
					$functionName,
					$parameters
				);

			}

			// reverse result if negation has been used e.g. !is_page(20)
			$negation = isset($parsedStatement['negation']) && $parsedStatement['negation'];

			if ($negation){
				$result = !$result;
			}

		}

		// boolean
		$boolean = isset($parsedStatement['boolean']) ? $parsedStatement['boolean'] : false;
		if ($boolean){
			$result = $boolean === 'true';
		}

		// number
		$number = isset($parsedStatement['number']) ? $parsedStatement['number'] : false;
		if ($number){
			$result = strpos($number, '.') === false ? intval($number) : floatval($number);
		}

		// string
		$string = isset($parsedStatement['string']) ? $parsedStatement['string'] : false;
		if ($string){
			$result = str_replace(array('"', "'"), '', $string);
		}

		return $result;
	}

	protected function evaluateStatement($value){

		// split the statement on the comparison (e.g. ===)
		$results = preg_split(
			$this->patterns["comparison"],
			trim($value),
			-1,
			PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE
		);

		$comparison = false;

		$this->debug('Split on any comparison', $results);

		foreach ($results as $index => $part){

			if ($index === 1){
				$comparison = $part;
			}

			// process the result of the statement
			else {

				$parsedStatement = $this->parseStatement($part);

				if (!$parsedStatement) {
					$this->debug( 'Cannot parse statement: ' . $part);
					$results[$index] = null;
				} else {
					$this->debug( 'Could parse statement: ' . $part, $parsedStatement);
					$results[$index] = $this->statementResult($parsedStatement);
				}
			}
		}

		$this->debug('Processed statement results:', $results);

		// return comparison if defined and we have two values
		if ($comparison && count($results) > 2){

			$a = $results[0];
			$b = $results[2];

			switch ($comparison) {
				case '==':
					return $a == $b;
				case '===':
					return $a === $b;
				case '!=':
					return $a != $b;
				case '!==':
					return $a !== $b;
				case '>':
					return $a > $b;
				case '<':
					return $a < $b;
				case '>=':
					return $a >= $b;
				case '<=':
					return $a <= $b;
				default:
					return false;
			}

		}

		// otherwise simply return the first result
		return isset($results[0])
					? $results[0]
					: null;

	}

	public function parse($string){

		if (!$string) {
			return array();
		}

		$this->current = array();
		$this->stack = array();

		// use caret ^^ placeholder for function parenthesis we don't create an extra group
		// and replace && with and for simpler regex/logic
		$string = $this->normaliseAndOr(
			$this->addCarets(
				trim($string)
			)
		);

		$this->string = $string;
		$this->length = strlen($this->string);

		// look at each character
		for ($this->position=0; $this->position < $this->length; $this->position++) {

			switch ($this->string[$this->position]) {
				case '(':

					$this->push();
					// push current scope to the stack and begin a new scope
					$this->stack[] = $this->current;
					$this->current = array();
					break;

				case ')':
					$this->push();
					// save current scope
					$t = $this->current;
					$this->current = array_pop($this->stack);

					// add just saved scope to current scope
					if (count($t)){

						// get the last scope from stack
						$this->current[] = $t;
						break;
					}

					break;

				default:
					// remember the offset to do a string capture later
					// could've also done $buffer .= $string[$position]
					// but that would just be wasting resources…
					if ($this->buffer_start === null) {
						$this->buffer_start = $this->position;
					}
			}
		}

		// catch any trailing text
		if ($this->buffer_start <= $this->position) {
			$this->push();
		}

		return $this->current;
	}

	public function evaluate($statementsArray, $string, $test, $fileExists = null){

		$result = $this->traverseStatements($statementsArray, 'evaluateStatement');

		$this->debug('Debug', array(
			'result' => $result,
			'load' => $result ? 'Yes' : 'No',
			'logic' => $string,
			'num_statements' => count($statementsArray, COUNT_RECURSIVE),
			'analysis' => '<pre>'.print_r($statementsArray, 1).'</pre>'
		), false);

		return !$test
			? $result
			: array(
				'fileExists' => $fileExists,
				'empty' => !$fileExists,
				'result' => $result,
				'resultString' => $result
					? 'true'
					: ($result === null ? 'null' : 'false'),
				'load' => $result ? 'Yes' : 'No',
				'logic' => $string,
				'num_statements' => $this->countNumStatements($statementsArray),
				'analysis' => '<pre>'.print_r($statementsArray, 1).'</pre>'
			);

			/*(
				$fileExists === false && false // seb test todo this causes issues - but check why needed...
					? array(
						'result' => 0,
						'resultString' => 'false',
						'load' => 'No',
						'logic' => $string,
						'num_statements' => $this->countNumStatements($statementsArray),
						'analysis' => 'The folder is not loading because it has no styles. <br /> 
									   Therefore, the logic result is irelevent: <br /><br />' .
						              '<pre>'.print_r($statementsArray, 1).'</pre>'
					)
					: array(
						'result' => $result,
						'resultString' => $result
							? 'true'
							: ($result === null ? 'null' : 'false'),
						'load' => $result ? 'Yes' : 'No',
						'logic' => $string,
						'num_statements' => $this->countNumStatements($statementsArray),
						'analysis' => '<pre>'.print_r($statementsArray, 1).'</pre>'
					)
			);*/

	}

	public function countNumStatements($array){

		foreach ($array as $value) {

			if ( is_array( $value ) ) {
				$this->countNumStatements($value);
			} else {
				if ($value === 'and' || $value === 'or' || $value === 'AND' || $value === 'OR'){
					continue;
				}
				++$this->statementCount;
			}
		}

		return $this->statementCount;
	}

	public function result($string, $test = false, $fileExists = null){

		$this->debug('String received: ' . $string);

		$result = null;
		$error = false;
		$statementsArray = $this->parseStatements($string);

		// Running a function could result in an error which we should capture but suppress
		try {
			$result = $this->evaluate($statementsArray, $string, $test, $fileExists);
		}

		// 'Throwable' is executed in PHP 7+, but ignored in lower PHP versions
		catch (\Throwable $t) {
			$error = $t->getMessage();
		}

		// 'Exception' is executed in PHP 5, this will not be reached in PHP 7+
		catch (\Exception $e) {
			$error = $e->getMessage();
		}

		// return error result if a PHP exception occurs - this should fail silently
		if ($error){

			if ($test){

				$result = array(
					'error' => $error,
					'result' => null,
					'resultString' => 'null',
					'load' => 'No',
					'logic' => $string,
					'num_statements' => 0,
					'analysis' => 'Your condition generated a PHP error. The folder will not load until you fix it: ' . '<br /><br /><b><pre>' . $error . '</pre></b>'
				);
			}

			// the folder just won't load, but no errors will display on the frontend
			else {
				$result = null;
			}

		}

		return $result;

	}

	public function getAllowedPHPSyntax(){
		return array(
			'functions' => $this->allowedFunctions,
			'superglobals' => $this->allowedSuperglobals,
			'characters' => 'or | and & ( ) ! = > <'
		);
	}


	// Integrations
	public static function getBricksTemplateIds($template_id, &$template_ids, $content_type = 'nested'){

		if (is_numeric($template_id) && $template_id > 0
		    && !isset($template_ids[$template_id])
		    && !\Bricks\Database::is_template_disabled($content_type)) {

			$template_ids[intval($template_id)] = $content_type;
			$meta_key = $content_type === 'header'
				? BRICKS_DB_PAGE_HEADER
				: ($content_type === 'footer'
					? BRICKS_DB_PAGE_FOOTER
					: BRICKS_DB_PAGE_CONTENT);
			$bricks_data = get_post_meta( $template_id, $meta_key, true );

			if (is_array($bricks_data)){
				foreach($bricks_data as $item){
					if (!empty($item['settings']['template'])){
						Logic::getBricksTemplateIds($item['settings']['template'], $template_ids);
					}
				}
			}
		}

	}

}

/*
 * Custom (namespaced) microthemer functions for use with logical conditions
 * These fill gaps in WordPress API and can support integrations with other plugins
 * IMPORTANT - all params must be optional to prevent user from generating a fatal error (extra params OK it seems)
 */

// check what admin page the user is on - allow the page name or an id
function is_admin_page($pageNameOrId = false){

	global $post;

	return is_admin() && !$pageNameOrId

	       // e.g. edit.php
	       || (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === $pageNameOrId)

	       // e.g. 123
	       || (is_numeric($pageNameOrId) && isset($_GET['post']) && intval($_GET['post']) === intval($pageNameOrId))

	       // e.g. my-post-slug
	       || (!is_numeric($pageNameOrId) && isset($post->post_name) && $post->post_name === $pageNameOrId);
}

// check what admin page the user is on
function is_public(){
	return !is_admin();
}

// check what admin page the user is on
function is_public_or_admin($postOrPageId = null){
	return !$postOrPageId
	       || ( !is_admin() && (is_page($postOrPageId) || is_single($postOrPageId)) )
	       || is_admin_page($postOrPageId);
}

function query_admin_screen($key = null, $value = null){

	if (!function_exists('get_current_screen')){
		return false;
	}

	$current_screen = get_current_screen();

	return ($key === null || isset($current_screen->$key))
	       && ($value === null || $current_screen->$key === $value);
}

// check if the user has a particular role
function user_has_role($role = null){
	return is_user_logged_in() && $role === null || wp_get_current_user()->roles[0] === $role;
}

// check if a theme or plugin is active, slug is the directory slug e.g. 'microthemer' or 'divi'
function is_active($item = null, $slug = null){
	switch ($item) {
		case 'plugin':
			$active_plugins = get_option('active_plugins', array());
			foreach($active_plugins as $path){
				if (strpos($path, $slug) !== false){
					return true;
				}
			}
			return is_plugin_active_for_network($slug);
		case 'theme':
			$theme = wp_get_theme();
			return $theme->get_stylesheet() === $slug;
		default:
			return false;
	}
}

// check if the current url matches a path
function match_url_path($value = null, $regex = false){
	$urlPath = $_SERVER['REQUEST_URI'];
	return $regex
		? preg_match('/'.$value.'/', $urlPath)
		: strpos($urlPath, $value) !== false;
}

function has_template($source = null, $id = null, $label = null){

	global $post;

	$cache = !empty(Logic::$cache[$source]['template_ids']) ? Logic::$cache[$source]['template_ids'] : false;
	$template_ids = $cache ?: array();

	if (!$source || !$id){
		return false;
	} if ($cache){
		return !empty($cache[$id]);
	}

	// maybe populate template_ids
	switch ($source) {
		case 'bricks':
			if ( \Bricks\Helpers::render_with_bricks($post->ID) ) {
				foreach (\Bricks\Database::$active_templates as $content_type => $template_id){
					Logic::getBricksTemplateIds($template_id, $template_ids, $content_type);
				}
			}
			break;
		case 'elementor': // todo
			break;
	}

	return !empty($template_ids[$id]);
}