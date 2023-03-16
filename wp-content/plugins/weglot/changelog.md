<!-- logo -->
<img src="https://cdn.weglot.com/logo/logo-hor.png" height="40" />

# Change Log

##3.9.1 (30/01/2023) =
* Bug: add english in destination language list during installation if original language is different
* Update: Prevent using empty .json settings file during install

##3.9 (17/01/2023) =
* Update: Add whitelist mode
* Update: Add notices messages if other translate plugin is active
* Update: Add index text for translate ldjson
* Update: Translate pdf from Germanized pdf plugins
* Bug: Fix cookie issue with WP Rocket (mandatory cookies)
* Bug: Check if index query exist before use it
* Update: Fix wp vip code issue (wp parse url instead of native parse_url)
* Update: Check WPLANG on first install instead of put 'en' by default
* Bug: Add filter to prevent issue with Gform upload input ajax
* Bug: Fix issue with FluentCRM, WP social Ninja and Fluent Support

##3.8.3 (15/11/2022) =
* Bug: Prevent Ajax call from original lang to original lang
* Bug: Fix PHP Warning on class-replace-url-service line 192
* Update: Add index 'text' to ld+json translated value

##3.8.2 (08/11/2022) =
* Bug: Update cdn url for pageviews replace cdn-api-weglot.com by cdn-api.weglot.com

##3.8.1 (18/10/2022) =
* Bug: Default is_rtl value for custom_languages
* Bug: Update render button for gutenberg editor
* Bug: Improve UI for adding excluded block
* Add: Update assets for wordpress.org

##3.8 (11/10/2022) =
* Add: Add ajax checker for woocommerce variations cart popin
* Add: Rework switcher. Generate it direclty on render method
* Add: Add vary header accept language on redirect
* Bug: Fix rtl issue
* Bug: Add missing ; on pageviews script
* Bug: Send code lang instead of name for wp search query
* Bug: Don't translate pdf on original language (woocommerce PDF invoice plugin)
* Bug: Prevent add twice weglot_language post meta on woocommerce order
* Bug: Prevent block translate if ajax referer are not exclude

##3.7.4 (19/09/2022) =
* Bugfix: Update CA Root Certificates from Mozilla

##3.7.3 (05/07/2022) =
* Bugfix: Remove mod_rewrite check with apache_get_module()
* Bugfix: Fix problem with empty switcher from switcher editor
* Bugfix: Compatibility with gravitform upload input

##3.7.2 (20/06/2022) =
* Bugfix: Fix bug between apache_get_module() and wpengine.com
* Add: Add changelog file into plugin directory

##3.7.1 (15/06/2022) =
* Add: Disable translate pdf and add filter to activate ite

##3.7 (08/06/2022)
* Add: Translate pdf service
* Add: Optimize button accessibility
* Add: Pageviews integration
* Add: Add switcher editor integration
* Add: Woocommerce translate all mail
* Add: Do not translate .eps or .txt
* Add: Detect if switcher is child of an iframe and if so, don't display it
* Add: Reduce api call on wp-admin

##3.6.1 (02/03/2022)
* Bugfix: Fix hide button option on switcher menu
* Bugfix: Fix bug VE not translated
* Bugfix: Fix bug add Custom full name for orginal language

##3.6 (23/02/2022)
* Add: Advance exclude url option
* Add: Add blocks for gutenberg (wp 5.9)
* Add: Disable autoswitch for weglot visual editor
* Add: Add Forminator (plugin de WPMUDEV) compatibility
* Add: Disable weglot on rankmath sitemap
* Add: Add hook filter to replace_url method
* Bugfix: php8 parameters order
* Bugfix: Refresh destination language list
* Bugfix: Display hreflang even url have parameters

##3.5 (07/12/2021)
* Add: Autoswitch work on all page not only on homepage
* Add: Exclude url doesn't generate redirection
* Add: Add switcher from switcher editor
* Add: Call API from cdn to translate
* Add: Limited call API for deactivate account
* Bugfix: Problem with gform and multisite
* Bugfix: Fix hreflang generation with Cyrillic url
* Bugfix: Weglot search option now works even if we're not on a main_query

