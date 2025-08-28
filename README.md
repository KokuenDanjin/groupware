# groupware
少人数用ポータルアプリ

## 🛠使用技術

### フロントエンド 
- JavaScript (ES6)
- SCSS (Dart Sass v1.89.2, Vite によるコンパイル)
- HTML5 (Laravel Blade)

### バックエンド 
- PHP 8.2
- Laravel 12.x
- MySQL 8.0

### 開発環境
- Docker
- Laravel Sail
- Node.js (SCSS / JavaScript ビルド用, Vite 利用)
  
## 開発環境について

本プロジェクトは **Docker** 上で構築されており、  
Laravel 公式の Docker 環境ツール **Laravel Sail** を利用しています。  
そのため、ローカルに PHP や MySQL を直接インストールする必要はありません。  

### フロントエンドビルド
Laravel 標準の **Vite** を利用して SCSS / JavaScript をバンドルしています。

開発用コマンド（ホットリロード有効）:
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

本番ビルド:
```bash
./vendor/bin/sail npm run build
```

### コンテナ起動例
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```
