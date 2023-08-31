<?php

namespace Microthemer;

trait LogTrait {

	function log($short, $long, $type = 'error', $preset = false, $vars = array()){
		// some errors are the same, reuse the text
		if ($preset) {
			if ($preset == 'revisions'){
				$this->globalmessage[++$this->ei]['short'] = __('Revision log update failed.', 'microthemer');
				$this->globalmessage[$this->ei]['type'] = 'error';
				$this->globalmessage[$this->ei]['long'] = '<p>' . esc_html__('Adding your latest save to the revisions table failed.', 'microthemer') . '</p>';
			} elseif ($preset == 'json-decode'){
				$this->globalmessage[++$this->ei]['short'] = __('Decode json error', 'microthemer');
				$this->globalmessage[$this->ei]['type'] = 'error';
				$this->globalmessage[$this->ei]['long'] = '<p>' . sprintf(esc_html__('WordPress was not able to convert %s into a usable format.', 'microthemer'), $this->root_rel($vars['json_file']) ) . '</p>
<p>JSON Error code: '. $this->json_last_error() . '</p>';

				//wp_die('<pre>'.$this->globalmessage[++$this->ei].'</pre>');

			}

		} else {
			$this->globalmessage[++$this->ei]['short'] = $short;
			$this->globalmessage[$this->ei]['type'] = $type;
			$this->globalmessage[$this->ei]['long'] = $long;
		}
	}

	function json_last_error(){
		if (function_exists('json_last_error')){
			return json_last_error();
		}

		return '';
	}

	// save ajax-generated global msg in db for showing on next page load
	// this is now used for debugging too (session and append = true)
	function cache_global_msg($append = false, $session = false){
		$pref_array = array();
		$pref_array['returned_ajax_msg'] = $this->globalmessage;
		$pref_array['returned_ajax_msg_seen'] = 0;
		$this->savePreferences($pref_array);
	}

	// display the logs
	function display_log(){

		// if the page is reloading after an ajax request, we may have unseen status messages to show - merge the two
		if (!empty($this->preferences['returned_ajax_msg']) and !$this->preferences['returned_ajax_msg_seen']){
			$cached_global = $this->preferences['returned_ajax_msg'];
			if (is_array($this->globalmessage)){
				$this->globalmessage = array_unique(
					array_merge($this->globalmessage, $cached_global),
					SORT_REGULAR
				);
			} else {
				$this->globalmessage = $cached_global;
			}
			// clear the cached message as it is beign shown
			$pref_array['returned_ajax_msg'] = '';
			$pref_array['returned_ajax_msg_seen'] = 1;
			$this->savePreferences($pref_array);
		}

		// append the session cached debug messages
		/* Sessions conflict with WP
		 * if (TVR_DEV_MODE && !empty($this->globalmessage) && isset($_COOKIE['cached_mt_messages'])){
			$this->globalmessage = array_merge($this->globalmessage, $_COOKIE['cached_mt_messages']);
		}*/

		$html = '';
		if (!empty($this->globalmessage)) {
			$html.= '<ul class="logs">'; // so 'loading WP site' msg doesn't overwrite
			foreach ($this->globalmessage as $key => $log) {
				if ($log['type'] == 'dev-notice'){
					continue;
				}
				$html .= $this->display_log_item($log['type'], $log, $key);
			}
			$html .= '</ul><span id="data-msg-pending" rel="1"></span>';
		}

		else {
			$html.= '<ul class="logs"></ul><span id="data-msg-pending" rel="0"></span>';
		}
		return $html;
	}

	// display log item - used as template so need as function to keep html consistent
	function display_log_item($type, $log, $key, $id = ''){
		$html = '
				<li '.$id.' class="tvr-'.$type.' tvr-message mt-fixed-opacity mt-fixed-color row-'.($key+1).'">
					<span class="short">'.$log['short'].'</span>
					<div class="long">'.$log['long'].'</div>
				</li>';
		return $html;
	}


	// Error Reporting methods

