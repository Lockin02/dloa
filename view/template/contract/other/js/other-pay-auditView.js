$(function(){
    var viewActVal = $("#viewAct").val();
    // ���������鿴ҳ��
    if(viewActVal != undefined && viewActVal == 'auditView'){
        var mainId = $("#id").val();
        //ajax���������Ƿ�Ϊ��һ����������
        // var responseText = $.ajax({
        //     url:'index1.php?model=contract_other_other&action=ajaxChkIsFirstAudit',
        //     data : {'id':mainId},
        //     type : "POST",
        //     async : false
        // }).responseText;
        // var canEdit = (responseText == 'ok')? true : false;
        var canEdit = true;

        // ��鲢�ж��Ƿ�����ʾ�Ӻ�ؿ������޸������
        if($("#EXT2").css("display") != 'none' && canEdit){
            // $("#delayPayDaysShow").hide();
            // $("#delayPayDaysInput")[0].type = "number";

            $("#delayPayDaysInput").blur(function(){
                var oldDelayPayDays = $("#delayPayDaysShow").text();
                var newDelayPayDays = $(this).val();
                if((/^(\+|-)?\d+$/.test( newDelayPayDays )) && newDelayPayDays >= 0){
                    if(newDelayPayDays > 60){
                        alert("�Ӻ�ؿ������޸ķ�Χ���ô���60�죡");
                        $(this).val(oldDelayPayDays);
                        return false;
                    }if(newDelayPayDays != oldDelayPayDays && confirm("ȷ��Ҫ�����Ӻ�ؿ�������?")){
                        var responseText = $.ajax({
                            url:'index1.php?model=contract_other_other&action=ajaxUpdateDelayPayDaysTemp',
                            data : {'id':mainId,'delayPayDays':newDelayPayDays},
                            type : "POST",
                            async : false
                        }).responseText;
                        if(responseText == '1'){
                            $("#delayPayDaysShow").text(newDelayPayDays);
                            $("#updateResult").text("���³ɹ�!");
                        }else{
                            $("#updateResult").text("����ʧ��!");
                        }
                        setTimeout(function(){
                            $("#updateResult").text("");
                        },2000);
                    }else{
                        $(this).val(oldDelayPayDays);
                    }
                }else{
                    alert("��������������");
                    $(this).val(oldDelayPayDays);
                    return false;
                }
            });
        }

        // ��ʾ��������
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
                            if(confirm("ȷ��Ҫ���»���������?")){
                                var responseText = $.ajax({
                                    url:'index1.php?model=contract_other_other&action=ajaxUpdateBufferDays',
                                    data : {'id':mainId,'bufferDays':newBufferDays},
                                    type : "POST",
                                    async : false
                                }).responseText;
                                if(responseText == '1'){
                                    $("#bufferDaysShow").text(newBufferDays);
                                    $("#updateBufferResult").text("���³ɹ�!");
                                }else{
                                    $("#updateBufferResult").text("����ʧ��!");
                                }
                                setTimeout(function(){
                                    $("#updateBufferResult").text("");
                                },2000);
                            }
                        }else{
                            alert("��������������");
                            $(this).val(oldBufferDays);
                            return false;
                        }
                    });
                }
            }
        }
    }
});