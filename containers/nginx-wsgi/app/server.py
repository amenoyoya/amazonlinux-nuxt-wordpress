'''
WSGIサーバ: 以下のような関数を定義すればAPサーバになる
    function(環境, レスポンス) => [バイナリレスポンス]
'''

# 以下のようなサーバを定義
## レスポンスヘッダ: 200 OK [Content-Type: text/html]
## レスポンス: 'Hello, world'
def application(env, start_response):
    start_response('200 OK', [('Content-Type','text/html')])
    return [b"Hello World"]
