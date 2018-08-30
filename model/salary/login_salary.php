<html>
    <head>
        <title>工资导入</title>
        <meta http-equiv="content-type" content="text/html; charset=gb2312">
        <link rel="stylesheet" type="text/css" media="screen" href="js/jqgrid/css/ui.jqgrid.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="js/jqgrid/css/jquery-ui-1.8.2.custom.css" />
        <script type="text/javascript" src="js/jqgrid/jquery-1.4.2.min.js"></script>
        <style>
            <!--
            html, body {
                margin: 0;			/* Remove body margin/padding */
                padding: 0;
                overflow: hidden;	/* Remove scroll bars on browser window */
                font-size: 9pt;
            }
            .ui-jqgrid{
                border-top: 0px;
                border-right: 1px solid #AED0EA;
                border-left: 1px solid #AED0EA;
                border-bottom: 1px solid #AED0EA;
            }
            #t_rowedgrid{ height: 23px; }
            -->
        </style>
    </head>
    <body>
        <br>
        <br>
        <br>
        <br>
        <div id="hr_user_new"  style="left: 0px; top: 0px; text-align: center;" >
            <table class="ui-widget-content ui-corner-all" align="center" style="text-align: center;font-size: 12px;" >
                <tr class="ui-widget-header ui-corner-all">
                    <td colspan="2" align="center" height="30"><b>工资私钥输入</b>
                    </td>
                </tr>
                <tr>
                    <td  height="120">请输入您的私钥：</td>
                    <td>
                        <input type="password" id="xdata" size="40" name="xfile" class="BigInput"  >
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center" height="30">
                        <input type="button" value=" 确 定 " class="BigInput" title="修改" onclick="setPrivateKey()" >
                    </td>
                </tr>
            </table>
        </div>
        <script type="text/javascript">
            function setPrivateKey(){
                if($("#xdata").val()==""){
                    alert('请输入私钥！');
                    return false;
                }
                var rand=Math.random()*100000;
                $.post('?model=salary',{timer:rand,prikey:$("#xdata").val()},
                function (data)
                {
                    if (data=='2')
                    {
                        alert('非法ID或输入数据有误！');
                    }else{
                            if(data=='4'){
                                alert('您输入的私钥不正确，请重新输入！');
                            }else{
                                alert('您输入的私钥正确！');
                                location.reload();
                            }
                        }
                    }
                )
                }
        </script>
    </body>
</html>