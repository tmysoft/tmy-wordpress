=== TMY Globalization ===
Contributors: yushao
Donate link: https://tmysoft.com/solutions.html
Tags: i18n, l10n, translation, localization, globalization
Requires at least: 5.0
Tested up to: 6.0.2
Stable tag: 1.5.5
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Make your website multilingual ready at ease with live translation or with support of full translation cycle, with machine translation integration.

== Description ==

TMY Globalization Plugin is an open source tool for internationalization and localization of Wordpress based websites. TMY Plugin provides two translation workflows:

* Live Translation workflow
* Full Professional Translation workflow

Live translation workflow provides instant translation based on Google Translate engine. Simply configure the extra languages you want to support through the intutuive setup interface and save, then you are done.

Full Professional workflow provides more control and higher translation quality, which involves extracting the text for translation, translating and integrating back into Wordpress. More specifically TMY plugin hosts your translations locally for proof reading, editing and final publication. You can intergrate machine translation of your choice to prepare your contents, then leveraging fully featured translation editor with any 3rd party translation agencies.

TMY Plugin provides multiple ways to handle different development phases of the Wordpress websites with intuitive and easy to use interfaces, some features include:

* Live Translation powered by Google Translate
* Full cycle to keep translation locally for proof read, edit and publish.
* Support new block Gutenberg editor and classic editor.
* Support Google Translate integration with editing capability
* Language switcher based on
  * Draggable floating meanu
  * Sidebar widget
  * Along with title or description
  * With any page or post
* Language switcher is in language name or flags
* Detect browser language setting
* Support browser cookie
* Premium service available
* Live support community

== Installation ==

1. Install and Activate "TMY Globalization" from Plugin directory.
1. Setup the plugin from Settings-> TMY Setup.

More information at [TMY Plugin for Wordpress Getting Started & FAQ](https://github.com/tmysoft/tmy-wordpress)

== Frequently Asked Questions ==

= There is no translation after I setup everything 

Make sure translation is enabled in TMY Setup page:

<kbd>![TMY Enable Translation](https://github.com/tmysoft/tmy-wordpress/blob/master/doc/tmy-enabletranslation.png "TMY Enable Translation")</kbd>

= TMY Plugin shows connecting error code 7 in Wordpress

On CentOS/Feodra Linux system, the error is mostly due to the SE Linux setting which blocks the network connection, using following command to change the SELinux setting:

```
# setsebool httpd_can_network_connect on
```
== Screenshots ==

1. The screenshot description corresponds to screenshot-1.(png|jpg|jpeg|gif).
2. The screenshot description corresponds to screenshot-2.(png|jpg|jpeg|gif).
3. The screenshot description corresponds to screenshot-3.(png|jpg|jpeg|gif).

== Changelog ==

= 1.5.0 =
* added bulk action for Page and Post and other UI improvement

= 1.4.0 =
* "g11n_translation" listing page add columns

= 1.3.0 =
* Live Translation

= 1.2.0 =
* Miscellaneous Improvement.

= 1.1.0 =
* Miscellaneous Improvement.
* Machine translation integration

= 1.0.0 =
* Initial Release.

== Upgrade Notice ==

= 1.5.0 =
* added bulk action for Page and Post and other UI improvement

= 1.4.0 =
* "g11n_translation" listing page add columns

= 1.3.0 =
* Live Translation is added. It is powered by Google Translate

= 1.2.0 =
* Miscellaneous Improvement.

= 1.1.0 =
* Miscellaneous Improvement.
* Machine translation integration
