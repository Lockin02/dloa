var $thisCustomerId = ""; // ���ݿͻ�
var $thisCustomerName = ""; // ���ݿͻ�
var defaultCurrency = '�����'; // Ĭ�ϻ���

// ҵ����������
$(function(){
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

                    //��ȡ����ӱ�����
                    var objGrid = $("#allotTable"); // ����ӱ����
                    var objArr = objGrid.yxeditgrid('getCmpByCol','objType');
                    objArr.each(function(){
                        var rowNum = $(this).data('rowNum');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objCode','');
                        reloadCombo(this.value,rowNum);
                    });
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
                        // �������˱ұ�������˿�ӱ�
                        initAllot(data.Currency);
                    }
                }
            }
        });
    }

    // ��ȡ����
    var currency = $("#currency").val();

    // �������
    initAllot(currency);
});

// ���������ϸ
function initAllot(currency){
    var objTypeOptions = getObjType('KPRK');
    if($("#allotTable").html() != "") $("#allotTable").yxeditgrid('remove'); // ���������Ѿ����ڣ���ɾ������Ⱦ
    $("#allotTable").yxeditgrid({
        objName : 'income[incomeAllots]',
        title : '�˿����',
        tableClass : 'form_in_table',
        colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        }, {
            display : 'Դ������',
            name : 'objType',
            type : 'select',
            options : objTypeOptions,
            event : {
                change : function(){
                    reloadCombo(this.value,$(this).data('rowNum'));
                }
            }
        }, {
            display : 'Դ��id',
            name : 'objId',
            type : 'hidden'
        }, {
            display : 'Դ�����',
            name : 'objCode',
            tclass : 'txt',
            readonly : true,
            process : function($input) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                var thisType = g.getCmpByRowAndCol(rowNum,'objType').val();
                reloadComboClear(thisType,rowNum);
            }
        }, {
            display : '������',
            name : 'money',
            type : 'money',
            readonly : currency == defaultCurrency ? false : true,
            tclass : currency == defaultCurrency ? 'txtmiddle' : 'readOnlyTxt',
            event : {
                blur : function(){
                    if(currency == defaultCurrency){
                        countRow($(this).data('rowNum'),currency);
                        countAll();
                    }
                }
            }
        }, {
            display : '������('+ currency +')',
            name : 'moneyCurrency',
            type : currency == defaultCurrency ? 'hidden' : 'money',
            event : {
                blur : function(){
                    if(currency != defaultCurrency){
                        countRow($(this).data('rowNum'),currency);
                        countAll();
                    }
                }
            }
        }, {
            display : 'ҵ����',
            name : 'rObjCode',
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '��������',
            name : 'allotDate',
            tclass : 'txtmiddle Wdate',
            type : 'date',
            value : $("#incomeDate").val()
        }]
    });
}

// �д���
function countRow(rowNum,currency){
    var objGrid = $("#allotTable"); // ����ӱ����
    var rate = $("#rate").val();
    if(currency == defaultCurrency){
        var money = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'money',true).val();
        var moneyCurrency = accDiv(money,rate,2);
        objGrid.yxeditgrid("setRowColValue",rowNum,"moneyCurrency",moneyCurrency,true);
    }else{
        var moneyCurrency = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'moneyCurrency',true).val();
        var money = accMul(moneyCurrency,rate,2);
        objGrid.yxeditgrid("setRowColValue",rowNum,"money",money,true);
    }
}

// ���ϼ�
function countAll(){
    //��ȡ����ӱ�����
    var objGrid = $("#allotTable"); // ����ӱ����
    var objArr = objGrid.yxeditgrid('getCmpByCol','objType');
    var allAmount = 0; // ������
    var allCurrency = 0; // ������(��Ӧ�ұ�)
    objArr.each(function(){
        var money = objGrid.yxeditgrid('getCmpByRowAndCol',$(this).data('rowNum'),'money',true).val();
        if(!isNaN(money)){
            allAmount = accAdd(money,allAmount,2);
            var moneyCurrency = objGrid.yxeditgrid('getCmpByRowAndCol',$(this).data('rowNum'),'moneyCurrency',true).val();
            allCurrency = accAdd(allCurrency,moneyCurrency,2);
        }
    });
    var allotAble = accSub($('#incomeMoney').val(),allAmount,2);
    $('#allotAble').val(allotAble);
    var allotCurrency = accSub($('#incomeCurrency').val(),allCurrency);
    $('#allotCurrency').val(allotCurrency);
}