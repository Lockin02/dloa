$(function() {
	//������
	$("#chargerName").yxselect_user({
		hiddenId : 'chargerId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'compensate'
	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	var totalPrice = totalUnitPrice = 0;
	var detailObj = $("#detail");
	// ��Ʒ�嵥
	detailObj.yxeditgrid({
		objName : 'compensate[detail]',
        url: "?model=finance_compensate_compensatedetail&action=listJson",
        param : {
            'mainId' : $("#id").val()
        },
		tableClass : 'form_in_table',
		title : "�����嵥",
        isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'borrowequId',
			name : 'borrowequId',
			type : 'hidden'
		}, {
			display : 'returnequId',
			name : 'returnequId',
			type : 'hidden'
		}, {
            display : '����Id',
            name : 'productId',
            type : 'hidden'
        }, {
			display : '���ϱ��',
			name : 'productNo',
            tclass : 'readOnlyTxtMiddle',
			width : 90
		}, {
			display : '��������',
			name : 'productName',
			readonly : true,
			tclass : 'readOnlyTxtNormal'
		}, {
            display : '����ͺ�',
            name : 'productModel',
            readonly : true,
            tclass : 'readOnlyTxtNormal'
        }, {
			display : '��λ',
			name : 'unitName',
			width : 50,
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display : '����',
			name : 'number',
			width : 50,
            tclass : 'readOnlyTxtShort'
		}, {
			display : 'Ԥ��ά�޽��',
			name : 'money',
			width : 80,
			type : 'money',
            readonly : true,
            tclass : 'readOnlyTxtShort'
		}, {
            display : '����',
            name : 'unitPrice',
            width : 70,
            type : 'money',
            validation : {
                required : true
            },
			process : function (v, row) {
				totalUnitPrice = accAdd(totalUnitPrice, Number(row.unitPrice), 2);
				totalUnitPrice = moneyFormat2(totalUnitPrice);
				$("#formUnitPrice_v").val(totalUnitPrice);
				return ($(v).val() <= 0)? $(v).val('') : $(v).val();
			},
			event : {
				blur : function(v,row){
					var priceVal = $(this).val();
					updatePrice($(this).data('rowNum'),priceVal.replaceAll(",",""));
					checkPrice($(this).data('rowNum'));
					updateTotalCount();
				}
			}
        }, {
            display : '��ֵ',
            name : 'price',
            width : 70,
            type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
            validation : {
                required : true
            },
			process : function (v, row) {
				totalPrice = accAdd(totalPrice, Number(row.price), 2);
				totalPrice = moneyFormat2(totalPrice);
            	$("#formPrice_v").val(totalPrice);
			}
        }, {
//			display : '�⳥����',
//			name : 'compensateType',
//			type : 'select',
//			width : 80,
//			datacode : 'PCFSX',
//			validation : {
//				required : true
//			}
//		}, {
//			display : '�⳥���',
//			name : 'compensateMoney',
//			width : 80,
//			type : 'money',
//			event : {
//				blur : function(){
//					countForm();
//				}
//			}
//		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txtmiddle',
			width : 90
		}, {
			display : '���к�',
			name : 'serialNos',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 90
		}]
	});

	detailObj.find('tbody').after("<tr class='tr_count'>" +
		"<td colspan='2'></td><td>�ϼ�</td><td colspan='3'></td>" +
		"<td><input id='formMoney_v' style='width:80px;' class='readOnlyTxtShortCount' readonly='readonly' value='" +
			moneyFormat2($("#formMoney").val()) + "'/>" +
		"</td>" +
		"<td><input id='formUnitPrice_v' style='width:80px;' class='readOnlyTxtShortCount' readonly='readonly' value=''/>" +
		"</td>" +
		"<td><input id='formPrice_v' style='width:80px;' class='readOnlyTxtShortCount' readonly='readonly' value=''/>" +
		"</td>" +
		"<td colspan='2'></td>" +
		"</tr>");

	//����֤
	validate({
		"formDate" : {
			required : true
		},
		"chargerName" : {
			required : true
		},
		"deptName" : {
			required : true
		}
		// "dutyObjName" : {
		// 	required : true
		// }
	});

	$("#dutyType").change(function(){
		if($(this).val() == "PCZTLX-01"){
			$("#dutyObjName").val($("#chargerName").val());
			$("#dutyObjId").val($("#chargerId").val());
		}else if($(this).val() != ""){
			$("#dutyObjName").val($("#deptName").val());
			$("#dutyObjId").val($("#deptId").val());
		}
	}).change();

    //��ʾ���÷�̯��ϸ
//    $("#costshareGrid").costshareGrid({
//        objName : 'compensate[costshare]',
//        url : "?model=finance_cost_costshare&action=listjson",
//        param : {'objType' : 1 ,'objId' : $("#id").val()}
//    });

	//��ʾ�ʼ����
	$("#showQualityReport").showQualityDetail({
		tableClass : 'form_in_table',
		param : {
			"objId" : $("#relDocId").val(),
			"objType" : $("#qualityObjType").val()
		}
	});
});

