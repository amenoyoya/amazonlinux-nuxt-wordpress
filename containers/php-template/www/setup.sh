#!/bin/bash
# -- www-data@docker://web/

# プロジェクト準備
mkdir -p ./app/public/
echo '<?php phpinfo() ?>' > ./app/public/index.php
