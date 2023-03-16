# Changelog
All notable changes to the OptinMonster plugin will be documented in this file.

### 2.13.0 - 2023-03-10
* Introduce integration support with MemberPress!
* Improve compatability with LearnPress LMS plugin
* Fixed issue where errors could appear on the campaign output settings page preventing output settings from being edited.
* Fixed an issue where campaign output settings could disappear making if difficult to edit the output settings.
* Various npm package updates used for generating our JavaScript code.

### 2.12.2 - 2023-02-10
* Security update to ensure OptinMonster shortcodes can only load from the campaign post-type.

### 2.12.1 - 2023-02-03
* Fixed Template preview button text stuck on "Loading Preview".

### 2.12.0 - 2023-02-02
* Fixed wp_enqueue_script error on widgets page.
* Addresses issues with page caching by fetching rules data for Easy Digital Downloads and WooCommerce via ajax.
* Small improvements to onbaording
* Updated bundled version of Vue and related dependencies to address some security scanners.
* Introduced Playbooks to the plugin

### 2.11.2 - 2023-01-18
* Fixed issue where plain-text shortcode output could be parsed by search engines.
* Set minimum Elementor support to 3.1.0, and fix deprecated function warnings.
* Update code initialization logic to allow just-in-time loading and preventing some errors.
* Various npm packages updates used for generating our JavaScript code.
* Remove references to Bronto, as Bronto shutdown as a service.
* Fix issue where inline campaigns would be could show excerpts.

### 2.11.1 - 2022-11-29
* Fix occasional undefined variable warning in logs
* Maintenance updates to some JS packages
* Removed some unused development files
* Improve date-oriented rules
* Improved performance on notifications
* Improved some of the Output Settings labels to be more accurate.

### 2.11.0 - 2022-11-04
* Added new `optinmonster_prevent_all_campaigns` filter to allow preventing campaigns in custom conditions.
* Updated the schedule link to actually display the campaign schedule feature.
* Fix issue with WP Forms event listener when our api script loaded later.
* Fix issue where a conversion was registered despite WP Forms recaptcha error.
* Fix issue where plugin admin page requests may fail for accounts with large numbers of campaigns.
* Various npm package updates.
* Added new menu item.

### 2.10.0 - 2022-09-13
* Update/improve the campaign auto-insertion (after X words/paragraphs) feature.
* Fix issue where WP Forms datepicker could sometimes be hidden behind the campaign.
* Fix display of the Quick Links widget for RTL viewers.
* Added filter for defining the post types that will work with the auto-insertion feature.
* Introduced some tools to improve compatibility with WordFence.

### 2.9.0 - 2022-08-11
* Introduce integration support with WPForms! Now WPForms forms will be able to be embedded within campaigns, and conversions/success tracked.
* Minification to our frontend JS helper file.

### 2.8.1 - 2022-07-21
* Fix issue with Ecommerce Output rules being applied even when the Ecommerce was not connected anymore.
* Fix issue with `optin-monster-inline` shortcode not working in many cases.
* Fix issue in built JS files which caused them to be falsely-flagged in virus software.
* Updated build script to keep file-names consistent when possible.

### 2.8.0 - 2022-07-18
* Introduce integration support with Easy Digital Downloads!
* Fix issue with the OptinMonster "Disable All" setting in block editor when custom fields not supported for the post-type.
* Fix issue where "This account does not have any campaigns to retrieve" error would show incorrectly.
* Fix issue with output settings link having an incorrect trailing slash sometimes causing 404s.
* Code refactor and function/method deprecations.

### 2.7.0 - 2022-05-17
* Introduce revenue attribution support for EDD and WooCommerce, and add revenue attribution settings
* Format dates for subscriber information according to the WordPress site timezone
* Fixed some broken template image icon urls
* Fix broken utm_medium query args for some urls
* Javascript package updates

### 2.6.12 - 2022-03-23
* Improved support for ecommerce display rule targeting.
* Improved goal support during onboarding.
* Added new links to quickly create campaigns from the dashboard.
* Improve display of new campaigns by prioritizing Featured templates.

### 2.6.11 - 2022-02-15
* Fixed an issue where inline campaigns were not being output on the front-end of the site.

### 2.6.10 - 2022-01-28
* Updated email providers in the onboarding wizard.
* Added support for Gutenberg's blocks.json when registering our Campaign Selector block.
* Fixed display of an authentication error for non-authentication errors.
* Fixed error that can occur during plugin upgrade processes.