##3.4 (15/09/2021)
* Add: All 404 pages are excluded from translation if you exclude /404 in the Weglot dashboard
* Improved performance: Files for multilingual compatibility with other plugins are only called when necessary
* Bugfix: Autoswitch feature now works with custom languages
* Bugfix: Password reset link in translated emails now works
* Bugfix: WooCommerce emails are now translated when using custom languages
* Bugfix: Fixed warning in 404 styles.css.map
* Bugfix: Autoswitch doesn't redirect when visitor comes from an external link from now on

##3.3.6 (15/06/2021)
* Add new flag from dashboard
* Improve hreflang display
* Dynamise limit languages check
* Fix bug when excluded URL /cart gives empty URL
* Fix small bug on multisite where we translated links from / website when located on /subsite
* Better handle 301 redirect

##3.3.5 (12/04/2021)
* Add url from canonical if existing
* Add vip code review
* Increase timeout when updating setting and disable submit button
* Adding message to tell user to purge cache from cache plugin after editing translation

##3.3.4 (22/03/2021)
* Check if curl_exec is enable
* Fix js problem on admin on preview
* Optimize plugin size
* Change screenshot on store

##3.3.3 (08/03/2021)
* Language repo
* drag and drop
* bug greek url
* bug parsing empty node


##3.3.2 (15/02/2021)
* Update settings dropdown
* Fix rare bug when root equal slug page


##3.3.1 (01/02/2021)
* Small fixes following major release


##3.3.0 (18/01/2021)
* Full refacto of the code
* Add: custom language


##3.2.0 (15/10/2020)
* Add translate slug option
* Fix: auto redirect on traditional chinese and brazilian portuguese
* Fix: admin-ajax bug containing language code in some case

##3.1.9 (06/08/2020)
* Add flag choice for ZH and TW
* Update plugin translation files
* Translate Iframe SRC as external link
* Fix: Custom URL links (empty base in correspondence table / trailing slash)
* Fix: Remove "!important" CSS properties on AMP

##3.1.8 (02/07/2020)
* Exclude URL by languages
* Translate by default all Woocommerce mails with customer language
* Update URLs translation, possibility to use custom URLs for hierarchical pages
* Translate External URLs
* Add SVG files to media translation
* Exclude wp-cron.php from translation
* Fix links translation with custom URLS

##3.1.7 (04/05/2020)
* Add a Weglot Menu to admin bar
* Add Woocommerce feature : Translate following mail
* Better text escaping in Back Office (thanks to @joehoyle and @drvy for contribution)
* Add attribute to HTML tag if custom code is used for current language
* Optimize CSS size for AMP
* Fix: Add compatibility with AMP plugin +1.5
* Fix: No load Weglot CSS in AMP if option is set to false
* Fix error on JS script loading (thanks to @joehoyle for contribution)

##3.1.6 (06/02/2020)
* Add: Use WP core code editor for Weglot custom CSS
* Add: Add weglot_translate_email filter to control when mail are translated
* Bugfix: Fixes small minor bugs

##3.1.5 (08/01/2020)
* Add: weglot_language_code_replace filter to use custom language code
* Bugfix: Formatter on JSON source for untranslated WooComerce fields

##3.1.4 (12/12/2019)
* Update back office style for WordPress 5.3
* Fix: Flags SRC attribute with AMP
* Fix: Custom URL feature - Revisions
* Improve compatibility: WP Optimize
* Improve compatibility: Cache Enabler
* Add default exclude block for SecuPress plugin and SQLI protect
* Add default exclude block for plugin query monitor > 3.3.0
* Remove the "Not allowed" mechanism.

