<?php

namespace Microthemer;

trait PreferencesTrait {

	function savePreferences($pref_array) {

		// get the full array of preferences
		$thePreferences = get_option($this->preferencesName);

		// update the preferences array with passed values
		foreach ($pref_array as $key => $value) {
			$thePreferences[$key] = $value;
		}

		// store the version so e.g. inactive functions.php code will load most recent PIE / animation-events.js
		$previous_version = empty($thePreferences['version']) ? 0 : $thePreferences['version'];
		if (!$previous_version || $previous_version != $this->version){
			$thePreferences['previous_version'] = $previous_version;
			$thePreferences['version'] = $this->version;
		}

		// we released 5 beta with system for remembering targeting mode on page load,
		// but decided against this, have this hard set for a while to fix in DB for beta testers
		//$thePreferences['hover_inspect'] = 0;

		// save in DB and go to relevant page
		// don't do deep escape here as it can run more than once
		update_option($this->preferencesName, $thePreferences);

		// update the global preferences array
		$this->preferences = $thePreferences;

		// Also update the autoload preferences
		update_option($this->autoloadPreferencesName, $this->copyToAutoloadValues($thePreferences));

		return true;
	}

}