### 2.6.9 - 2021-11-30
* Fixes a scenario where non-inline campaigns could be prevented from showing if inline campaigns are present.

### 2.6.8 - 2021-11-16
* Security hardening.
* Fixed some output settings not working properly for shortcodes.
* Address some confusion by updating shortcode output to have the `followrules` attribute on by default for new shortcodes, or to use the `optin-monster-inline` shortcode.
* Fixed display of redundant errors in the OptinMonster settings pages.

### 2.6.7 - 2021-11-03
* Fix issue with Visual Composer when monsterlinks are not available to site.
* Fix issue with tooltip not showing for Site Settings integration default setting.

### 2.6.6 - 2021-10-27
* Fix distinction between pages/single pages (as there is no page archive).
* Fix UX issue where clicking the around checkboxes in output settings would sometimes result in the wrong field being checked.
* Fix issue where Monster Links feature shown when not applicable.
* Clean up output settings description in block editor sidebar.
* Improve API requests by caching the results, where applicable.
* Fixed styling for Gutenberg block on newer versions of WordPress.
* Fixed Gutenberg Monster Link formatting errors when no text selected, on newer versions of WordPress.
* Fixed errors for requests on the University page.
* Fixed issue/conflict when site had a taxonomy registered with the slug of "categories".
* Fixed "non-static method cannot be called statically" notice.

### 2.6.5 - 2021-10-06
* Security hardening, and improved notifications.

### 2.6.1 - 2021-09-08
* Fixed: Security hardening for campaign previews.
* Updated the notification-fetch logic to not happen on every admin page-load.
* Updated the review request notice to only shown when significant milestones are met.

### 2.6.0 - 2021-08-10
* Fix broken integration images for a few integrations.
* Improved rules debug output for support.
* Fixes for errors found in WordPress 4.8.
* Fix onboarding issue where other plugins would redirect to their welcome pages.
* Other syle improvements to the onboarding process.
* Fix issue with onboarding process not being able to reopen the app's registration-completion window.
* Add redirect to welcome page for when plugin is first installed.
* Fix issuer where the site's default integration setting would not load the available options.
* Fix styles for setting descriptions in site settings.

### 2.5.2
* Security hardening.

### 2.5.1 - 2021-07-22
* Fix issue with adblockers causing integration images not to be shown.

### 2.5.0 - 2021-07-21
* Fix the Shareable MonsterLink URL for campaigns.
* Updated integration provider logo images, and prepared for new integrations.
* Improved workflow for Onboarding Wizard.
* Prevent wizard keyboard navigation when in input/textarea/form-element.
* Added filters to many of the script-tags we output.

### 2.4.2
* Security hardening.

### 2.4.1 - 2021-06-25
* Fix "Cannot read property 'isDevelopment' of undefined" when connecting woocommerce.
* Fix static modal positioning so page can scroll (when plugin has not been connected yet).

### 2.4.0 - 2021-06-24
* Introduce Integrations page to manage and add email integration services, Monster Leads settings, webhooks, zapier connections, etc.
* Improved UX for select elements where posts/pages were displayed by adding the post/page ID to the label.
* Addressed some UI issues for RTL language mode.
* Updated dependencies in the javascript stack.
* Removing extra "This account does not have any campaigns to retrieve" error.
* Fixed an issue that could cause the Editor to break on Wordpress versions < 5.3.
* Update shortcodes to handle the id paramater for back-compat.
* Update classic editor shortcode button to use the slug parameter.
* Fix bug causing "To receive the requested features, you will need to upgrade to Pro" notice to show, even after upgrading.
* Fix preview-campaign not working when site is using OptinMonster custom domains.
* Improved information in the support data output.

### 2.3.4
* Security hardening.

### 2.3.3 - 2021-05-12
* Prevent autoloading WooCommerce classes. Fixes error when Jetpack is active while activating WooCommerce.

### 2.3.2 - 2021-05-05
* Fixed an issue where the editor would not work in WordPress < 5.3.

### 2.3.1 - 2021-04-02
* Fixed issues when multiple tinymce instances existed on a page (specifically, the double OptinMonster link button).
* Fix console/blocking errors JS errors because specific data was missing on the page related to the OptinMonster Monster Link buttons.
* Fix height of link search results when other fields added.
* Fix errors that can occur with WooCommerce data-store failures.

