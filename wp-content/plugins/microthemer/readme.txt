=== Microthemer Lite - Visual Editor to Customize CSS ===

Contributors: bastywebb, joseluiscruz, ahrale
Donate link: http://themeover.com/microthemer/
Tags: css, customize, visual editor, google fonts, responsive
Requires at least: 6.0
Tested up to: 6.3
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A visual editor to customize the CSS styling of anything on your site - from Google fonts to responsive layouts.

== Description ==

A light-weight yet powerful visual editor to customize the CSS styling of any aspect of your site, from Google fonts to responsive layouts. Microthemer caters for both coders and non-coders, and plays really well with page builders like Elementor, Beaver Builder, and Oxygen.


= Feature list =

1. Style anything
1. Use with any theme or plugin
1. Point & click visual styling
1. Code editor (CSS, Sass, JS)
1. Sync code editor with the UI
1. Customisable breakpoints
1. HTML and CSS inspection
1. 150+ CSS properties
1. Dark or light theme
1. Custom toolbar layouts
1. Work with any CSS unit
1. Color picker with palettes
1. Slider, mousewheel, keyboard adjustments
1. In-program CSS reference
1. History
1. Draft mode
1. Global or page-specific styling
1. Import & export
1. Light-weight
1. Minify CSS code
1. Keyboard shortcuts
1. Deep integration with Elementor, Beaver Builder, Oxygen
1. Multisite support
1. Uninstall MT, but keep your edits
1. **[Pro]** CSS grid (drag & drop)
1. **[Pro]** Flexbox
1. **[Pro]** Stock SVG mask images
1. **[Pro]** Transform
1. **[Pro]** Animation
1. **[Pro]** Transition


= Lite VS Pro =
This lite version limits you styling 15 things, and doesn't include the features marked [Pro] in the list above. To unlock the full program, you can <a href="https://themeover.com/">purchase a license</a> (monthly, annual, or lifetime).

= Useful links =

- <a href="https://themeover.com/">Website</a>
- <a href="https://themeover.com/introducing-microthemer-7/">Video docs</a>
- <a href="https://livedemo.themeover.com/setting-up-demo-site/?create_demo">Live demo</a>
- <a href="https://themeover.com/forum/">Support forum</a>
- <a href="https://www.facebook.com/groups/microthemer">Facebook group</a>

= Author note  =

Hello everyone, my name is Sebastian. I've designed Microthemer for developers as well as beginners. My aim is to level up beginners by exposing the CSS code Microthemer generates when using the visual controls. This is of course helpful for developers who may wish to make manual edits. Some developers use Microthemer as an in-browser CSS or Sass editor, and just lean on the interface for element selection or more advanced properties like filters, grid, and animation.

I've been happily developing Microthemer and supporting users of varying technical experience in my forum for many years now. I'm always ready to answer questions about the software and help out with CSS hurdles. Please don't hesitate to get in touch!


== Installation ==

1. Click the 'Plugins' menu option in WordPress.
2. Search for 'Microthemer'.
3. Install and activate the plugin.
4. Go to the 'Microthemer' menu option.
5. Start customizing the appearance of your site.

== Changelog ==

= 7.2.4.4 (September 6th, 2023) =

# Bugs Fixed
* Z-index conflict with CartPops plugin meant Microthemer's element selection overlays did not display above a shopping cart popup.

= 7.2.4.3 (September 5th, 2023) =

# Enhancement
* New option added to the preferences: "Auto-focus the code editor cursor after selecting an element". This saves time clicking on the code editor after selecting an element, but can also lead to accidental typing in the editor. Furthermore, there may be some bugs as a result of automatically shifting focus - please report any that you find.
* Added a "Wireframe mode" option to the Settings > view menu. This gives all elements a semi-transparent gray background and a dashed border. This helps understand the underlying HTML structure of the page, and is useful for identifying elements that cause unwanted overflow on the page, possibly leading to scrollbars appearing in strange places. Microthemer also highlights ::before and ::after pseudo elements - a bit more faded out, so they're distinguishable from regular elements.

= 7.2.4.2 (September 1st, 2023) =

# Bugs fixed
* Error publishing settings on Cloudways hosting since a recent Cloudways server update. This happened if the "Minify CSS" option was enabled in preferences.

= 7.2.4.1 (August 3rd, 2023) =

# Change
* Automatic error reports only send MT settings for specific errors, even if permission is enabled. I can fix most errors without this additional info, so have switched to a system of explicitly defining errors that should be accompanied by data.

# Bugs fixed
* CSS grid highlighting could linger on when switching between selectors in some cases.

= 7.2.4.0 (July 31st, 2023) =

# Bugs fixed
* Possible error message could display on the frontend if an empty string was passed into a folder logic condition: Cannot parse $parameterString.
* Microthemer was not targeting draft pages correctly if the Auto folder option was enabled (because the page slug was not yet set).

= 7.2.3.9 (July 26th, 2023) =

# Bugs fixed
* Microthemer did not notify about user JS errors when working with the full code JS editor.

= 7.2.3.8 (July 25th, 2023) =

# Enhancement
* Added a preview link to the error reporting options, so you can see exactly what data MT will send depending on which checkboxes are ticked.
* Microthemer sends error reports for unique JavaScript errors it discovers on your site while using Microthemer (edge mode is no longer required).

= 7.2.3.7 (July 18th, 2023) =

# Change
* Microthemer does not automatically set the order of selectors within folders when adjusting the selector code, only when selectors are first created (and the Auto folder option is enabled).

