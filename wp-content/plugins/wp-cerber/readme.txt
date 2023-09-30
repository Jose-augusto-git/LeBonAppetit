=== WP Cerber Security, Anti-spam & Malware Scan ===
Contributors: gioni
Tags: security, malware scanner, antispam, firewall, limit login attempts, custom login url, login, recaptcha, captcha, activity, log, logging, access list
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SQ5EC8WQP654Q&source=url
Requires at least: 4.9
Requires PHP: 7.0
Tested up to: 6.3
Stable tag: 9.5.7
License: GPLv2

Protection against hacker attacks and bots. Malware scanner & integrity checker. User activity log. Antispam reCAPTCHA. Limit login attempts.

== Description ==

Defends WordPress against hacker attacks, spam, trojans, and malware. Mitigates brute-force attacks by limiting the number of login attempts through the login form, XML-RPC / REST API requests, or using auth cookies. Tracks user and bad actors activity with flexible email, mobile and desktop notifications. Stops spammers by using a specialized anti-spam engine. Uses Google reCAPTCHA to protect registration, contact, and comments forms. Restricts access with IP Access Lists. Monitors the website integrity with an advanced malware scanner and integrity checker. Reinforces the security of WordPress with a set of flexible security rules and sophisticated security algorithms.

**Features you will love**