### 2.3.0 - 2021-04-01
* Introduce the Personalization page, for documenting available rules/triggers.
* Introduce functionality for Classic Editor (and classic editor instances) for inserting inline campaign shortcodes, or adding Monster Links to text.
* Improvements to the Gutenberg Block.
* New setting for globally disabling campaigns for a given post/page/etc (Gutenberg sidebar setting, and a fallback settings metabox for the Classic Editor). Also adds error boundaries around all campaigns in the Gutenberg editor if this option is selected (since they will not work on the frontend).
* New Gutenberg text formatting option for adding Monster Links to text.
* Improved UX for select elements where campaigns were displayed by adding the campaign slug to the label.
* Better error handling and output when certain API requests fail.
* Better error handling and UX when user's site domain has changed (e.g. from a temp domain to the permanent one).
* Introduced caching for various requests to improve plugin page performance.
* Added helpful title attribute tooltips for the various options in the Output Settings (displaying the term slug and the associated taxonomy slug, etc).
* Improved various other tooltips on the Output Settings to be more helpful
* Improved UX for select elements where taxonomy terms were displayed by adding the term slug to the label.
* Add singular post-type options "Show on Post Types and Archives" output settings.
* Improved messaging in various errors.
* Fixed bug where exiting and then re-entering output settings, the advanced settings would disappear.
* Fixed conflict when BigCommerce plugin installed, triggered by their admin scripts.
* Fixed php warning, "strpos(): Empty needle in optin-monster-wp-api/OMAPI/Inserter.php..."
* Fixed bug where the "Product Archive Page (shop)" output setting option was only visible for inline campaigns.
* Fixed bug where output settings would conflict if a post and category had the same ID.
* Fixed `WP_Scripts::localize` deprecation warning by switching to `wp_add_inline_script()` where applicable.

### 2.2.1
* Security hardening.

### 2.2.0 - 2021-02-18
* Added Elementor Block and other integration.
* Added WooCommerce Integrations.
* Added features to onboarding flow.
* Fixed issue with search "X" button.
* Fixed issue with selecting posts in the campaign output settings.
* Additional fixes to the University page responsive styling.
* Fixed issue with campaign shortcode storage
* Improved live preview/rule preview for campaigns with shortcodes.
* Fix to put campaigns in preview mode when in the WordPress customizer preview or post preview.
* Fix PHP notices for using `$_SERVER['HTTP_REFERER']` when it doesn't exist.
* Fix some performance issues by only performing `wp_update_post` if the synced campaign contains changes.
* Fix help link in Gutenberg Block's sidebar settings.
* Fix help link in Gutenberg Block when no campaigns have yet been created.
* Added additional filter for filtering campaigns to embed on the frontend.

### 2.1.2
* Security hardening.

### 2.1.1 - 2021-01-20
* Notifications improvements.
* Better handling to prevent Gutenberg block from using same inline slug multiple times (which does not work).
* Fix output settings link in Gutenberg sidebar not working.
* Fixes University responsive styling.
* Code cleanup

### 2.1.0 - 2021-01-14
* Introduce Subscribers page to manage Monster Leads for your WordPress site, with helpful analytics data, graphs, management, and export capabilities.
* Introduce the OptinMonster University page.
* Various help-text improvements, and fixed typos.
* Bug fixes, and error output for campaign-status changes.
* Better alert output.
* Better notification output, improving visibility/functionality.
* Improved communication around connection process.
* Include javascript source map files in build to prevent console notices.
* Improved account-upgrade workflow.
* Improved request performance on campaigns page.

### 2.0.4
* Security hardening.

### 2.0.3 - 2020-12-07
* Updates the "get started" interface to be more intuitive for existing users.
* Remove incorrect concept of "pending" for split tests.

### 2.0.2 - 2020-11-24
* Include the JS source map files in the release to prevent unnecessary 404s in the dashboard.
* Use `POST` request to save campaign output settings, since some servers don't like `PUT` requests.
* Updated dependencies.
* Better cache-busting for js files via file-name changes with new builds.
* Fixed typos.
* Move constants-setting to separate method, add a hook for just-in-time constants-setting.
* Update our Amp checks to run at the correct hook, to prevent php notices in debug logs.
* Better UI when connecting/disconnecting, showing loaders/success alerts, even while page is refreshing.
* Improved alert notifications when actions fail in the Campaigns dashboard.
* If campaign-status setting fails, output errors, and reset status to previous setting.
* UI fixes/improvements.
* Ensure campaign-creation errors are displayed on the Templates page.
* Update description around site cookie settings.

