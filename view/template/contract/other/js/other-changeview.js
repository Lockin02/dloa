$(function () {
    var thisObj;
    $.ajax({
        type: "POST",
        url: "?model=common_changeLog&action=getChangeInformation",
        data: {tempId: $("#pid").val(), logObj: "other"},
        async: false,
        success: function (data) {
            if (data) {
                data = eval("(" + data + ")");
                var mainTypeSltedVal = $("#payForBusinessMain").find("option:selected").val();
                var catchObjective= {};
                for (var i = 0; i < data.length; i++) {
                    thisObj = $("#" + data[i]['changeField']);
                    if (thisObj.attr("class") == "formatMoney") {
                        thisObj.html(moneyFormat2(data[i]['oldValue']) + " => " + moneyFormat2(data[i]['newValue']));
                    }
                    else {
                        thisObj.html(data[i]['oldValue'] + ' => ' + data[i]['newValue']);
                    }
                    thisObj.attr('style', 'color:red');

                    if(data[i]['changeField'] == "chanceCode"){
                        catchObjective.chanceCode = {
                            oldVal : data[i]['oldValue'],
                            newVal : data[i]['newValue']
                        }
                    }else if(data[i]['changeField'] == "contractCode"){
                        catchObjective.contractCode = {
                            oldVal : data[i]['oldValue'],
                            newVal : data[i]['newValue']
                        }
                    }
                }

                if(mainTypeSltedVal == 'FKYWLX-06' && catchObjective.contractCode != undefined && catchObjective.chanceCode != undefined){// �б��������⴦��
                    console.log(catchObjective);
                    if(catchObjective.contractCode.oldVal != '' && catchObjective.contractCode.newVal == '' && catchObjective.chanceCode.newVal != ''){// ��ͬ���̻�
                        $("#codeType").html("���ۺ�ͬ => �̻�").attr('style', 'color:red');
                        $("#codeValue").html(catchObjective.contractCode.oldVal + " => " + catchObjective.chanceCode.newVal).attr('style', 'color:red');
                        // console.log("��ͬ���̻�");
                    }else if(catchObjective.chanceCode.oldVal != '' && catchObjective.chanceCode.newVal == '' && catchObjective.contractCode.newVal != ''){// �̻��ĺ�ͬ
                        $("#codeType").html("�̻� => ���ۺ�ͬ").attr('style', 'color:red');
                        $("#codeValue").html(catchObjective.chanceCode.oldVal + " => " + catchObjective.contractCode.newVal).attr('style', 'color:red');
                        // console.log("�̻��ĺ�ͬ");
                    }
                }
            }
        }
    });

    if ($("#isNeedStamp").val() == '��' && $("#isStamp").val() == '��') {
        $("#isNeedStampView").show();
    }

    if ($("#isNeedRestamp").val() == '��') {
        $("#isNeedRestampView").show();
    }

    //�����ͬ��ʼ��
    if ($("#fundType").val() == 'KXXZB') {
        $("#projectInfo").show();

        // ������Ϣ�¼��ֶ�
        $('.payForBusinessMainTd').show();
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        var mainTypeCode = mainTypeSlted.val();
        var extCode = mainTypeSlted.attr("e1");
        switch (extCode){
            case 'FKYWLX_EXT1':
                $(".prefBidDateWrap").show();
                $("#EXT1").show();
                $("#EXT1-2").show();
                break;
            case 'FKYWLX_EXT2':
                $("#EXT2").show();
                $("#EXT1-2").show();
                break;
            case 'FKYWLX_EXT3':
                $("#EXT1-2").hide();
                $("#EXT3").show();
                break;
            case 'FKYWLX_EXT4':
                $(".relativeContract").show();
                $("#EXT4").show();
                break;
            case 'FKYWLX_EXT5':
                $("#EXT1").show();
                $(".prefBidDateWrap").hide();
                break;
        }

        // ��鲢�ж��Ƿ�����ʾ�Ӻ�ؿ������޸������
        if($("#EXT2").css("display") != 'none'){
            // $("#delayPayDaysShow").hide();
            // $("#delayPayDaysInput")[0].type = "number";
            var mainId = $("#id").val();

            $("#delayPayDaysInput").blur(function(){
                var oldDelayPayDays = $("#delayPayDaysShow").text();
                var newDelayPayDays = $(this).val();
                if((/^(\+|-)?\d+$/.test( newDelayPayDays )) && newDelayPayDays >= 0){
                    if(newDelayPayDays > 60){
                        alert("�Ӻ�ؿ������޸ķ�Χ���ô���60�죡");
                        $(this).val(oldDelayPayDays);
                        return false;
                    }else if(newDelayPayDays != oldDelayPayDays && confirm("ȷ��Ҫ�����Ӻ�ؿ�������?")){
                        var responseText = $.ajax({
                            url:'index1.php?model=contract_other_other&action=ajaxUpdateDelayPayDaysTemp',
                            data : {'id':mainId,'delayPayDays':newDelayPayDays,'isChange':1},
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

        //��ʾ���÷�̯��ϸ
        $("#shareGrid").costShareGrid({
            url: "?model=finance_cost_costshare&action=listjson",
            param: {objType: 2, objId: $("#id").val(), isChange: 1},
            type: 'view',
            event: {
                'reloadData': function (e, g, data) {
                    if (!data) {
                        $("#shareGrid").hide();
                    }
                }
            }
        });
    }
});