<html>
    <head>
        <title>���ʵ���</title>
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
                    <td colspan="2" align="center" height="30"><b>����˽Կ����</b>
                    </td>
                </tr>
                <tr>
                    <td  height="120">����������˽Կ��</td>
                    <td>
                        <input type="password" id="xdata" size="40" name="xfile" class="BigInput"  >
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center" height="30">
                        <input type="button" value=" ȷ �� " class="BigInput" title="�޸�" onclick="setPrivateKey()" >
                    </td>
                </tr>
            </table>
        </div>
        <script type="text/javascript">
            function setPrivateKey(){
                if($("#xdata").val()==""){
                    alert('������˽Կ��');
                    return false;
                }
                var rand=Math.random()*100000;
                $.post('?model=salary',{timer:rand,prikey:$("#xdata").val()},
                function (data)
                {
                    if (data=='2')
                    {
                        alert('�Ƿ�ID��������������');
                    }else{
                            if(data=='4'){
                                alert('�������˽Կ����ȷ�����������룡');
                            }else{
                                alert('�������˽Կ��ȷ��');
                                location.reload();
                            }
                        }
                    }
                )
                }
        </script>
    </body>
</html>