# CC 文本清理

CC 文本清理是一個輕量級 WordPress 外掛，用於清理上傳的文字檔，並將內容轉成 UTF-8 純文字檔。

這個外掛特別適合研究者、檔案整理者、數位人文使用者，以及需要處理 Big5、SJIS、GB2312、HTML、XML、CSV 或 Markdown 等舊文字檔的工作流程。

## 功能特色

- 可直接在 WordPress 上傳並清理文字檔
- 將文字內容轉換為 UTF-8
- 移除無效字元或亂碼
- 移除 HTML、XML、CSS、Script 與其他標籤或語法
- 保留換行，同時移除不需要的控制字元
- 將不支援或無法辨識的字元替換為可見符號 ■
- 清理完成後直接下載純文字檔
- 支援 shortcode：`[cc_text_cleaner]`
- 支援 i18n，多語系 text domain 為 `cc-text-cleaner`
- 上傳表單使用 nonce 保護，並在伺服器端檢查允許的副檔名

## 安裝方式

1. 將外掛資料夾上傳到 `/wp-content/plugins/`
2. 在 WordPress 後台啟用外掛
3. 前往 `工具 > CC 文本清理`
4. 或在頁面、文章中插入 shortcode：`[cc_text_cleaner]`

## 支援檔案格式

- `.txt`
- `.csv`
- `.html`
- `.htm`
- `.xhtml`
- `.xml`
- `.md`

## 注意事項

- 最大檔案大小限制為 5MB
- shortcode 可用於公開頁面
- 檔案只會暫時處理，下載後會刪除
- 上傳檔案在網站本機處理，不會傳送到第三方服務
- 外掛已準備好多語系支援

## 更新紀錄

### 1.0.0

- 初始版本