##3.1.3 (29/10/2019)
* Improve compatibility: Woocommerce with IE 11
* Improve compatibility: Contact Form 7
* Improve compatibility: MailOptin
* Improve compatibility: The Event Calendar
* Improve compatibility: Font Awesome
* Add default exclude block: address
* Bugfix: Do not cache page if API answers error

##3.1.2 (24/09/2019)
* Bugfix: Custom URL with GET parameters
* Bugfix: Ninja Forms JSON translate
* Bugfix: Prevent errors due to call protected method

##3.1.1 (11/09/2019)
* Add: IE 11 compatibility with languages switcher
* Add: WP-CLI compatibility
* Bugfix: admin api call
* Bugfix: double language when WC + multisite with subdomains
* Bugfix: do not add language on external links also in JSON

##3.1.0 (29/08/2019)
* Add: Better JSON compatiblity
* Bugfix: WC password reset mechanism

##3.0.6 (28/05/2019)
* Add: Compatibility with WP Forms
* Add: Reset postdata filter for custom URLs
* Bugfix: Auto switch fallback
* Bugfix: Custom url on is_front_page

##3.0.5 (22/05/2019)
* Bugfix: Prevent array key exists for Gravity Form
* Bugfix: Save menu Weglot Switcher
* Bugfix: Check DOM on json-ld and inactive by default

##3.0.4 (10/05/2019)
* Bugfix: Prevent errors due to the parser of the JSON-LD

##3.0.3 (09/05/2019)
* Bugfix: Weglot switcher on menu
* Add : Translate all JSON-LD

##3.0.2 (24/04/2019)
* Bugfix: Fixed saving custom CSS
* Bugfix: Auto detection of a bot (google, bing,...)
* Bugfix: Compatibility with caldera forms

##3.0.1 (17/04/2019)
* Bugfix: API key check only if it does not exist
* Bugfix: prevent array_key_exists on private languages for older installations

##3.0.0 (16/04/2019)
* New major version
* Link between WordPress options and Weglot dashboard options
* Bugfix: Fixed an error on the JSON translation

##2.7.0 (18/03/2019)
* Changed : Improve Compatibility with Caldera Forms

##2.6.0 (06/03/2019)
* Add : Prevent elementor ajax action on 2.5
* Add : Compatibility with Caldera Forms
* Add : Prevent ajax MMP Map
* Changed: Improved AJAX translation performance
* Bugfix: No translate link on weglot menu item
* Bugfix: meta og facebook
* Bugfix: prevent undefined index on widget

##2.5.0 (07/02/2019)
* Add : Compatibility with Ninja Forms
* Add : DOM Checker on input type reset
* Bugfix : have the same menu switcher on the same page several times
* Bugfix : Remove no redirect on hreflang
* Improve DOM Checker meta content image

##2.4.1 (09/01/2019)
* Bugfix: undefined function if there is no antislash before the function ( \is_rest )

##2.4.0 (09/01/2019)
* Compatibility PHP 7.3
* Changed : the language selector for menus
* Add : Compatibility with the REST API of Contact Form 7
* Add [BETA] : Be able to translate the keywords of a search
* Bugfix : translation of the empty cart on WooCommerce
* Bugfix: correction of options on a multisite


##2.3.1 (05/12/2018)
* Bugfix : Button preview fail on migration for private mode

##2.3.0 (05/12/2018)
* Bugfix : Custom URL on archive page
* Bugfix : Prevent error on translate AJAX
* Bugfix : Href lang on custom URLs
* Improve code quality
* Compatibility SEOPress : exclude sitemap
* Improve private languages
* Add two DOM checkers

##2.2.2 (05/11/2018)
* Fix bug on change country flag
* Change load custom css inline

##2.2.1 (01/11/2018)
* Fix bug when language was not passed on navigation

##2.2.0 (31/10/2018)
* Added private mode for administrators
* Addition apply_filters
* Bugfix : an ajax request
* Improved compatibility with wpestate
* Compatibility with mega max menu

