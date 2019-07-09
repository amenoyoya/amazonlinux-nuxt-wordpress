# AlpineLinux + nodejs + gcc 9.1.0

## Setup

### Docker Containers
- Containers:
    - app: FROM `alpine:8.3`
        - Gnu C++ Compiler: `9.1.0`
        - nodejs: `8.14.0`
        - yarn (package manager): `1.16.0`

---

### Setup
- Prepare Docker container image: `alpine-node-gcc:9.1.0`
    - Run `cmd.exe`
        ```bash
        # Combine tar files
        > copy /b alpine-node-gcc.9.1.0_src\alpine-node-gcc.9.1.0.tar_* alpine-node-gcc.9.1.0.tar

        # Load Docker image
        > docker load < alpine-node-gcc.9.1.0.tar
        ```
- Build up Docker containers
    ```bash
    > docker-compose up
    ```

#### cf) How to build alpine-node-gcc:9.1.0
See [src/Dockerfile](./src/Dockerfile)

***

## Test run

### Create sample C++ program
- **app/hello.cpp**
    ```cpp
    #include <bits/stdc++.h>
    using namespace std;

    int main() {
        cout << "Hello, world!" << endl;
        return 0;
    }
    ```
- Compile `hello.cpp`
    ```bash
    # Attach to Docker container: app
    $ docker-compose exec app ash

    # call package.json:scripts.build
    ## => node compile.js $1
    ## => gcc "$1.cpp" -lstdc++ -o "$1"
    % yarn build hello
    ```
- Execute `hello`
    ```bash
    # In Docker container: app

    # Execute
    % ./hello
    Hello, world!
    ```