//������
function countMoney(rowNum){
	var detailObj = $("#detail");
	//��ֵ
	var money = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"money").val();
	//����������⳥
	var compensateType = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"compensateType").val();//�⳥��ʽ
    var compensateRate = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"compensateRate").val();

	var compensateMoney = accDiv(accMul(money,compensateRate,2),100,2);
	detailObj.yxeditgrid("setRowColValue",rowNum,"compensateMoney",compensateMoney,true);
}

//��ֵ��֤
function checkPrice(rowNum){
    var detailObj = $("#detail");
    //��ֵ
    var price = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val();
    if(price==0){
        alert("��ֵ������Ϊ0");
        detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val("");
		detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"unitPrice").val("");
    }
}

//���ݽ����㷽��
function countForm(){
	var detailObj = $("#detail");

	//���㵥�ݽ��
	var moneyArr = detailObj.yxeditgrid("getCmpByCol", "money");
	var formMoney = 0;
	moneyArr.each(function(){
		formMoney = accAdd(formMoney,$(this).val(),2);
	});
	setMoney('formMoney',formMoney);

	//�����⳥���
	var compensateMoneyArr = detailObj.yxeditgrid("getCmpByCol", "compensateMoney");
	var compensateMoney = 0;
	compensateMoneyArr.each(function(){
		compensateMoney = accAdd(compensateMoney,$(this).val(),2);
	});
	setMoney('compensateMoney',compensateMoney);
}

//���ύ����
function audit(thisVal){
	$("#isSubAudit").val(thisVal);
}

// ѡ�����к�
function serialNum(rowNum,serialIds,serialNos,returnequId,number,detailId) {
	showThickboxWin('?model=finance_compensate_compensate&action=toSerialNos'
		+ '&relDocId=' + $("#relDocId").val()
		+ '&relDocType=' + $("#relDocType").val()
		+ '&rowNum=' + rowNum
		+ '&serialIds=' + serialIds
		+ '&serialNos=' + serialNos
		+ '&returnequId=' + returnequId
		+ '&number=' + number
		+ '&id=' + $("#id").val()
		+ '&detailId='
		+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=350");
}

// ��ֵ���� ����ֵ = ����*������
function updatePrice(rowNum,unitPrice){
	var detailObj = $("#detail");
	var number = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"number",true).val();
	var newPrice = moneyFormat2(Number(number) * Number(unitPrice));
	detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val(newPrice);
	$("#detail_cmp_price"+rowNum).val(newPrice);
}

// ͳ�Ƶ����Լ���ֵ
function updateTotalCount(){
	var detailObj = $("#detail");

	var totalUnitPric = totalPrice = 0;
	var unitPriceArr = detailObj.yxeditgrid("getCmpByCol", "unitPrice");
	var priceArr = detailObj.yxeditgrid("getCmpByCol", "price");

	unitPriceArr.each(function(){
		totalUnitPric = accAdd(totalUnitPric,$(this).val(),2);
	});
	priceArr.each(function(){
		totalPrice = accAdd(totalPrice,$(this).val(),2);
	});

	// console.log("totalUnitPric: "+totalUnitPric+"; totalPrice:"+totalPrice);
	$("#formUnitPrice_v").val(moneyFormat2(totalUnitPric));
	$("#formPrice_v").val(moneyFormat2(totalPrice));
}