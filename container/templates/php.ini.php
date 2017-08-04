date.timezone=UTC
display_errors=Off
log_errors=On
; Maximum amount of memory a script may consume (128MB)
; http://php.net/memory-limit
memory_limit = 256M
; Maximum allowed size for uploaded files.
; http://php.net/upload-max-filesize
upload_max_filesize = 20M
; Sets max size of post data allowed.
; http://php.net/post-max-size
post_max_size = 20M
max_execution_time=180

<?php if (getenv('INSTALL_XDEBUG') == 'true') {
    ?>
    xdebug.remote_enable=1
    xdebug.remote_port=9000
    <?php if (empty(getenv('XDEBUG_REMOTE_HOST'))) {
        ?>
        xdebug.remote_connect_back=1
        <?php
    } else {
        ?>
        xdebug.remote_connect_back=0
        xdebug.remote_host=<?php echo getenv('XDEBUG_REMOTE_HOST'); ?>
        <?php
    } ?>
    xdebug.remote_autostart=1
    xdebug.var_display_max_depth=10
    xdebug.remote_log="/var/log/xdebug.log"
    <?php
} else {
    ?>
    # XDebug is deactivated
    <?php
} ?>
