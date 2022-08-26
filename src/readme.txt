=== TMY Globalization ===
Contributors: Yu Shao
Donate link: https://tmysoft.com/solutions.html
Tags: i18n, l10n, translation, localization, globalization
Requires at least: 4.7
Tested up to: 5.4
Stable tag: 4.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Here is a short description of the plugin.  This should be no more than 150 characters.  No markup here.

== Description ==

TMY Globalization plugin provides internationalization, localization capability to your 
webistei by hosting your translations locally for proof reading and editing. Fine controlled
internationalization and localization features 
This is the long description.  No limit, and you can use Markdown (as well as in the following sections).

For backwards compatibility, if this section is missing, the full length of the short description will be used, and
Markdown parsed.

A few notes about the sections above:

* "Contributors" is a comma separated list of wordpress.org usernames
* "Tags" is a comma separated list of tags that apply to the plugin
* "Requires at least" is the lowest version that the plugin will work on
* "Tested up to" is the highest version that you've *successfully used to test the plugin*
* Stable tag must indicate the Subversion "tag" of the latest stable version

Note that the `readme.txt` value of stable tag is the one that is the defining one for the plugin.  If the `/trunk/readme.txt` file says that the stable tag is `4.3`, then it is `/tags/4.3/readme.txt` that'll be used for displaying information about the plugin.

If you develop in trunk, you can update the trunk `readme.txt` to reflect changes in your in-development version, without having that information incorrectly disclosed about the current stable version that lacks those changes -- as long as the trunk's `readme.txt` points to the correct stable tag.

If no stable tag is provided, your users may not get the correct version of your code.

== A brief Markdown Example ==

Markdown is what the parser uses to process much of the readme file.

[markdown syntax]: https://daringfireball.net/projects/markdown/syntax

Links require brackets and parenthesis:

Here's a link to [WordPress](https://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax]. Link titles are optional, naturally.

Blockquotes are email style:

> Asterisks for *emphasis*. Double it up  for **strong**.

And Backticks for code:

`<?php code(); ?>`

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].

Titles are optional, naturally.

Asterisks for *emphasis*.

Double it up  for **strong**.


== Installation ==

1. Upload "test-plugin.php" to the "/wp-content/plugins/" directory.
1. Activate the plugin through the "Plugins" menu in WordPress.
1. Place "do_action( 'plugin_name_hook' );" in your templates.

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

= 1.2.0 =
* Miscellaneous Improvement.

= 1.1.0 =
* Miscellaneous Improvement.
* Machine translation integration

= 1.0.0 =
* Initial Release.

