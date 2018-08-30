$(document).ready(function() {

	//��ʼ����ϸ
	initDetail();

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
});

function initDetail(){
    // ��ѡ��Ʊ����
    $("#organization").yxcombogrid_ticket({
        hiddenId: 'organizationId',
        gridOptions: {
            param: {
                "findTicketVal": "Ʊ��"
            }
        }
    });

	var itemTableObj = $("#itemTable");
	itemTableObj.yxeditgrid({
		url : "?model=flights_balance_batchitem&action=listJson",
		param : {"mainId" : $("#id").val()},
		objName : 'balance[items]',
//        title: '��Ʊ��Ϣ',
		event : {
			'reloadData' : function(e){
                //��ȡ����������ֶ�
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "airName");

                //ѭ������ԭ����
                itemArr.each(function(){
                	$(this).removeClass('txtmiddle').addClass('readOnlyTxtMiddle');
                });

                //���ڹ��񳤶�ʱ
                if(itemArr.length > 15){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:550px;");
                }
			},
			'removeRow' : function(){
                //��ȡ����������ֶ�
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");
                //���ڹ��񳤶�ʱ
                if(itemArr.length > 3){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:550px;");
                }else{
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:hidden;");
                }

				//������㵥���
				recount();
			},
			'addRow' : function(e,rowNum,rowData){
				if(!rowData){
					var airNameObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airName");
					var msgIdObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"msgId");
	                airNameObj.yxcombogrid_msginfo({
	                    hiddenId: msgIdObj.attr('id'),
                    	height : 230,
	                    gridOptions: {
	                    	isTitle : true,
	                        param: {
	                            "auditState": "1"
	                        },
	                        event : {
	                        	"row_dblclick" : function(rowNum){
	                        		return function(e, row, data){
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"msgTypeName").val(changeMsgType(data.msgType));
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airId").val(data.airId);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"auditDate").val(data.auditDate);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"flightTime").val(data.flightTime);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"arrivalTime").val(data.arrivalTime);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"flightNumber").val(data.flightNumber);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airline").val(data.airline);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airlineId").val(data.airlineId);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"msgType").val(data.msgType);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"departPlace").val(data.departPlace);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"arrivalPlace").val(data.arrivalPlace);
										itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"costPay").val(data.costPay);

										//������㵥���
										recount();
		                        	}
	                        	}(rowNum)
	                        }
	                    }
	                });
				}

                //��ȡ����������ֶ�
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "airName");
                //���ڹ��񳤶�ʱ
                if(itemArr.length > 15){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:550px;");
                }
			}
		},
        colModel: [{
            name: 'id',
            display: 'id',
            type : 'hidden'
        },{
            name: 'msgTypeName',
            display: '��������',
            readonly: true,
            tclass : 'readOnlyTxtMiddle',
            width : 70
        },{
            name: 'auditDate',
            display: '��������',
            readonly: true,
            tclass : 'readOnlyTxtMiddle',
            width : 80
        },{
            name: 'airName',
            display: '�˻���',
            readonly: true,
            tclass : 'txtmiddle',
			validation : {
				required : true
			}
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

    $("form").submit(checkform);
}

//����ת��
function changeMsgType(thisVal){
	if(thisVal == '0'){
		return '����';
	}else if(thisVal == "1"){
		return '��ǩ';
	}else{
		return '��Ʊ';
	}
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