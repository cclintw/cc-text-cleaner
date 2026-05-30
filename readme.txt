=== CC Text Cleaner ===
Contributors: cclin
Tags: text cleaner, utf-8, big5, encoding, digital humanities
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Convert text files to UTF-8 and clean invalid or non-Unicode content.

== Description ==

CC Text Cleaner is a lightweight text cleaning plugin built specifically for **legacy text files, encoded documents, and digital humanities workflows**.

This plugin focuses on text conversion, cleanup clarity, and temporary file handling, making it useful for researchers, archivists, editors, and site owners who need to process old text files such as Big5, SJIS, GB2312, HTML, XML, CSV, or Markdown files.

== Key Features ==

* Upload and clean text files directly in WordPress
* Convert text content to UTF-8
* Remove invalid or garbled characters
* Strip HTML, XML, CSS, and script tags
* Preserve line breaks while removing unwanted control characters
* Replace unsupported characters with the visible placeholder ■
* Download the cleaned result as a plain text file
* Includes shortcode support: `[cc_text_cleaner]`
* Translation ready with `cc-text-cleaner` text domain
* Nonce-protected upload form with server-side file extension checks

== Designed for ==

CC Text Cleaner is especially suitable for:

* Digital humanities projects
* Historical text archives
* Legacy encoded text files
* Research notes and source materials
* Plain text cleanup before editing or publishing

== Processing and Privacy ==

Files are processed on the same WordPress site where the plugin is installed.

The plugin provides structured upload restrictions such as:

* Maximum file size limit
* Allowed filename extension checks
* Nonce-protected upload and download actions
* Temporary cleaned file download links
* Automatic cleanup after download or expiration

This keeps the workflow simple while avoiding permanent storage of uploaded or cleaned files.

== Installation ==

1. Upload the plugin folder `cc-text-cleaner` to `/wp-content/plugins/`
2. Activate the plugin through the “Plugins” menu in WordPress
3. Go to Tools → Text Cleaner to upload and clean a file
4. Or place the shortcode `[cc_text_cleaner]` on any page or post

== Frequently Asked Questions ==

= What file types are supported? =

The plugin supports `.txt`, `.csv`, `.html`, `.htm`, `.xhtml`, `.xml`, and `.md`.

= What is the file size limit? =

The maximum accepted file size is 5MB.

= Can the shortcode be used on public pages? =

Yes. The shortcode can be used on public pages. Uploaded files are limited by extension and file size, processed as temporary text cleaning files, and removed after the cleaned file is sent to the browser.

= Does the plugin inspect file contents? =

No. The plugin validates allowed filename extensions and is intended for plain text files. It does not perform binary file or MIME content inspection because legacy encodings such as Big5 and SJIS may be misdetected by strict MIME checks.

= Does the plugin keep uploaded files? =

No. The plugin temporarily processes the file and removes it after download.

== Changelog ==

= 1.0.0 =
* Initial release
