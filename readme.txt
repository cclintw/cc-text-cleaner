=== CC Text Cleaner ===
Contributors: cclintw
Tags: text cleaner, utf-8, big5, encoding, text conversion, digital humanities
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Convert text files to UTF-8 and clean invalid or non-Unicode content.

== Description ==

CC Text Cleaner is a lightweight WordPress plugin for cleaning uploaded text files and converting them into UTF-8 plain text.

It is especially useful for researchers, archivists, and digital humanities users who need to process old text files such as Big5, SJIS, GB2312, HTML, XML, CSV, or Markdown files.

Features:

* Upload and clean text files directly in WordPress
* Convert text content to UTF-8
* Remove invalid or garbled characters
* Strip HTML, XML, CSS, and script tags
* Preserve line breaks while removing unwanted control characters
* Replace unsupported characters with a visible placeholder
* Download the cleaned result as a plain text file
* Includes shortcode support: `[cc_text_cleaner]`
* Translation ready with `cc-text-cleaner` text domain

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin
3. Go to **Tools > Text Cleaner**
4. Or place the shortcode `[cc_text_cleaner]` on any page or post

== Frequently Asked Questions ==

= What file types are supported? =

The plugin supports `.txt`, `.csv`, `.html`, `.htm`, `.xhtml`, `.xml`, and `.md`.

= What is the file size limit? =

The maximum accepted file size is 5MB.

= Does the plugin keep uploaded files? =

No. The plugin temporarily processes the file and removes it after download.

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release.