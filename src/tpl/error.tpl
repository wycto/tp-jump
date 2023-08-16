<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>失败</title>
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        .message{ padding: 24px 48px; text-align: center}
        .message .jump{ padding-top: 10px; }
        .message .jump a{ color: #333; }
        .message .error{ line-height: 1.5em; font-size: 30px; }
        .message .detail{ line-height: 1.5em; margin-top: 12px;margin-top: 10px;}
    </style>
</head>
<body>
    <div class="message">
        <p class="error">失败</p>
        <p class="detail"><?php echo(strip_tags($msg));?></p>
        <p class="jump">
            <?php if($url):?>
            页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
            <?php else:?>
            当前页面不跳转
            <?php endif;?>
        </p>
    </div>
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</body>
</html>
