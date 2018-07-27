<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="loginHtml">
<head>
    <meta charset="utf-8">
    <title>登录--X-Manage</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="/Public/static/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/Public/static/css/public.css" media="all" />
    <link rel="stylesheet" href="/Public/static/css/jquery.toast.min.css" media="all" />
    <style type="text/css">
        .vaptcha-init-main {
            display: table;
            width: 100%;
            height: 100% background-color: #EEEEEE;
        }

        .vaptcha-init-loading {
            display: table-cell;
            vertical-align: middle;
            text-align: center
        }

        .vaptcha-init-loading>a {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: none;
        }

        .vaptcha-init-loading>a img {
            vertical-align: middle
        }

        .vaptcha-init-loading .vaptcha-text {
            font-family: sans-serif;
            font-size: 12px;
            color: #CCCCCC;
            vertical-align: middle
        }
    </style>
</head>
<body class="loginBody">
<form class="layui-form" id="loginform" style="opacity: 0.9;">
    <div class="login_face" style="text-align: center"><img src="/Public/static/images/jqm.jpg" class="userAvatar"></div>
    <div class="layui-form-item input-item">
        <label for="userName">用户名</label>
        <input type="text" placeholder="请输入用户名" autocomplete="off" id="userName" class="layui-input" name="username" lay-verify="required" lay-verType="tips">
    </div>
    <div class="layui-form-item input-item">
        <label for="password">密码</label>
        <input type="password" lay-verType="tips" name="password" placeholder="请输入密码" autocomplete="off" id="password" class="layui-input" lay-verify="required">
    </div>
    <div class="layui-form-item input-item" id="imgCode">
        <div class="vaptcha-container" style="width:100%;height:36px;">
            <!--vaptcha-container是用来引入VAPTCHA的容器，下面代码为预加载动画，仅供参考-->
            <div class="vaptcha-init-main">
                <div class="vaptcha-init-loading">
                    <a href="https://vaptcha.com" target="_blank">
                        <img src="https://cdn.vaptcha.com/vaptcha-loading.gif" />
                    </a> <span class="vaptcha-text">VAPTCHA启动中...</span>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="token">
    <div class="layui-form-item">
        <button class="layui-btn layui-block layui-btn-disabled" id="btn" lay-filter="login" lay-submit disabled>登录</button>
    </div>
</form>
<script type="text/javascript" src="/Public/static/js/jquery.min.js"></script>
<script type="text/javascript" src="/Public/static/layui/layui.js"></script>
<script type="text/javascript" src="/Public/static/js/jquery.toast.min.js"></script>
<script type="text/javascript" src="/Public/static/js/alert.js"></script>
<script type="text/javascript" src="https://cdn.vaptcha.com/v2.js"></script>
<script type="text/javascript">

    let ob = {};
    vaptcha({
        vid: '<?php echo ($vid); ?>', // 验证单元id
        type: 'click', // 显示类型 点击式
        container: '.vaptcha-container' // 按钮容器，可为Element 或者 selector
    }).then(function (vaptchaObj) {
        ob = vaptchaObj;
        vaptchaObj.listen('pass',function(){
            $('input[name=token]').val(ob.getToken());
            $("#btn").removeClass('layui-btn-disabled').removeAttr('disabled');
        });
        vaptchaObj.render()// 调用验证实例 vpObj 的 render 方法加载验证按钮
    });

    layui.use(['form','layer','jquery'],function(){
        var form = layui.form,
            layer = parent.layer === undefined ? layui.layer : top.layer;
        //登录按钮
        layer.alert("账号：<span style='color: #01AAED'>testdemo</span><br>密码：<span style='color: #dd4444'>123456</span>");
        form.on("submit(login)",function(data){
            $("#codeImg").click();
            var _this = $(data.elem);
            _this.text("登录中...").attr("disabled","disabled").addClass("layui-btn-disabled");
            $.ajax({
                url:"/admin/login/check",
                type:'post',
                dataType:'json'
                ,data:{para:$("form").serialize()}
                ,success:function(data){
                    if(1 === data.status){
                        toast('登陆成功','Success',0);
                        setTimeout(function(){
                            window.location.href = "<?php echo (urldecode($_GET['jumpLink'])); ?>" ? "<?php echo (urldecode($_GET['jumpLink'])); ?>" : "<?php echo ($url); ?>";
                        },1000);
                    }else{
                        $('input[name=token]').val('');
                        ob.reset();
                        _this.text("登录").addClass('layui-btn-disabled').prop('disabled',true);
                        toast(data.info,data.code);
                    }
                }
                ,error:function(data){
                    _this.text("登录").addClass('layui-btn-disabled').prop('disabled',true);
                    $('input[name=token]').val('');
                    ob.reset();
                    toast(data.info,data.code);
                }
            });
            return false;
        });
        //表单输入效果
        $(".loginBody .input-item").click(function(e){
            e.stopPropagation();
            $(this).addClass("layui-input-focus").find(".layui-input").focus();
        });
        $(".loginBody .layui-form-item .layui-input").focus(function(){
            $(this).parent().addClass("layui-input-focus");
        });
        $(".loginBody .layui-form-item .layui-input").blur(function(){
            $(this).parent().removeClass("layui-input-focus");
            if($(this).val() != ''){
                $(this).parent().addClass("layui-input-active");
            }else{
                $(this).parent().removeClass("layui-input-active");
            }
        })
    });
    console.log("联系邮箱： %ci@xiny9.com","font-size:20px;color:red");
</script>
<script type="text/javascript" src="/Public/static/js/bg.js"></script>
</body>
</html>