# CC Text Cleaner

CC Text Cleaner 是一款輕量級 WordPress 外掛，用於清理上傳的文字檔案，並將其轉換為 UTF-8 純文字格式。

此工具特別適合研究者、檔案管理者與數位人文工作者，能處理各種舊式文字檔，例如 Big5、SJIS、GB2312、HTML、XML、CSV 或 Markdown 檔案。

## 功能特色

- 直接在 WordPress 上傳並清理文字檔
- 將文字內容轉換為 UTF-8 編碼
- 移除非法字元與亂碼
- 清除 HTML、XML、CSS 與 Script 標籤
- 保留換行符號，同時移除不必要的控制字元
- 將不支援字元替換為可視化佔位符
- 下載清理後的純文字檔
- 支援 shortcode：`[cc_text_cleaner]`
- 支援翻譯（text domain：`cc-text-cleaner`）

## 安裝方式

1. 將外掛資料夾上傳至 `/wp-content/plugins/`
2. 在 WordPress 後台啟用外掛
3. 前往 `工具 > Text Cleaner`
4. 或在文章或頁面中插入 shortcode `[cc_text_cleaner]`

## 支援檔案格式

- `.txt`
- `.csv`
- `.html`
- `.htm`
- `.xhtml`
- `.xml`
- `.md`

## 注意事項

- 最大檔案大小：5MB
- 檔案僅暫時處理，下載後即自動刪除
- 外掛支援多語系翻譯

## 版本記錄

### 1.0.0

- 初始版本發佈