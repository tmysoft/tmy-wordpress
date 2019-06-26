# TMY Plugin for Wordpress Getting Started & FAQ

----

TMY Plugin is an open source system for internationalization and localization of Wordpress based websites. TMY Plugin could extract the text for translation, translate and integrate back into Wordpress. TMY Plugin provides multiple ways to handle different development phases of the Wordpress websites.


----

## Install and activate TMY Plugin 

<kbd>![TMY Plugin Install](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-addplugin.png "TMY Plugin Install")</kbd>

Search the "TMY plugin", install and activate it.

## Configure TMY Plugin

<kbd>![TMY Plugin Setup](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-setup.png "TMY Plugin Setup")</kbd>

## Use TMY Plugin to translate a Post or Page

Log into the admin panel of wordpress, navigate to the post page, click button ![TMY Translate Button](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-translatebutton.png "TMY Translate Button")

<kbd>![TMY Post](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-page.png "TMY Post")</kbd>

Put the translation into the corresponding translation editor of the page or post, then Publish it.

<kbd>![TMY Post Translation](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-pagetranslated.png "TMY Page Translation")</kbd>

Visit the site to confirm translation is available.

<kbd>![TMY After Translation](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-sitetranslatedview.png "TMY After Translation")</kbd>

## Using TMY Web Editor

Register with tmysoft.com, log into the TMY Web Editor/Zanata, create the API key. Create the project and version of your project.

<kbd>![TMY CONFIG](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-apikey.png "TMY CONFIG")</kbd>

Enter the username and API Key into TMY Wordpress setup page: username, token, project and version.

<kbd>![TMY SERVER CONFIG](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-pluginserverconfig.png "TMY SERVER CONFIG")</kbd>

## Pushing page or post contents to TMY Web Editor for translation

After the TMY Setup page is configured with Translation Server information.

1. When Site Title and Tagline are updated in the Wordpress Setting page, after button "Save Changes" is pressed.
2. When Page or Post is updated in the Posts or Pages editor, after button "Update" or "Publish" is pressed.

## Translate Post or Page with TMY Editor

Visit `tmysoft.com` then `Editor` or `editor.tmysoft.com` directly.
<kbd>![TMY Web Editor](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-webeditor.png "TMY Web Editor")</kbd>


## Using Google Tranlate translate your post or pages

## Pull translation from TMY Editor to your local Wordpress instance

Visit TMY Dashboard page would automatically pull the finished translation from TMY Web Editor:

<kbd>![TMY Dashboard](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-dashboard.png "TMY Dashboard")</kbd>

## To start developing TMY Wordpress Plugin

TMY Wordpress plugin is following open source license, look at code here:

`[https://github.com/tmysoft/tmy-wordpress](https://github.com/tmysoft/tmy-wordpress)`

Ask questions, submit PRs.

## There is no translation after I setup everything 

Make sure translation is enabled in TMY Setup page:

<kbd>![TMY Enable Translation](https://github.com/tmysoft/tmy-wordpress/blob/master/tmy-enabletranslation.png "TMY Enable Translation")</kbd>

## TMY Plugin shows connecting error code 7 in Wordpress

On CentOS/Feodra Linux system, the error is mostly due to the SE Linux setting which blocks the network connection, using following command to change the SELinux setting:

```
# setsebool httpd_can_network_connect on
```


## Getting More Support

If you need further support, reach out to us at [tmysoft.com/subscriptions.html](https://www.tmysoft.com/subscriptions.html)