# Bugs fixed
* Deleting a design pack did not work if a design pack screenshot had been uploaded.
* Syncing the editor and UI did not work on certain properties since the 7.2.3.5 update 6 days ago.

= 7.2.3.6 (July 14th, 2023) =

# Bugs fixed
* Responsive tabs did not appear to have an effect in certain in browsers, particularly at odd number breakpoints (e.g. 411) or if the browser zoom was set. This is because of pixel rounding issues browsers can encounter. MT fixes this by settings the site preview width slightly above or below the min/max-width media query values.
* When switching between styles docked top (with the options horizontally scrolled) and then docked left, the options could appear cropped.

= 7.2.3.5 (July 12th, 2023) =

# Bugs fixed
* When adding comma separated box-shadow values to the single selector code editor, MT tried to sync the values with the UI fields, which stripped away all values after the first comma.

= 7.2.3.4 (July 11th, 2023) =

# Bugs fixed
* Microthemer page-specific folder logic did not work if the page slug had the word "and" / "or" in it.

= 7.2.3.3 (July 11th, 2023) =

# Bugs fixed
* CSS added to the full code editor applied to the WordPress admin area, when viewed inside Microthemer.

= 7.2.3.2 (July 10th, 2023) =

# Enhancement
* A setup screen displays when you first load Microthemer. This supports importing workspace preferences from another MT install, or setting some commonly changed preferences like global !important or Sass support. You can also set up automatic error reporting and watch the top 10 videos, with progress tracking.
* A new condition function is supported e.g. \Microthemer\has_template('bricks', 215, 'Header template'). Use the conditions menu to generate this kind of logic. It checks if a page loads a particular template, so your custom Microthemer CSS can follow templates around wherever you place them. Right now, only Bricks templates are supported. Feel free to request more.

# Bugs fixed
* When exporting a design pack, after previously deleting one with the same name, MT presented an error "Create json error".
* If Auto folder was enabled, setting a custom folder name in the top toolbar during targeting did not work sometimes.
* An issue with an exported design pack name being a bit different in the Export screen packs menu.

= 7.2.2.8 (June 20th, 2023) =

# Bugs fixed
* Issue saving custom folder logic when using a custom port number on localhost e.g. localhost:9017. This may have caused other issues as well.

= 7.2.2.7 (June 19th, 2023) =

# Bugs fixed
* Microthemer did not create valid conditional folders when targeting content on single post type pages.

= 7.2.2.6 (June 9th, 2023) =

# Change
* Microthemer used to try to maintain the current folder if it loaded on the page, even if that was at odds with the Global vs Page switch in the footer. Now it honours the switch more strictly. This makes the feature more predictable and solves the issue of accidentally switching between global vs page styles when re-selecting elements that have an existing selector.

= 7.2.2.5 (June 9th, 2023) =

# Change
* GZIP compression on the MT interface is enabled by default. This will make MT load faster, and should not occasionally result in a blank page now that the session issue has been addressed.

# Bugs fixed
* Possible PHP warning about $preferences being defined in Trait and Class.
* Possible https warning in the console when saving preferences on the standalone page.
* Compatibility issue with GZIP preference if a PHP session was active, a blank screen was displayed.


= 7.2.2.3 (June 2nd, 2023) =

# Bugs fixed
* When viewing logic settings for a folder with no condition immediately after one that does have a condition, the condition continued to display in the textarea.

= 7.2.2.2 (June 1st, 2023) =

# Change
* Microthemer used to highlight elements in grey if a selector would target the current element, but is in a conditional folder that doesn't load on the page. This was useful for hinting if you might want to extend the targeting of an existing selector to other pages, but arguably distracting. As it was necessary to use the "Create an additional selector" option fairly frequently. So on balance, I think it's better not to highlight such selectors in grey. Grey highlighting is now reserved for when you click a non-loading selector in the folders menu (rather than selecting elements on the page).

# Bugs fixed
* Microthemer falsely flagged comma separated :not/:is pseudo selectors as invalid e.g. not:(p, li). Also, MT formatted such comma separated selectors on new lines, which looked messy in the editor.
* Possible PHP warning after an initial install when viewing site frontend: Warning: Undefined array key 'initial_scale'.

= 7.2.2.1 (May 30th, 2023) =

# Bugs fixed
* Display issue on the view code tabs if a folder name was fairly long.
* Custom JavaScript added via the full code view was not loading on the page.

= 7.2.2.0 (May 25th, 2023) =

# Enhancement
* Added links to  help videos when displaying the "Folder not loading" warning.

# Bugs fixed
* Two issues if "Stylesheet loading order" preference was set - MT could output the same CSS file again under some circumstances (after the body tag). And Microthemer folders did not always show as faded out in the main menu to indicate when they are not loading.
* When Microthemer automatically created a folder for the current page, the folder name could be quite long. MT now caps the length at 35 characters, to the nearest whole word.

= 7.2.1.9 (May 22nd, 2023) =

# Enhancement
* Two new conditional loading functions supported:
* \Microthemer\is_active() - for checking the currently active theme or plugin.
* \Microthemer\match_url_path() - for testing string or regex pattern match on thr current URL path.

# Bugs fixed
* Folder logic tests failed to run if the "Stylesheet loading order" was set in the preferences.
* Issues customizing pages with Microthemer if the page didn't run the wp_footer hook.

= 7.2.1.8 (May 15th, 2023) =

# Bugs fixed
* The previous update caused issues if the 'load stylesheet in footer' preference was set.

= 7.2.1.7 (May 15th, 2023) =

