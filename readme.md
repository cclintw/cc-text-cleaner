# CC Text Cleaner

CC Text Cleaner is a lightweight WordPress plugin for cleaning uploaded text files and converting them into UTF-8 plain text.

It is especially useful for researchers, archivists, and digital humanities users who need to process old text files such as Big5, SJIS, GB2312, HTML, XML, CSV, or Markdown files.

## Features

- Upload and clean text files directly in WordPress
- Convert text content to UTF-8
- Remove invalid or garbled characters
- Strip HTML, XML, CSS, and script tags
- Preserve line breaks while removing unwanted control characters
- Replace unsupported characters with a visible placeholder
- Download the cleaned result as a plain text file
- Includes shortcode support: `[cc_text_cleaner]`
- Translation ready with `cc-text-cleaner` text domain

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin in WordPress admin
3. Go to `Tools > Text Cleaner`
4. Or insert the shortcode `[cc_text_cleaner]` into a page or post

## Supported File Types

- `.txt`
- `.csv`
- `.html`
- `.htm`
- `.xhtml`
- `.xml`
- `.md`

## Notes

- Maximum file size: 5MB
- Files are processed temporarily and removed after download
- The plugin is translation ready

## Changelog

### 1.0.0

- Initial release