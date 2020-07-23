# 初始化

```linux
composer init
```

# 创建项目

```shell
# --prefer-dist从dist安装
# --prefer-source从source安装
composer create-project --prefer-dist laravel/laravel mylaravekl
```



# 安装

```shell
# 如果存在composer.lock文件,会优先读取该文件的内容,安装该文件里面对应版本的依赖文件,如果没有,会读取composer.json,安装对应的依赖
composer install
# 安装依赖包
composer require laravel/password
```

# 更新

```shell
# 更新composer版本
composer self-update
# 获取最新版本依赖并安装,修改composer.lock文件里面的依赖文件信息以及版本
composer update
# 更新package1与package2
composer update vender/package1 vender/package2
```

# 搜索

```shell
composer search packagename
```

# 卸载依赖包

```shell
composer remove laravel/password
```

# 修改镜像

```shell
#将镜像修改为阿里云镜像
# -g 全局设置
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```

# 配置查看

```shell
# 全局
composer config -l -g
# 当前项目
composer config -l
# 查看镜像
composer config -g repo.packagist
```

# 依赖包版本约束

1. **>** ,>=,<,<=,!=,=
2. \*:通配,1.0.* 与 >=1.0,<1.1 是等效的
3. ~:只改变最后末尾的版本,如~1.2.3表示版本在1.2.3与1.3.0之间
4. ^:保持大版本不变,如^2.3.6表示版本在2.3.6与3.0之间

# 自动加载

```shell
composer dump-autoload
```

