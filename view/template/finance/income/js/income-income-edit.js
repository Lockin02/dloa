$(function() {
	$("input[name='income[isAdjust]']").each(function() {
		if ($("#isAdjustHidden").val() == $(this).val()) {
			$(this).attr('checked', true);
			return false;
		}
	});

    // ��ѡ�ͻ�
    $("#incomeUnitName").yxcombogrid_customer({
        hiddenId : 'incomeUnitId',
        isFocusoutCheck : false,
        height : 300,
        gridOptions : {
            showcheckbox : false,
            event : {
                'row_dblclick' : function(e, row, data) {
                    $("#province").val(data.Prov);
                    $("#contractUnitId").val(data.id);
                    $("#contractUnitName").val(data.Name);
                    $("#incomeUnitType").val(data.TypeOne);
                    $("#areaName").val(data.AreaName);
                    $("#areaId").val(data.AreaId);
                    $("#managerId").val(data.AreaLeaderId);
                    $("#managerName").val(data.AreaLeader);

                    //�ռ�������
                    if(data.SellManId != ""){
                        $("#TO_ID").val(data.SellManId);
                        $("#TO_NAME").val(data.SellMan);
                    }else{
                        if(data.Prov != ""){
                            //��ȡ��Ӧ������
                            $.ajax({
                                type: "POST",
                                url: "?model=system_saleperson_saleperson&action=getPersonByProvince",
                                data: {'province' : data.Prov},
                                async: false,
                                success: function(data){
                                    if(data){
                                        var dataArr = eval("(" + data + ")");
                                        $("#TO_ID").val(dataArr.personId);
                                        $("#TO_NAME").val(dataArr.personName);
                                    }
                                }
                            });
                        }
                    }

                    var sendNamesArr = new Array();
                    var sendUserIdArr = new Array();

                    if($("#sendName").val() != ""){
                        sendNamesArr.push($("#sendName").val());
                        sendUserIdArr.push($("#sendUserId").val());
                    }

                    if(data.AreaLeader != ""){
                        sendNamesArr.push(data.AreaLeader);
                        sendUserIdArr.push(data.AreaLeaderId);
                    }

                    $("#ADDIDS").val(sendUserIdArr.toString());
                    $("#ADDNAMES").val(sendNamesArr.toString());

                    //����ʡ�ݲ�ѯƥ������۸�����

                }
            }
        }
    });

    //������˾
    $("#businessBelongName").yxcombogrid_branch({
        hiddenId : 'businessBelong',
        height : 250,
        isFocusoutCheck : false,
        gridOptions : {
            showcheckbox : false
        }
    });

    // ��Ⱦ�ұ�
    var currencyObj = $("#currency");
    if(currencyObj.length > 0){
        // ���ұ�
        currencyObj.yxcombogrid_currency({
            hiddenId : 'currency',
            valueCol : 'currencyCode',
            isFocusoutCheck : false,
            gridOptions : {
                showcheckbox : false,
                event : {
                    'row_dblclick' : function(e, row, data) {
                        $("#rate").val(data.rate);
                        if(data.Currency != '�����'){
                            setMoney('incomeMoney',0,2);
                            setMoney('incomeCurrency',0,2);
                            $("#currencyInfo").show();
                            $("#currencyShow").text(data.Currency);
                            $("#incomeMoney_v").removeClass('txt').addClass('readOnlyTxtNormal');
                        }else{
                            setMoney('incomeMoney',$("#incomeCurrency").val(),2);
                            $("#currencyInfo").hide();
                            $("#incomeMoney_v").addClass('txt').removeClass('readOnlyTxtNormal');
                        }
                    }
                }
            }
        });
    }
});