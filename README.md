![](http://www.wycto.cn/static/img/logo.png)

# [gitee仓库](https://gitee.com/wycto/tp-jump)

# tp-jump

适用于thinkphp的跳转扩展

## 安装

~~~php
composer require wycto/tp-jump

thinkphp 6.0
composer require wycto/tp-jump:^6.0
~~~

## 配置
~~~php
// 安装之后会在config目录里生成jump.php配置文件
return[
    // 默认跳转页面对应的模板文件
    'success_tpl' => app()->getRootPath().'/vendor/wycto/tp-jump/src/tpl/success.tpl',
    'error_tpl'   => app()->getRootPath().'/vendor/wycto/tp-jump/src/tpl/error.tpl',
];
~~~

## 用法示例

使用 use wycto\tp-jump\Jump; 

在所需控制器内引用该扩展即可：
~~~php
<?php
namespace app\controller;

class Index 
{
    use \wycto\jump\Jump; 
    public function index()
    {
        //return $this->error('失败','data数据',['username' => 'wycto', 'sex' => '男']);
        //return $this->success('成功','data数据',['username' => 'wycto', 'sex' => '男'],'index/index');
        //return $this->redirect('/admin/index/index',302);
        //return $this->json(200,'ok','token',['name'=>'占三'],$header = []);
        return $this->result(200,'成功',['username' => 'wycto', 'sex' => '男']);  
    }
}
~~~