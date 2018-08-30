$(document).ready(function() {
    // ��ѡ��Ʊ����
    $("#organization").yxcombogrid_ticket({
        hiddenId: 'organizationId',
        gridOptions: {
            param: {
                "findTicketVal": "Ʊ��"
            }
        }
    });

    //�����ʼ����ݱ�
    var itemTableObj = $("#itemTable");
    itemTableObj.yxeditgrid({
        objName: 'balance[items]',
        url: "?model=flights_message_message&action=getConfirmedMsgJson",
        param: {
            "ids":  $("#msgId").val()
        },
        isAddAndDel: false,
        event: {
            'reloadData': function() {
                //��ȡ����������ֶ�
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");

                var allCost = 0;
                if (itemArr.length > 0) {
                    //ѭ��
                    itemArr.each(function() {
                        //accAdd�ӷ�
                        allCost = accAdd(allCost, $(this).val(), 2);
                    });
                }

                setMoney("balanceSum",allCost);
                setMoney("actualMoney",allCost);

                //���ڹ��񳤶�ʱ
                if(itemArr.length > 3){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:500px;");
                }
            }
        },
        title: '��Ʊ��Ϣ',
        colModel: [{
            name: 'msgType',
            display: '��������',
            type: 'statictext',
            process : function(v){
				if(v == "0"){
					return '<span class="green">����</span>';
				}else if(v == '1'){
					return '<span class="blue">��ǩ</span>';
				}else{
					return '<span class="red">��Ʊ</span>';
				}
            }
        },{
            name: 'airName',
            display: '�˻���',
            readonly: true,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'airId',
            display: 'airId',
            type : 'hidden'
        },{
            name: 'airline',
            display: '���չ�˾',
            readonly: true,
            width : 150,
            tclass : 'readOnlyTxtNormal'
        },{
            name: 'airlineId',
            display: 'airlineId',
            type : 'hidden'
        },{
            name: 'flightNumber',
            display: '����/�����',
            readonly: true,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'flightTime',
            display: '�˻�ʱ��',
            readonly: true,
            width : 130,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'arrivalTime',
            display: '����ʱ��',
            readonly: true,
            width : 130,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'departPlace',
            display: '�����ص�',
            readonly: true,
            tclass : 'readOnlyTxtShort'
        },{
            name: 'arrivalPlace',
            display: '����ص�',
            readonly: true,
            tclass : 'readOnlyTxtShort'
        },{
            name: 'costPay',
            display: 'ʵ�ʸ�����',
            process : function(v){
            	return moneyFormat2(v);
            },
            readonly: true,
            tclass : 'readOnlyTxtMiddle'
        },{
            name: 'msgType',
            display: 'msgType',
            type: 'hidden',
            readonly: true
        },{
            name: 'msgId',
            display: 'msgId',
            type: 'hidden',
            readonly: true
        }]
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

//����
function sumCost(){
    var balanceSum = $("#balanceSum").val();
    var allCost = balanceSum;
   	allCost = accSub(allCost,$("#rebate").val(), 2);
    allCost = accSub(allCost,$("#exchangeMoney").val(), 2);
    setMoney("actualMoney",allCost);
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