	function maybeSendReport($post, $manual = false){

		// update the reporting preference (with latest permissions), which gets updated on the client side
		if (isset($post['reporting'])){
			$this->savePreferences(array(
				'reporting' => $post['reporting']
			));
		}

		// permission and quota values
		$reporting         = &$this->preferences['reporting'];
		$permission        = $reporting['permission'];
		$filePermission    = !empty($permission['file']);
		$dataPermission    = !empty($permission['data']);
		$contactPermission = !empty($permission['contact']);
		$disallowed        = !$manual && !$filePermission && !$dataPermission;
		$status            = $_POST['status'];
		$fileQuotaReached  = !$manual && $status['fileQuotaReached'];
		$dataQuotaReached  = !$manual && $status['dataQuotaReached'];
		$allQuotaReached   = $fileQuotaReached && $dataQuotaReached;

		// bail if error reporting is disabled or quotas have been reached
		if ($disallowed || $allQuotaReached){
			return json_decode(array('message' => 'Sending quota reached'));
		}

		// minimum information to send
		$error     = !empty($post['error']) ? $post['error'] : null;
		$onboard   = !empty($post['onboard']) ? $post['onboard'] : null;
		$member_id = !empty($this->preferences['subscription']['member_id'])
			? $this->preferences['subscription']['member_id']
			: null;
		$postLimit = $this->reporting['max'][($manual ? 'manual' : 'auto')];
		$payload   = array(
			'pro'           => !empty($this->preferences['buyer_validated']) ? 1 : 0,
			'manual'        => $manual ? 1 : 0,
			'onboard'       => $onboard ? 1 : 0,
			'partial'       => 0,
			'size'          => 0,
			'layout_preset' => $this->preferences['suggested_layout'],
			'site_id'       => $reporting['anonymous_id'],
			'member_id'     => $contactPermission && $member_id ? $member_id : null,
			'domain'     => $contactPermission && isset($post['domain']) ? $post['domain'] : null,
			'vendor'     => isset($post['vendor']) ? $post['vendor'] : null,
			'vendor_type'     => isset($post['vendor_type']) ? $post['vendor_type'] : null,
			'version'       => $this->version,
			'note'          => '',
			'error_message'     => isset($post['error_message']) ? $post['error_message'] : null,
			'error_key'     => isset($post['error_key']) ? $post['error_key'] : null,
			'error_stack'     => isset($post['error_stack']) ? $post['error_stack'] : null,
			'context'       => isset($post['context']) ? json_encode($post['context'], true) : null,
			//'reporting'     => isset($post['reporting']) ? json_encode($post['reporting'], true) : null, // if we need to debug
			'history'       => $this->getRevisionActionsAsString($onboard),

		);

		// if they have given permission to send data
		// don't send data routinely even if permission is set
		// (may change this if I need to set TvrUi.errorsRequiringData keys regularly)
		if ($dataPermission &&
		    !$dataQuotaReached &&
		    ($manual || !empty($this->errorsRequiringData[$payload['error_key']]))) {

			// include the domain name
			$serverMaxBytes = $this->sizeStringToBytes(ini_get('post_max_size')) - 1024;

			$maxBytes = $postLimit['bytes'] && $postLimit['bytes'] <= $serverMaxBytes
				? $postLimit['bytes']
				: $serverMaxBytes;

			// include data in priority of helpfulness for debugging, bail if too big
			$analysis = array(
				'items'        => array('options_live', 'options', 'preferences'),
				'total_size'   => 0,
				'max_size'     => $maxBytes, // 1.5MB
				'max_reached' => false,
			);

			foreach ($analysis['items'] as $key){

				if ($analysis['max_reached']){
					break;
				}

				$serialised_data = null;

				switch ($key) {
					case 'options_live':
					case 'preferences':
						if (!empty($post[$key])){
							$serialised_data = serialize(json_decode(stripslashes($post[$key]), true ));
						}
						break;
					case 'options':
						$serialised_data = serialize($this->options);
						break;
				}

				if ($serialised_data){
					$this->includeDataIfNotTooBig(
						$key, $serialised_data, $payload, $analysis
					);
				}
			}

			//$payload['analysis'] = $analysis; // for debugging
			$payload['size'] = $this->bytesToMB($analysis['total_size']);

		}

		// show user what MT is sending if this is a preview
		if (!empty($post['preview'])){
			return print_r($payload, 1);
		}

		// Post the payload to Themeover
		$url = false
			? 'http://localhost/microthemer/wordpress/report/'
			: 'https://validate.themeover.com/report/';

		$response = wp_remote_post($url, array(
			'method'   => 'POST',
			'timeout'  => $postLimit['timeout'],
			'blocking' => $manual,
			'body'     => $payload,
			'compress' => true
		));

		if (!$manual){
			return 'sent';
		}

		if ( is_wp_error( $response ) ) {
			$this->log(
				esc_html__('Error report failed', 'microthemer'),
				'<p>' . $response->get_error_message() . '</p>',
				'error'
			);
			return false;
		} else {
			$this->log(
				esc_html__('Error report sent', 'microthemer'),
				'<p>Thank you, your report has been successfully sent.</p>',
				'success'
			);
			return json_decode($response['body']);
		}

	}

	function bytesToMB($value){
		return $value ? round($value / 1000000, 3) : 0;
	}

	function sizeStringToBytes($size_str)
	{
		switch (substr ($size_str, -1))
		{
			case 'M': case 'm': return (int) $size_str * 1048576;
			case 'K': case 'k': return (int) $size_str * 1024;
			case 'G': case 'g': return (int) $size_str * 1073741824;
			default: return $size_str;
		}
	}

	function includeDataIfNotTooBig($key, $serialisedData, &$payload, &$analysis){

		$size = mb_strlen($serialisedData, '8bit');
		$analysis[$key] = $this->bytesToMB($size);
		$analysis['total_size']+= $size;

		// bail if data is too large to send
		if ($analysis['total_size'] > $analysis['max_size']){
			$analysis['max_reached'] = true;
			$sizeMB = $this->bytesToMB($size);
			$totalMB = $this->bytesToMB($analysis['total_size']);
			$maxMB = $this->bytesToMB($analysis['max_size']);
			$payload['partial'] = 1;
			$payload['note'].= $key . ' ('.$sizeMB.'MB) was too big to include. Total would have been '. $totalMB . 'MB.  The max is '.$maxMB.'MB. ';
			return false;
		}

		// all good
		$payload[$key] = $serialisedData;

		return true;
	}

	function getRevisionActionsAsString($onboard){

		$history_limit = $onboard ? intval($this->preferences['num_history_points']) : 2;

		global $wpdb;
		$table_name = $wpdb->prefix . "micro_revisions";

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT user_action FROM $table_name order by id desc limit %d",
				$history_limit
			),
			ARRAY_A
		);

		return $rows ? strip_tags( serialize($rows) ) : null;
	}

}
