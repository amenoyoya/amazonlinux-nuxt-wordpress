server {
    listen 80;
    server_name {{.Host}};
{{- if .LegoRoot}}
    location /.well-known/acme-challenge/ {
        root {{.LegoRoot}};
        index index.html;
    }
{{- end}}
{{- if .CertFile}}
    return 301 https://{{.Host}}$request_uri;
{{- else}}
    location / {
{{- if .Root}}
        root {{.Root}};
        index index.html;
{{- else if .Port}}
        proxy_set_header Host $http_host;
        proxy_pass http://localhost:{{.Port}};
{{- end}}

{{- if .BasicAuth}}
        auth_basic "StaffOnly";
        auth_basic_user_file {{.BasicAuth}};
{{- end}}
    }
{{- end}}
}

{{- if .CertFile}}
server {
    listen 443 ssl;
    server_name {{.Host}};
{{- if .LegoRoot}}
    location /.well-known/acme-challenge/ {
        root {{.LegoRoot}};
        index index.html;
    }
{{- end}}
    location / {
{{- if .Root}}
        root {{.Root}};
        index index.html;
{{- else if .Port}}
        proxy_set_header Host $http_host;
        proxy_pass http://localhost:{{.Port}};
{{- end}}

{{- if .BasicAuth}}
        auth_basic "StaffOnly";
        auth_basic_user_file {{.BasicAuth}};
{{- end}}
    }
    ssl_certificate {{.CertFile}};
    ssl_certificate_key {{.CertKey}};
}
{{- end}}