### 2.0.1 - 2020-11-16
* Bug fixes and adjustments for compatibility with older versions of PHP.
* Bug fixes related to wildcard domains and subdomains.

### 2.0.0 - 2020-11-16
* NEW: Overhaul of the plugin to make managing your popup campaigns easier than ever!
* Added the ability to see all your popup campaigns in your dashboard (draft, pending and published)
* Added a new dashboard to see stats and details about your popup optins
* Added the ability to see all popup templates and create new popup campaigns from within the plugin
* Added a new menu link to see all your popup subscribers
* Added the ability to create popup split tests from within the plugin
* Improved the popup output settings for each individual popup campaign
* Many other performance improvements, product enhancements and bug fixes to the plugin

### 1.9.17
* Fixed a bug where taxonomy settings may not properly display.
* Fixed an error that could occur when non-admins logged in.

### 1.9.16
* Fixed a bug where category settings may not properly display.

### 1.9.15
* Fixed a bug that caused issues for non-admin users when the plugin was not yet connected to an OptinMonster account.

### 1.9.14
* Fixed a bug that occasionally prevented changes in campaigns via the OptinMonster App to not properly sync to the plugin, due to cached responses.

### 1.9.13
* Fixed a bug that caused campaigns to be incorrectly referenced in the admin dashboard.

### 1.9.12
* Fixed a bug where the `Access-Control-Allow-Headers` was being improperly reset for REST requests.

### 1.9.11
* Fixed a bug that caused too many redirects in the admin when clicking on certain plugin settings links.
* Removed a plugin action link that was not used.

### 1.9.10
* New Gutenberg block for embedding inline campaigns.
* Improved syncing of data between the OptinMonster app and the WordPress plugin to help with shortcode parsing, and adding/removing campaigns when going live or pausing. Will also retain previously saved Output Settings when un-pausing a campaign and refreshing.
* Improvements to a11y across the plugin's admin pages.
* Updated Constant Contact branding
* Updates to improve performance on the plugin's admin pages.
* Minified assets to improve performance on the plugin's admin pages.
* Increase timeout time for the WooCommerce auto-generate keys request to accomodate for some servers.
* Clean up admin notices when on the OptinMonster plugin's admin pages.
* New About Us page.
* Added filter for auto-enabling new campaigns (true by default).
* Added filter for changing the API enqueue location from the default footer.
* Added plugin settings menu links to plugin action links in the Plugins table.
* Improvements to the Welcome page to allow connecting account directly.
* Improvements to the account-connection process.
* Improvements to the Campaigns page.
* Improvements to the Campaigns' Output Settings pages.
* Fix bug with mailpoet phone number possibly being set to 0.
* Fix bug to allow campaigns to show on categories, even when the category is registered to a non-"post" post-type.
* Fixed potential issues with storing our embed code for multisite installations.
* Fixed bug where cookie-delete button was not deleting all OptinMonster cookies for given user.
* Improved WooCommerce connect screen by showing the auto-generate option by default.
* Removed duplicate WooCommerce categories/tags from the Output Settings.
* Various other bug fixes, and performance updates.
* Improved debug output.

### 1.9.9
* Fix issue where if multiple post tags were selected, popups and other campaigns would only appear on the first tag selected.
* Fix campaign shortcode suggestion in admin being incorrect.
* Full security audit to patch any potential issues.

### 1.9.8
* Fix compatibility with AMP.
* Update compatibility with popular caching plugins.
* Update to make all strings translatable.
* Fix bug where phone numbers wouldn't save when using MailPoet.
* Remove old jQuery dependencies.
* Update internal notices to be more friendly with other plugins.

### 1.9.7
* Update the OptinMonster API JS URL.
* Update trustpulse menu title.

### 1.9.6
* You can now use Gravity Forms AJAX submissions and form validation with your OptinMonster campaigns.
* Update admin notices to use the recommended classes

### 1.9.5
* Add support for www domains in Api.js embed code.
* Improve MailPoet error outputs.

### 1.9.4
* Fix issue where site settings were not being retrieved properly.

### 1.9.3
* Additional improvements to output of Api.js URL in embed code.

### 1.9.2
* Improve output of Api.js URL in embed code.

### 1.9.1
* Fix issue where closing Cyber Monday notification would not prevent it from showing again.