# Bugs fixed
* Compatibility issue with Divi theme, whereby Divi styles did not load properly.

= 7.2.1.6 (May 14th, 2023) =

# Bugs fixed
* Use of a PHP $_SESSION in the 7.2.1.4 caused issues for some sites. Microthemer now uses $_COOKIE instead.

= 7.2.1.5 (May 13th, 2023) =

# Bugs fixed
* Jumpy layout shift when MT loads for the very first time.

= 7.2.1.4 (May 12th, 2023) =

# Change
* Asynchronous CSS loads by settings media="print" initially, rather than rel="preload". Preload isn't supported in IE11, and is slightly less performant than switching the media type.
* Rolling back to high CSS specificity by default as that may be an adjustment for existing users.

# Enhancement
* New XL2 option added to layout presets. This sets up two columns in left hand sidebar, one for folders another for styles.

# Bugs fixed
* Draft folders (any folder with only one draft (unsaved) selector in it) were being created twice when navigating between site pages because they were mistakenly assessed as not loading on the new page.

= 7.2.1.3 (May 11th, 2023) =

# Bugs fixed
* Microthemer popup screens did not display correctly in yesterday's release.

= 7.2.1.2 (May 10th, 2023) =

# Change
* Microthemer no longer creates an example selector to target the h1 on the home page.

# Bugs fixed
* The prev/next selector arrows did not update correctly when auto-positioning selectors within folders based on the order they appear on the page.
* The page-id modifier did not work when set to mtp, on this beta version.
* Possible issue loading MT.

= 7.2.1.0 (May 5th, 2023) =

# Change
* Conditional folder icons just have an outline, global folders are solid.

# Bugs fixed
* When adding a condition to a folder, the and/or buttons could show instead of the "Add" button.
* Alignment of Auto-folder context menu.
* MT was not always highlighting the correct line when showing the source of a Sass compile error.

= 7.2.0.8 (May 4th, 2023) =

# Change
* The "Auto Folder" setting is disabled for users upgrading from previous versions, so that switching to automatic page-specific folders that automatically organise selectors within folders is a deliberate decision.
* Slight change to the behaviour when switching between global and page styles - the folder only changes if you select an initial folder, or you select the next element on the page. This preserves to opportunity to adjust the "Move current selector" checkbox.

# Bugs fixed
* JS error when using the "Detach preview" feature.
* Layout shift when Microthemer was initially loading.

= 7.2.0.7 (May 1st, 2023) =

# Enhancement
* If a folder has a condition that effectively makes it global on the frontend or admin area, it is displayed in the Global folders list in the footer.
* Global and current page options added to the "Add condition" menu.

# Bugs fixed
* Automatic page-specific folders did not work correctly when added to the admin area.
* Switching to a global folder on the admin side, could default to a frontend only global folder.

= 7.2.0.6 (April 27th, 2023) =

# Change
* The options for leaving Microthemer are only at the bottom right of the interface, they are not in the settings menu anymore.
* Microthemer's default selector labels do not include the parent context, to make them a bit shorter and more readable.
* Microthemer only minifies PUBLISHED CSS and JS files, and those preferences are enabled by default. Draft CSS files are beautified.
* Microthemer creates an example selector when you first install it - Heading 1.
* Microthemer generates CSS selectors with low specificity by default. This seems like the best approach when used in combination with the "Auto folder" settings, which is enabled by default (see below).

# Enhancement
* Microthemer automatically organises your selectors into page-specific folders, with no configuration necessary.
* Switch between global and page-specific styles with a single switch in the footer panel.
* Or fully disable "Auto folder" by unchecking the checkbox in the footer panel.
* With "Auto folder" enabled, Microthemer adds selectors to folders in an order that matches the HTML DOM order - the order elements display on the page.
* With "Auto folder" enabled, Microthemer creates a "below fold" variation of any page-specific folder - if elements are positioned below a height threshold set in Preferences (the default is 1440px).
* Added UI support for :is and :where pseudo classes.

# Bugs fixed
* The Microthemer shortcut did not display on the frontend.
* Possible PHP Warning: Undefined array key "global_g_fonts".
* Adding a selector to a folder manually was not stored in the history table.
* Possible file writing error on new installations.

= 7.2.0.4 (March 16th, 2023) =

# Enhancement
* Added basic support for container queries. Add these manually or load a sample set via the media queries screen. Make an element a container using the new "container-type" property in the behavior  group. Learn more about container queries here: https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Container_Queries

= 7.2.0.3 (March 10th, 2023) =

# Change
* When clicking the folder in the top toolbar, the menu scrolls to the current selector in focus, rather than the top of the folder.

# Enhancement
* Microthemer warns you if your folder loading conditions generate a PHP error when testing your logic, and suppresses error reporting on the frontend.
* Folder icons added to conditional stylesheets in the view generated code window.
* Unpublished CSS is flagged clearly when viewing a CSS file that only exists as a draft.

# Bugs fixed
* When applying styles to a folder that is not loading due to conditional logic, the "Folder not loading" message could appear many times.
* The styling of the folder in the top toolbar did not reflect the state of loading on the current page, or if it was conditional.
* The 'Folder not loading' warning did not display when using the CSS grid drag and drop controls.
* Microthemer uses absolute image paths for background images if the WordPress install directory structure means that the root relative paths it tried to generate are not valid.
* Microthemer could output empty selectors in CSS it generates, particularly when using the responsive tabs.
* On the standalone preferences page, the tabs did not work.

= 7.2.0.1 (March 3rd, 2023) =

