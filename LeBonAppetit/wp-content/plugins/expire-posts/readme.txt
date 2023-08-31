=== Expire posts  ===
Contributors: sirmacik
Donate link: 
Tags: post, custom post type, expire, expirator
Requires at least: 3.0.1
Tested up to: 4.2.3
Stable tag: 1.0.5
License: MIT
License URI: http://mit-license.org/

Automatic post expirator for WordPress (with custom-post-type support). 

NO LONGER IN ACTIVE DEVELOPMENT OR SUPPORT. 

== Description ==

NO LONGER IN ACTIVE DEVELOPMENT OR SUPPORT. This plugin serves only as proof of concept that worked at some point and is a good starter for many other solutions. 

## How to:
After installation go to Expire posts section in your Dashboard. It's expected
that you already have created your custom post type or not (if you'll be using
posts or pages for expiration).

Choose:
- desired post type
- expiration checking frequency (plugin utilizes wp-cron)
- and expiration time (for ex. 2 weeks, 5 days, 1 month, 2 years - must be
  provided in English)
- enable post expiration and save options, you're good to go!

## Use case:
If you're running some WordPress based service (with job offers or anything
else) or just want to add some post type with expiration date to your blog 
(let's say contests or polls). After proper setup expire-posts lets you 
expire them automatically!

## Goals:
- ✓ define post-type
- ✓ define time
- ✓ expire post (move to trash or draft state) under given circumstances. 
- ✓ wp-admin interface
- ✓ make it compliant with WordPress plugins directory guidelines
- define action (this is to be written later since WordPress has really poor support of custom post statuses)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `expire-post.php` to the `/wp-content/plugins/expire-posts/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

No questions yet. 

== Screenshots ==

1. Plugin settings interface

== Changelog ==

= 1.0.4 = 
* Ceased development and support.

= 1.0.3 =
* Possible fix for installation error, due to previous plugin upload glitch. 

= 1.0.2 =
* readme.txt improvements

= 1.0.1 = 
* preparations for 

= 1.0 =
* improved time calculations
* added admin settings page
* settings kept in database instead of code

= 0.1-alpha =
* expires posts
* no other interface than code

== Upgrade Notice ==

= 1.0 =
Setup your expiration conditions once again, in dashboard section. 