### 1.9.0
* Improves compatibility when WordPress is installed in a subdirectory or uses multisite with paths.
* Bump the minimum, required, version of WooCommerce to 3.2. Any installs below this version will not have WooCommerce support.
* Address some incompatibilities with the MailPoet plugin.
* Includes some notifications regarding holiday/sale promotions.

### 1.8.4
* Minor update: Added a new filter for action links.

### 1.8.3
* Improved logic to prevent welcome screen from showing in the wrong context.

### 1.8.2
* Fix issue where the WooCommerce cart object wasn't always available.
* Fix issue where top floating bars would cover the WP admin bar for logged in users.

### 1.8.1
* Fix issue with backwards compatibility with PHP 5.4 or lower, and WordPress 4.0 or lower.

### 1.8.0
* New campaigns that are fetched from OptinMonster will be enabled by default.
* API Keys can now be added with a click-based authentication flow
* Add a REST API endpoint that can be used to refresh campaigns
* Fix issues where the OptinMonster campaign preview wouldn't load if the campaign was not already active.

### 1.7.0
* Add additional WooCommerce support.

### 1.6.9
* Fixed an issue where saving to MailPoet may fail on pages where only shortcodes are used to embed campaigns.

### 1.6.8
* Fix issue with backwards compatibility with PHP 5.3 or lower.

### 1.6.7
* Fix issue with backwards compatibility with PHP 5.4 or lower, and WordPress 4.0 or lower.

### 1.6.6
* Fixed an issue where campaign refresh would deactivate live campaigns, and remove their settings

### 1.6.5
* Users who have not entered an API key into will now be redirected to the OptinMonster welcome page instead of the OptinMonster settings page
* Added a pointer to the Admin Dashboard if an API Key is not entered
* Added pagination to the API requests when refreshing campaigns
* Additional fixes for future improvements to OptinMonster

### 1.6.4
* Updated the API domain URL.

### 1.6.3
* Improved searching when adding advanced rules for posts/pages/tags.
* Add `optin_monster_pre_store_options` filter to allow users to override which campaigns are imported.

### 1.6.2
* Fix issue where the "Automatically add after post setting" was not working properly after changes in 1.6.0.

### 1.6.1
* Fix dashboard notice showing at incorrect times.

### 1.6.0
* Add widget option, "Apply Advanced Output Settings?". If checked, widget will follow the advanced settings rules for the campaign (found in the Output Settings for the campaign).
* Fix bug where advanced settings would not apply to inline after-post campaigns.
* Update the inline/automatic setting language to make the new behavior more explicit.

### 1.5.3
* "Display the campaign automatically after blog posts" setting no longer selected by default for inline campaigns.
* Fix inline campaigns showing in some scenarios, even when "Display the campaign automatically after blog posts" is NOT checked.

### 1.5.2
* Fixed potential privilege escalation bug.
* Bumped for 5.0.

### 1.5.1
* Fixed a possible security issue with admin notices.
* Updated outdated URLs in the admin.

### 1.5.0
* Refactored WordPress rules system, and a new `[optin-monster]` shortcode parameter, `followrules=true`. This means if you have specific WordPress display rules (e.g. which categories/posts/pages to display the campaign), and use the shortcode to output the campaign, you can have the shortcode follow the rules you have setup. Example shortcode usage: `[optin-monster slug="XXXXXXXXXXXXXXXXXXXX" followrules=true]`

### 1.4.2
* Fixed a bug that caused issues with PHP versions under 5.6.

### 1.4.1
* Include a file that was missing in 1.4.0. Sorry!

### 1.4.0
* Updated to work with OptinMonster 5.0 campaigns.
* Fix PHP notices.

### 1.3.5
* Fix issue where shortcodes in campaigns would not be parsed until the campaigns were refreshed a second time.

### 1.3.4
* Updated the API url to reflect the new endpoint.

### 1.3.3
* Fixed an issue that prevented campaigns from showing on some custom taxonomy terms.
* Performance improvements when retrieving, and determining when to display, campaigns.
* All URLs updated to use HTTPS.
* Updated notifications.

### 1.3.2
* Fixed issue where campaigns of an "advanced age" may not work in the plugin.

### 1.3.1
* Fixed missing files in WordPress.org repository.

### 1.3.0
* Is it "campaign"? Or "optin"? No, it's definitely "campaign".
* OptinMonster now works with the shiny new MailPoet 3.
* We're feeling a little lighter after removing some deprecated code.

