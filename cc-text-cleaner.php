<?php
/**
 * Plugin Name: CC Text Cleaner
 * Plugin URI: https://cclin.cc
 * Description: Convert text files to UTF-8 and clean invalid or non-Unicode content.
 * Version: 1.0.0
 * Author: Chance Lin
 * Author URI: https://cclin.cc
 * Text Domain: cc-text-cleaner
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

class CC_Text_Cleaner
{
    public const TOKEN_PREFIX = 'cc_text_download_';
    public const TMP_DIR_NAME = 'tmp_clean';

    private static $instance = null;

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', [$this, 'load_textdomain']);
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_post_cc_text_clean_download', [$this, 'download']);
        add_action('admin_post_nopriv_cc_text_clean_download', [$this, 'download']);
        add_action('cc_text_cleaner_delete_files', [$this, 'delete_files']);
        add_action('admin_post_cc_text_clean_upload', [$this, 'handle_upload']);
        add_action('admin_post_nopriv_cc_text_clean_upload', [$this, 'handle_upload']);
        add_shortcode('cc_text_cleaner', [$this, 'render_shortcode']);
    }

    public function load_textdomain()
    {
        load_plugin_textdomain(
            'cc-text-cleaner',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );
    }

    public function register_admin_menu()
    {
        add_management_page(
            __('Text Cleaner', 'cc-text-cleaner'),
            __('Text Cleaner', 'cc-text-cleaner'),
            'manage_options',
            'cc-text-cleaner',
            [$this, 'render_page']
        );
    }

    public function render_page()
    {
        echo $this->render_form();
    }

    public function render_shortcode()
    {
        return $this->render_form();
    }

    public function render_form()
    {
        ob_start();
        ?>
        <div class="cc-text-cleaner-wrap" style="max-width:780px;text-align:center;">
            <h2><?php echo esc_html__('Text Cleaning', 'cc-text-cleaner'); ?></h2>
            <p style="margin-top:0;font-size:14px;">
                <?php echo esc_html__('Clean the text and download it directly as a plain text file (.txt).', 'cc-text-cleaner'); ?>
            </p>

            <form method="post" enctype="multipart/form-data"
                action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                style="margin-top:3rem;">
                <input type="hidden" name="action" value="cc_text_clean_upload">

                <h6><?php echo esc_html__('Choose a file to clean', 'cc-text-cleaner'); ?></h6>

                <div class="cc-text-dropzone"
                    id="cc-text-dropzone"
                    style="position:relative;border:2px dashed #c3c4c7;border-radius:12px;padding:40px 20px;text-align:center;background:#fafafa;cursor:pointer;transition:all .2s ease;">
                    <input type="file"
                        name="cc_text_file"
                        id="cc_text_file"
                        accept=".txt,.csv,.html,.htm,.md,.xml,.xhtml"
                        required
                        style="position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;z-index:2;">

                    <div class="cc-text-dropzone-content" style="pointer-events:none;position:relative;z-index:1;">
                        <div id="cc-text-dropzone-title" style="font-size:16px;font-weight:600;margin-bottom:8px;">
                            <?php echo esc_html__('Drag a file here, or click to select a file.', 'cc-text-cleaner'); ?>
                        </div>
                        <div id="cc-text-dropzone-filename" style="color:#666;margin-bottom:2rem;">
                            <?php echo esc_html__('No file selected.', 'cc-text-cleaner'); ?>
                        </div>

                        <p style="font-size:14px;margin-block-start:10px;">
                            <?php echo esc_html__('Supported formats: .txt, .htm, .html, .xhtml, .csv, .xml, .md', 'cc-text-cleaner'); ?>
                        </p>
                    </div>
                </div>

                <p style="text-align:right;margin-top:3rem;display:flex;justify-content:flex-end;gap:10px;align-items:center;">
                    <input
                        type="submit"
                        class="button button-primary"
                        value="<?php echo esc_attr__('Clean Text and Download', 'cc-text-cleaner'); ?>">
                    <button type="button" id="cc-text-cleaner-help-toggle" class="button">
                        <?php echo esc_html__('Help', 'cc-text-cleaner'); ?>
                    </button>
                </p>
            </form>

            <div id="cc-text-cleaner-help-box" style="display:none;margin-top:2rem;text-align:left;border:1px solid #dcdcde;border-radius:8px;padding:20px;background:#fff;">
                <p style="margin-top:0;line-height:1.8;">
                    <?php
                    echo esc_html__(
                        'This tool performs the following cleanup tasks, then converts the result into a plain text file (.txt) for direct download. No files are stored on this website. The maximum accepted file size is 5MB. For more advanced cleanup options, please use the site’s other tool.',
                        'cc-text-cleaner'
                    );
                    ?>
                </p>
                <p style="margin-bottom:0;line-height:1.8;">
                    <?php echo esc_html__('1. Remove half-width spaces except those between English letters or symbols.', 'cc-text-cleaner'); ?><br />
                    <?php echo esc_html__('2. Remove all control characters except line breaks.', 'cc-text-cleaner'); ?><br />
                    <?php echo esc_html__('3. Replace invalid characters or garbled text with ■.', 'cc-text-cleaner'); ?><br />
                    <?php echo esc_html__('4. Replace HTML heading (<h1> - <h6>) and paragraph (<p>) tags with line breaks.', 'cc-text-cleaner'); ?><br />
                    <?php echo esc_html__('5. Remove HTML, XML, CSS, Script, and other syntax or tags.', 'cc-text-cleaner'); ?><br />
                    <?php echo esc_html__('6. Convert some vertical full-width punctuation marks to horizontal punctuation marks (﹃ ﹄ ﹁ ﹂ ︿ ﹀ ︽ ︾).', 'cc-text-cleaner'); ?><br />
                </p>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropzone = document.getElementById('cc-text-dropzone');
            var input = document.getElementById('cc_text_file');
            var filename = document.getElementById('cc-text-dropzone-filename');
            var helpToggle = document.getElementById('cc-text-cleaner-help-toggle');
            var helpBox = document.getElementById('cc-text-cleaner-help-box');
            var noFileText = <?php echo wp_json_encode(__('No file selected.', 'cc-text-cleaner')); ?>;
            var selectedPrefix = <?php echo wp_json_encode(__('Selected: ', 'cc-text-cleaner')); ?>;

            if (dropzone && input && filename) {
                function updateFileName(files) {
                    if (files && files.length > 0) {
                        filename.textContent = selectedPrefix + files[0].name;
                    } else {
                        filename.textContent = noFileText;
                    }
                }

                input.addEventListener('change', function () {
                    updateFileName(input.files);
                });

                ['dragenter', 'dragover'].forEach(function (eventName) {
                    dropzone.addEventListener(eventName, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzone.style.borderColor = '#2271b1';
                        dropzone.style.background = '#f0f6fc';
                    });
                });

                ['dragleave', 'dragend', 'drop'].forEach(function (eventName) {
                    dropzone.addEventListener(eventName, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzone.style.borderColor = '#c3c4c7';
                        dropzone.style.background = '#fafafa';
                    });
                });

                dropzone.addEventListener('drop', function (e) {
                    var files = e.dataTransfer.files;
                    if (!files || !files.length) {
                        return;
                    }

                    input.files = files;
                    updateFileName(files);
                });
            }

            if (helpToggle && helpBox) {
                helpToggle.addEventListener('click', function () {
                    var isOpen = helpBox.style.display === 'block';
                    helpBox.style.display = isOpen ? 'none' : 'block';
                });
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_upload()
    {
        if (isset($_GET['downloaded']) && $_GET['downloaded'] === 'true') {
            return;
        }

        if (empty($_FILES['cc_text_file'])) {
            wp_die(esc_html__('No file was uploaded.', 'cc-text-cleaner'));
        }

        $file = $_FILES['cc_text_file'];

        if ((int) $file['error'] !== UPLOAD_ERR_OK) {
            echo '<div class="notice notice-error"><p>' .
                sprintf(
                    esc_html__('Upload error. Error code: %s', 'cc-text-cleaner'),
                    esc_html((string) $file['error'])
                ) .
                '</p></div>';
            return;
        }

        $max_size = 5 * 1024 * 1024;
        if ((int) $file['size'] > $max_size) {
            echo '<div class="notice notice-error"><p>' .
                esc_html__('The file is too large. Please keep it under 5MB.', 'cc-text-cleaner') .
                '</p></div>';
            return;
        }

        $raw = file_get_contents($file['tmp_name']);
        $original_name = sanitize_file_name($file['name']);

        if (mb_check_encoding($raw, 'UTF-8')) {
            $clean = self::clean_utf8_bytes($raw);
        } elseif (mb_check_encoding($raw, 'BIG5')) {
            $clean = self::clean_big5_bytes($raw);
        } elseif (mb_check_encoding($raw, 'SJIS')) {
            $converted = mb_convert_encoding($raw, 'UTF-8', 'SJIS');
            $clean = self::clean_utf8_bytes($converted);
        } elseif (mb_check_encoding($raw, 'GB2312')) {
            $converted = mb_convert_encoding($raw, 'UTF-8', 'GB2312');
            $clean = self::clean_utf8_bytes($converted);
        } else {
            $clean = self::clean_big5_bytes($raw);
        }

        $clean = strtr($clean, [
            '﹃' => '『',
            '﹄' => '』',
            '﹁' => '「',
            '﹂' => '」',
            '︿' => '〈',
            '﹀' => '〉',
            '︽' => '《',
            '︾' => '》',
        ]);

        $clean = preg_replace('/<(p|h[1-6])[^>]*>/i', "\n", $clean);
        $clean = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $clean);
        $clean = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $clean);
        $clean = preg_replace('/<head\b[^>]*>.*?<\/head>/is', '', $clean);
        $clean = preg_replace('/<svg\b[^>]*>.*?<\/svg>/is', '', $clean);

        $clean = preg_replace('/<\?(?i:xml)[^>]*\?>/s', '', $clean);
        $clean = preg_replace('/<!\s*(?i:doctype)\b[^>]*>/s', '', $clean);
        $clean = preg_replace('/<\/?[A-Za-z][A-Za-z0-9.-]*(?:\s+[^<>]*?)?\s*\/?>/is', '', $clean);
        $clean = preg_replace('/<!--.*?-->/s', '', $clean);

        $clean = preg_replace('/^[ \t]+/m', '', $clean);
        $clean = preg_replace('/\n {1,}\n/', "\n\n", $clean);
        $clean = preg_replace("/\n{3,}/", "\n\n", $clean);

        $upload_dir = wp_upload_dir();
        $tmp_dir = trailingslashit($upload_dir['basedir']) . self::TMP_DIR_NAME;
        wp_mkdir_p($tmp_dir);

        $base = pathinfo($original_name, PATHINFO_FILENAME);
        $filename = $base . '.txt';

        $output_path = trailingslashit($tmp_dir) . wp_unique_filename($tmp_dir, $filename);
        file_put_contents($output_path, $clean);

        $token = wp_generate_password(20, false, false);
        set_transient(
            self::TOKEN_PREFIX . $token,
            [
                'path' => $output_path,
                'name' => $filename,
                'orig' => $output_path,
            ],
            15 * MINUTE_IN_SECONDS
        );

        $nonce = wp_create_nonce(self::TOKEN_PREFIX . $token);
        $download_url = add_query_arg([
            'action'     => 'cc_text_clean_download',
            'token'      => $token,
            '_wpnonce'   => $nonce,
            'downloaded' => 'true',
        ], admin_url('admin-post.php'));

        wp_redirect($download_url);
        exit;
    }

    public function download()
    {
        if (!isset($_GET['downloaded']) || $_GET['downloaded'] !== 'true') {
            wp_die(esc_html__('The file cannot be downloaded.', 'cc-text-cleaner'));
        }

        $token = sanitize_text_field($_GET['token'] ?? '');

        if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', self::TOKEN_PREFIX . $token)) {
            wp_die(esc_html__('Verification failed.', 'cc-text-cleaner'));
        }

        $payload = get_transient(self::TOKEN_PREFIX . $token);
        if (!$payload || !file_exists($payload['path'])) {
            wp_die(esc_html__('The file has expired or does not exist.', 'cc-text-cleaner'));
        }

        delete_transient(self::TOKEN_PREFIX . $token);

        $filepath = $payload['path'];
        $filename = $payload['name'];

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Expires: 0');
        header('X-Content-Type-Options: nosniff');

        flush();
        readfile($filepath);
        flush();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        if (file_exists($filepath)) {
            @unlink($filepath);
        }

        $tmp_dir = dirname($filepath);
        if (is_dir($tmp_dir)) {
            $remaining = array_diff(scandir($tmp_dir), ['.', '..']);
            if (empty($remaining)) {
                @rmdir($tmp_dir);
            }
        }

        exit;
    }

    public function delete_files($files)
    {
        foreach ($files as $file) {
            if ($file && file_exists($file)) {
                @unlink($file);
            }
        }

        if (!empty($files[0])) {
            $dir = dirname($files[0]);
            if (is_dir($dir)) {
                $remaining = array_diff(scandir($dir), ['.', '..']);
                if (empty($remaining)) {
                    @rmdir($dir);
                }
            }
        }
    }

    public static function clean_big5_bytes($raw)
    {
        $parts = [];
        $len = strlen($raw);

        for ($i = 0; $i < $len; $i++) {
            $b1 = ord($raw[$i]);

            if ($raw[$i] === "\n") {
                $parts[] = "\n";
                continue;
            }

            if ($raw[$i] === ' ' || $raw[$i] === "\t") {
                $prev = ($i > 0) ? ord($raw[$i - 1]) : 0;
                $next = ($i + 1 < $len) ? ord($raw[$i + 1]) : 0;

                $is_prev_ascii_alnum = ($prev >= 0x30 && $prev <= 0x39) || ($prev >= 0x41 && $prev <= 0x5A) || ($prev >= 0x61 && $prev <= 0x7A);
                $is_next_ascii_alnum = ($next >= 0x30 && $next <= 0x39) || ($next >= 0x41 && $next <= 0x5A) || ($next >= 0x61 && $next <= 0x7A);

                if ($is_prev_ascii_alnum && $is_next_ascii_alnum) {
                    $parts[] = ' ';
                }
                continue;
            }

            if ($b1 === 0xA1 && isset($raw[$i + 1]) && ord($raw[$i + 1]) === 0x40) {
                $i++;
                continue;
            }

            if ($b1 <= 0x7F) {
                $parts[] = chr($b1);
                continue;
            }

            if (isset($raw[$i + 1])) {
                $b2 = ord($raw[$i + 1]);
                $is_big5_pair = ($b1 >= 0xA1 && $b1 <= 0xF9) && (($b2 >= 0x40 && $b2 <= 0x7E) || ($b2 >= 0xA1 && $b2 <= 0xFE));

                if ($is_big5_pair) {
                    $big5 = chr($b1) . chr($b2);

                    $unicode = @iconv('BIG5-HKSCS', 'UTF-8//IGNORE', $big5);
                    if ($unicode === false || $unicode === '') {
                        $unicode = @iconv('BIG5', 'UTF-8//IGNORE', $big5);
                    }

                    if ($unicode && mb_check_encoding($unicode, 'UTF-8')) {
                        $codepoint = mb_ord($unicode, 'UTF-8');

                        if ($codepoint >= 0xE000 && $codepoint <= 0xF8FF) {
                            $parts[] = sprintf('[%02X%02X]', $b1, $b2);
                        } elseif (
                            ($codepoint >= 0x20 && $codepoint <= 0xD7FF) ||
                            ($codepoint >= 0xF900 && $codepoint <= 0xFDCF) ||
                            ($codepoint >= 0xFDF0 && $codepoint <= 0xFFEF) ||
                            ($codepoint >= 0x10000 && $codepoint <= 0x10FFFF &&
                            ($codepoint < 0xF0000 || $codepoint > 0x10FFFD))
                        ) {
                            $parts[] = $unicode;
                        } else {
                            $parts[] = '■';
                        }
                    } else {
                        $parts[] = '■';
                    }

                    $i++;
                    continue;
                }

                $i++;
            }

            $parts[] = '■';
        }

        $clean = implode('', $parts);
        $clean = str_replace("\xEF\xBF\xBD", '■', $clean);
        $clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\x80-\x9F]/u', '', $clean);
        $clean = preg_replace("/\n{3,}/", "\n\n", $clean);

        if (class_exists('Normalizer')) {
            $clean = Normalizer::normalize($clean, Normalizer::FORM_KC);
        }

        return $clean;
    }

    public static function clean_utf8_bytes($raw)
    {
        $clean = '';
        $raw = str_replace(["\r\n", "\r"], "\n", $raw);
        $chars = preg_split('//u', $raw, -1, PREG_SPLIT_NO_EMPTY);
        $len = count($chars);

        for ($i = 0; $i < $len; $i++) {
            $char = $chars[$i];

            if ($char === "\n") {
                $clean .= "\n";
                continue;
            }

            if ($char === ' ' || $char === "\t") {
                $prev = $i > 0 ? $chars[$i - 1] : '';
                $next = $i < $len - 1 ? $chars[$i + 1] : '';
                if (preg_match('/[A-Za-z0-9]/u', $prev) && preg_match('/[A-Za-z0-9]/u', $next)) {
                    $clean .= ' ';
                }
                continue;
            }

            if (!mb_check_encoding($char, 'UTF-8')) {
                $clean .= '■';
                continue;
            }

            $codepoint = mb_ord($char, 'UTF-8');

            if ($codepoint >= 0xE000 && $codepoint <= 0xF8FF) {
                $clean .= sprintf('[U+%04X]', $codepoint);
            } elseif (
                ($codepoint >= 0x20 && $codepoint <= 0xD7FF) ||
                ($codepoint >= 0xF900 && $codepoint <= 0xFDCF) ||
                ($codepoint >= 0xFDF0 && $codepoint <= 0xFFEF) ||
                ($codepoint >= 0x10000 && $codepoint <= 0x10FFFF &&
                ($codepoint < 0xF0000 || $codepoint > 0x10FFFD))
            ) {
                $clean .= $char;
            } else {
                $clean .= '■';
            }
        }

        $clean = str_replace("\xEF\xBF\xBD", '■', $clean);
        $clean = preg_replace('/\?[A-Za-z0-9]/u', '■', $clean);

        $pattern = '/\?\?/u';
        preg_match_all($pattern, $clean, $matches);
        if (count($matches[0]) > 3) {
            $clean = str_replace('??', '■', $clean);
        }

        $clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\x80-\x9F]/u', '', $clean);
        $clean = preg_replace("/\n{3,}/", "\n\n", $clean);

        return $clean;
    }
}

CC_Text_Cleaner::get_instance();