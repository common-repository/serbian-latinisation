=== Serbian Latinisation (Serbian Transliteration) ===
Contributors: seebeen
Donate link: https://sgi.io/donate
Tags: letter, serbian, cyrillic, latin, transliteration, latinisation, script, multilanguage, wpml-compatible
Requires at least: 3.8
Tested up to: 5.1.1
Requires PHP: 5.4
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Serbian Latinisation plugin allows you to use both Cyrillic and Latin scripts on your website.

== Description ==

Serbian Latinisation **enables you to have both cyrillic and latin scripts** on your website. Transliteration is done in-place automatically.

If your content is written in cyrillic script, this plugin will allow your visitors to view the content both in cyrillic and latin scripts.
This plugin also fixes searching cyrillic posts using latin script.

**Features**

* Works everywhere - Plugin hooks into WordPress core transliterating your content inplace
* **Auto-Fix permalinks** - Your cyrillic permalinks will be automatically saved as latin (optional)
* **SEARCH FIX** - Search posts written in cyrillic script using both latin and cyrillic script
* Lightweight - Plugin does not use any external stylesheets, or js files.
* **Partial transliteration ** - You can selectively choose which parts of your website to keep in cyrilic
* WPML Compatible - Fully compatible with WPML. Transliteration only works on Serbian portion of your website
* Script / Language selector - Integrates into WPML language selectors in menu / widget
* Widget Ready * - Switch script via sidebar widget

== Installation ==

1. Upload serbian-latinisation.zip to plugins via WordPress admin panel, or upload unzipped folder to your plugins folder
2. Activate the plugin through the "Plugins" menu in WordPress
3. Go to Settings->Latinisation to manage the options

== Frequently Asked Questions ==

= Can I use your plugin to transliterate my latin content into cyrillic? =

Short answer: no.

Long Answer: No. Such conversions are very hard to do since HTML language is also written in latin script, and converting text only is very resource intensive process.

= How can I keep parts of content in cyrilic? =

You can use [srlat_cyr][/srlat_cyr] shortcode. This will keep text in cyrilic script, even when viewing latin version of the site.

= Will this plugin transliterate my page title into latin as well? =

Yes, it will.

= Will this plugin enable my visitors to search for content using latin script =

Yes it will. Plugin hooks into default WordPress search function and enables searching of cyrilic content using latin text on frontend

= Is this plugin Compatible with WPML =

Yes it is. It has no compatibility issues with WPML, since the transliteration core is being used only in serbian version of the website

= Will I be able to select script for Serbian language in WPML language Widget? =

Yes, Plugin fully integrates with all WPML functions on the frontend because it directly extends the available language list

= I'm having search issues - not all posts show up when searching for them using latin characters = 

Open a support thread, or send me an e-mail.

= Your plugin is converting my cyrilic filenames into latin which prevents them from loading = 

First of all - you shouldn't be using cyrilic filenames in the first place. Due to the fact that most hosting providers do not have full UTF-8 support.

An easy fix is to redownload all attachments with cyrilic filenames and reupload them. This plugin will do an on-the-fly conversion of filenames.

= Some parts of my page aren't being transliterated =

Depending on the theme / plugins you're currently using, some content might be generated via javascript / ajax. This plugin works fully on the backend side (content is being transliterated via PHP), so it cannot influence JS generated content and content pulled via ajax calls.

Feel free to contact me via e-mail, and I'll see if I can assist you for your specific case.


== Screenshots ==

== Changelog ==

= 1.2.1 = 
* Bugfix: Fixed a bug which prevented version 1.2 from working on certain hosting configurations

= 1.2.0 =
* New feature: Cyrilic shortcode

= 1.0.1 =
* Improvement: Update text domain for translation

= 1.0 =
* Initial release

== Upgrade Notice ==

None for now