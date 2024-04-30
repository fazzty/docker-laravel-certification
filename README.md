#docker-laravel-certification

## 環境
- laravel 9
- mysql
- breezeとかはmainブランチいれてない

## 環境の再構築
### GitHubからリポジトリをクローン
mainブランチ

```
[mac] $ git clone git@github.com:fazzty/docker-laravel-certification.git
[mac] $ cd docker-laravel-certification.
[mac] $ docker compose up -d
```


/data/public/../vendor/autoload.php を開くのに失敗してエラーになっていることを確認します。  
git cloneが終わった状態では app コンテナ内に /data/vendor ディレクトリが存在しないためです。

Laravelインストール
app コンテナに入ります。

```
[mac] $ docker compose exec app bash
```
書き込み権限がないとキャッシュやログにエラーを書き込めないので、権限を付与しておきます。

```
[app] $ chmod -R 777 storage bootstrap/cache
```

ホスト側で弾かれる場合はホスト側で問題となっているファイル及びディレクトリの所有者を変更します。
```
sudo chown -R $USER:$USER .
```


vendor ディレクトリへライブラリ群をインストールします。
composer.lock ファイルを参照します。

```
[app] $ composer install
```
画面を開いて確認します。


500 SERVER ERROR だと何が原因なのか分かりません。
ログファイルを見てエラーを確認します。

```
[app] $ cat storage/logs/laravel.log
```

```
[2022-02-15 15:07:29] production.ERROR: No application encryption key has been specified. {"exception":"[object] (Illuminate\\Encryption\\MissingAppKeyException(code: 0): No application encryption key has been specified. at /data/vendor/laravel/framework/src/Illuminate/Encryption/EncryptionServiceProvider.php:79)
[stacktrace]
#0 /data/vendor/laravel/framework/src/Illuminate/Support/helpers.php(302): Illuminate\\Encryption\\EncryptionServiceProvider->Illuminate\\Encryption\\{closure}(NULL)
#1 /data/vendor/laravel/framework/src/Illuminate/Encryption/EncryptionServiceProvider.php(81): tap(NULL, Object(Closure))
#2 /data/vendor/laravel/framework/src/Illuminate/Encryption/EncryptionServiceProvider.php(60): Illuminate\\Encryption\\EncryptionServiceProvider->key(Array)
#3 /data/vendor/laravel/framework/src/Illuminate/Encryption/EncryptionServiceProvider.php(32): Illuminate\\Encryption\\EncryptionServiceProvider->parseKey(Array)
#4 /data/vendor/laravel/framework/src/Illuminate/Container/Container.php(873): Illuminate\\Encryption\\EncryptionServiceProvider->Illuminate\\Encryption\\{closure}(Object(Illuminate\\Foundation\\Application), Array)
#5 /data/vendor/laravel/framework/src/Illuminate/Container/Container.php(758): Illuminate\\Container\\Container->build(Object(Closure))
#6 /data/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(855): Illuminate\\Container\\Container->resolve('encrypter', Array, true)
#7 /data/vendor/laravel/framework/src/Illuminate/Container/Container.php(694): Illuminate\\Foundation\\Application->resolve('encrypter', Array)
#8 /data/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(840): Illuminate\\Container\\Container->make('encrypter', Array)
#9 /data/vendor/laravel/framework/src/Illuminate/Container/Container.php(1027): Illuminate\\Foundation\\Application->make('encrypter')
#10 /data/vendor/laravel/framework/src/Illuminate/Container/Container.php(947): Illuminate\\Container\\Container->resolveClass(Object(ReflectionParameter))
#11 /data/vendor/laravel/framework/src/Illuminate/Container/Container.php(908): Illuminate\\Container\\Container->resolveDependencies(Array)
#12 /data/vendor/laravel/framework/src/Illuminate/Container/Container.php(758): Illuminate\\Container\\Container->build('App\\\\Http\\\\Middle...')
#13 /data/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(855): Illuminate\\Container\\Container->resolve('App\\\\Http\\\\Middle...', Array, true)
#14 /data/vendor/laravel/framework/src/Illuminate/Container/Container.php(694): Illuminate\\Foundation\\Application->resolve('App\\\\Http\\\\Middle...', Array)
#15 /data/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(840): Illuminate\\Container\\Container->make('App\\\\Http\\\\Middle...', Array)
#16 /data/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(206): Illuminate\\Foundation\\Application->make('App\\\\Http\\\\Middle...')
#17 /data/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(180): Illuminate\\Foundation\\Http\\Kernel->terminateMiddleware(Object(Illuminate\\Http\\Request), Object(Illuminate\\Http\\Response))
#18 /data/public/index.php(55): Illuminate\\Foundation\\Http\\Kernel->terminate(Object(Illuminate\\Http\\Request), Object(Illuminate\\Http\\Response))
#19 {main}
"} 
```
例外が発生した時のログは下記のフォーマットで出力されます。

