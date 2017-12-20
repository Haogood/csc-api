# Concepoint 官網API (暫停開發) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
> 此專案已暫停開發，目前作為 Restful API 筆記 (備忘錄)

---

## 專案結構
- 針對目標: 大圖Slides資訊
- Token採用 [Firebase PHP-JWT](https://github.com/firebase/php-jwt)
- 採用Lumen PHP Framework 開發
- 依 [RESTful](https://en.wikipedia.org/wiki/Representational_state_transfer) 設計原則


# URI
### 可直接訪問 :
* 登入
    * `POST /api/auth/login`
* 取得所有Slides內容
    * `GET /api/mainpages`
* 取得指定Slide內容
    * `GET /api/mainpages/{id}`
* 取得指定圖片 (可保護真實檔案路徑)
    * `GET /api/mainpages/src/{id}`    
### 需要驗證認證才可訪問 :
* 取得用戶資料
    * `GET api/user`
* 新增Slide ( 資訊 + 圖片 )
    * `POST /api/mainpages`
    * 必填欄位 ( title, describe )
* 更新Slide圖片
    * `POST /api/mainpages/switch/{id}`
* 更新Slide資訊
    * `PUT /api/mainpages/{id}`
* 刪除指定Slide
    * `DELETE /api/mainpages/{id}`