=== Companion Auto Update ===
Contributors: Papin, qweb
Donate link: https://www.paypal.me/dakel/10/
Tags: auto, automatic, background, update, updates, updating, automatic updates, automatic background updates, easy update, wordpress update, theme update, plugin update, up-to-date, security, update latest version, update core, update wp, update wp core, major updates, minor updates, update to new version, update core, update plugin, update plugins, update plugins automatically, update theme, plugin, theme, advance, control, mail, notifations, enable
Requires at least: 3.6.0
Tested up to: 6.2
Requires PHP: 5.1
Stable tag: 3.8.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage all updates on your WordPress site. Stay in the know with several optional e-mail notifications and logs. For free.

== Description ==

Companion Auto Update is a powerful and completely free plugin that allows you to manage all the updates on your WordPress site. Our aim is to give you the best control over these updates and stay in the know at all times.

We understand that you might not always be able to check if your WordPress site has any updates that need to be installed. Especially when you maintain multiple websites keeping them up-to-date can be a lot of work. This plugin can help you with that. We'll keep your site up-to-date and keep you posted about what's happening and notify you when we need your help with something.

If you have a feature suggestion or idea you’d like to see in the plugin, we’d love to hear about it! [Suggest a Feature](https://codeermeneer.nl/contact/)

= Main features =
1. Auto-updating for plugins, themes, core and translation files
1. Set at what time you wish to update
1. Filter plugins and themes to not be updated
1. E-mail notifications about old software, pending updates and completed updates
1. An update log with all updates
1. Option to delay automatic updates with an x number of days

= Full control over everything =
Full control, that's what this plugin is all about. With this plugin you can enable (or disable) automatic updating for plugins, themes, WordPress core updates (both minor and major can be changed separately) and for translation files. Don't want to run the updater for all plugins? Don't worry, just disable auto updating for the plugins you'd like to skip and we can even notify you when there's an update for these plugins so you can go and update them yourself.

= Scheduling =
By default we'll check for updates twice a day but you can change this to several different options if you'd like. When choosing to update on a daily basis you can even select at what time you'd like it to run. Besides the updaters you can also schedule te notifications, want to update every hour but only recieve notifications once a day? No problem!
Sometimes developers will push an update that will cause errors on your site, they'll often fix it within a day but if the updater has run in the mean time it can cause all kinds of issues. Now you can choose to delay updates with an x number of days to prevent this from happening.

= Know what's happening =
We want you to know what's happening on your website. This plugin offers settings for various email notifications. We can send you an email when an update is available, when a plugin has been updated or when wordpress has been updated.
But if you don't want to recieve emails about this you can still log in and view the changelog to see what happened.

== Installation ==

How to install Companion Auto Update

= Manual install =
1. Download Companion Auto Update.
1. Upload the 'Companion Auto Update' directory to your '/wp-content/plugins/' directory.
1. Activate Companion Auto Update from your Plugins page.

= Via WordPress =
1. Search for 'Companion Auto Update'.
1. Click install.
1. Activate.

= Settings =
Settings can be found trough Tools > Auto Updater

== Frequently Asked Questions ==

= Check our website for the FAQ =

[https://codeermeneer.nl/documentation/auto-update/faq-auto-updater/](https://codeermeneer.nl/documentation/auto-update/faq-auto-updater/)

= What features can I expect to see in the future? =

Your feedback is what made this plugin what is and what it’ll become so keep the feedback coming! To see what features you've suggested and what we're working on [read our blogpost here](https://codeermeneer.nl/blog/companion-auto-update-and-its-future/)

= What's the difference between WordPress 5.5 and this plugin? =

WordPress 5.5 was released recently and it packs tons of new features. One of those features is auto-updates for plugins and themes. Something Companion Auto Update does too.
So obviously, some of you wondered what the difference would be between the default WordPress method and the one offered by Companion Auto Update and I figured I’d quickly write a blog about it, explaining the differences.

[You can read this blogpost here](https://codeermeneer.nl/blog/wordpress-5-5-versus-companion-auto-update/)


== Screenshots ==

1. Full control over what to update and when to recieve notifications
2. Disable auto-updating for certain plugins and/or themes
3. Advanced scheduling options for updating and notifcations
4. Keep track of updates with the update log

== Changelog ==

= 3.8.7.1 (September 28, 2022) =
* Tweak: Extended function_exists check with get_plugins() for the fatal error when trying to send update emails

= 3.8.7 (September 12, 2022) =
* Fix: Fatal error when trying to send update emails

= 3.8.6 (August 11, 2022) =
* Tweak: Code optimization for better performance

= 3.8.5 (March 17, 2022) =
* New: Added more checks on the status page and added an explanation to some of them.
* New: Added an list of delayed updates on the status page to help with troubleshooting.
* Tweak: Made some improvements to the update delay feature.
* Tweak: Added a notice to explain that update delay does not work with WordPress update currently.
* Tweak: Improved code on the status page to be more reliable.

= 3.8.4 (February 2, 2022) =
* Tweak: Fixed a few styling errors with WP5.9

= 3.8.3 (December 9, 2021) =
* New: Plugin update e-mails now have an option to link to a few important pages
* Fix: Error: Undefined index: dbupdateemails
* Fix: Error: A non-numeric value encountered

= 3.8.2 (July 1, 2021) =
* Fix: Error: Call to undefined function get_plugin_updates()

= 3.8.1 (June 4, 2021) =
* New: Be notified when we need your help updating to a new database version [Feature Request](https://wordpress.org/support/topic/feature-request-839/)
* Tweak: Made some under the hood performance improvements

= 3.8.0 (January 14, 2021) =
* New: Better handling of plugins with an unknown WP version
* New: More intervals for notifications
* Fix: Call to undefined function errors

= 3.7.1.1 (November 2, 2020) =
* Fix: Type in wp_next_scheduled

= 3.7.1 (October 30, 2020) =
* Fix: PHP Warning: strtotime() expects parameter 2 to be integer, string given

= 3.7 (September 8, 2020) =
* New: Delay updates with an x number of days
* New:  Be notified of plugins that have not been tested with the latest 3 major releases of WordPress.
* New: Choose to see more info in the emails (like the time at which the update happened)
* Fix: "Contact for support" button will work again
* Fix: Fixed a few PHP errors
* Tweak: Made improvements to the "Fix it" button for the "All automatic updates are disabled" error.
* Tweak: You can now choose to ignore the "Search Engine Visibility" and "Cronjobs" warnings
* Tweak: Reports on the Site Health page will only show a summary and point to the status page for more information and possible fixes
* Tweak: Removed cronjob check and Search Engine Visibility check from site health
* Tweak: E-mails are now fully translatable 
* Tweak: Renamed Core notifications to WordPress notifications
* Tweak: WordPress plugin and theme update notifications are now disabled

Also: Check out what features we're working on at [our blogpost](https://codeermeneer.nl/blog/companion-auto-update-and-its-future/)

= 3.6 (August 12, 2020) =
* New: Added an "after core update" hook [More info](https://codeermeneer.nl/documentation/codex-auto-updater/)
* New: Select which userroles can access settings. (Defaults to only administrators)
* Tweak: Added Theme and Core update method the log
* Tweak: Few WordPress 5.5 improvements

= 3.5.5 (August 5, 2020) =
* Fix: Added better multisite support
* Tweak: We've added a bunch more checks to the status page and you can now see more passed checks.
* Support for WordPress 5.5

= 3.5.4.1 (June 20, 2020) =
* Fix: Sometimes the hour settings for intervals wouldn't show up

= 3.5.4 (June 19, 2020) =
* New: See translations in the update log
* New: We've added a few checks to WordPress' Site Health page
* Fix: Error with Companion Auto Update Database Update
* Fix: Schedule interval duplicates

= 3.5.3 (June 5, 2020) =
* New: We're working on a better update log. You should see the Update method (Manual/Automatic) in the log now. (Only works for Plugins right now)
Please report any issues with this feature over at our sticky post: [Problems with the Update method in the new update log?](https://wordpress.org/support/topic/problems-with-the-update-method-in-the-new-update-log/)
* Fix: Not able to see checks in boxes (reverted back to before it all went wrong)
* Fix: Error date_default_timezone_set(): Timezone ID +00:00 is invalid
* Tweak: The status tab will no longer show turned-off settings as an error
* Tweak: Made some improvements to the "Update pending" emails, you can now see a list of all pending updates and go directly to the update page.

= 3.5.2 (April 1, 2020) =
* Fix: Not able to see checks in boxes

= 3.5.1 (March 25, 2020) =
* Tweak: You seem to like the new dashboard, we've fixed a few issues regarding the responsiveness of the design. We've also tweaked the icons to be a bit more transparant.
* Tweak: We've added the release notes link to Plain text emails
* Tweak: Fixed a few typos
* Tweak: Various minor security improvements

= 3.5.0 (March 5, 2020) =
* New: In version 3.4.6 we've changed to HTML emails rather than plain text, in this version you can opt to change it back to plain text emails
* Fix: We've 'fixed an issue where on occasion nothing would update
* Tweak: Made some improvements to the "Fix it" button for the AUTOMATIC_UPDATER_DISABLED error
* Tweak: We've changed the dashboard, moved both the settings page and de support page to the dashboard. Please let us know if you like this change or not.
* Bug: We've had to (temporarily) disable the theme filter because it was causing issues on some installations. We'll try to get it working again in a future update.

[View full changelog](https://codeermeneer.nl/stuffs/auto-updater-changelog/)