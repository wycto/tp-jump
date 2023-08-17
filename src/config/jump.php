<?php
/**
 * Created by PhpStorm.
 * User: wycto
 * Date: 2023/8/15
 * Time: 19:20
 */
return[
    // 默认跳转页面对应的模板文件
    'success_tpl' => app()->getRootPath().'/vendor/wycto/tp-jump/src/tpl/success.tpl',
    'error_tpl'   => app()->getRootPath().'/vendor/wycto/tp-jump/src/tpl/error.tpl',
    'jsonMethod' => ['POST'] //哪些请求类型返回json格式
];
