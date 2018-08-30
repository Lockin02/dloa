$(function(){
    var viewActVal = $("#viewAct").val();
    // 付款审批查看页面
    if(viewActVal != undefined && viewActVal == 'auditView'){
        var mainId = $("#id").val();
        //ajax检查该审批是否为第一个审批步骤
        // var responseText = $.ajax({
        //     url:'index1.php?model=contract_other_other&action=ajaxChkIsFirstAudit',
        //     data : {'id':mainId},
        //     type : "POST",
        //     async : false
        // }).responseText;
        // var canEdit = (responseText == 'ok')? true : false;
        var canEdit = true;

        // 检查并判断是否能显示延后回款天数修改输入款
        if($("#EXT2").css("display") != 'none' && canEdit){
            // $("#delayPayDaysShow").hide();
            // $("#delayPayDaysInput")[0].type = "number";

            $("#delayPayDaysInput").blur(function(){
                var oldDelayPayDays = $("#delayPayDaysShow").text();
                var newDelayPayDays = $(this).val();
                if((/^(\+|-)?\d+$/.test( newDelayPayDays )) && newDelayPayDays >= 0){
                    if(newDelayPayDays > 60){
                        alert("延后回款天数修改范围不得大于60天！");
                        $(this).val(oldDelayPayDays);
                        return false;
                    }if(newDelayPayDays != oldDelayPayDays && confirm("确定要更新延后回款天数吗?")){
                        var responseText = $.ajax({
                            url:'index1.php?model=contract_other_other&action=ajaxUpdateDelayPayDaysTemp',
                            data : {'id':mainId,'delayPayDays':newDelayPayDays},
                            type : "POST",
                            async : false
                        }).responseText;
                        if(responseText == '1'){
                            $("#delayPayDaysShow").text(newDelayPayDays);
                            $("#updateResult").text("更新成功!");
                        }else{
                            $("#updateResult").text("更新失败!");
                        }
                        setTimeout(function(){
                            $("#updateResult").text("");
                        },2000);
                    }else{
                        $(this).val(oldDelayPayDays);
                    }
                }else{
                    alert("请输入正整数！");
                    $(this).val(oldDelayPayDays);
                    return false;
                }
            });
        }

        // 显示缓冲天数
        if($("#EXT2").css("display") != 'none'){
            $(".bufferDaysWrap").show();
            if($("#bufferDaysIsShow").val() != '1'){
                if($("#bufferDaysEditLimit").val() == 1){
                    $("#bufferDaysInput").show();
                    $("#bufferDaysShow").hide();

                    $("#bufferDaysInput").blur(function(){
                        var oldBufferDays = $("#bufferDaysShow").attr("data-org");
                        var newBufferDays = $(this).val();
                        if((/^(\+|-)?\d+$/.test( newBufferDays )) && newBufferDays >= 0){
                            if(confirm("确定要更新缓冲天数吗?")){
                                var responseText = $.ajax({
                                    url:'index1.php?model=contract_other_other&action=ajaxUpdateBufferDays',
                                    data : {'id':mainId,'bufferDays':newBufferDays},
                                    type : "POST",
                                    async : false
                                }).responseText;
                                if(responseText == '1'){
                                    $("#bufferDaysShow").text(newBufferDays);
                                    $("#updateBufferResult").text("更新成功!");
                                }else{
                                    $("#updateBufferResult").text("更新失败!");
                                }
                                setTimeout(function(){
                                    $("#updateBufferResult").text("");
                                },2000);
                            }
                        }else{
                            alert("请输入正整数！");
                            $(this).val(oldBufferDays);
                            return false;
                        }
                    });
                }
            }
        }
    }
});