* Limit login attempts when logging in by IP address or entire subnet.
* Monitors logins made by login forms, XML-RPC requests or auth cookies.
* Permit or restrict access by [IP Access Lists](https://wpcerber.com/using-ip-access-lists-to-protect-wordpress/) with a single IP, IP range or subnet.
* Create **Custom login URL** ([rename wp-login.php](https://wpcerber.com/how-to-rename-wp-login-php/)).
* Cerber anti-spam engine for protecting contact and registration forms.
* Automatically detects and moves spam comments to trash or denies them completely.
* [Manage multiple WP Cerber instances from one dashboard](https://wpcerber.com/manage-multiple-websites/).
* [Two-Factor Authentication for WordPress](https://wpcerber.com/two-factor-authentication-for-wordpress/).
* Logs users, bots, hacker and other suspicious activities.
* Security scanner verifies the integrity of WordPress files, plugins and themes.
* Monitors file changes and new files with email notifications and reports.
* [Mobile and email notifications with a set of flexible filters](https://wpcerber.com/wordpress-notifications-made-easy/).
* Advanced users' sessions manager
* Protects wp-login.php, wp-signup.php and wp-register.php from attacks.
* Hides wp-admin (dashboard) if a visitor isn't logged in.
* Immediately blocks an intruder IP when attempting to log in with non-existent or prohibited username.
* Restrict user registration or login with a username matching REGEX patterns.
* [Restrict access to WP REST API with your own role-based security rules](https://wpcerber.com/restrict-access-to-wordpress-rest-api/).
* Block access to WordPress REST API completely.
* Block access to XML-RPC (block access to XML-RPC including Pingbacks and Trackbacks).
* Disable feeds (block access to the RSS, Atom and RDF feeds).
* Restrict access to XML-RPC, REST API and feeds by **White IP Access list** by an IP address or an IP range.
* [Authorized users only mode](https://wpcerber.com/only-logged-in-wordpress-users/)
* [Block a user account](https://wpcerber.com/how-to-block-wordpress-user/).
* Disable automatic redirection to the hidden login page.
* **Stop user enumeration** (blocks access to author pages and prevents user data leaks via REST API).
* Proactively **blocks IP subnet class C**.
* Anti-spam: **reCAPTCHA** to protect WordPress login, register and comment forms.
* [reCAPTCHA for WooCommerce & WordPress forms](https://wpcerber.com/how-to-setup-recaptcha/).
* Invisible reCAPTCHA for WordPress comments forms.
* A special Citadel mode for **massive brute force attacks**.
* [Play nice with fail2ban](https://wpcerber.com/how-to-protect-wordpress-with-fail2ban/): write failed attempts to the syslog or a custom log file.
* Filter out and inspect activities by IP address, user, username or a particular activity.
* Filter out activities and export them to a CSV file.
* Reporting: get weekly reports to specified email addresses.
* Limit login attempts works on a site/server behind a reverse proxy.
* [Be notified via mobile push notifications](https://wpcerber.com/wordpress-mobile-and-browser-notifications-pushbullet/).
* Trigger and action for the [jetFlow.io automation plugin](http://jetflow.io).
* Protection against (DoS) attacks (CVE-2018-6389).

= Limit login attempts done right =

By default, WordPress allows unlimited login attempts through the login form, XML-RPC or by sending special cookies. This allows passwords to be cracked with relative ease via brute force attack.

WP Cerber blocks intruders by IP or subnet from making further attempts after a specified limit on retries is reached, making brute force attacks or distributed brute force attacks from botnets impossible.

You will be able to create a **Black IP Access List** or **White IP Access List** to block or allow logins from a particular IP address, IP address range or a subnet any class (A,B,C).

Moreover, you can create your Custom login page and forget about automatic attacks to the default wp-login.php, which takes your attention and consumes a lot of server resources. If an attacker tries to access wp-login.php they will be blocked and get a 404 Error response.

= Malware scanner =

Cerber Security Scanner is a sophisticated and extremely powerful tool that thoroughly scans every folder and inspects every file on a website for traces of malware, trojans, backdoors, changed and new files.

[Read more about the malware scanner](https://wpcerber.com/wordpress-security-scanner/).

= Integrity checker =

The scanner checks if all WordPress folders and files match what exist in the official WordPress core repository, compares your plugins and themes with what are in the official WordPress repository and alerts you to any changes. As with scanning free plugins and themes, the scanner scans and verifies commercial plugins and themes that are installed manually.

= Scheduled Scans With Automatic File Recovery =

Cerber Security Scanner allows you to configure a schedule for automated recurring scanning easily. Once the schedule is configured the scanner automatically scans the website, deletes malware and recovers modified and infected WordPress files. After every scan, you can get an optional email report with the results of the scan.

[Read more about the scheduled scans](https://wpcerber.com/automated-recurring-malware-scans/).

= Two-Factor Authentication =

Two-Factor Authentication (2FA) provides an additional layer of security requiring a second factor of identification beyond just a username and password. When 2FA is enabled on a website, it requires a user to provide an additional verification code when signing into the website. This verification code is generated automatically and sent to the user by email.

[Read more about Two-Factor Authentication](https://wpcerber.com/wordpress/2fa/).

= Log, filter out and export activities =

WP Cerber tracks time, IP addresses and usernames for successful and failed login attempts, logins, logouts, password changes, blocked IP and actions taken by itself. You can export them to a CSV file.

= Limit login attempts reinvented =

You can **hide WordPress dashboard** (/wp-admin/) when a user isn't logged in. If a user isn't logged in and they attempt to access the dashboard by requesting /wp-admin/, WP Cerber will return a 404 Error.

Massive botnet brute force attack? That's no longer a problem. **Citadel mode** will automatically be activated for awhile and prevent your site from making further attempts to log in with any username.

= Cerber anti-spam engine =

Anti-spam and anti-bot protection for contact, registration, comments and other forms. WP Cerber anti-spam and bot detection engine now protects all forms on a website. No reCAPTCHA is needed. It’s compatible with virtually any form you have. Tested with Gravity Forms, Caldera Forms, HappyForms, Contact Form 7, Ninja Forms, Formidable Forms, Fast Secure Contact Form, Contact Form by WPForms.

= Anti-spam protection: invisible reCAPTCHA for WooCommerce =

* WooCommerce login form
* WooCommerce register form
* WooCommerce lost password form

= Anti-spam protection: invisible reCAPTCHA for WordPress =

* WordPress login form
* WordPress register form
* WordPress lost password form
* WordPress comment form

= Integration with Cloudflare =

A [special Cloudflare add-on for WP Cerber](https://wpcerber.com/cloudflare-add-on-wp-cerber/) keeps in sync the list of blocked IP addresses with Cloudflare IP Access Rules.

**Stay in compliance with GDPR**

How to get full control of personal data to be in compliance with data privacy laws such as GDPR in Europe or CCPA in California.

* [Personal data export feature](https://wpcerber.com/export-personal-data/)
* [Personal data erase feature](https://wpcerber.com/delete-personal-data/)
* [How WP Cerber processes browser cookies](https://wpcerber.com/browser-cookies-set-by-wp-cerber/)

**Documentation & Tutorials**

* [Configuring Two-Factor Authentication](https://wpcerber.com/two-factor-authentication-for-wordpress/)
* [How to set up notifications](https://wpcerber.com/wordpress-notifications-made-easy/)
* [Push notifications with Pushbullet](https://wpcerber.com/wordpress-mobile-and-browser-notifications-pushbullet/)
* [How to set up invisible reCAPTCHA for WooCommerce](https://wpcerber.com/how-to-setup-recaptcha/)
* [Changing default plugin messages](https://wpcerber.com/wordpress-hooks/)
* [2FA alternatives to the Clef plugin](https://wpcerber.com/two-factor-authentication-plugins-for-wordpress/)
* [Why reCAPTCHA does not protect WordPress from bots and brute-force attacks](https://wpcerber.com/why-recaptcha-does-not-protect-wordpress/)

**Translations**

* Czech, thanks to [Hrohh](https://profiles.wordpress.org/hrohh/)
* Deutsche, thanks to mario, Mike and [Daniel](http://detacu.de)
* Dutch, thanks to Jos Knippen and [Bernardo](https://twitter.com/bernardohulsman)
* Français, thanks to [hardesfred](https://profiles.wordpress.org/hardesfred/)
* Norwegian (Bokmål), thanks to [Eirik Vorland](https://www.facebook.com/KjellDaSensei)
* Portuguese (Portugal), thanks to Helderk
* Portuguese (Brazil), thanks to [Felipe Turcheti](http://felipeturcheti.com)
* Spanish, thanks to Ismael Murias and [leemon](https://profiles.wordpress.org/leemon/)
* Український, thanks to [Nadia](https://profiles.wordpress.org/webbistro)
* Русский, thanks to [Yui](https://profiles.wordpress.org/fierevere/)
* Italian, thanks to [Francesco Venuti](http://www.algostream.it/)
* Swedish, thanks to Fredrik Näslund

Thanks to [POEditor.com](https://poeditor.com) for helping to translate this project.

**Compatibility is not verified**

There are some plugins that were not checked to be compatible: Login LockDown, Login Security Solution, BruteProtect, Ajax Login & Register, Lockdown WP Admin, Loginizer, Sucuri, Wordfence, BulletProof Security, SiteGuard WP Plugin, iThemes Security, All In One WP Security & Firewall, Brute Force Login Protection

**Another reliable plugins from the trusted author**

* [Plugin Inspector reveals issues with installed plugins](https://wordpress.org/plugins/plugin-inspector/)

Checks plugins for deprecated WordPress functions, known security vulnerabilities, and some unsafe PHP functions

* [Translate sites with Google Translate Widget](https://wordpress.org/plugins/goo-translate-widget/)

Make your website instantly available in 90+ languages with Google Translate Widget. Add the power of Google automatic translations with one click.

== Installation ==

Installing the WP Cerber Security plugin is the same as other WordPress plugins.

1. Install the plugin through Plugins > Add New > Upload or unzip plugin package into wp-content/plugins/.
2. Activate the WP Cerber through the Plugins > Installed Plugins menu in the WordPress admin dashboard.
3. Read carefully: [Getting Started Guide](https://wpcerber.com/getting-started/)

**Important notes**

1. Before enabling invisible reCAPTCHA, you must obtain separate keys for the invisible version. [How to enable reCAPTCHA](https://wpcerber.com/how-to-setup-recaptcha/).
2. If you want to test out plugin's features, do this on another computer (or incognito browser window) and remove computer IP address or network from the White Access List. Cerber is smart enough to recognize "the boss".
3. If you've set up the Custom login URL and you use some caching plugin like **W3 Total Cache** or **WP Super Cache**, you have to add the new Custom login URL to the list of pages not to cache.
4. [Read this if your website is under CloudFlare](https://wpcerber.com/cloudflare-and-wordpress-cerber/)
5. If you use the Jetpack plugin or another plugin that needs to connect to wordpress.com, you need to unlock XML-RPC. To do that go to the Hardening tab, uncheck Disable XML-RPC, and click the Save changes button.

The following steps are optional but they allow you to reinforce the protection of your WordPress.

1. Fine tune **Limit login attempts** settings making them more restrictive according to your needs
2. Configure your **Custom login URL** and remember it (the plugin will send you an email with it).
3. Once you have configured Custom login URL, check 'Immediately block IP after any request to wp-login.php' and 'Block direct access to wp-login.php and return HTTP 404 Not Found Error'. Don't use wp-admin to log in to your WordPress dashboard anymore.
4. If your WordPress has a few experienced users, check 'Immediately block IP when attempting to log in with a non-existent username'.
5. Specify the list of prohibited usernames (logins) that legit users will never use. They will not be permitted to log in or register.
6. Configure mobile and browser notifications via Pushbullet.
7. Obtain keys and enable invisible reCAPTCHA for password reset and registration forms (WooCommerce supported too).

== Frequently Asked Questions ==

= Can I use the plugin with CloudFlare? =

Yes.  [WP Cerber settings for CloudFlare](https://wpcerber.com/cloudflare-and-wordpress-cerber/).

= Is WP Cerber Security compatible with WordPress multisite mode? =

Yes. All settings apply to all sites in the network simultaneously. You have to activate the plugin in the Network Admin area on the Plugins page. Just click on the Network Activate link.

= Is WP Cerber Security compatible with bbPress? =

Yes. [Compatibility notes](https://wpcerber.com/compatibility/).

= Is WP Cerber Security compatible with WooCommerce? =

Completely.

= Is reCAPTCHA for WooCommerce free feature? =

Yes. [How to set up reCAPTCHA for WooCommerce](https://wpcerber.com/how-to-setup-recaptcha/).

= Are there any incompatible plugins? =

The following plugins can cause some issues: Ultimate Member, WPBruiser {no- Captcha anti-Spam}, Plugin Organizer, WP-SpamShield.
The Cerber Security plugin won't be updated to fix any issue or conflict related to them, you should decide and stop using one or all of them.
Read more: [https://wpcerber.com/compatibility/](https://wpcerber.com/compatibility/).

= Can I change login URL (rename wp-login.php)? =

Yes, easily. [How to rename wp-login.php](https://wpcerber.com/how-to-rename-wp-login-php/)

= Can I hide the wp-admin folder? =

Yes, easily. [How to hide wp-admin and wp-login.php from possible attacks](https://wpcerber.com/how-to-hide-wp-admin-and-wp-login-php-from-possible-attacks/)

= Can I rename the wp-admin folder? =

Nope. It's not possible and not recommended for compatibility reasons.

= Can I hide the fact I use WordPress? =

No. We strongly encourage you not to use any plugin that renames wp-admin folder to protect a website.
 Beware of all plugins that hide WordPress folders or other parts of a website and claim this as a security feature.
 They are not capable to protect your website. Don't be silly, hiding some stuff doesn't make your site more secure.

= Can WP Cerber Security work together with the Limit Login Attempts plugin? =

Nope. WP Cerber is a drop in replacement for that outdated plugin.

= Can WP Cerber Security protect my site from DDoS attacks? =

Nope. The plugin protects your site from Brute force attacks or distributed Brute force attacks. By default WordPress allows unlimited login attempts either through the login form or by sending special cookies. This allows passwords to be cracked with relative ease via a brute force attack. To prevent from such a bad situation use WP Cerber.

= Is there any WordPress plugin to protect my site from DDoS attacks? =

Nope. This hard task cannot be done by using a plugin. That may be done by using special hardware from your hosting provider.

= What is the goal of the Citadel mode? =

Citadel mode is intended to block massive bot (botnet) attacks and also a slow brute force attack. The last type of attack has a large range of intruder IPs with a small number of attempts to log in per each.

= How to turn off the Citadel mode completely? =

Set Threshold fields to 0 or leave them empty.

= What is the goal of using Fail2Ban? =

With Fail2Ban you can protect site on the OS level with iptables firewall. See details here: [https://wpcerber.com/how-to-protect-wordpress-with-fail2ban/](https://wpcerber.com/how-to-protect-wordpress-with-fail2ban/)

= Do I need to use Fail2Ban to get the plugin working? =

No, you don't. It is optional.

= Can I use this plugin on the WP Engine hosting? =

Yes! WP Cerber Security is not on the list of disallowed plugins.

= Is the plugin compatible with Cloudflare? =

Yes, read more: https://wpcerber.com/cloudflare-and-wordpress-cerber/

= Does the plugin works on websites with SSL(HTTPS) =

Absolutely!

= It seems that old activity records are not removing from the activity log =

That means that scheduled tasks are not executed on your site. In other words, WordPress cron is not working the right way.
Try to add the following line to your wp-config.php file:

define( 'ALTERNATE_WP_CRON', true );

= I'm unable to log in / I'm locked out of my site / How to get access (log in) to the dashboard? =

There is a special version of the plugin called **WP Cerber Reset**. This version performs only one task. It resets all WP Cerber settings to their initial values (excluding Access Lists) and then deactivates itself.

To get access to your dashboard you need to copy the WP Cerber Reset folder to the plugins folder. Follow these simple steps.

1. Download the wp-cerber-reset.zip archive to your computer using this link: [https://wpcerber.com/downloads/wp-cerber-reset.zip](https://wpcerber.com/downloads/wp-cerber-reset.zip)
2. Unpack the wp-cerber folder from the archive.
3. Upload the wp-cerber folder to the **plugins** folder of your WordPress using any FTP client or a file manager in your hosting control panel. If you see a question about overwriting files, click Yes.
4. Log in to your website as usual. Now WP Cerber is disabled completely.
5. Reinstall the WP Cerber plugin again. You need to do that, because **WP Cerber Reset** cannot work as a normal plugin.

== Screenshots ==

1. The WP Cerber dashboard provides a real-time overview of important security metrics, displays activity of WordPress users, and shows important security events.
2. The activity log in WP Cerber helps website owners and WordPress administrators monitor user activity, identify potential security threats, and troubleshoot issues that may arise on the website.
3. A user session management console is a tool that enables WordPress administrators to manage user sessions on their websites from a central location. It allows administrators to search for, monitor, and manage active user sessions, including the ability to view user activity and terminate user sessions.
4. A list of blocked malicious IP addresses is another tool for WordPress administrators. It provides a detailed overview of all IP addresses that have been blocked by WP Cerber, including host names, countries of origin, reasons for being blocked, and the ability to unlock an IP address if necessary.
5. The Main Settings tab enables you to configure login security features, brute-force protection parameters, custom WordPress login page and other important plugin settings.
6. On the Hardening tab you can manage multiple access control features that block access to sensitive website data, prevent user enumeration, disable user detail exposure, restrict access to WordPress REST API, and other WordPress data interfaces that are vulnerable to bad actors and data scraping bots.
7. On the Notification tab administrators configure email parameters for notifications, alerts, and reports. WP Cerber provides mobile alerts and email notifications to WordPress administrators to keep them informed about security-related events on their websites.

== Changelog ==

= 9.5.7 =
New: When [two-factor authentication](https://wpcerber.com/two-factor-authentication-for-wordpress/) is enabled, users can now optionally click a checkbox on the 2FA form to remember their devices for a predefined period of days. Available in the professional version of WP Cerber.
Improved: Enhanced details about generated 2FA PIN codes on the user profile page.
Improved: The tabs labeled "Role-based" and "Global" are now renamed to "Role Policies" and "Global Policies" respectively.
Fixed: The 2FA email address set on the user profile page is ignored when sending 2FA codes.
Fixed: A fatal error occurs when using [Cerber.Hub](https://wpcerber.com/manage-multiple-websites/) and switching to a managed website where automatic updates for WP Cerber were enabled.

= 9.5.6 =
New: WP Cerber now sends 2FA verification codes via SMTP. If an SMTP server is set up in the WP Cerber settings, it will be used to send these codes.
New: Implemented a backup method for sending emails via an SMTP server. If an attempt to send an email through the SMTP server fails, WP Cerber will resort to using the default WordPress mailer.
New: Email error reporting has been introduced. If an error occurs while WP Cerber is sending an email, the error details are captured and shown as a warning on the WP Cerber dashboard.
Improved: If your website crashes and displays the WordPress message "There has been a critical error on this website", WP Cerber captures and logs fatal PHP errors.
Improved: WP Cerber now identifies and shows the name, version and author of a plugin or a theme that produced PHP errors.
Improved: All users with prohibited usernames (logins) are marked with the red label "PROHIBITED" on the Users admin page.
Improved: The limits on the maximum length of SMTP setting fields have been increased from 28 characters to 64.
Fixed: If HTTP redirection is set to handle attempts to access protected areas, and WP Cerber blocks an intruder's IP address, no email alerts are sent even if lockout alerting is enabled.

= 9.5.5 =
New: WP Cerber now supports establishing outgoing network connections via a proxy server that is configured for WordPress.
Improved: File operations and error handling in the WP Cerber scanner have been enhanced. Any unsuccessful file recoveries are displayed in the scan results.
Improved: If a file recovery requires creating missing folders, the scanner create them.
Improved: To prevent altering source files, the scanner recovery folders are emptied before starting a scan.
Improved: When email notifications for new versions of installed plugins are enabled, you will receive an alert as soon as either WP Cerber or WordPress detects an update.
Improved: You can enable automatic updates for WP Cerber in the main plugin settings now.
Fixed: If a file is missing, the scanner does not recover it.

= 9.5.4 =
Improved: The breaking changes introduced in WooCommerce 7.5.1 interfered with the WP Cerber anti-spam engine when enabled, causing issues with AJAX-based functionality in WooCommerce.
Fixed: Multiple admin notices to appear when a new version of WP Cerber is available but not installed.
Fixed: A PHP error message can appear while viewing log entries filtered by an IP address.

= 9.5.3 =
New: You can define a more secure location of the protected WP Cerber directory [by using a PHP constant](https://wpcerber.com/changing-location-wp-cerber-directory/).
Improved: JSON payload of REST API and other requests is decoded and saved to the "Live Traffic" log.
Improved: The "Form submissions" filter, located on the Live Traffic tab, filters out conventional form submissions and no longer includes REST API requests.
Improved: The activity export file now includes a new column, "By User," which contains the user ID of the user who initiated the row event.
Improved: The names of export files are now unified and include the website URL, making it easier to identify which website the file was downloaded from.
Improved: Prevent Jetpack’s Asset CDN from destroying the layout and style of WP Cerber admin pages.

= 9.5 =
New: Get an email notification whenever a new version of a plugin is available.
New: An additional option for granting access to users’ data via REST API for selected user roles.
New: An additional option for [sending activity alerts](https://wpcerber.com/wordpress-notifications-made-easy/). Email alerts can be sent to an email address you have on your WordPress account.
Improved: WP Cerber now permanently stores users’ last login data (IP address, time, user’s country) for all users. [The data can be erased by website admin](https://wpcerber.com/delete-personal-data/).
Improved: To prevent having insecure plugin configuration, WP Cerber validates required HTTP headers before enabling [the behind a proxy mode](https://wpcerber.com/wordpress-ip-address-detection/) in the WP Cerber settings.
Fixed: A specially formatted request can bypass the disabled redirection from a /wp-admin/ locations to the [custom login page](https://wpcerber.com/how-to-rename-wp-login-php/).
Fixed: The [integrity scanner](https://wpcerber.com/wordpress-security-scanner/) labels a file as "File is missing" if the folder containing the file is on the "Directories to exclude" list.
Fixed: After clicking "Apply" on the "Screen Options" on the [Cerber.Hub](https://wpcerber.com/manage-multiple-websites/) admin page, a blank page is displayed.

= 9.4 =
New: In addition to weekly reporting, WP Cerber can be configured to generate and send monthly activity reports once a month.
New: Weekly activity reports now can be generated either for the last 7 days or the previous calendar week.
New: Redirecting requests to a specified URL instead of generating a 404 page when attempting to access prohibited locations on a website.
New: The "Remember Me" checkbox on the WordPress login form can be disabled.
Improved: No access to author archives via any possible URLs if "Block access to user pages via their usernames" is enabled.
Improved: The default period of weekly reports is the previous calendar week.
Fixed: If WordPress is installed in a subfolder and the custom login page is configured, submitting the password reset form doesn’t redirect users to the page with a success message showing "Not Found" instead.
Fixed: If the custom login page is configured, disabling the login language switcher has no effect on the login form and the language switcher is still displayed.
Fixed: On some multi-site WordPress installations, WP Cerber can produce warning messages about using undefined UPLOADBLOGSDIR constant
Fixed: If the access lists contain IPv6 addresses and the Activity log contains entries with IPv6 addresses, viewing those entries causes PHP warnings "undefined property: stdClass::$comments".
Fixed: If Pushbullet mobile notifications are enabled and the list of available devices contains inactive (removed) devices, WP Cerber produces PHP notices "Undefined index: nickname" while parsing the list.

= 9.3.2 =
* Improved: Every locked-out IP address on the "Lockout" tab has a link to check its suspicious activity in the Activity log.
* Improved: The activity log provides more details on [two-factor authentication (2FA)](https://wpcerber.com/two-factor-authentication-for-wordpress/) events with several new statuses that are logged if an attempt to log in using 2FA was aborted.
* Improved: The activity log provides more details when a user was forcefully logged out (user session has been terminated) due to a restriction.
* Fixed minor vulnerability: If WordPress is installed in a subfolder and [access to WordPress REST API has been blocked on the "Hardening" tab](https://wpcerber.com/restrict-access-to-wordpress-rest-api/), a bad actor can get access to REST API by using a specially formatted request.
* Fixed bug: Multiple duplicate notifications are sent via email and [Pushbullet](https://wpcerber.com/wordpress-mobile-and-browser-notifications-pushbullet/) if an IP address is permanently getting blocked due to multiply consequent malicious requests.

= 9.3 =
* This is a bug fix and code optimization version
* Fixed: Unable to remove a blocked IP network class C (with an asterisk) from the list of locked out IP addresses by clicking the "Remove" link on the Lockouts tab.
* Fixed: "Fatal error: Uncaught Error: Cannot use object of type WP_Error as array in … /cerber-common.php on line 4634". The bug occurs if the PHP constant WP_ACCESSIBLE_HOSTS is defined and it does not contain 'downloads.wpcerber.com'.

= 9.2 =
* New: Custom login error message. If showing the default WordPress login error message is disabled, you can optionally specify your own login error message. Available in the professional version.
* New: Custom password reset error message. If showing the default WordPress password reset error message is disabled, you can optionally specify your own password reset error message. Available in the professional version.
* Improved: Implemented Content-Security-Policy HTTP header as an extra layer of protection for the WP Cerber admin pages.
* Fixed A critical XSS vulnerability.
* Fixed: Fatal error "Call to a member function is_block_editor() on null" that occurs when attempting to load any admin page (starting with /wp-admin/) by an unauthorized request. The bug only occurs if the two following settings are configured as: "Disable dashboard redirection" is enabled and "Display 404 page" is set to "Use 404 template from the active theme".
* Fixed: No country flags are shown in some log rows while viewing WP Cerber logs on the managed website via Cerber.Hub.
* Fixed: The file viewer doesn't show the content of a file while viewing the results of a scan on the managed website via Cerber.Hub.

= 9.1 =
* New: A new feature that prevents exposing user’s first name, last name, and ID via an HTTP request with a username (login) in an author_name parameter.
* New: A new user status report while viewing the user activity/requests log.
* Improved: When renders admin pages, WP Cerber uses the language selected on the user profile.
* Improved: Improved the speed of rendering of the "Users" admin page. Reduced the number of HTTP requests if some columns on the page are hidden.
* Improved: Implemented support for rate limiting when the scanner retrieves checksum data from remote servers.
* Fixed: A bug that allows an attacker to bypass the "Stop user enumeration" feature if it’s enabled.
* Fixed: A bug that produces incorrect messages in the server error log when the WordPress database connection is lost.
* Fixed: A bug with not escaping comments in the IP access lists entries.

= 9.0 =
* New: Different [alerts](https://wpcerber.com/wordpress-notifications-made-easy/) can be sent through different channels. You can select delivering notifications through Pushbullet and email simultaneously, Pushbullet only, or email only. The settings are configured on a per-alert basis in the alert creation form.
* New: Implemented a new "Message format" feature and setting. You can reduce the number of links in WP Cerber’s messages or disable them completely to prevent sending sensitive data.
* New: Implemented separate rate limiting settings for email and [Pushbullet notifications](https://wpcerber.com/wordpress-mobile-and-browser-notifications-pushbullet/).
* New: Lockout notifications and appropriate threshold can be enabled for Pushbullet and emails separately.
* New: Email reports and alerts can be sent via a separate SMTP server configured in the WP Cerber settings.
* New: Implemented masking IP addresses and usernames (logins) in emails and mobile alerts.
* New: Disabling login language switcher. If enabled, removes language switcher on the standard WordPress login page introduced in WordPress 5.9.
* Improved: If WP Cerber is unable to load its saved settings from the website database, it uses hard-coded default values.
* Improved: If you have configured the [list of prohibited usernames](https://wpcerber.com/using-list-of-prohibited-logins-to-catch-stupid-bots/) (logins) and the username of an existing user is among prohibited ones, the user is now shown as BLOCKED on the "Users" admin page, user edit page, Activity tab, and Live Traffic tab.
* Improved: When multiple email addresses are specified for notifications, each email will be sent separately. No multiply recipients in a single email are used anymore.
* Improved: The subjects of alerts now contain corresponding event labels.
* Improved: The subject of WP Cerber’s emails have been unified. It begins with website name in square brackets plus the "WP Cerber" string.
* Improved: All test alerts and messages manually sent from the WP Cerber admin dashboard now contain *** TEST MESSAGE *** in the subject.
* Improved: Displaying detailed information about PHP generated by phpinfo(). A new link is on the Diagnostic tab in the System Info section.
* Fixed: An issue with multiple "IP blocked" in the log if the reason for a lockout is changing.
* Fixed: An issue with "Site title" containing apostrophes.

= 8.9.6 =
* New: A new [alert creation dialog with a set of new alert settings](https://wpcerber.com/wordpress-notifications-made-easy/) enables you to create alerts with new limits: an expiration time, the maximum number of alerts allowed to send, and optional rate-limiting. The alert conditions can include the URL of a request now.
* New: Deleting of [WordPress application passwords](https://wpcerber.com/wordpress-application-passwords-how-to/) is logged now.
* New: Ability to monitor [anti-spam](https://wpcerber.com/antispam-for-wordpress-contact-forms/), reCAPTCHA, and several other setting-specific events using links on the settings pages.
* Improved: Meaningful and actionable messages on the log screens if no activity has been found in the logs using a given search filter.
* Improved: If a WP Cerber feature requires a newer version of WordPress, such a feature will not be shown in the plugin admin interface anymore.
* Fixed: A fatal PHP error occurs while logging in on a version of WordPress older than 5.5 and a user has more than one active session.
* Fixed: A fatal PHP error occurs while using the reset password form on a version of WordPress older than 5.4.
* Fixed: While opening the Tools admin page, a PHP error might occur on some web servers.
* Fixed: While rendering the Activity tab, depending on the activities logged, the PHP warning can be logged in the server error log.
* Fixed: When [managing WP Cerber on a remote website via Cerber.Hub](https://wpcerber.com/manage-multiple-websites/), the admin page footer incorrectly displays the version of WP Cerber installed on the main website.
* Fixed: If the Site Title of a website contains some special characters like apostrophes, the subject of [email alerts and notifications](https://wpcerber.com/wordpress-notifications-made-easy/) contains such characters in encoded form.

= 8.9.5 =
* New: A new setting for [WP Cerber's anti-spam engine](https://wpcerber.com/antispam-for-wordpress-contact-forms/): "Disable bot detection engine for IP addresses in the White IP Access List".
* New: A new setting for [the reCAPTCHA module](https://wpcerber.com/how-to-setup-recaptcha/): "Disable reCAPTCHA for IP addresses in the White IP Access List".
* Improved: Logging all user session terminations including those that occurred when an admin manually terminate user sessions or [block users](https://wpcerber.com/how-to-block-wordpress-user/).
* Improved: If a user session has been terminated by a website admin, the admin’s name is logged and shown in the Activity log.
* Improved: Logging all user password changes including those made on the edit user admin page, and the WooCommerce edit account page.
* Improved: Logging [application passwords](https://wpcerber.com/wordpress-application-passwords-how-to/) changes.
* Improved: New status labels in the Activity log: "reCAPTCHA verified" is shown when a user solves reCAPTCHA successfully
* Improved: New status labels in the Activity log: "Logged out everywhere" is shown when a user has completely logged out on all devices and of all locations.
* Improved: Failed reCAPTCHA verifications are logged with form submission events they are linked to.
* Improved: A new event is logged: "Password reset request denied". With possible statuses "reCAPTCHA verification failed", "User blocked by administrator", "Username is prohibited".
* Improved: Handling reset of user passwords is improved to support changes in the WordPress core.
* Fixed: A cookie-related bug that causes a fatal software error if a user has been deleted or their password has been changed in the WordPress dashboard by the website administrator while the user is being logged in.
* Fixed: A bug with the WordPress lost password (reset password) form that prevents displaying error messages to a user.
* Fixed: When the [limit on the number of allowed concurrent user sessions](https://wpcerber.com/limiting-concurrent-user-sessions-in-wordpress/) is set to one, an attempt to log in with the user name and incorrect password terminates the existing session of the user.
* [Read more](https://wpcerber.com/wp-cerber-security-8-9-5/)

= 8.9.3 =
* Improved: The scanner: now checksums generated using manually uploaded ZIP archives have priority over the remote ones.
* Improved: You can configure exceptions for WP Cerber's anti-spam by disabling its code on selected WordPress pages.
* Improved: New diagnostic messages were added for better troubleshooting issues with ZIP archives uploaded in the scanner.
* Fixed: A vulnerability that affects WP Cerber's two-factor authentication (2FA) mechanism.
* Fixed: A bug that prevents uploading ZIP archives on the scan results page if the filename contains multiple dots.
* Fixed: Fixed admin message "Error: Sorry, that username is not allowed." which is wrongly displayed on the user edit page while updating users with prohibited usernames.
* Fixed: Not detecting malformed REST API requests with a question mark in this format: /wp-json?
* [Read more](https://wpcerber.com/wp-cerber-security-8-9-3/)

= 8.9 =
* Improved: An updated scan statistic and filtering widget. Dynamically displays the most important issues with sorting.
* Improved: The percentage of completion of a scanner step is shown now.
* Improved: Sanitizing of malformed filenames in the scanner reports has been improved to avoid possible issues with the layout of the scan results page if malware creates malformed filenames to hinder their detection.
* Improved: Handling of WordPress locales and versions on websites with multilanguage plugins has been improved.
* Improved: A missing wp-config-sample.php file is not reported as an issue in the results of the scan anymore.
* Improved: Handling REGEX patterns for the setting fields "Restrict email addresses" and "Prohibited usernames". Now they support REGEX quantifiers.
* Improved: You can specify the "User-Agent" string for requests from the main Cerber.Hub website by defining the PHP constant CERBER_HUB_UA in the wp-config.php file.
* Improved: Diagnostic logging for network requests to the WP Cerber cloud. To enable logging, define the PHP constant CERBER_CLOUD_DEBUG in the wp-config.php file. Logging covers admin operations on the WP Cerber admin pages only.
* Improved: Text on the forbidden page is translatable now.
* Fixed bug: Some long filenames in the scan results break the layout of the scan results page, making it hard to navigate and use.
* Fixed bug: Unwanted file extensions are not detected if a file is identified as malicious.
* Fixed bug: If a file is missing, the full filename is not shown in the scan results when clicking the “Show full filenames” icon.
* Fixed bug: "PHP Deprecated: Required parameter $function follows optional parameter $pattern in /plugins/wp-cerber/cerber-scanner.php".
* Fixed bug: "PHP Fatal error: Call to undefined function crb_admin_hash_token() in cerber-load.php:1521".
* Fixed bug: "PHP Notice: Undefined property: WP_Error::$ID in cerber-load.php on line 1131".
* [Read more](https://wpcerber.com/wp-cerber-security-8-9/)

= 8.8.6 =
* New: You can specify the "User-Agent" string for requests from the main Cerber.Hub website by defining the PHP constant CERBER_HUB_UA in the wp-config.php file.
* New: Diagnostic logging for network requests to the WP Cerber cloud. To enable logging, define the PHP constant CERBER_CLOUD_DEBUG in the wp-config.php file. Logging covers admin operations on the WP Cerber admin pages only.
* Fixed bug: "PHP Fatal error: Call to undefined function crb_admin_hash_token() in cerber-load.php:1521".
* Fixed bug: "PHP Notice: Undefined property: WP_Error::$ID in cerber-load.php on line 1131".

= 8.8.5 =
* New: Quick user activity analytics (user insights) with filtering links on the Activity and Live Traffic log pages. Select a user to see how it works.
* New: Quick IP address activity and analytics (IP insights) with filtering links on the Activity and Live Traffic log pages. Select an IP address to see how it works.
* Improved: The selected user profile is displayed when filtering log entries by the user login or using the username search on the Activity log page.
* Improved: The IP address details and analytics are displayed when filtering log entries by the IP address or using the IP address search on the Activity log page.
* Improved: Implemented AJAX rendering of the plugin admin pages for faster loading and more convenient navigation through WP Cerber’s admin pages
* Improved: To load the Users admin page faster, the user table columns generated by WP Cerber are now loaded via AJAX.
* Improved: Highlighting the selected filtering link in the navigation bar on the Activity and Live Traffic log pages.
* Improved: You will not see false DB errors on the Diagnostic page anymore.
* Fixed bug: When scanning, you can come across the software error "Process has been aborted due to server error. Check the browser console for errors." and "Too few arguments" error in the server error log.

= 8.8.3 =
* New: Mimicking the default WordPress user authentication through the wp-login.php to detect slow brute-force attacks.
* New: Prevent guessing valid usernames and user emails by disabling WordPress hints in the login error message when attempting to log in with non-existing usernames and emails.
* New: Prevent guessing valid usernames and user emails by disabling WordPress hints in the password reset error message when attempting to reset passwords for non-existing accounts.
* New: Prevent username discovery via oEmbed and user XML sitemaps.
* New: User and malicious activity are displayed separately in two different areas on WP Cerber’s main dashboard page.
* New: More convenient navigation through the WP Cerber admin pages by having the admin menu at the top.
* New: A new quick link "Login issues" to view all login issues such as failed logins, denied attempts, attempts to reset passwords, and so forth.
* Improved: Reduced the number of false positives when the malware scanner inspecting directives with external IP addresses in .htaccess files.
* Improved: Better 2FA emails: the wording of the verification email has been updated and can be translated. The email subject includes the site name.
* Improved: The size of the database tables used by the integrity checker and malware scanner has been reduced.
* Improved: Implemented a strictly secure way of utilizing the unserialize() PHP function known for being used to deliver and run malicious code.
* Improved: Implemented a backup way of running WP Cerber maintenance tasks if WordPress scheduled tasks are not configured properly.
* Fixed bug: 2FA PINs are not displayed on the edit user admin pages in the WordPress dashboard.
* Fixed bug: The "API request authorization failed" event was logged as "Login failed".

= 8.8 =
* New: [You get control over the WordPress application passwords and the ability to monitor related events in the log with email and mobile notifications.](https://wpcerber.com/wordpress-application-passwords-how-to/)
* New: A custom comment URL feature improves the efficiency of spam protection of the WordPress comment form. Available in the professional version of WP Cerber.
* Improved: Handling user authentication and authorization by WP Cerber’s access control mechanism has been significantly improved and optimized to allow using external user authentication via third-part solutions and connectors.
* Improved: You can now specify a user message to be displayed if [the configured limit to the number of concurrent user sessions](https://wpcerber.com/limiting-concurrent-user-sessions-in-wordpress/) has been reached and an attempt to log in is denied.
* Improved: Traffic log settings and features: "Log all REST API requests", "Log all XML-RPC requests", "Save response headers", and "Save response cookies".
* Improved: For better compatibility with different web server configurations, [the anti-spam query whitelist](https://wpcerber.com/antispam-exception-for-specific-http-request/) now ignores trailing slashes if a list entry or a requested URI has no GET parameters.
* Improved: Processing of extended and invalid UTF-8 characters in the Traffic Inspector log has been improved.
* Improved: Displaying of invalid UTF-8 characters (invalid byte sequences) in the WP Cerber’s logs throughout the admin interface has been improved.
* Improved: WP Cerber's dashboard code is updated and now fully jQuery 3 compatible.
* Fixed: A bug that prevented activating the [Cerber.Hub main mode](https://wpcerber.com/manage-multiple-websites/) on PHP 8.
* Fixed: A fatal PHP error occurs while saving some WP Cerber settings when using [Cerber.Hub](https://wpcerber.com/manage-multiple-websites/) on a remote website with “Standard mode” enabled.
* Fixed: A bug that generated warning messages in the web server error log: Use of undefined constant LOGGED_IN_COOKIE – assumed ‘LOGGED_IN_COOKIE’
* Fixed: A bug that blocked theme preview if the anti-spam engine is enabled for all forms on the website.
* [Read more](https://wpcerber.com/wp-cerber-security-8-8/)

= 8.7 =
* New: [Limiting the number of allowed concurrent user sessions.](https://wpcerber.com/limiting-concurrent-user-sessions-in-wordpress/) Depending on settings, WP Cerber will either block new logins or terminate the oldest ones.
* New: Enforcing [two-factor authentication (2FA)](https://wpcerber.com/two-factor-authentication-for-wordpress/) if the number of concurrent user sessions is greater than the specified threshold.
* Improved: [The integrity checker and malware scanner](https://wpcerber.com/wordpress-security-scanner/) now more effectively handle and log I/O errors that might occur during a scan.
* Improved: [The Traffic Inspector firewall](https://wpcerber.com/traffic-inspector-in-a-nutshell/) now processes files uploaded via nested, grouped, and obfuscated form fields in a more effective way.
* Improved: WP Cerber got necessary code improvements, and now it is fully compatible with PHP 8.
* Improved: [The default list of allowed REST API namespaces](https://wpcerber.com/restrict-access-to-wordpress-rest-api/) now includes "wp-site-health".
* Improved: Downloadable files generated by WP Cerber are generated with appropriate HTTP Content-Type headers now.
* Fixed: Misalignment of Cerber’s table footer labels on the "Users" admin page.
* Fixed: If the diagnostic log contains invalid Unicode (UTF-8) codes, it is not displayed on the Diagnostic log tab.
* [Read more](https://wpcerber.com/wp-cerber-security-8-7/)

= 8.6.8 =
* New: [A shortcode to display WP Cerber’s cookies. You can display a list of cookies set by WP Cerber on any page.](https://wpcerber.com/browser-cookies-set-by-wp-cerber/)
* New: [Deferred rendering of the custom login page. This new feature can help you if you need to solve plugin compatibility issues.](https://wpcerber.com/user-switching-with-wp-cerber/)
* Improved: The style of the scanner email reports has been improved.
* Fixed: A bug with displaying the status icon of an IP address on the Activity and Live Traffic admin pages.
* Fixed: If the name of a commercial plugin contains a special HTML symbol like ampersand, it cannot be uploaded to verify the integrity of the plugin.
* [Read more](https://wpcerber.com/wp-cerber-security-8-6-8/)

= 8.6.7 =
* New: In the professional version of WP Cerber, you can now permit user registrations for IP addresses in the [White IP Access List only](https://wpcerber.com/using-ip-access-lists-to-protect-wordpress/).
* New: All URLs in the logs are displayed in a shortened form without the website’s domain.  There is no much value having see known things.
* New: A new label "IP Whitelisted" with green borders has been introduced. It is displayed in a log row on the Live Traffic if the IP address was in the White IP Access List, but the appropriate setting "Use White IP Access List" was not enabled at the moment when the event was logged.
* New: If you now hover the mouse over a red square icon in the Activity or Live Traffic log, you see the reason why the IP address in the row is currently locked out.
* New: If you now hover the mouse over a green or black square Access List icon in the Activity or Live Traffic log, you see the comment you’ve previously specified for that Access List entry.
* Improved: All non-REGEX entries [in the list of prohibited usernames (logins)](https://wpcerber.com/using-list-of-prohibited-logins-to-catch-stupid-bots/) are case-insensitive now. This applies to standard Latin-based (ASCII) WordPress usernames only.
* Improved: The name of a group in the Group column on [Cerber.Hub’s](https://wpcerber.com/manage-multiple-websites/) website list is a link that takes you to the list of websites in the group.
* Improved: The launch time of the daily maintenance tasks is now set to the night-time at 02:20. If you need them to get rescheduled, you can manually delete the “cerber_daily” cron task via a plugin or deactivate/activate WP Cerber.
* Fixed: Configured [REST API restrictions](https://wpcerber.com/restrict-access-to-wordpress-rest-api/) have no effect if a WordPress is installed not in the root folder of a website (there is a path in the site URL). Affected versions: 8.6.1 and newer.
* Fixed: A bug in the logging subsystem: depending on server configuration, submitted form fields are not saved into the DB (if it is enabled in the logging settings).
* Fixed: A bug with Cerber’s admin CSS styles that were added in the previous version and hid the top pagination links on the "All posts" and "All posts" admin pages.
* [Read more](https://wpcerber.com/wp-cerber-security-8-6-7/)

= 8.6.6 =
* New: On the user sessions page, you can now search sessions by a user name, email, and the IP address from which a user has logged in.
* New: You can specify locations (URL Paths) to exclude requests from logging. They can be either exact matches or regular expressions (REGEX).
* New: You can exclude requests from logging based on the value of the User-Agent (UA) header.
* New: A new, minimal logging mode. When it is set, only HTTP requests related to known activities are logged.
* Improved: The layout of the Live Traffic log has been improved: now all events that are logged during a particular request are shown as an event list sorted in reverse order.
* Improved: The user sessions page has been optimized for performance and compatibility and now works blazingly fast.
* Improved: If your website is behind a proxy, IP addresses of user sessions now are detected more precisely.
* Improved: When you configure the request whitelist in the Traffic Inspector settings, you can now specify rules with or without trailing slash.
* Improved: A new version of [Cloudflare add-on for WP Cerber](https://wpcerber.com/cloudflare-add-on-wp-cerber/) is available: the performance of the add-on has been optimized.
* [Read more](https://wpcerber.com/wp-cerber-security-8-6-6/)

= 8.6.5 =
* New: File system analytics. It's generated based on the results of the last full integrity scan.
* New: Logging user deletions. The user’s display name and roles are temporarily stored until all log entries related to the user are deleted.
* New: Faster export with a new date format for CSV log export.
* New: Ability to disable adding the website administrator's IP address to the White IP Access List upon WP Cerber activation.
* Improved: Handling the creation of new users by WooCommerce and membership plugins.
* Improved: Handling user registrations with prohibited emails.
* Improved: Handling secure Cerber‘s cookies on websites with SSL encryption enabled.
* Improved: The performance of the integrity checker and malware scanner on huge websites with a large number of files.
* Fixed: Loading the default plugin settings has no effect. Now it’s fixed and moved from the admin sidebar to the Tools admin page.
* [Read more](https://wpcerber.com/wp-cerber-security-8-6-5/)

= 8.6.3 =
* New: Ability to load IP access list's entries in the CSV format (bulk load).
* Update: A new malware scanner setting allows you to permit the scanner to change permissions of folders and files when required.
* Fixed: The access list IPv4 wildcard *.*.*.* doesn’t work (has no effect).
* Fixed: If the anti-spam query whitelist contains more than one entry, they do not work as expected.
* Fixed: Several settings fields are not properly escaped.
* [Read more](https://wpcerber.com/wp-cerber-security-8-6-3/)

= 8.6 =
* New: [An integration with the Cloudflare cloud-based firewall. It’s implemented as a special WP Cerber add-on.](https://wpcerber.com/cloudflare-add-on-wp-cerber/)
* Update: The malware scanner has got improvements to the monitoring of new and modified files feature.
* Update: Additional search fields for the Activity log. They enable you to find a specific request by its Request ID (RID) or/and to search for a string in the request URL.
* Update: The minimum supported PHP version is 5.6.
* [Read more](https://wpcerber.com/wp-cerber-security-8-6/)

= 8.5.9 =
* New: On the Live Traffic log, now you can find requests with software errors if they occurred.
* Update: The code of WP Cerber has been updated and tested to fully support and be compatible with PHP 7.4.
* Update: The layout of the list of managed websites on the Cerber.Hub's main page has been improved to display the list more accurately on narrow and mobile screens.
* Update: If a managed website has the professional version of WP Cerber, it is shown with a PRO label in the "WP Cerber" column. The license expiration date is shown when you hover the mouse over the sign.
* Fixed: A bug with displaying long file names in the Security Scanner Quarantine that makes unavailable deleting or restoring quarantined files manually.
* Fixed: A bug that requires installing a valid license key on a Cerber.Hub main website to permit configuring settings on managed websites remotely, which is not intended behavior.
* [Read more](https://wpcerber.com/wp-cerber-security-8-5-9/)

= 8.5.8 =
* New: A personal data export and erase features which can be used through the WordPress personal data export and erase tool. This feature helps your organization to be in compliance with data privacy laws such as GDPR in Europe or CCPA in California
* Update: The performance of the algorithm that handles exporting rows from the Activity log and the Live Traffic log to a CSV file has been improved enabling export larger datasets
* Update: When you block a user you can add an optional admin note now
* Fixed: If a user is blocked, it’s not possible to update the user message
* Fixed: Depending on the logging settings the "Details" links on the Live Traffic log are not displayed in some rows
* [Read more](https://wpcerber.com/wp-cerber-security-8-5-8/)

= 8.5.6 =
* New: Ability to separately set the number of days of keeping log records in the database for authenticated (logged in) website users and non-authenticated (not logged in) visitors.
* New: You can completely turn off the Citadel mode feature in the Main Settings
* Update: When you upload a ZIP archive on the integrity scanner page it processes nested ZIP archives now and writes errors to the diagnostic log if it's enabled
* Update: The appearance of the Activity log has got small visual improvements
* Update: If the number of days to keep log records is not set or set to zero, the plugin uses the default setting instead. Previously you can set it to zero and keep log records infinitely.
* Fixed: The blacklisting buttons on the Activity tab do not work showing "Incorrect IP address or IP range".
* Fixed: PHP Notice: Trying to get property "ID" of non-object in cerber-load.php on line 1131

= 8.5.5 =
* IP Access Lists now support IPv6 networks, ranges, and wildcards. Add as many IPv6 entries to the access lists as you need. We've developed an extraordinarily fast ACL engine to process them.
* The algorithm of handling consecutive IP address lockouts has been improved: the reason for an existing lockout is updated and its duration is recalculated in real-time now.
* Traffic inspection algorithms were optimized to reduce false positives and make algorithms more human-friendly.
* Improved compatibility with WooCommerce: the password reset and login forms are not blocked anymore if a user’s IP gets locked out due to using a non-existing username by mistake, using a prohibited username, or if a user has exceeded the number of allowed login attempts.
* Improved compatibility with WordPress scheduled cron tasks if a website runs on a server with PHP-FPM (FastCGI Process Manager)
* Very long URLs on the Live Traffic page are now displayed in full when you click the "Details" link in a row.
* The [Cerber.Hub multi-site manager](https://wpcerber.com/manage-multiple-websites/): the server column on the managed websites list page now contains a link to quickly filter out websites on the same server.
* The [Cerber.Hub multi-site manager](https://wpcerber.com/manage-multiple-websites/): now it remembers the filtered list of managed websites while you’re switching between them and the main website.
* Fixed: If the Custom login URL is enabled on a subfolder WordPress installation, the user redirection after logout generates the HTTP 404 error page.
* Fixed: Very long HTTP referrers and request URLs are displayed in a truncated form on the Live Traffic page due to CSS bug.
* Fixed: If the Data Shield security feature is active, the password reset page on WordPress 5.3 doesn’t work properly showing "Your password reset link appears to be invalid. Please request a new link below."
* [Read more](https://wpcerber.com/wp-cerber-security-8-5-5/)

= 8.5.3 =
* New: The malware scanner and integrity checker window has got a new filter that enables you to filter out and navigate to specific issues quickly.
* New: Cerber.Hub: new columns and filters have been added to the list of managed websites. The new columns display server IP addresses, hostnames, and countries where servers are located.
* Bug fixed: Depending on the number of items in the access lists, the IP address 0.0.0.0 can be erroneously marked as whitelisted or blacklisted.
* Bug fixed in Cerber.Hub: if a WordPress plugin is installed on several managed websites and the plugin needs to be updated on some of the managed websites, the plugin is shown as needs to be updated on all managed websites.
* [Read more](https://wpcerber.com/wp-cerber-security-8-5-3/)

= 8.5 =
* New: Data Shield module for advanced protection of user data and vital settings in the website database. Available in the PRO version.
* Improvement: Compatibility with WooCommerce significantly improved.
* Update: Strict filtering for the Custom login URL setting.
* Update: Chinese (Taiwan) translation has been added. Thanks to Sid Lo.
* Bug fixed: Custom login URL doesn't work after updating WordPress to 5.2.3.
* Bug fixed: User Policies tabs are not switchable if a user role was declared with a hyphen instead of the underscore.
* Bug fixed: A PHP warning while adding a network to the Black IP Access List from the Activity tab.
* Bug fixed: An anti-spam false positive: some WordPress DB updates can't be completed.
* [Read more](https://wpcerber.com/wp-cerber-security-8-5/)

= 8.4 =
* New: More flexible role-based GEO access policies.
* New: A logged in users' sessions manager.
* Update: Access to users’ data via WordPress REST API is always granted for administrator accounts now
* Improvement: The custom login page feature has been updated to eliminate possible conflicts with themes and other plugins.
* Improvement: Improved compatibility with operating systems that natively doesn’t support the PHP GLOB_BRACE constant.

= 8.3 =
* New: Two-Factor Authentication.
* New: Block registrations with unwanted (banned) email domains.
* New: Block access to the WordPress Dashboard on a per-role basis.
* New: Redirect after login/logout on a per-role basis.
* Update: The Users tab has been renamed to Global and now is under the new User Policies admin menu.
* Fixed: Switching to the English language in Cerber’s admin interface has no effect.
* Fixed: Multiple notifications about a new version of the plugin in the WordPress dashboard.
* [Read more](https://wpcerber.com/wp-cerber-security-8-3/)

= 8.2 =
* New: Automatic recovery of infected files. When [the malware scanner](https://wpcerber.com/wordpress-security-scanner/) detects changes in the core WordPress files and plugins, it automatically recovers them.
* New: A set of quick navigation buttons on the Activity page. They allow you to filter out log records quickly.
* New: A unique Session ID (SID) is displayed on the Forbidden 403 Page now.
* New: The advanced search on the Live Traffic page has got a set of new fields.
* New: To make a website comply with GDPR, a cookie prefix can be set.
* Update: The lockout notification settings are moved to the Notifications tab.
* Update: The list of files to be scanned in Quick mode now also includes files with these extensions:  phtm, phtml, phps, php2, php3, php4, php5, php6, php7.
* [Read more](https://wpcerber.com/wp-cerber-security-8-2/)

= 8.1 =
* New: In a single click you can get a list of active plugins and available updates on a managed website.
* New: Notification about a newer versions of Cerber and WordPres available ot install on a managed website.
* New: You can select what language to use when a managed admin page is being displayed on the main Cerber.Hub website.
* Improvement: Long URLs on the Live Traffic page now are shortened and displayed more neatly.
* Improvement: The plugin uninstallation process has been improved and now cleans up the database completely.
* Improvement: Multiple translations have been updated. Thanks to Maxime, Jos Knippen, Fredrik Näslund, Francesco.
* Fixed: The "Add to the Black List" button on the Activity log page doesn't work.
* Fixed: When the "All suspicious activity" button is clicked on the Dashboard admin page, the "Subscribe" link on the Activity page doesn't work correctly.
* Fixed: When you open an email report, the link to the list of deleted files during a malware scan doesn't work as expected.
* [Read more](https://wpcerber.com/wp-cerber-security-8-1/)

= 8.0 =
* New: [Manage multiple WP Cerber instances from one dashboard](https://wpcerber.com/manage-multiple-websites/).
* New: A new bulk action to block multiple WordPress users at a time.
* Improvement: The performance of the export feature has been improved significantly.
* Improvement: Multiple code optimizations improve overall plugin performance.

= 7.9.7 =
* New: [Authorized users only mode](https://wpcerber.com/only-logged-in-wordpress-users/).
* New: [An ability to block a user account](https://wpcerber.com/how-to-block-wordpress-user/).
* New: [Role-based access to WordPress REST API](https://wpcerber.com/restrict-access-to-wordpress-rest-api/).
* Update: Added ability to search and filter a user on the Activity page.
* Update: A new, separate setting for preventing user enumeration via WordPress REST API.
* Update: A new Changelog section on the Tools page.
* Update: Improved handling scheduled maintenance tasks on a multi-site WordPress installation.
* Fixed: Several HTML markup errors on plugin admin pages.
* [Read more](https://wpcerber.com/wp-cerber-security-7-9-7/)

= 7.9.3 =
* New: New settings for [the Traffic Inspector firewall](https://wpcerber.com/traffic-inspector-in-a-nutshell/) allow you to fine-tune its behavior. You can enable less or more restrictive firewall rules.
* Update: Troubleshooting of possible issues with scheduled maintenance tasks has been improved.
* Update: To make troubleshooting easier the plugin logs not only a lockout event but also logs and displays the reason for the lockout.
* Update: Compatibility with ManageWP and Gravity Forms has been improved.
* Update: The layout of the Activity and Live Traffic pages has been improved.
* Bug fixed: [The malware scanner](https://wpcerber.com/wordpress-security-scanner/) wrongly prevents PHP files with few specific names in one particular location from being deleted after a manual scan or during [the automatic malware removal](https://wpcerber.com/automatic-malware-removal-wordpress/).
* Bug fixed: The number of email notifications might be incorrectly limited to one email per hour.
* [Read more](https://wpcerber.com/wp-cerber-security-7-9-3/)

= 7.9 =
* New: The plugin monitors suspicious requests that cause 4xx and 5xx HTTP errors and blocks IP addresses that aggressively generate such requests.
* New: A set of WordPress navigation menu links. Login, logout, and register menu items can be automatically generated and shown in any WordPress menu or a widget.
* New: Software error logging. A handy feature that logs PHP errors and shows them on Live Traffic page.
* New: A new export feature for Traffic Inspector. It allows exporting all log entries or a filtered set from the log of HTTP requests.
* Update: Multiple improvements to Traffic Inspector firewall algorithms. In short, the detection of obfuscated malicious SQL queries and injections has been  improved.
* Update: Improved handling of malformed requests to wp-cron.php.
* Fix: The number of email notifications per hour can exceed the configured limit.
* [Read more](https://wpcerber.com/wp-cerber-security-7-9/)

= 7.8.5 =
* New: A new set of heuristics algorithms for detecting obfuscated malicious JavaScript code.
* New: A new file filter on the Quarantine page lets to filter out quarantined files by the date of the scan.
* New: The performance of [the malware scanner](https://wpcerber.com/wordpress-security-scanner/) has been improved. Now the scanner deletes all files in the website session and temporary folders permanently before the scan.
* Update: If the plugin is unable to detect the remote IP address, it uses 0.0.0.0 as an IP.
* Update: The anti-spam engine will never block the localhost IP
* Update: Performance improvements for database queries related to the process of user authentication.
* Update: Improved handling the plugin settings in a buggy or misconfigured hosting environment that could cause the plugin to reset settings to their default values.
* Update: Translations have been updated. Thanks to Francesco, Jos Knippen, Fredrik Näslund, Slobodan Ljubic and MARCELHAP.
* Fix: Fixed an issue with saving settings on the Hardening tab: "Unable to get access to the file…"
* [Read more](https://wpcerber.com/wp-cerber-security-7-8-5/)

= 7.8 =
* New: An ignore list for the malware scanner.
* New: Disabling execution of PHP scripts in the WordPress media folder helps to prevent offenders from exploiting security flaws.
* New: Disabling PHP error displaying as a setting is useful for misconfigured servers.
* New: English for the plugin admin interface. Enable it if you prefer to have untranslated, original admin interface.
* New: Diagnostic logging for the malware scanner. Specify a particular location of the log file by using the CERBER_DIAG_DIR constant.
* Update: The performance of malware scanning on a slow web server with thousands of issues and tens of thousands of files has been improved.
* Update: PHP 5.3 is not supported anymore. The plugin can be activated and run only on PHP 5.4 or higher.
* Fix: If a malicious file is detected on a slow shared hosting, the file can be shown twice in the results of the scan.
* Fix: A possible issue with the short PHP syntax on old PHP versions in /wp-content/plugins/wp-cerber/common.php on line 1970
* [Read more](https://wpcerber.com/wp-cerber-security-7-8/)

= 7.7 =
* New: [Automatic cleanup of malware and suspicious files](https://wpcerber.com/automatic-malware-removal-wordpress/). This powerful feature is available in the PRO version and automatically deletes trojans, viruses, backdoors, and other malware. Cerber Security Professional scans the website on an hourly basis and removes malware immediately.
* Update: Algorithms of the malware scanner have been improved to detect obfuscated malware code more precisely for all types of files.
* Update: Email reports for [scheduled malware scans](https://wpcerber.com/automated-recurring-malware-scans/) have been extended with useful performance numbers and a list of automatically deleted malicious files if you’ve enabled automatic malware removal and some files have been deleted.
* Fix: A possible issue with uploading large JSON and CSV files. When Traffic Inspector scans uploaded files for malware payload, some JSON and CSV files might be erroneously identified as containing a malicious payload.
* Fix: A possible Divi theme forms incompatibility. If you use the Divi theme (by Elegant Themes), you can come across a problem with submitting some forms.
* [Read more](https://wpcerber.com/wp-cerber-security-7-7/)

= 7.6 =
* New: The quarantine has got a separate admin page in the WordPress dashboard which allows viewing deleted files, restoring or deleting them.
* New: Now [the malware scanner and integrity checker](https://wpcerber.com/wordpress-security-scanner/) supports multisite WordPress installations.
* Fix: Once an address IP has been locked out after reaching the limit to the number of attempts to log in the "We’re sorry, you are not allowed to proceed" forbidden page is being displayed instead of the normal user message "You have exceeded the number of allowed login attempts".
* Fix: PHP Notice: Only variables should be passed by reference in cerber-load.php on line 5377
* [Read more](https://wpcerber.com/wp-cerber-security-7-6/)

= 7.5 =
* New: Firewall algorithms have been improved and now inspect the contents of all files that are being tried to upload on a website.
* New: The traffic logger can save headers, cookies and the $_SERVER variable for every HTTP request.
* New: The scanner now scans installed plugins for known vulnerabilities. If you have enabled scheduled automatic scans you will be notified in a email report.
* Update: A set of new malware signatures amd patterns have been added to detect malware submitted through a contact form as well as any HTTP request fields.
* Update: Now the plugin inspects user sign ups (user registrations) on multisite WordPress installations and BuddyPress.
* Update: The search for user activity, as well as enabling activity notifications, is improved.
* [Read more](https://wpcerber.com/wp-cerber-security-7-5/)

= 7.2 =
* New: Monitoring new and changed files.
* New: Detecting malicious redirections and directives in .htaccess files.
* New: [Automated hourly and daily scheduled scans with flexible email reports](https://wpcerber.com/automated-recurring-malware-scans/).
* Update: Added a protection from logging wrong time stamps on some misconfigured web servers.
* Fix: Unexpected warning messages in the WordPress dashboard.
* Fix: Some file status links on the scanner results page may not work.

= 7.0 =
* Cerber Security Scanner: [integrity checker, malware detector and malware removal tool](https://wpcerber.com/wordpress-security-scanner/).
* New: a new setting for Traffic Inspector: Use White IP Access List.
* Update: the redirection from /wp-admin/ to the login page is not blocked for a user that has been logged in once before.
* Bug fixed: the limit to the number of new user registrations is calculated the way that allows one additional registration within a given period of time.
* [Read more](https://wpcerber.com/wp-cerber-security-7-0/)

== Other Notes ==

1. If you want to test out plugin's features, do this from another computer and remove that computer's network from the White Access List. Cerber is smart enough to recognize "the boss".
2. If you've set up the Custom login URL and you use some caching plugin like **W3 Total Cache** or **WP Super Cache**, you have to add a new Custom login URL to the list of pages not to cache.
3. [Read this if your website is under CloudFlare](https://wpcerber.com/cloudflare-and-wordpress-cerber/)

**Deutsche**
Schützt vor Ort gegen Brute-Force-Attacken. Umfassende Kontrolle der Benutzeraktivität. Beschränken Sie die Anzahl der Anmeldeversuche durch die Login-Formular, XML-RPC-Anfragen oder mit Auth-Cookies. Beschränken Sie den Zugriff mit Schwarz-Weiß-Zugriffsliste Zugriffsliste. Track Benutzer und Einbruch Aktivität.

**Français**
Protège site contre les attaques par force brute. Un contrôle complet de l'activité de l'utilisateur. Limiter le nombre de tentatives de connexion à travers les demandes formulaire de connexion, XML-RPC ou en utilisant auth cookies. Restreindre l'accès à la liste noire accès et blanc Liste d'accès. L'utilisateur de la piste et l'activité anti-intrusion.

**Український**
Захищає сайт від атак перебором. Обмежте кількість спроб входу через запити ввійти форми, XML-RPC або за допомогою авторизації в печиво. Обмежити доступ з чорний список доступу і список білий доступу. Користувач трек і охоронної діяльності.

**What does "Cerber" mean?**

Cerber is derived from the name Cerberus. In Greek and Roman mythology, Cerberus is a multi-headed dog with a serpent's tail, a mane of snakes, and a lion's claws. Nobody can bypass this angry dog. Now you can order WP Cerber to guard the entrance to your site too.


