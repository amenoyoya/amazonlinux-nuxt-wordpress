# Vagrant トラブルシューティング

## Vagrantのネットワーク処理が異常に遅い場合の解決策

- 参考:
    - https://qiita.com/s-kiriki/items/357dc585ee562789ac7b
    - https://github.com/hashicorp/vagrant/issues/1172

Vagrant内のネットワーク処理（外部APIを叩く等）が極端に遅い場合、以下の設定を追加すると上手くいくことがある

### Vagrantfile
```ruby
config.vm.provider :virtualbox do |vb|
  # 以下の設定を追記
  vb.customize ["modifyvm", :id, "--natdnsproxy1", "off"]
  vb.customize ["modifyvm", :id, "--natdnshostresolver1", "off"]

  # ...(略)...
end
```

