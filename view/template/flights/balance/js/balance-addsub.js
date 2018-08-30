$(document).ready(function() {
    // ��ѡ��Ʊ����
    $("#airline").yxcombogrid_ticket({
        hiddenId: 'airlineId',
    	height:300,
        gridOptions: {
            param: {
                "findTicketVal": "����"
            },
            isTitle : true,
            showcheckbox : true
        }
    });

    // ��ѡ��Ʊ����
    $("#organizationSel").yxcombogrid_ticket({
        hiddenId: 'organizationIdSel',
    	height:300,
        gridOptions: {
            param: {
                "findTicketVal": "Ʊ��"
            },
            isTitle : true,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#organizationId").val(data.id);
					$("#organization").val(data.institutionName);
				}
			}
        },
        event : {
        	clear : function(){
				$("#organizationId").val('');
				$("#organization").val('');
        	}
        }
    });

    validate({
        "balanceDateB": {
            required: true
        },
        "balanceDateE": {
            required: true
        },
        "organization": {
            required: true
        },
        "rebate_v": {
            required: true
        },
        "exchange_v": {
            required: true
        },
        "exchangeMoney_v": {
            required: true
        }
    });
    $("form").submit(checkform);
});

//

//���ñ���Ⱦ
function initBalance(){
	if($("#isSearch").val() == "1"){
		reloadBalance();
	}else{
		createBalance();
	}
}

//����
function createBalance(){
	$("#isSearch").val(1);

    $("#messageInfo").show();
    $("#balanceInfo").show();

    var paramObj = {
        "beginDateThen":  $("#balanceDateB").val(),
        "endDateThen":  $("#balanceDateE").val(),
        "auditState" : 1,
        "airlineId" : $("#airlineId").val(),
        "organizationId" : $("#organizationIdSel").val()
    };

	//ʵ������Ʊ��Ϣ
    var itemTableObj = $("#itemTable");
    $.ajax({
    	type : "POST",
    	url : "?model=flights_message_message&action=listHtml&sort=auditDate&dir=ASC",
    	data : paramObj,
    	success : function(data) {
			if (data) {
				itemTableObj.html(data);
				getAllCost(paramObj);
			}
		}
    });
//    itemTableObj.yxeditgrid({
//        objName: 'balance[items]',
//        url: "?model=flights_message_message&action=listJson&sort=auditDate&dir=ASC",
//        param: paramObj,
//		tableClass : 'form_in_table',
//        isAdd: false,
//        event: {
//            'reloadData': function() {
//                //��ȡ����������ֶ�
//                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");
//
//                var allCost = 0;
//                if (itemArr.length > 0) {
//                    //ѭ��
//                    itemArr.each(function() {
//                        //accAdd�ӷ�
//                        allCost = accAdd(allCost, $(this).val(), 2);
//                    });
//                }
//                setMoney("balanceSum",allCost);
//                setMoney("actualMoney",allCost);
//
//				itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:500px;");
//            },
//			'removeRow' : function(){
//				//������㵥���
//				recount();
//			}
//        },
//        colModel: [{
//            name: 'msgType',
//            display: '��������',
//            type: 'statictext',
//            process : function(v){
//				if(v == "0"){
//					return '<span class="green">����</span>';
//				}else if(v == '1'){
//					return '<span class="blue">��ǩ</span>';
//				}else{
//					return '<span class="red">��Ʊ</span>';
//				}
//            },
//            width : 70
//        },{
//            name: 'auditDate',
//            display: '��������',
//            readonly: true,
//            tclass : 'readOnlyTxtMiddle',
//            width : 80
//        },{
//            name: 'airName',
//            display: '�˻���',
//            readonly: true,
//            tclass : 'readOnlyTxtMiddle',
//            width : 90
//        },{
//            name: 'airId',
//            display: 'airId',
//            type : 'hidden'
//        },{
//            name: 'airline',
//            display: '���չ�˾',
//            readonly: true,
//            width : 140,
//            tclass : 'readOnlyTxtNormal'
//        },{
//            name: 'airlineId',
//            display: 'airlineId',
//            type : 'hidden'
//        },{
//            name: 'flightNumber',
//            display: '����/�����',
//            readonly: true,
//            tclass : 'readOnlyTxtMiddle'
//        },{
//            name: 'flightTime',
//            display: '�˻�ʱ��',
//            readonly: true,
//            width : 130,
//            tclass : 'readOnlyTxtMiddle'
//        },{
//            name: 'arrivalTime',
//            display: '����ʱ��',
//            readonly: true,
//            width : 130,
//            tclass : 'readOnlyTxtMiddle'
//        },{
//            name: 'departPlace',
//            display: '�����ص�',
//            readonly: true,
//            tclass : 'readOnlyTxtShort'
//        },{
//            name: 'arrivalPlace',
//            display: '����ص�',
//            readonly: true,
//            tclass : 'readOnlyTxtShort'
//        },{
//            name: 'costPay',
//            display: 'ʵ�ʸ�����',
//            process : function(v){
//            	return moneyFormat2(v);
//            },
//            readonly: true,
//            tclass : 'readOnlyTxtMiddle'
//        },{
//            name: 'msgType',
//            display: 'msgType',
//            type: 'hidden',
//            readonly: true
//        },{
//            name: 'msgId',
//            display: 'msgId',
//            type: 'hidden',
//            readonly: true
//        }]
//    });

    // ��ѡ��Ʊ����
    $("#organization").yxcombogrid_ticket({
        hiddenId: 'organizationId',
    	height:300,
        gridOptions: {
            param: {
                "findTicketVal": "Ʊ��"
            },
            isTitle : true
        }
    });
}

