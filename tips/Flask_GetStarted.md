# Flask入門

## Flaskとは
Flask
: Python用のWebフレームワーク
Djangoのようなフルセットのフレームワークとは異なり、必要最小限の機能のみを実装したマイクロフレームワーク

<br/>

## インストール
pipコマンドでインストール可能
```shell
pip install flask
```

<br/>

## Hello, World
Flaskを用いて、簡単なローカルサーバーを実装する

1. `index.py`を作成し、以下のように編集
    ```python
    # Flaskをインポートする
    from flask import Flask

    # 自身の名称を app という名前でインスタンス化する
    app = Flask(__name__)

    # ここからウェブアプリケーション用のルーティングを記述
    # index にアクセスしたときの処理
    @app.route('/')
    def index():
        return "Hello, World" # "Hello, World" を表示

    if __name__ == '__main__':
        app.debug = True # デバッグモード有効化
        app.run(port=8000) # http://localhost:8000 でサーバー実行
        #app.run(host='0.0.0.0', port=80) # どこからでもアクセス可能に
    ```
2. `python index.py` でローカルサーバーを起動
    - http://localhost:8000 にアクセスして、"Hello, World" が表示されていればOK

<br/>

## Webアプリケーション作成
Flaskには日本語化されたユーザーガイド（ http://a2c.bitbucket.org/flask/ ）があるため、目を通しておくと良い

ここでは、Flaskを用いてごく簡単なWebアプリケーションを作成してみる

### 仕様
1. ランダムなメッセージを表示し、名前の入力を受け付ける
2. 入力された名前をPOST送信
3. 入力された名前を表示

```plantuml
(*)->名前を入力
ランダムなメッセージを表示->名前を入力
名前を入力->POST送信処理
POST送信処理->名前を表示
名前を表示->(*)
```

---

### バックエンド処理
`index.py`を編集し、以下のように記述する
```python
# Flask などの必要なライブラリをインポートする
from flask import Flask, render_template, request, redirect, url_for
import numpy as np

# 自身の名称を app という名前でインスタンス化する
app = Flask(__name__)

# メッセージをランダムに表示するメソッド
def picked_up():
    messages = [
        "こんにちは、あなたの名前を入力してください",
        "やあ！お名前は何ですか？",
        "あなたの名前を教えてね"
    ]
    # NumPy の random.choice で配列からランダムに取り出し
    return np.random.choice(messages)

# ここからウェブアプリケーション用のルーティングを記述
# index にアクセスしたときの処理
@app.route('/')
def index():
    title = "ようこそ"
    message = picked_up()
    # index.html をレンダリングする
    return render_template('index.html',
                           message=message, title=title)

# /post にアクセスしたときの処理
@app.route('/post', methods=['GET', 'POST']) # GET, POST両方を受け付ける
def post():
    title = "こんにちは"
    if request.method == 'POST':
        # リクエストフォームから「名前」を取得して
        name = request.form['name']
        # index.html をレンダリングする
        return render_template('index.html',
                               name=name, title=title)
    else:
        # エラーなどでリダイレクトしたい場合
        return redirect(url_for('')) # / にリダイレクト

if __name__ == '__main__':
    app.debug = True # デバッグモード有効化
    app.run(port=8000) # http://localhost:8000 でサーバー実行
    #app.run(host='0.0.0.0', port=80) # どこからでもアクセス可能に
```

---

### 画面の実装
FlaskではJinja2というテンプレートエンジンが採用されている

Jinja2
: http://jinja.pocoo.org/docs/dev/

基本的には普通の HTML だが、特殊な記法を間にはさむことによって Python から渡されたオブジェクトの表示や、条件分岐、ループ等の処理が可能

#### レイアウトの準備
すべての画面に CSS や JavaScript を読み込むためのヘッダーなどを書くのは非効率的なので、共通部分を `layout.html` のようにレイアウト用の HTML としてまとめてしまうことが多い

今回は CSS フレームワークである Bootstrap を利用する

Flask では CSS や JavaScript のようなファイルは `/static` ディレクトリに置くようになっているため、公式サイトからダウンロードした Bootstrap の .zip を展開して `static` ディレクトリの下に配備する

また、 `templates` というディレクトリを用意して、この下に HTML を配置する

```shell
/
|-- static/
|   |-- css/
|   |   |-- bootstrap.min.css # Bootstrap CSS
|   |
|   |-- js/
|       |-- bootstrap.min.js # Bootstrap Javascript
|
|-- templates/
|   |-- index.html # indexページ
|   |-- layout.html # 共通レイアウトHTML
|
|-- index.py # Webアプリケーション バックエンドプログラム
```

`layout.html`を以下のように編集する（基本的に Bootstrap の **Basic Template** そのまま）
```html
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {% if title %}
      <title>{{ title }}</title>
    {% else %}
      <title>Bootstrap 101 Template</title>
    {% endif %}
    <!-- Bootstrap -->
    <link href="/static/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    {% block content %}{% endblock %}
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/static/js/bootstrap.min.js"></script>
  </body>
</html>
```

`{% ... %}` 及び `{{ ... }}` で囲まれた部分が Jinja2

今回の例では

- タイトル（`title`）がインスタンスとして存在すればそれを表示する
- `content` という名前のブロックをはさむ

という処理をしている

<br/>

#### 個別のページの実装
今回は `index.html` の1ページのみ実装すればよい
```html
{% extends "layout.html" %}

{% block content %}
<!-- Form -->
<div class="form">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <p class="lead">
          {% if name %}
            こんにちは {{ name }} さん
          {% else %}
            {{ message }}
          {% endif %}
        </p>
        <form action="/post" method="post" class="form-inline">
          <label for="name">名前</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Name">
          <button type="submit" class="btn btn-default">送信する</button>
        </form>
      </div>
    </div>
  </div>
</div>
{% endblock %}
```

最初の `{% extends "layout.html" %}` という部分で `layout.html` を継承したページであることを示す

これにより、レイアウト以外の部分だけを記述すれば良くなる

また、 `{% block content %} ... {% endblock %}` で囲うことにより `content` という名前のブロックという扱いになる

この `content` ブロックは、レイアウトの `content` ブロックの部分にレンダリングされることになる

上記の場合、 `content` ブロック内部では

- `name` インスタンスがあれば名前を表示
- `name` インスタンスがなければメッセージ（`message`）を表示

するという処理を行っている

---

### アプリケーションの起動
以上のように、`index.py`, `templates/layout.html`, `templates/index.html` を準備できたらアプリケーションを起動する

```shell
python index.py
```

http://localhost:8000 にアクセスして、作成したアプリケーションが稼働することを確認する