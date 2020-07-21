/*
nginx.conf.go: Generate nginx virtual host conf files
@Copyright (C) amenoyoya 2020. https://github.com/amenoyoya/
@License: MIT

$ go run nginx.conf.go << EOS
[
    {
        "host": "<string> server_name (required)",
        "root": "<string> document root directory (required | port)",
        "port": "<int> proxy target port (required | root)",
        "certfile": "<string> ssl_certificate (optional)",
        "cerkey": "<string> ssl_certificate_key (optional)",
        "basicauth": "<string> auth_basic_user_file (optional)"
    },
    ...
]
EOS

=> generate: /etc/nginx/conf.d/[each "host"].conf
*/
package main

import (
    "bufio"
    "os"
    "log"
    "encoding/json"
    "io/ioutil"
    "bytes"
    "text/template"
)

/* NginxConf構造体
 * - 構造体のフィールド名は大文字で始める必要がある
 * - 構造体のフィールド名と JSON フィールド名が異なる場合はアノテーションをつける
 */
type NginxConf struct {
    // required
    Host string `json:"host"` // server_name
    // required each of one
    Port int `json:"port"` // port_forwarding
    Root string `json:"root"` // root_dir
    // optional
    CertFile string `json:"certfile"` // ssl_certificate
    CertKey string `json:"certkey"` // ssl_certificate_key
    BasicAuth string `json:"basicauth"` // auth_basic_user_file
    LegoRoot string `json:legoroot` // lego webroot /.well-known/acme-challenge
}

// 標準入力から JSON データ取得
func parseJsonFromStdin() []NginxConf {
    // 標準入力 Reader
    reader := bufio.NewReader(os.Stdin)
    // JSON データとして []NginxConf 型に parse
    var data []NginxConf
    dec := json.NewDecoder(reader)
    if err := dec.Decode(&data); err != nil {
        // エラー時: 標準エラーに出力してプログラム終了
        log.Fatal(err)
    }
    return data
}

// nginx.conf.gtpl テンプレートを NginxConf に沿って展開
func parseNginxConfTemplate(data NginxConf) bytes.Buffer {
    // validation
    if data.Host == "" {
        log.Fatal("host が指定されていません: ", data)
    }
    if data.Port == 0 && data.Root == "" {
        log.Fatal("port or root を指定する必要があります: ", data)
    }
    if (data.CertFile != "" && data.CertKey == "") || (data.CertFile == "" && data.CertKey != "") {
        log.Fatal("certfile, certkey は両方指定する、もしくは両方指定しないでください: ", data)
    }
    if data.LegoRoot == "" {
        // lego webroot が指定されていない場合は /path/to/server_util/lego/webroot/ を設定
        data.LegoRoot = os.Getenv("DOCKS_ROOT") + "/lego/webroot/"
    }
    // read template
    buf, err := ioutil.ReadFile("/work/tools/nginx.conf.gtpl")
    if err != nil {
        log.Fatal(err)
    }
    // text/template 展開
    t, err := template.New("nginx.conf").Parse(string(buf))
    if err != nil {
        log.Fatal(err)
    }
    var res bytes.Buffer
    if err := t.Execute(&res, data); err != nil {
        log.Fatal(err)
    }
    return res
}

func main() {
    // conf ファイル格納ディレクトリ作成（存在する場合は削除して新規作成）
    // os.RemoveAll("/etc/nginx/conf.d/")
    // if err := os.Mkdir("/app/nginx.conf/", 0755); err != nil {
    //     log.Fatal(err)
    // }
    for _, data := range parseJsonFromStdin() {
        buf := parseNginxConfTemplate(data)
        if err := ioutil.WriteFile("/etc/nginx/conf.d/" + data.Host + ".conf", buf.Bytes(), 0755); err != nil {
            log.Fatal(err)
        }
    }
}