##2.1.0 (25/09/2018)
* New feature: Custom URL
* Bugfix : Translate AJAX with return JSON on error
* Bugfix : Backslash on function PHP
* Bugfix : Replace links href on JSON translate
* Bugfix : Compatibility with theme use ob_start

##2.0.7 (31/08/2018)
* Bugfix: Ajax load media library
* Improve choice original and destination language

##2.0.6 (29/08/2018)
* Add DOM checker to translate button value and data-value attribute
* Update Weglot Translate setting page
* Bugfix : email translation
* Bugfix : external link with quickpay
* Prevent auto redirect on homepage translate

##2.0.5 (09/08/2018)
* Bugfix : Fatal error if use weglot menu custom

##2.0.4 (09/08/2018)
* Bugfix : lost password email on WooCommerce
* Bugfix : translate custom login page
* Bugfix : uniq id on each button selector
* Bugfix : no translate image on a href html tag with wp-content/uploads src
* Bugfix : admin-ajax url

##2.0.3 (27/07/2018)
* Bugfix : Hide shortcode on non translatable URL
* Bugfix : filter nav_menu_css_class
* Bugfix : Redirect URL on checkout WooCommerce
* Bugfix : CSS Flag on dropdown menu
* Improve AMP compatibility

##2.0.2 (24/07/2018)
* Bugfix : Hide menu on non translatable URL
* Bugfix : Hide widget on non translatable URL
* Improve max file size HTML

##2.0.1 (19/07/2018)
* Improve flag style
* Prevent cURL function
* Solved nav_class warning

##2.0 (18/07/2018)
* Major changes on the plugin architecture
* Adding developer functions & filters
* Refactoring

##1.13.1 (01/06/2018)
* Bugfix: Error on the encoding of ignored nodes

##1.13 (31/05/2018)
* Bugfix : Improve filter words_translate to prevent matching part of words
* BugFix : Bug in parser when ignored node had an attribute
* BugFix : character limit on chinese paragraphs
* Add : Update message for version 2.0

##1.12.2 (04/05/2018)
* Bugfix : Limitation on the number of characters translated at the same time

##1.12.1 (03/05/2018)
* Bugfix : error for users with a version lower than PHP 5.4 . []> array()

##1.12 (03/05/2018)
* Bugfix : undefined index on ajax call
* Bugfix : Redirection checkout payment on WooCommerce
* Bugfix : Register widget
* Add option for AMP compatibility
* Add filter for dynamic string

##1.11 (05/04/2018)
* Add new languages
* Add new filters
* Add Yoast Premium compatibility on redirect
* Bugfix : Exclusion AMP
* Bugfix : Redirection checkout order on WooCommerce

##1.10
* Add new languages + add Oman flag
* Can potentially translate email sent from admin
* Add tags to inline elements to ignore when parsing

##1.9.3
* Remove Freemius

##1.9.2
* Fix Freemius assets

##1.9.1
* Fix Freemius error when changing base dir
* Fix wc translations when special characters.

##1.9
* Fix login redirection
* Add translation for Town, cities and other dynamic fields in WC checkout
* exclude URL now accepts full URL and any blank separator

##1.8.2
* Fix pb when permalinks has no ending slash
* Add notif when plugin is not congigured


##1.8.1
* Fix redirection on woocommerce

##1.8
* Add new banner and icon
* improve wc redirection
* can now translate email


##1.7.1
* Fix redirection bug on cart

##1.7
* Add 6 languages
* Translate microdata
* New element translated

##1.6.1
* Fix url when non standard characters
* change freemius image

##1.6
* Add Freemius
* Refactor code
* Replace api ur
* Add several attributes to translations

##1.5
* Add data-value, data-title, title attribute support
* Add links in readme

##1.4.6
* Add pretty selection of languages
* Improve flags quality

##1.4.5
* Add more i18n luv. Now we speak WordPress
* Add  Dutch, English_UK, German, Italian, Portuguese_BR, Russian and Spanish languages

##1.4.4
* Update i18n and improve strings