# Bugs fixed
* Clear logic button did not live update the styles.

= 7.2.0.0 (March 1st, 2023) =

# Enhancement
* Support for conditionally loading CSS on specific pages only.
* Load a folder's CSS as an inline style tag or as an external stylesheet that can be loaded asynchronously.
* Enter one or more PHP-like logical conditions to determine folder loading.
* Alternatively, search for pages to include or exclude.
* Optimisation to ensure that there is zero performance gain from deactivating Microthemer and using the code for simply loading frontend assets. The same standalone PHP class is now used for both scenarios.

# Bugs fixed
* Compatibility issue with PHP 8.
* The viewport meta tag was not added when loading Microthemer assets manually, which could lead to inconsistent results.
* Microthemer styles were not added to the WP login page when loading Microthemer assets manually.

= 7.1.6.9 (Jan 17th, 2023) =

# Bugs fixed
* When typing "flex-flow: column wrap" (or wrap-reverse) in the single selector editor, Microthemer converted the CSS to "flex-flow: column nowrap".

= 7.1.6.7 (Jan 10th, 2023) =

# Bugs fixed
* Compatibility issue when loading Elementor 3.10 inside Microthemer - the site preview was tiny.

= 7.1.6.6 (Jan 10th, 2023) =

# Bugs fixed
* Compatibility issue with the Cart Props plugin, the cart drawer elements could not be selected.

= 7.1.6.5 (Jan 3rd, 2023) =

# Bugs fixed
* Compatibility issue with Beaver Builder, which could add dynamic ids starting with yui_3 that changed on each page load. MT was picking up on these ids when suggesting CSS selectors, which quickly became invalid.

= 7.1.6.4 (Dec 15th, 2022) =

# Bugs fixed
* Styles did not display outside of MT for logged in administrators if the auto-publish feature was enabled.

= 7.1.6.2 (November 29th, 2022) =

# Enhancement
* Added shallow integration with Breakdance and OptimizePress.
* New keyboard shortcut added for beautifying CSS in the code editor: Crtl + Alt + O (the letter O).

= 7.1.6.1 (November 24th, 2022) =

# Enhancement
* Microthemer supports "shallow" integration with five more page builders including Divi, Bricks, Brizy, Zion, and WP Page Builder. This means that Microthemer will auto-reload its CSS in other browser tabs, and reload the site preview if you publish or save draft changes in one of the aforementioned builders. To enable this feature, go to Settings > Integrations > Sync browser tabs.

= 7.1.6.0 (November 22nd, 2022) =

# Enhancement
* Performance improvement when selecting elements. Selection was exceptionally slow when the Query Monitor Plugin was active, because it adds lots of HTML to the page, but the fix for QM should speed selection up in general, especially for page builders. Note, Query Monitor still slows MT even with this update, so try not to leave it active. It's only meant for temporary performance debugging anyway.

# Bugs fixed
* Error when loading a builder. Uncaught TypeError: Failed to execute 'getComputedStyle' on 'Window': parameter 1 is not of type 'Element'.

= 7.1.5.9 (November 1st, 2022) =

# Enhancement
* New preference added "Auto-scroll to the current element, if out of view". Set this to "No" to disable MT's default auto-scrolling behaviour.

# Bugs fixed
* The code editor falsely flagged certain :not() selectors as invalid.

= 7.1.5.8 (October 20th, 2022) =

# Enhancement
* Add two more pseudo elements to the CSS modifiers menu.
::marker for styling list bullets, and
::placeholder for styling input field placeholder text.
* Added an extra "BB large" media query to the "Beaver Builder MQs" media query set, to match the new breakpoint Beaver Builder added recently.

= 7.1.5.6 (August 8th, 2022) =

# Bugs fixed
* The code editor CSS validation rules were falsely flagging square brackets in :not selectors as invalid e.g. img:not([src$=".svg"]).

= 7.1.5.5 (August 2nd, 2022) =

# Bugs fixed
* The folder search results area could extend too wide if the folders pane wasn't docked left.
* The page-id shortcut in the advanced options / context menu didn't work.
* When setting a color value, the history entry was zero (0).

= 7.1.5.4 (July 25th, 2022) =

# Bugs fixed
* Possible JS error when loading Microthemer: Uncaught TypeError: p.getElIndex is not a function.

= 7.1.5.3 (July 13th, 2022) =

# Bugs fixed
* Starter styles could be set to larger than 20px by 20px if the default unit was not pixels.

= 7.1.5.2 (July 13th, 2022) =

# Bugs fixed
* Invalid selector created when selecting ::before or ::after pseudo elements not created using MT.

= 7.1.5.1 (July 12th, 2022) =

# Change
* When editing a selector's targeting, the CSS properties do not update until the "Update" button is clicked.

# Enhancement
* New starter styles switch added to the selector variation options for ::before and ::after. The starter styles make the element visible as a pink square so it's easier to position and be confident it has been set properly.
* Selector variation labels are adapted from the label used by any other variation, thus honouring any custom labels that have been set.

# Bugs fixed
* Selector modifiers like :hover and ::before were not applied to single class or id selectors.
* Wish Sass enabled, retargeting a selector did not apply the CSS properties immediately (even after clicking the "Update" button).
* Wish Sass enabled, the HTML pane did not immediately display the ::before or ::after line when creating a pseudo element variation.

= 7.1.5.0 (July 8th, 2022) =

# Change
* MT does not open links in a new tab when holding Ctrl to follow a link during targeting mode, as it did for a brief period after the release of version 7.1.4.0.

