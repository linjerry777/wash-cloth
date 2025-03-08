FROM php:8.2-fpm

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# 清理apt缓存
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 安装PHP扩展
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 获取最新的Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www

# 创建系统用户
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# 注意：不要复制应用程序，因为我们使用卷挂载
# 删除 COPY ./temp /var/www 这一行

# 设置目录权限
RUN chown -R www:www /var/www

# 切换到非root用户
USER www

# 暴露端口9000
EXPOSE 9000

CMD ["php-fpm"] 