### 1.2.2
* Updated API calls to always be done over HTTPS.
* Updated error responses from the OptinMonster API to be more informative.

### 1.2.1
* Added additional checks during save routines for user capabilities.

### 1.2.0
* Added additional support for WooCommerce display settings.
* Updated language for legacy migrations.
* Fixed a multisite activation issue.

### 1.1.9
* Updated version numbers to prevent possible asset caching errors.

### 1.1.8
* Fixed possible undefined errors for API credentials.

### 1.1.7
* Updated the API script domain for adblock.
* Added new authentication method for the new OptinMonster REST API.

### 1.1.6.2
* Fixed undefined index errors when API responses returned an error.

### 1.1.6.1
* General plugin enhancements and bug fixes.

### 1.1.6
* Compatibility updates for WordPress 4.7.

### 1.1.5.9
* Added the async attribute to the OptinMonster API script output for improved performance.
* Fixed a bug that caused the debugging report to not properly grab shortcodes.
* Added helper to remove faulty admin scripts from the OptinMonster settings area that would cause things to fail in some cases.

### 1.1.5.8
* Fixed bug that caused the MailPoet integration to fail in some scenarios.

### 1.1.5.7
* Improved checks for when to output and localize the OptinMonster API script.

### 1.1.5.6
* Fixed bug that caused people to have to define two constants to set the OptinMonster license key in config files.

### 1.1.5.5
* Fixed bug that redirected people already using the plugin to the Welcome screen on update.

### 1.1.5.4
* Fixed bug that caused issues with viewing the Welcome screen.

### 1.1.5.3
* Fixed issue with notices appearing oddly on OM screens.
* Updated support video.

### 1.1.5.2
* Fixed bug with post category selections causing campaigns to load globally.

### 1.1.5.1
* Improved welcome screen for new installs.
* Bug fixes and enhancements.

### 1.1.5
* Campaigns will now load on the archive pages of individual taxonomies (if selected) by default.
* Clarified language regarding how the "load exclusively on" and "never load optin on" settings work.
* Removed after post optins from RSS feeds.
* Removed the test mode setting in favor of using the "show only to logged-in users" setting for testing campaign output.
* When going live, campaigns will load globally by default unless other advanced output settings are specified.
* Automatically adding an after post optin after a post is now checked on by default for new after post campaigns.
* Added a new "Support" tab with a helpful video, links to documentation and ability to send support details when submitting a ticket.
* Migration tab is now only shown if the old plugin exists on the site.
* Added helpful tooltips in various areas of the admin.
* Moved all advanced output rules into a toggle field to make working with output settings easier.
* Fixed the clear local cookies function (it actually works now!).
* Removed the confusing Delete button - campaigns should be deleted from the app.
* Added an inline shortcode "copy to clipboard" button for after post campaigns.
* Improved shortcode processing - it is now automated (no longer need to enter in a setting) and supports non self-closing shortcodes!
* Improved individual campaign action links by always making them visible.

### 1.1.4.7
* Updated compatibility for WordPress 4.6.

### 1.1.4.6
* Removed shortcode ajax method that could possibly be exploited by other plugins to run malicious shortcode.

### 1.1.4.5
* Added new feature to allow reviews to be given for OptinMonster.

### 1.1.4.4
* Allow API credentials to be force resaved to clean out stale messages about accounts being expired or invalid.

### 1.1.4.3
* Fixed API script getting cached by CloudFlare Rocket Loader.
* Fixed omhide=true conflicting with MonsterLinks in some cases.
* Fixed pre 4.1 installs getting incorrect API ID.
* Updated Readme so OptinMonster App and account requirement is clearly stated.

### 1.1.4.2
* Added Welcome page on first install.
* Updated error messages.
* Updated debug code for better error handling.

### 1.1.4.1
* Added No-Cache headers on API requests.

### 1.1.4
* Fixed bug with adblock.
* Added new API script with easier updates.

### 1.1.3.9
* Fixed conflict with jQuery and Modernizr when the optin object was not set properly.

### 1.1.3.8
* Fixed bug with canvas slide-in not being able to be closed.

