# TMY Plugin for Wordpress Getting Started & FAQ

----

TMY Plugin is an open source system for internationalization and localization of Wordpress based websites. TMY Plugin could extract the text from Wordpress make it ready for translation. TMY Plugin provides multiple ways to translate the extracted and integrate back with Wordpress.


----

## Install and activate TMY Plugin 

<kbd>![TMY Plugin Install](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-addplugin.png "TMY Plugin Install")</kbd>

Search the "TMY plugin", install and activate it.

## Configure TMY Plugin

<kbd>![TMY Plugin Setup](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-setup.png "TMY Plugin Setup")</kbd>

## Use TMY Plugin to translate a Post or Page

Log into the admin panel of wordpress, navigate to the post page, clicke buton ![TMY Translate Button](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-translatebutton.png "TMY Translate Button")

<kbd>![TMY Post](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-page.png "TMY Post")</kbd>

<kbd>![TMY Post Translation](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-pagetranslated.png "TMY Page Translation")</kbd>

<kbd>![TMY After Translation](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-sitetranslatedview.png "TMY After Translation")</kbd>

## Start using TMY Web Editor

Register with tmysoft.com

Create access token

Configure TMY Plugin on the Wordpress instance: username, token, project and version

<kbd>![TMY CONFIG](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-apikey.png "TMY CONFIG")</kbd>
Log into the admin panel ...
Entering the username, API token

## Syncing between Wordpress instance and TMY Web Editor


## Translate Post or Page with TMY Editor

Visit `tmysoft.com` then `Editor` or `editor.tmysoft.com` directly.
<kbd>![TMY Web Editor](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-webeditor.png "TMY Web Editor")</kbd>


## Using Google Tranlate translate your post or pages

## Pull translation from TMY Editor to your local Wordpress instance
<kbd>![TMY Dashboard](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-dashboard.png "TMY Dashboard")</kbd>

## To start developing TMY Wordpress Plugin
Inline `code` has `back-ticks around` it.

## TMY Plugin shows connecting error code 7 in Wordpress

On CentOS/Feodra Linux system, the error is mostly due to the SE Linux setting which blocks the network connection, using following command to change the SELinux setting:

```
# setsebool httpd_can_network_connect on
```


## Support

If you need support, reach out to us at. 
