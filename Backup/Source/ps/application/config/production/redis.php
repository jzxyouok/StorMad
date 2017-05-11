<?php
defined('BASEPATH') or die('非法请求');

$config['socket_type'] = 'tcp'; //`tcp` or `unix`
#$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = '127.0.0.1';
$config['password'] = '123456';
$config['port'] = 6379;
$config['timeout'] = 0;