//����ˢ��
function reloadBalance(){
    var paramObj = {
        "beginDateThen":  $("#balanceDateB").val(),
        "endDateThen":  $("#balanceDateE").val(),
        "auditState" : 1,
        "airlineId" : $("#airlineId").val(),
        "organizationId" : $("#organizationIdSel").val()
    };

  //ʵ������Ʊ��Ϣ
    var itemTableObj = $("#itemTable");
    $.ajax({
    	type : "POST",
    	url : "?model=flights_message_message&action=listHtml&sort=auditDate&dir=ASC",
    	data : paramObj,
    	success : function(data) {
			if (data) {
				itemTableObj.html(data);
				getAllCost(paramObj);
			}
		}
    });
	//ʵ������Ʊ��Ϣ
//    $("#itemTable").yxeditgrid("setParam",paramObj).yxeditgrid("processData");
    
}

//����
function sumCost(){
    var balanceSum = $("#balanceSum").val();
    var allCost = balanceSum;
   	allCost = accSub(allCost,$("#rebate").val(), 2);
    allCost = accSub(allCost,$("#exchangeMoney").val(), 2);
    setMoney("actualMoney",allCost);
}

//���¼�������
function recount(){
	var itemTableObj = $("#itemTable");
    var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");
    var allMoney = 0;
    if(itemArr.length > 0){
		itemArr.each(function(){
			//���˵�ɾ������
			var rowNum = $(this).data('rowNum');
			if($("#balance[items]_" + rowNum +"_isDelTag").length == 0){
				allMoney = accAdd($(this).val() , allMoney);
			}
		});
    }
	setMoney("balanceSum",allMoney);
	sumCost();
}

//��֤
function checkform(){
	if($("#actualMoney").val()*1 <= 0){
		alert('ʵ�ʽ���С�ڻ��ߵ���0');
		return false;
	}

	//����msgId�����ж��Ƿ��ظ�����
	var msgIdArr = [];
	var itemTableObj = $("#itemTable");
    var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "msgId");
    var isUnique = true; //�Ƿ�����ظ�����
	itemArr.each(function(){
		//���˵�ɾ������
		var rowNum = $(this).data('rowNum');
		if($("#balance[items]_" + rowNum +"_isDelTag").length == 0){
			if($.inArray($(this).val(),msgIdArr) == -1){
				msgIdArr.push($(this).val());
			}else{
				var flightNo = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"flightNumber").val();
				alert('���ܽ�ͬһ����Ʊ��Ϣ������ţ�'+ flightNo +'������ν���');
				isUnique = false;
				return false;
			}
		}
	});
	return isUnique;
}

//��������
function getAllCost(paramObj){
	$.ajax({
    	type : "POST",
    	url : "?model=flights_message_message&action=getAllCost",
    	data : paramObj,
    	success : function(data) {
    		setMoney("balanceSum",data);
			sumCost();
		}
    });
}

//ɾ����
function removeRow(rowNum){
	var balanceSum =  accSub($("#balanceSum").val(),$("#itemTable_cmp_costPay"+rowNum).val(),2);
	setMoney("balanceSum",balanceSum);
	sumCost();
	$("tr[rownum='"+rowNum+"']").remove();
}
