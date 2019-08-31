# nginx-uwsgi-flask

## Architecture

- WebServer: nginx
- uWSGI Emperor: 複数uWSGI管理
    - WSGI Server: uWSGI
    - App Server: Python + Flask

### Docker Containers
- nginx-proxy
    - `proxy`コンテナ
        - VIRTUAL_HOST振り分け用
- nginx-uwsgi
    - `web`コンテナ
        - Image: `nginx:1.17-alpine`
    - `flask`コンテナ
        - Image: `python:3.7-alpine`
        - WSGI Server: uWSGI
        - App Server: Python + Flask


### Structures
```bash
./
 |_ app/ # Webアプリ
 |   |_ app1/ # Flaskアプリケーション1
 |   |_ app2/ # Flaskアプリケーション2
 |   |_ static/ # 静的ファイル格納ディレクトリ（共通）
 |   |_ vassals/ # uWSGI起動設定ファイル格納ディレクトリ
 |       |_ app1.ini # app1用uWSGI設定
 |       |_ app2.ini # app2用uWSGI設定
 |
 |_ flask/ # flaskコンテナ作成用
 |   |_ Dockerfile
 |   |_ requirements.txt # 依存Pythonモジュール記述用
 |
 |_ web/ # webコンテナ作成用
     |_ logs/ # Nginxログファイル格納ディレクトリ
     |_ Dockerfile
     |_ nginx.conf # Nginxリバースプロキシ設定
```