# Enhancements
* Performance optimisation for the HTML inspector.
* Refresh HTML pane icon added.
* Added a "Save JavaScript" button next to the JS tab as an alternative to using Ctrl + S to save JavaScript.
* Custom classes can be added to the body tag by setting the new MT preference "Insert body classes defined using custom fields" to Yes. You then define your custom classes using native wordpress custom fields. Set the custom field key to "my_body_classes" and the value to one or more space separated CSS class names (with no dot prefix).
* Added an option for adding a custom class or id selector prefix below the page-id CSS modifier.

# Bugs fixed
* The new pseudo element highlighting could make inner elements unselectable by MT under certain conditions.
* A pseudo element's z-index was not always factored in correctly when selecting elements visually.
* Resizing the full code CSS or JavaScript pane could load content from the single selector editor.
* The code editor flashed white when first loading if the dark theme was enabled.
* Selectors with quotes e.g. [type="submit"] caused issues when used in combination with selector variations e.g :hover.
* The HTML inspector beautifier could fail under some circumstances, and this affected the ability to select elements via the HTML pane.
* Microthemer output shorthand code e.g. padding:16px even if padding-left was set to a value other than 16px.
* Pasting a hex or RGB color in the color picker did not set the color properly.
* The Alt key and mousewheel could not be used to tweak numbers in the filter drop-shadow property.
* CSS comments are a bit more readable in the editor in dark mode.
* The full code view had an unnecessary left with certain layout configurations.
* Selectors with pseudo states like :hover were not recognised as targeting an element in terms of the blue element highlighting.
* The CSS unit for linked fields (e.g. padding) did not always synchronise properly.
* Holding the shift key did not select multiple element when clicking on lines of HTML in the inspection pane.
* The transition event value was getting lost when editing via the code editor.

= 7.1.4.0 (June 14th, 2022) =

# Enhancements
* ::before and ::after pseudo elements shown in the HTML inspector.
* ::before and ::after pseudo elements can be visually clicked to select them.
* ::before and ::after pseudo elements can be created using the selector variations option.
* The selector modifier checkboxes synchronise with the selector code as it is edited live.

# Bugs fixed
* An issue using the dropdown menus on empty fields when the computed value was [mix] (meaning different for multiple elements).
* The options above the selector variation options did not update properly when switching between variations.
* Element border highlighting displayed outside the main container.
* The cancel selector targeting icon could be cut off sometimes.
* The settings panel could have horizontal scroll if the history menu was expanded and contained long names.
* An issue triggering Bricks form submissions even when Microthemer's targeting mode was disabled.

= 7.1.3.1 (May 26th, 2022) =

# Bugs fixed
* An import CSS issue caused by the previous update.

= 7.1.3.0 (May 25th, 2022) =

# Bugs fixed
* An error occurred when enabling Sass support on a fresh install, or if no code had been added to the full code editor.

= 7.1.2.9 (May 11th, 2022) =

# Bugs fixed
* Some missing icons in the CSS reference.
* Possible PHP notice on site frontend with WP_DEBUG active: Notice: WP_Scripts::localize was called incorrectly.
* The MT logo displayed very large when loading MT with dark mode and the style options docked top.
* The selector suggestions popup could not be triggered on top aligned elements if the WP toolbar was not showing.
* The selector suggestions popup did not display in the detached preview window.
* The "Oxygen Elements for WooCommerce" plugin caused Microthemer's frontend script to load multiple times, which caused odd behaviour.
* On-canvas padding highlight could be offset.

= 7.1.2.7 (April 25th, 2022) =

# Bugs fixed
* Added a better fallback option if ZipArchive PHP extension is not installed.

= 7.1.2.6 (April 23rd, 2022) =

# Bugs fixed
* Possible PHP error if ZipArchive extension was not installed.

= 7.1.2.5 (April 20th, 2022) =

# Bugs fixed
* JS error that could arise on loading MT: Uncaught TypeError: labelCodeStr.split is not a function.

= 7.1.2.4 (April 20th, 2022) =

# Change
* MT wraps URL paths with url("") in input field (not just in the editor) for consistency with mask-image.

# Bugs fixed
* Slider generated NaN on properties that don't have a unit (e.g. opacity).
* The new clip-path and shape-margin properties were not listed in the transition property menu.
* Microthemer was not reloading the CSS property reference when launching from a different property.
* No dashed outline was shown when hovering over the currently selected element when element highlighting was switched off.
* MT did not auto-wrap plain file paths with url() for background-image or mask-image if URL parameters were used.

= 7.1.2.3 (April 14th, 2022) =

# Change
* The "M" toolbars preset set the left columns to full height by default.

# Enhancement
* Added support for CSS mask, clip-path, and shape-outside properties with stock SVG mask images and clip-path shapes.
* Added object-fit and object-position to the Dimensions property group
* Added a quick navigation toolbar for the property groups when the styles are docked left. This can be turned on or off via Settings > View > Fine tune layout > Sticky styles toolbar.
* Microthemer's slider, mousewheel, and keyboard number adjustments can be applied to single numbers when there are multiple numbers in a field. This is useful for adjusting e.g. background-position, polygon, and gradient position/opacity values when used as an image-mask.

