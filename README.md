# TMY Plugin for Wordpress Getting Started & FAQ

----

TMY Plugin is an open source software for internationalization and localization of Wordpress based websites. TMY Plugin could extract the text for translation, translate and integrate back into Wordpress. TMY Plugin provides multiple ways to handle different development phases of the Wordpress websites.


----

## Install and activate TMY Plugin 

<kbd><img src="doc/tmy-addplugin.png" width="450"/></kbd>

Download the plugin zip file from https://github.com/tmysoft/tmy-wordpress/releases, e.g. tmy-globalization-1.0.0.zip 

Or, search the "TMY plugin", install and activate it.

## Configure TMY Plugin

From the Wordpress dashboard side menu, Settings -> TMY setup:

<kbd><img src="doc/tmy-setup.png" width="500"/></kbd>

Key configuration:
- Configure the additional enabled languages
- Do you enable translations on key properties: site title, tagline, posts and pages
- Language switcher location
- How do you want your language switcher to look like, just text or showing flags

Remember to save your changes

## Use TMY Plugin to translate a Post or Page

Log into the admin panel of wordpress, navigate to the post page you want to translate, click button ![TMY Translate Button](doc/tmy-translatebutton.png "TMY Translate Button")

<kbd><img src="doc/tmy-page.png" width="600"/></kbd>

Follow the information in the Translation Status box to get to the specific lanaguge translation page:

<kbd><img src="doc/tmy-trans-status.png" width="300"/></kbd>

Put the translation into the corresponding translation editor of the page or post, then Publish it. Make sure you see the green LIVE button

<kbd><img src="doc/tmy-pagetranslated.png" width="600"/></kbd>

## Use TMY Plugin to translate the site title or tagline 

Site title is also called blogname at some places, similarly, tage line is often being called blogdescription, you can configure or change them at Setting -> General menu:

<kbd><img src="doc/tmy-blogname-setup.png" width="350"/></kbd>

To start translating them, enable the translation to them from Setting -> TMY Setup:

<kbd><img src="doc/tmy-blogname-enable.png" width="350"/></kbd>

Then the plugin will automatically create the place holder post corresponding to the blogname or blogdesription, the place holder post will be set as private. 

<kbd><img src="doc/tmy-blogname-placeholder.png" width="600"/></kbd>

Following the same way to translate the page or post to complete the translation.

## Configure the language swither location 

The language switcher could be placed at multiple places, in Settings -> TMY Setup, you can put it in:

1. In Title
2. In Tagline
3. In Each Post
4. Top of Sidebar
5. Draggable Floating Menu 

Here is the illustration:

<kbd><img src="doc/tmy-lang-switcher.png" width="800"/></kbd>

## Using TMY Dashboard

The plugin also provides a dashboard for your convenience to show the translation work in a nice summary:

<kbd><img src="doc/tmy-dashboard-free.png" width="600"/></kbd>


## Configuration and use TMY Translation Editor(Premium Service)

Register with tmysoft.com, log into the TMY Web Editor/Zanata, create the API key. Create the project and version of your project.

<kbd>![TMY CONFIG](https://github.com/tmysoft/tmy-wordpress/blob/master/doc/tmy-apikey.png "TMY CONFIG")</kbd>

Enter the username and API Key into TMY Wordpress setup page: username, token, project and version.

<kbd>![TMY SERVER CONFIG](https://github.com/tmysoft/tmy-wordpress/blob/master/doc/tmy-pluginserverconfig.png "TMY SERVER CONFIG")</kbd>

## Pushing page or post contents to TMY Web Editor for translation

After the TMY Setup page is configured with Translation Server information.

1. When Site Title and Tagline are updated in the Wordpress Setting page, after button "Save Changes" is pressed.
2. When Page or Post is updated in the Posts or Pages editor, after button "Update" or "Publish" is pressed.

## Translate Post or Page with TMY Editor

Visit `tmysoft.com` then `Editor` or `editor.tmysoft.com` directly.

<kbd>![TMY Web Editor](doc/tmy-webeditor.png "TMY Web Editor")</kbd>


## Using Google Translate to translate your post or pages

Google Translate is fully integrated into tmysoft.com and TMY Web Editor, follow the page at [tmysoft.com/subscriptions.html](https://www.tmysoft.com/subscriptions.html) to start.

## Pull translation from TMY Editor to your local Wordpress instance

Visit TMY Dashboard page would automatically pull the finished translation from TMY Web Editor:

<kbd>![TMY Dashboard](doc/tmy-dashboard.png "TMY Dashboard")</kbd>

## To start developing TMY Wordpress Plugin

TMY Wordpress plugin is following open source license, look at code here:

[https://github.com/tmysoft/tmy-wordpress](https://github.com/tmysoft/tmy-wordpress)

Ask questions, submit PRs.

## There is no translation after I setup everything 

Make sure translation is enabled in TMY Setup page:

<kbd>![TMY Enable Translation](https://github.com/tmysoft/tmy-wordpress/blob/master/doc/tmy-enabletranslation.png "TMY Enable Translation")</kbd>

## TMY Plugin shows connecting error code 7 in Wordpress

On CentOS/Feodra Linux system, the error is mostly due to the SE Linux setting which blocks the network connection, using following command to change the SELinux setting:

```
# setsebool httpd_can_network_connect on
```


## Getting More Support

If you need further support, reach out to us at [tmysoft.com/subscriptions.html](https://www.tmysoft.com/subscriptions.html)
