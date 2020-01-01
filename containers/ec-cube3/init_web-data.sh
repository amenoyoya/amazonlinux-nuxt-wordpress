#!/bin/bash
mkdir web-data
curl -O http://downloads.ec-cube.net/src/eccube-3.0.18.tar.gz
tar -zxvf eccube-3.0.18.tar.gz -C web-data --strip-components=1
