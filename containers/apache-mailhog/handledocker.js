const fs = require('fs');
const handlebars = require('./docker/handlebars.min-v4.7.2');

/**
 * オプション引数をJSON化
 * @return opt {
 *   '__': [フラグなしの引数リスト],
 *   'オプション名': 'value',      // input: `--<オプション名> <value>`
 *   'オプション名': true/false,   // input: `+<オプション名>` => true, `-<オプション名>` => false
 *   'オプション名': [value list], // input: `++<オプション名> <value>`
 *   ...
 * }
 */
const getopt = () => {
  const opt = {'__': []};
  const re_opt   = /^\-\-(.+)/;
  const re_true  = /^\+([^\+].*)/;
  const re_false = /^\-([^\-].*)/;
  const re_array = /^\+\+(.+)/;
  let mode = {flag: 0, capture: ''};
  for (let i = 2; i < process.argv.length; ++i) {
    const arg = process.argv[i];
    switch (mode.flag) {
    case 1: // --option
      opt[mode.capture] = arg;
      mode.flag = 0;
      break;
    case 2: // ++option
      if (Array.isArray(opt[mode.capture])) {
        opt[mode.capture].push(arg);
      } else {
        opt[mode.capture] = [arg];
      }
      mode.flag = 0;
      break;
    default:
      let res = false;
      if (res = arg.match(re_opt)) {
        // --option
        mode.flag = 1;
        mode.capture = res[1];
        break;
      }
      if (res = arg.match(re_true)) {
        // +option
        opt[res[1]] = true;
        break;
      }
      if (res = arg.match(re_false)) {
        // -option
        opt[res[1]] = false;
        break;
      }
      if (res = arg.match(re_array)) {
        // ++option
        mode.flag = 2;
        mode.capture = res[1];
        break;
      }
      opt['__'].push(arg);
      break;
    }
  }
  return opt;
};

/**
 * usage: $ node handledocker.js [options]
 * options:
 *   --host <ドメイン名>: (本番公開時指定)ドメイン名
 *   --email <メールアドレス>: Let's Encrypt 申請時のメールアドレス
 *   +noproxy: nginx-proxy と letsencrypt コンテナを定義しない
 *   -noproxy (default): nginx-proxy と letsencrypt コンテナを定義する
 */
const template = handlebars.compile(fs.readFileSync('./docker/docker-compose.handlebars', 'utf-8'));
fs.writeFileSync('./docker-compose.yml', template(getopt()));