### 1.1.3.7
* Fixed issue with contact forms not displaying properly in optins. [See this doc on how to update shortcode support in your optins.](https://optinmonster.com/docs/how-to-use-wordpress-shortcodes-with-optinmonster/ "How to use WordPress shortcodes with OptinMonster" )

### 1.1.3.6
* Fixed possible issue with sending empty names that caused bugs with provider integrations.

### 1.1.3.5
* Fixed JS error with analytics if GA was not yet defined.

### 1.1.3.4
* Fixed bug with analytics tracking causing user sessions to be skewed.
* Fixed bug with fullscreen optins and mobile optins conflicting.
* Mobile optins now work for both mobile and tablet devices. Desktop optins work exclusively for desktop.
* Various bug fixes and improvements.

### 1.1.3.3
* Fixed bug where fullscreen wouldn't work on mobile if exit intent setting was checked.
* Fixed bug with analytics not tracking if multiple spaces were contained in a campaign name.
* Fixed bug with clearing local cookies not working in some instances.

### 1.1.3.2
* Fixed bug where shortcode would not parse for optins inserted via widget, shortcode or template tag.
* Fixed bug where Mailpoet helper would not output for optins inserted via widget, shortcode or template tag.

### 1.1.3.1
* Fixed issues revolving around split tests not loading properly for mobile devices.

### 1.1.3
* Fixed bug with freezing and not working in IE10/11.

### 1.1.2.7
* Fixed erroneous alert on screen.

### 1.1.2.6
* Fixed bug with lightbox and mobile optins in API script.

### 1.1.2.5
* Fixed bug with GA not tracking data.
* Added 13 new mobile themes!

### 1.1.2.4
* Fixed bug with cookies and split tests.
* Fixed bug with allowing split tests to be made primary.

### 1.1.2.3
* Added support for a new optin type - fullscreen optins!
* Fixed a bug with embedded HubSpot forms.
* Fixed bug where dropdown options would not show on Safari for post targeting.

### 1.1.2.2
* Fixed issue with API script not grabbing checkbox and radio fields properly inside an optin.

### 1.1.2.1
* Fixed issue for defining API url with function before filters can be applied to it.

### 1.1.2
* Fixed display error when multiple taxonomy terms were selected for an optin.
* Added selection of scheduled posts in optin output settings.

### 1.1.1
* Added option to move floating bar to top of the page. No custom CSS needed!
* Added option for a privacy statement below optin form.
* Added option to exclude by page slug
* Shortcode parsing now available for all optin types.
* Various bug fixes

### 1.1.0.5
* Added ability to pause parent campaigns from the app.

### 1.1.0.4
* Fixed bug that caused paused split tests to continue to run.
* Fixed bug with passing optin data to a redirect URL with query args.
* Added ability to submit lightbox optin forms with the enter button.

### 1.1.0.3
* Fixed bug that caused site verification to fail.

### 1.1.0.2
* Added support for assigning multiple domains to a single optin.
* Added unique optin slug on Overview screen to make life easier.

### 1.1.0.1
* Fixed fixed bug with bounce rate in GA.

### 1.1.0
* Fixed focus bug.

### 1.0.0.9
* Fixed analytics bug that caused bounce rates to go whacky in GA.
* Fixed "powered by" link placement when using display effects.
* Added focus effect for input fields when an optin is loaded.

### 1.0.0.8
* Clear out global cookie when clearing local cookies.
* Fixed bug with not loading in IE7-9.
* Fixed bug with placeholder shims not working in IE7-9.
* Fixed bug with GA clashes when using multiple tracking scripts on a page.

### 1.0.0.7
* Fixed bug with possible duplicate submissions in some configurations.
* Added enhanced conversion tracking with GA.

### 1.0.0.6
* Added a dedicated edit output settings link for each optin.

### 1.0.0.5
* Fixed bug with passing lead data to redirect URLs.
* Added improved UX by being able to create and edit optins from the plugin itself.

### 1.0.0.4
* Fixed another error with plugin update deploy.

### 1.0.0.3
* Fixed error with deploy.

### 1.0.0.2
* Fixed bug with API script.

### 1.0.0.1
* The "Go Live" link now enables an optin and sets the global/automatic loading setting as well.
* Fixed bug with not being able to uncheck clearing local cookies on optin save.
* Added extra XSS security checks with `esc_url_raw`.
* Added version number beside plugin header title for easy version checking.

### 1.0.0
* Fixed bug with exclusive/never settings not showing previously selected pages.
* Fixed bug with API script and loading social services for specific popup types.
* Removed unused updater class reference and code.

### 0.9.9
* Fix error with loading old API script.

### 0.9.8
* Initial release.