##1.4.3
* Compat with WP Fastest cache, improve RTL translations

##1.4.2
* PHP 7 compat, add auto redirect feature, no more FA, no more id on switcher

##1.4.1
* compat AMP, fix url bug on same language code than URL.

##1.4.0
* compat precaching, URLs

##1.3.3
* increase compatibility with other plugins and themes.

##1.3.2
* change support email
* exclude /amp, admin bar
* language on starter plan

##1.3.1
* Fix invalid links
* Handles multiple weglot_here

##1.3.0
* rollbackink parsing lib
* fix srcset, dslash link

##1.2.8
* parsing lib changed
* fix several small bugs

##1.2.7
* Adding Traditional Chinese
* Fix og:url
* scrybs

##1.2.6
* Fix jpeg translated version
* Fix moreclass and wg-notranslate on list button in menu
* Fix ajax json with html in it

##1.2.5
* Add other flags for english, spanish & portugese translations
* Review style for translation button in menu
* Add translation exclusion blocks by CSS selectors


##1.2.4
* Adding Hindi & Urdu translation languages.
* Adding version number on scripts.

##1.2.3
* Code review and optimization

##1.2.2
* WP Compliance

##1.2.1
* Fix style on dropdown list
* Fix link containing "admin" word

##1.2
* New choice of flags made by professional designers for your translation switch button. Rectangle mat, rectangle bright, square and circle. Enjoy!
* Add a "Settings" link under Weglot Translate in pugin list.

##1.1
* Add naviguation menu hook to let user display button in menu.
* Add possibility to show only flags
* Show warnings if PHP version is under 5.2 or rewrite rules not activated
* Rename simple html dom constant and handle no php-mbstring case
* Fix front page show box when home dir

##1.0
* Change portugese flag to brazilian, change limit message, starting 1.0 versioning as we reach viable product.

##Older versions

##0.1
* First version

##0.2
* Fix label and languages parameters

##0.3
* SEO now completly taken into account.

##0.4
* small fix on links

##0.5
* Fix rules + add url

##0.6
* Fix rules + new button design

##0.7
* Add meta translation, + regex eclusion

##0.8
* Add input button, fix small bug on link

##0.9
* Check rewrite rules are always here

##0.10
* Quick fix for PHP 5.3

##0.11
* Fix ajax, FB compat

##0.12
* Handle WP_HOME

##0.13
* General review

##0.14
* Prepare for localization

##0.15
* Change link to weglot

##0.16
* Place button by default

##0.17
* Fix vc_

##0.18
* Fix cdata

##0.19
* Http api integration

##0.20
* Fix PHP 5.2 compat with anonymous function

##0.21
* Change ob order for compatibility

##0.22
* More flexibility in destination language

##0.23
* Can have multiple youtube video for different languages

##0.24
* Fix some links that had multiple lang tag

##0.25
* Fix CSS style + subdirectory WP

##0.26
* Now support images

##0.27
* Adding 40+ languages + fix homepage bug

##0.28
* Change button to customizable widget, also fix bug https+wp_home

##0.29
* Fix is_html, add US flag possibility, fix link beginning with coutry code.

##0.30
* Fix style, add on-boarding to help users, add link to dashboard translations.

##0.31
* Adding ajax support for full html, fix style, fix link with wp_home

##0.32
* Quick fix on links

##0.33
* Add ajax for json-html

##0.34
* Adding chat support to help user set up the plugin

##0.35
* Rework classes + add search support (form tag)

##0.36
* Remove trial period, replace by free plan

##0.37
* More info on errors, translation limit from api

##0.38
* Fix canonical transated link, support RTL & LTR customization, WG logo to meet Wp standard, translate alt attribute, add possibility to drop button anywhere

##0.39
* Fix LTR CSS, api v2 transmit strings type, fix regex escaping

##0.40
* Add PDF translate, fix simple dom limit, uninstall hook, no &lt;/body&gt; case.
