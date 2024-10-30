=== Credit Line Generator ===
Contributors: hatesspam
Tags: credit, credits, image credit, licence, license, licenses, licences, caption, captions, photo credit, byline, attribution
Requires at least: 3.9
Tested up to: 6.6
Stable tag: 0.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A template for the Classic editor that allows you to copy and paste image credits into your posts.

This makes it easier to avoid typos.

== Description ==

This plug-in is a typing aid for image credits on posts and pages for the Classic editor.

It adds a button to your editor called 'by' in the Visual Editor and 'credit' in the Text Editor tab. Pressing the button conjures up a form that will let you fill out fields about the image, such as the name of the creator, and the URL of the license under which you are using the image. 

Press submit, and the plug-in will paste a nicely formatted string at the current cursor position of your editor.

I am currently a happy user of my own plugin, and foresee no major changes in the future. Please let me know if there is any feature you could use.

If you are using Wordpress 5.0 or higher with the Gutenberg editor, use the Classic Editor through the plugin of the same name. 

There is a known problem when using this plugin in the Gutenberg editor with the Classic block: https://wordpress.org/support/topic/pop-up-doesnt-work-if-classic-block-editor-is-itself-a-pop-up/ .

= GDPR compliance =

This plugin helps you helps you format personal data (such as the name and online address of a photographer), but it does not store such data itself.

= Requirements =

* WordPress 3.9 or newer.

Note that earlier versions of this plug-in function perfectly fine with earlier versions of Wordpress. Version 1.2.1 of the plug-in supports Wordpress 3.3 through 3.8.x, but does not support the visual editor.

= Examples =

* _Photo_ by John Smith.
* _Photo of a fire truck_ by John Smith, _some rights reserved_.

= Rationale =

My goal in writing this plugin was mainly to help me avoid typos. 

== Installation ==

1. Upload the folder containing the plugin to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Configuration ==

A setting will enable wrapping the credit line in HTML mark-up, so that you can style it more easily. This setting is off by default.

== Screenshots ==
1. The form.
2. A Classic Editor block in Gutenberg.

== Frequently Asked Questions ==

= Why doesn't it work? =

There could be a couple of reasons: 

1. You have Javascript disabled or another Javascript program is interfering.
2. If you are using the Gutenberg editor of Wordpress 5 or higher, make sure you use the Classic Editor plugin.

This plug-in may work with the Classic block in the Gutenberg editor: see the description for specifics.

If the reason it doesn't work isn't in this list, please file a support request on the plugin's homepage. Please describe what you have tried (step for step) and where it goes wrong. 

== Alternatives ==

There are plugins that add a credit field to media, which you can then display in your post using a shortcode. Media Credit by Peter Putzer is an example:

https://wordpress.org/plugins/media-credit/

Plugins also exist that let you read IPTC meta data from image files and display it in your content using meta tags, for instance using JSM's Adobe XMP / IPTC for WordPress:

https://wordpress.org/plugins/adobe-xmp-for-wp/

== Changelog ==

= 0.3.3. =
* Failed fix: a bug that made the pop-up appear underneath the Classic block pop-up in some instances. 

= 0.3.2 = 
* Changed: documentation, clarified usage with the Gutenberg editor.

= 0.3.1 =
* Added: interface translations.
* Added: support for narrow screens.
* Changed: minor tweaks to text and looks.
* Changed: in the Text editor, the credit line now gets inserted at the cursor.
* Fixed: in the Visual editor, adding a credit line no longer resets the cursor position.
* I also performed some minor code clean-up that should make it easier to add features.

= 0.3.0 =
* Added visual editor support.
* Changed the minimal required version of Wordpress to 3.9.
* Fixed a bug that would display an error message if a user had yet to save the settings.

= 0.2.1 =
* Added: information about compatibility with the new editor introduced for Wordpress 5, Gutenberg.
* Added: information about GDPR compliance.

= 0.2.0 =
* Changed: refactored the code to make future expansion easier.
* Changed: the Javascript produced by creditline_init() has been moved to its own file.
* Changed: the CSS produced by creditline_head() has been moved to its own file.
* Deprecated: creditline_head(). If for some reason you rely on this function, note that it no longer does anything, and that it will be removed in a future release.
* Added: optional HTML mark-up for the credit line.
* Added: a settings screen in which the HTML mark-up can be enabled.
* Fixed: a bug that returned 'Photo by .' when all fields were empty. Now nothing will be returned in that case.

= 0.1.7 =
* Tested: up to version 4.9.1.

= 0.1.6 =
* Added: an icon.
* Changed: expanded the Readme with alternative plugins.

= 0.1.5 = 
* Fixed: display the pop-up over WordPress' media buttons bar (it's like an arms race). 
* Changed: moved all of the changelog to the readme.txt. 
* Changed: moved todos out of the plugin.
* Changed: minor textual changes in plugin and readme.txt.
* Changed: some code clean-ups (I hope).

= 0.1.4.1 = 
* Fixed typos in the readme.

= 0.1.4 =
* Fixed: media buttons rendered over the pop-up.
* Changed: removed nonsense text to keep pop-up smaller.

= 0.1.3 = 
* First public version. Cleaned things up a bit, added a readme.txt.

= 0.1.2 =
* WordPress renamed a couple of action hooks we were using, and uses a Tags object now for the Quicktags.

= 0.1.1 =
Added a couple of WordPress styles to give it a more unified look.

= 0.1 =
* Initial version.