```
storage/logs/laravel.log
[YYYY-MM-DD HH:II:SS] 環境.ログレベル: ログメッセージ
```
トレースログ
トレースログは例外が発生した場所から呼び出し元の場所を遡って表示してくれます。
一番大事なのは最初のログメッセージです。

```
storage/logs/laravel.log
1[2022-02-15 15:07:29] production.ERROR: No application encryption key has been specified. 
```
「アプリケーション暗号化キーが指定されていません。」というエラーになります。  
.env の APP_KEY に値が設定されてないと表示されるエラーです。

```
[app] $ ls -l
total 328
drwxr-xr-x 25 root     root        800 Feb 15 15:05 .
drwxr-xr-x  1 root     root       4096 Feb 15 15:05 ..
-rw-r--r--  1 root     root        258 Feb 15 15:03 .editorconfig
-rw-r--r--  1 root     root        897 Feb 15 15:03 .env.example
-rw-r--r--  1 root     root        152 Feb 15 15:03 .gitattributes
-rw-r--r--  1 root     root        207 Feb 15 15:03 .gitignore
-rw-r--r--  1 root     root        175 Feb 15 15:03 .styleci.yml
-rw-r--r--  1 root     root       3958 Feb 15 15:03 README.md
drwxr-xr-x  7 root     root        224 Feb 15 15:03 app
-rwxr-xr-x  1 root     root       1686 Feb 15 15:03 artisan
drwxr-xr-x  4 root     root        128 Feb 15 15:03 bootstrap
-rw-r--r--  1 root     root       1746 Feb 15 15:03 composer.json
-rw-r--r--  1 root     root     285701 Feb 15 15:03 composer.lock
drwxr-xr-x 17 www-data www-data    544 Feb 15 15:03 config
drwxr-xr-x  6 root     root        192 Feb 15 15:03 database
drwxr-xr-x  4 www-data www-data    128 Feb 15 15:03 lang
-rw-r--r--  1 root     root        473 Feb 15 15:03 package.json
-rw-r--r--  1 root     root       1175 Feb 15 15:03 phpunit.xml
drwxr-xr-x  6      101 ssh         192 Feb 15 15:03 public
drwxr-xr-x  5 root     root        160 Feb 15 15:03 resources
drwxr-xr-x  6 root     root        192 Feb 15 15:03 routes
drwxr-xr-x  5 root     root        160 Feb 15 15:03 storage
drwxr-xr-x  6 root     root        192 Feb 15 15:03 tests
drwxr-xr-x 44 root     root       1408 Feb 15 15:06 vendor
-rw-r--r--  1 root     root        559 Feb 15 15:03 webpack.mix.js
```

.env.example はありますが、 .env ファイルが存在しません。  
理由は .env はGit管理対象外に設定されているからです。

```
$ cat .gitignore
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
docker-compose.override.yml
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
/.idea
/.vscode
```

そもそも .env ファイルがないのが問題なので、.env.example を元にコピーして作成します。  
(composer create-project 時は .env は作成されますが、 composer install 時は .env ファイルは作成されません。)


```
[app] $ cp .env.example .env
```


エラー画面の見た目が変わりました。  
これは .env で APP_DEBUG=true が設定されているためです。  

エラーメッセージはログと同じく .env に APP_KEY= の値がないと出ています。  
アプリケーションキーはこのコマンドで生成できます。  

```
[app] $ php artisan key:generate
Application key set successfully.
```

とりあえず、Welcome画面が表示されました。

続いて、 public/storage から storage/app/public へのシンボリックリンクを張ります。  
システムで生成したファイル等をブラウザからアクセスできるよう公開するためにシンボリックリンクを張ってます。  

```
[app] $ php artisan storage:link
```

最後にマイグレーションを実行して適用されればOKです。

```
[app] $ php artisan migrate
```

```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (49.19ms)
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table (84.15ms)
Migrating: 2019_08_19_000000_create_failed_jobs_table
Migrated:  2019_08_19_000000_create_failed_jobs_table (39.74ms)
Migrating: 2019_12_14_000001_create_personal_access_tokens_table
Migrated:  2019_12_14_000001_create_personal_access_tokens_table (54.08ms)
```

お疲れ様でした🙌🙌 コンテナを停止して終了してください。

```
[app] $ exit
[mac] $ docker compose down
```