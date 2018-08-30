$(function() {
	//������
	$("#chargerName").yxselect_user({
		hiddenId : 'chargerId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'compensate',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					if($("#dutyType").val() == "PCZTLX-01"){
						$("#dutyObjName").val($("#chargerName").val());
						$("#dutyObjId").val($("#chargerId").val());
					}else{
						$("#dutyObjName").val($("#deptName").val());
						$("#dutyObjId").val($("#deptId").val());
					}
				}
			}
		}
	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	//��������
	var applyType = $("#applyType").val();
	if(applyType == "JYGHSQLX-02"){
		var isAddAndDel = false;
	}else{
		var isAddAndDel = true;
	}

	var detailObj = $("#detail");
	// ��Ʒ�嵥
	detailObj.yxeditgrid({
		objName : 'compensate[detail]',
		url : '?model=finance_compensate_compensate&action=businessGetDetail',
		tableClass : 'form_in_table',
		isAdd : false,
		isAddAndDel : isAddAndDel,
		title : "�����嵥",
		param : {
			'relDocId' : $("#relDocId").val(),
			'relDocType' : $("#relDocType").val(),
			'applyType' : applyType
		},
		event : {
			'reloadData': function(e,g,data) {
				if(!data || data.length == 0){
//					alert('������Ҫ�⳥������');
//					closeFun();
				}
			},
			'removeRow': function(removeRow,rowNum) {
				updateTotalCount(rowNum,1);
			}
		},
		colModel : [{
			display : 'returnequId',
			name : 'returnequId',
			type : 'hidden'
		}, {
			display : 'borrowequId',
			name : 'borrowequId',
			type : 'hidden'
		}, {
			display : 'qualityequId',
			name : 'qualityequId',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productNo',
			tclass : 'readOnlyTxtMiddle',
			readonly : true,
			width : 80
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 120
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			name : 'productModel',
			display : '����ͺ�',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '�⳥����',
			name : 'number',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : 'Ԥ��ά�޽��',
			name : 'money',
			type : 'money',
			width : 80,
			validation : {
				required : true
			},
			event : {
				blur : function(){
					countMoney($(this).data('rowNum'));
					countForm();
				}
			}
		}, {
			display : '����',
			name : 'unitPrice',
			type : 'money',
			width : 80,
			validation : {
				required : true
			},
			process: function (v) {
				var totalUnitPrice = ($("#totalUnitPrice_v").val() == '')? 0 : $("#totalUnitPrice_v").val();
				totalUnitPrice = accAdd(Number(totalUnitPrice),$(v).val(),2);
				$("#totalUnitPrice_v").val(totalUnitPrice);
				return ($(v).val() <= 0)? $(v).val('') : $(v).val();
			},
			event : {
				blur : function(){
					var priceVal = $(this).val();
					updatePrice($(this).data('rowNum'),priceVal.replaceAll(",",""));
					checkPrice($(this).data('rowNum'));
					updateTotalCount($(this).data('rowNum'));
				}
			}
		}, {
			display : '��ֵ',
			name : 'price',
			type : 'money',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			validation : {
				required : true
			},
			process: function (v) {
				var totalPrice = ($("#totalPrice_v").val() == '')? 0 : $("#totalPrice_v").val();
				totalPrice = accAdd(Number(totalPrice),$(v).val(),2);
				$("#totalPrice_v").val(totalPrice);
			},
		}, {
			display : '�⳥���',
			name : 'compensateMoney',
			type : 'hidden'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txtmiddle'
		}, {
			name : 'serialNos',
			display : '���к�',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}]
	});

	//���Ϻϼ�
	if(applyType == "JYGHSQLX-02"){// ����ϼƴ�λ����
		var tdPlus = '<td colspan="1"></td>';
	}else{
		var tdPlus = '<td colspan="2"></td>';
	}
	detailObj.find('tbody').after("<tr class='tr_count'>" +tdPlus+
	"<td>�ϼ�</td><td colspan='4'></td>" +
	"<td>" +
	"<input id='formMoney_v' style='width:70px;' class='readOnlyTxtShortCount' readonly='readonly'/>" +
	"</td>"+"<td>" +
	"<input id='totalUnitPrice_v' style='width:70px;' class='readOnlyTxtShortCount' readonly='readonly'/>" +
	"</td>"+"<td>" +
	"<input id='totalPrice_v' style='width:70px;' class='readOnlyTxtShortCount' readonly='readonly'/>" +
	"</td>"+
	"<td colspan='2'></td>"+
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
		}else if($(this).val() != ''){
			$("#dutyObjName").val($("#deptName").val());
			$("#dutyObjId").val($("#deptId").val());
		}
	}).change();

	//��ʾ�ʼ����
	$("#showQualityReport").showQualityDetail({
		param : {
			"objId" : $("#relDocId").val(),
			"objType" : $("#qualityObjType").val()
		}
	});

	//����֤��
	$("form").bind("submit",function(){
		var moneyArr = $("#detail").yxeditgrid('getCmpByCol','money');//�ж���û���⳥�嵥
		if(moneyArr.length == 0){
			alert('û���⳥�����嵥');
			return false;
		}
		return true;
	});
});

//������
function countMoney(rowNum){
	var detailObj = $("#detail");
	//��ֵ
	var money = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"money",true).val();
//	detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val(money);
	detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"compensateMoney").val(money);
}

// ��ֵ���� ����ֵ = ����*������
function updatePrice(rowNum,unitPrice){
	var detailObj = $("#detail");
	var number = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"number",true).val();
	console.log(Number(number));
	console.log(Number(unitPrice));
	var newPrice = moneyFormat2(Number(number) * Number(unitPrice));
	detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val(newPrice);
	$("#detail_cmp_price"+rowNum).val(newPrice);
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
	$("#compensateMoney").val(formMoney);
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
// ͳ�Ƹ��ϼƽ��
function updateTotalCount(rowNum,isDel){
	var detailObj = $("#detail");

	var totalMoney = 0;
	var totalUnitPric = 0;
	var totalPrice = 0;
	var moneyArr = detailObj.yxeditgrid("getCmpByCol", "money");
	var unitPriceArr = detailObj.yxeditgrid("getCmpByCol", "unitPrice");
	var priceArr = detailObj.yxeditgrid("getCmpByCol", "price");

	moneyArr.each(function(){
		totalMoney = accAdd(totalMoney,$(this).val(),2);
	});
	unitPriceArr.each(function(){
		totalUnitPric = accAdd(totalUnitPric,$(this).val(),2);
	});
	priceArr.each(function(){
		totalPrice = accAdd(totalPrice,$(this).val(),2);
	});

	if(isDel == 1){
		var delMoney = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum, "money").val();
		var delUnitPrice = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum, "unitPrice").val();
		var delPrice = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum, "price").val();
		totalMoney = accSub(totalMoney,delMoney,2);
		totalUnitPric = accSub(totalUnitPric,delUnitPrice,2);
		totalPrice = accSub(totalPrice,delPrice,2);
	}
	console.log("totalMoney: "+totalMoney+"; totalUnitPric: "+totalUnitPric+"; totalPrice:"+totalPrice);
	$("#totalUnitPrice_v").val(moneyFormat2(totalUnitPric));
	$("#totalPrice_v").val(moneyFormat2(totalPrice));
	if(totalMoney > 0){
		$("#formMoney_v").val(moneyFormat2(totalMoney));
	}
}