# Bugs fixed
* Resizing the side panels caused the current property group to reload, which reset the scroll position and removed grid rows/column that hadn't been given a value yet.
* The HTML pane could become out of sync with the DOM elements if a script tag contained a string with a closing style tag. This affected Oxygen 4.0.
* Microthemer could sometimes scroll to the top of elements even when they were already in view.
* Typing styles in the full code editor could (re)trigger scrolling to the currently targeted element. This was only meant to happen when the cursor is positioned inside a new selector.
* The mt-inactive.zip file for loading MT CSS with a simple plugin was missing.
* Searching the CSS property menu suggestions could result in a spinning gif and an error message.
* A conflict with Astra caused MT to always select the mobile navigation close toggle (#menu-toggle-close).
* Firefox had a delay in style rendering when using the CSS property slider.
* Some layout issues when using Microthemer on a very small screen (approx 500px).
* Setting a new folder name when creating a selector generated an error.
* MT did not sync comma separated CSS property values in the editor with the UI, at times when it should.

= 7.1.1.5 (March 10th, 2022) =

# Enhancement
* Added an option to preferences for disabling targeting mode by default when Microthemer first loads. This may be useful for devs that prefer to code selectors manually, without assistance from Microthemer.

# Bugs fixed
* Text in the Google search field was the same color as the white background when the light theme is active.
* Entering align-items in the code editor synced with the flexbox UI fields, rather than the grid fields, even if the display property was set to grid.
* Setting a CSS Modifier like :hover or :first-child did not update the full list of selector suggestions in the top toolbar.
* Setting a CSS Modifier removed the ">" character from selectors.
* Removed some unhelpful selectors from the suggestions lists e.g. div:nth-of-type(1).
* When exiting a page builder with deep integration (Beaver Builder, Elementor, Oxygen), Microthemer was enabling targeting mode, even if it wasn't on before activating the builder.

= 7.1.1.4 (February 16th, 2022) =

# Bugs fixed
* The code editor did not recognise the following pseudo selectors as valid :has, :is, :where
* Fixed a conflict with ShortPixel Adaptive Images, which was using the same PHP Minify library.

= 7.1.1.3 (February 1st, 2022) =

# Bugs fixed
* An error could happen when selecting elements at the point of maxing out the number of selectors allowed in a folder.

= 7.1.1.2 (January 25th, 2022) =

# Bugs fixed
* An error that could occur when selecting multiple text inputs using the shift key could generate a downstream JS error when loading Microthemer: unauthorized TypeError:i.split is not a function.

= 7.1.1.1 (January 21st, 2022) =

# Bugs fixed
* PHP Warning: Illegal string offset 'label' in tvr-microthemer.php on line 5589.

= 7.1.1.0 (January 18th, 2022) =

# Enhancement
* Improved compatibility with Bricks builder - Microthemer now has a workaround for creating CSS grid layouts on container elements that also display in the Bricks editor. This workaround is on by default, but can be turned off via the preferences. There is also an option to remove the workaround from all selectors automatically, as it may become redundant in future.

# Bugs fixed
* When clearing the top field for editing selector targeting, and then typing very quickly, an error could be generated: "this.source is not a function".
* An upgrade error could happen if old MT settings contained data that hadn't been cleaned by a more recent version of Microthemer.

= 7.1.0.7 (January 16th, 2022) =

# Enhancement
* Styles applied to Bricks container elements also render in the Bricks editor which makes it easier to apply CSS grid layouts to Bricks content using Microthemer.

# Bugs fixed
* The grid controls could display behind the options panel with certain toolbar layout configurations.
* Artificial Microthemer body and HTML classes were being included in the selector suggestions list.

= 7.1.0.4 (January 14th, 2022) =

# Bugs fixed
* The detached preview layout did not load properly due to a JS error "TvrLang is not defined".
* Microthemer's targeting did not resume when switching between pages on the frontend.

= 7.1.0.2 (January 13th, 2022) =

# Bugs fixed
* Horizontal scrollbars could appear when targeting elements with Microthemer if the element name was too wide for the available space.

= 7.1.0.1 (January 12th, 2022) =

# Bugs fixed
* An issue with the dropdown menus on the standalone preferences page.

= 7.1.0.0 (January 12th, 2022) =

# Bugs fixed
* Using the re-target selector option could sometimes save the preliminary retargeting even if it was cancelled.
* The color picker could generate an error after re-targeting a selector if it was opened just before re-targeting.

= 7.0.9.8 (January 8th, 2022) =

# Bugs fixed
* The opacity input field was not working properly.

= 7.0.9.7 (January 7th, 2022) =

# Enhancement
* Minor performance increase.

# Change
* Some terminology updates to make it clearer that styles are saved as a draft until the Publish button is clicked (unless auto-publish is enabled).

# Bugs fixed
* When adding a new selector to a closed folder, not all selectors in the folder displayed alongside the newly added selector.
* Sometimes entries in the history table could say simply "false".

= 7.0.9.3 (January 7th, 2022) =

# Bugs fixed
* The previous update caused an issue loading the MT interface on some sites, due to an issue with the JS script loading order.

= 7.0.9.2 (January 6th, 2022) =

# Enhancement
* Added auto-publish option to Settings > General.
* Added support for completing a firewall captcha form when submitting a license key from a server with a blocked IP address.

# Bugs fixed
* The publish button was not semi transparent on first install, and the initial notification was a bit confusing.
* The color picker opacity slider did not work since the recent 7.0.8.5 update.
* It was awkward to save multiple colors, one after the other, using the color picker save button.

= 7.0.8.8 (December 28th, 2021) =

# Bugs fixed
* A conflict with UIPress admin styles.
* Styling issue with CSS reference - white BG and spinner could display below scroll area.

= 7.0.8.6 (December 27th, 2021) =

# Bugs fixed
* An issue loading tooltips on the frontend could cause issues loading MT.

= 7.0.8.5 (December 22nd, 2021) =

# Bugs fixed
* An issue with the color picker closing when using the VAR field and generating an error sometimes.
* Color picker opacity slider could overlap the VAR dropdown menu.
* Dark theme colors could display in odd places (e.g. dividers, color picker, input borders) with the light theme if a CSS concatenation plugin changed the name of MT's internal CSS file that loads on the frontend.
* The top selector suggestions menu could not be clicked under certain circumstances.

= 7.0.8.1 (December 16th, 2021) =

# Bugs fixed
* The previous update could cause the page navigator search to return no results on some sites.

= 7.0.8.0 (December 16th, 2021) =

# Change
* When enabling a page builder, Microthemer removes any draft selector, rather than saving it.

# Bugs fixed
* The page navigator was displaying a cached set of pages instead of fetching new results under some circumstances.

= 7.0.7.8 (December 15th, 2021) =

# Bugs fixed
* Context menus did not reposition when the screen cut off some of the content.
* Sometimes "undefined" could appear in the editor, rather than an empty string, if no property value was set.
* Scrollbars could appear on the single selector editor when docked top.
* Draft selectors were not removed when using the folders pane to navigate to an existing selector.
* Copying a draft selector did not save it properly.
* A warning could popup saying "Selector name already exists" when clicking on a draft selector that wasn't properly saved due to the previous two issues.
* Matching search text in the page navigator was the wrong color (poor contrast).

= 7.0.7.1 (December 14th, 2021) =

# Critical bug
* Brand new selectors did not save when using the single selector editor on a responsive tab and typing some CSS to confirm that the draft selector should be kept. This only happened when typing speed was neither very fast nor very slow, due to the way the auto-save system works.

= 7.0.7.0 (December 9th, 2021) =

# Change
* The color of CSS or Sass comments when using the code editor with the dark theme.

# Bugs fixed
* The grid highlighting persisted when collapsing the MT interface
* If the grid canvas was expanded, it continued to display when going back to the properties list when styles were docked left.
* The Google font field was a bit small with the styles docked top.
* When playing around with the layout options, sometimes overflowing docked top styles could overlap docked-left/right panels, instead of being hidden.
* Folder search could generate an "invalid regex" error under some circumstances.
* When doing a folder search, clicking any matching CSS code did not always work.

= 7.0.6.3 (December 6th, 2021) =

# Bugs fixed
* Due to the fix in the previous update, the loading spinner could remain in place if the site preview page had very little content.
* A light gradient was used to fade out overflowing responsive tabs, even with the dark theme.

= 7.0.6.1 (December 2nd, 2021) =

# Bugs fixed
* On some servers, a forbidden error message was shown instead of Microthemer's site loading spinner.

= 7.0.6.0 (December 2nd, 2021) =

# Change
* With the dark theme, the scrollbars are easier to see.
* Values in the history table have been emboldened to make them easier to read.

# Bugs fixed
* Under some circumstances, the icon font didn't load properly.
* An error in the sample code for loading Microthemer CSS manually.
* Switching the low/high specificity toggle did not update the selector label.
* Context menus, that are draggable via the header, did not have the correct cursor move icon.

= 7.0.5.4 (November 25th, 2021) =

# Change
* When you select an element, and an existing selector targets it but in a different view (full code editor vs GUI), Microthemer notifies you about this rather than automatically switching view. This was a problem when universal selectors were used (*). And there may be other edge cases I haven't considered.

# Bugs fixed
* When using a universal selector (*), every element had blue highlighting to show you've created a selector for it. And while technically true, it obscured a useful feature.

= 7.0.5.0 (November 21st, 2021) =

* Public release of version 7.

# Enhancements summary
* Hundreds of UI and UX improvements.
* Dark theme.
* Searchable folders.
* Sharper icons.
* Instant (draft) selectors.
* Easier selector sorting within folders.
* More customisable toolbar layouts.
* Drag resizable panels.
* Create hover selectors more easily.
* Shortcut for setting page specific selectors.
* Special on-page breadcrumbs for tightly nested elements.
* Keyboard shortcuts reference panel.
* Set the loading order of Microthemer's stylesheet.
* Set the prefix used for page specific body classes (mtp is now the default).
* Microthemer's stylesheet can be loaded in the footer.
* Responsive HTML and CSS inspection panels.
* Computed border, padding, margin box model properties.

= 7.0.4.9 (November 21st, 2021) =

# Change
* Restored V6 CSS grid control styling, as my attempt to simplify the styling came at the cost of usability.
* Current line of HTML in dark theme matches color scheme better.
* Dark theme yellow text highlight when searching folders.
* The settings toggle at the top right is hidden when the settings are permanently docked in view.

# Bugs fixed
* Oxygen device switching options did not work when loaded inside Microthemer.
* The manage packs page did not update after a fresh export of settings.
* The edit design pack icon prompted an 'Are you sure you want to delete this message'.
* The text editor did not always fill the available height when docked left.
* The checkboxes could be cropped on the 'Export' page on some screens.
* Microthemer beatified the minified CSS file when display it, so it wasn't clear if minification was working.
* Display issue when folder and editor were docked left, but styles were docked top.
* Issue with dragging Grid template areas, the controls could get stuck. And dragging anywhere outside the grid canvas removed the area (not just when dropping on the clear item).
* The CSS grid controls could get stuck when dragging the last item outside the graph area.
* Toggling the folders off didn't always bring content hidden underneath back to the top.
* In the full code view, switching the editor from docked top to docked left set it's width to zero.
* Microthemer was not remembering the cursor position for the single selector editor when switching responsive tab or between selectors.
* Disabling Beaver Builder via Microthemer's switch did not work of no changes had been made in BB.
* Scrollbars for Google fonts browser and frontend and were not dark even if dark mode was enabled.

= 7.0.4.8 [beta] (November 17th, 2021) =

# Change
* Alt key must be used to enable mousewheel adjust on styles docked top (not just when docked left). This is to prevent accidentally setting values.

# Bugs fixed
* Using the Alt key to select elements only worked once.
* Renaming a folder did not work.
* Tooltip accessibility content could display on screen if the jQuery stylesheet was unloaded.

= 7.0.4.4 [beta] (November 16th, 2021) =

# Bugs fixed
* Media query scope not visible in top ruler with the light theme.
* Color picker site colors refresh icon was missing.
* Pixel equivalent value under tape measure was misaligned.
* Microthemer was adding units to non-numeric values like clamp when loading the UI fields.

= 7.0.4.0 [beta] (November 14th, 2021) =

# Change
* Tweaked the grid control styling.

# Enhancement
* Dark theme switch added to View menu.

# Bugs fixed
* More options icon appeared in the context menu it triggered.
* Misaligned color picker saved and sampled styles panel.
* Item number was missing from 'Item' tab in grid controls if docked left.

= 7.0.3.6 [beta] (November 4th, 2021) =

# Enhancement
* Microthemer's stylesheet can now be loaded in the footer.

# Change
* New icon for "Re-target selector".
* Added chevron to media query icon to indicate it can be clicked for more options.

# Bugs fixed
* Compatibility issue with servers that disallow static HTML files - a 403 error was briefly displayed before the site preview loaded.
* Microthemer reset the default width for the current tab when switching property group, rather than keeping a custom width set by dragging the slider.
* Default WordPress form styling was overriding Microthemer's styling on some versions of WordPress.
* Legacy media queries were too long to be shown in the allocated space without the scroll arrows.
* Loading Oxygen via the Integrations menu had a false positive warning "Page not editable with Oxygen".
* Microthemer was not turning off targeting mode automatically when enabling a page builder with deep integration (Elementor, Beaver Builder, Oxygen) if it was activated via the WP toolbar menu, rather then the switch in Microthemer.
* When leaving an unsaved Oxygen page, Microthemer did not release the Oxygen post lock when exiting via the Integrations menu switch. This causes issues for re-enabling Oxygen (an MT interface refresh was needed).

= 7.0.2.7 [beta] (November 2nd, 2021) =

# Enhancement
* The loading order of Microthemer's stylesheet can be customised via the preferences.
* The prefix used for page specific body classes can now be customised. And the default value (for new installs only) is 'mtp' rather than 'mt'. This change is due to a conflict with tailwind and boostrap with use e.g. .mt-4 to set margin-top, which could result in unwanted top margin on the body element.

# Bugs fixed
* Enabling Sass resulted in an error.
* 'Fold widget' error when Microthemer was loading if cursor position was left at the end of the editor.
* The code editor was flagging CSS stroke properties as unknown.

= 7.0.2.2 [beta] (October 20th, 2021) =

# Change
* Top bar layout, reusing some patterns from version 6 to create more space.
* The 'S' preset view keeps the device tabs at the top by default.
* Unlock system referencing 'License key' rather than email or unlock code. But users can continue to use what ever is displayed in 'My Downloads'.

= 7.0.1.9 [beta] (October 11th, 2021) =

# Bugs fixed
* The low/high CSS specificity toggle always showed as low when the interface first loads.
* Clearing the suggested code field deselected the current element in the HTML pane.
* Holding down the ALT key when hovering over a CSS property field still scrolled the page.
* Using the ALT triggered the tools menu in Firefox.
* The light grey computed CSS overlaying the CSS property fields did not update when selecting elements if the inspection tools were not expanded.


= 7.0.1.4 [beta] (October 5th, 2021) =

# Bugs fixed
* Microthemer's HTML inspection pane could become out of sync if the page contained HTML comments.

= 7.0.1.2 [beta] (October 4th, 2021) =

# Bugs fixed
* Draft mode was not always on by default when upgrading existing versions of Microthemer (previously running version 6).

= 7.0.1.1 [beta] (September 30th, 2021) =

# Bugs fixed
* Occasionally Microthemer's dropdown menus could display with default jQuery UI styling.
* The selector label in the top toolbar could occasionally be cropped by 1 or 2 pixels.
* When toggling full height for the left or right sidebars, the HTML pane did not adjust the flow of text properly.

# Change
* Replaced 'Specificity' label on targeting options with a more detailed tooltip.
* Any field that contains selector code text matches the lighter color used for the code field in the top toolbar.

# Enhancements
* Inspect panels adapt to smaller screen sizes.
* Min-width of Inspect panels reduced.
* Color and background-color are included in key computed CSS info.
* On initial install, Microthemer defaults to the small (S) layout preset if a screen size of 796 or below is detected.

= 7.0.0.4 [beta] (September 28th, 2021) =

# Bugs fixed
* Oxygen and other page builders did not load when the detach preview feature was enabled.
* On small screens, panel resizing was not possible.
* On small screens, multiple left columns were not supported.

= 7.0.0.0 [beta] (September 24th, 2021) =

* Initial release of version 7 beta. See 7.0.5.0 for a summary of the new features.

# For version 6 and older changelogs visit: https://themeover.com/changelog-6-x/