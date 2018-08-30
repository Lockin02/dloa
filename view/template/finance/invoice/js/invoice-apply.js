var invoiceTypeArr = [];
$(function(){
	//��Ʊ����
	invoiceTypeArr = getData('CPFWLX');
	changeInvType('invoiceType');

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'invoice'
	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'invoice'
	});

	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			hiddenId : 'TO_ID',
			mode : 'check',
			formCode : 'invoice'
		});
	}

	$("#customerName").yxcombogrid_customer({
		hiddenId :  'customerId',
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#invoiceUnitType").val(data.TypeOne);
					$("#invoiceUnitProvince").val(data.Prov);
					$("#areaName").val(data.AreaName);
					$("#areaId").val(data.AreaId);
				}
			}
		}
	});

	$.each($("input[id^='invoiceEquName']"),function(i){
		var thisCount = i + 1;
		$("#invoiceEquName"+ thisCount).yxcombogrid_datadict({
			hiddenId :  'invoiceEquId'+ thisCount,
			nameCol : 'dataName',
			gridOptions : {
				param : {"parentCode":"KPXM"},
				showcheckbox : false,
				isTitle : true,
				event : {
					'row_dblclick' : function(){
						return function(e, row, data) {
							var thisObj = $("#invoiceEquName"+ thisCount);
							thisObj.attr('readonly',"readonly");
							if(data.dataCode == "QT"){
								thisObj.attr('readonly',"");
								thisObj.val("");
								thisObj.focus();
							}
						};
					}(thisCount)
				}
			}
		});
	});

	//��ȡʡ��
	$.ajax({
	    type : 'POST',
	    url : "?model=system_procity_province&action=getProvinceNameArr",
	    async: false,
	    success : function(data){
	    	var dataArr = eval( "(" + data + ")");
			addDataToSelect1(dataArr,'invoiceUnitProvince');
		}
	});

	$("#invoiceUnitProvince").val($("#customerProvince").val());

	//��Ʊ����
	initIncomeCheck();

    // ��ʾ������ҿ�Ʊ��ʾ
    setCurrencyShowTips();
});

//��Ʊ�Ǽ� ����֤
function checkformApply(){
	var remainMoney = $("#remainMoney").val()*1;
	var invoiceMoney = $("#invoiceMoney").val()*1;
	if(remainMoney < invoiceMoney){
		if(!confirm('��Ʊ���('+ invoiceMoney + ')���ڿɿ����('+ remainMoney  +')��ȷ��Ҫ¼�뷢Ʊ��')){
			return false;
		}
	}
	return checkform();
}

//��Ⱦ��������ӱ�
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		objName : 'invoice[incomeCheck]',
		title : '��Ʊ����',
		tableClass : 'form_in_table',
		isAdd : false,
		isAddOneRow : false,
		event : {
			'reloadData' : function(e, g, data){
				if(!data){
//					objGrid.hide();
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��ͬid',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '��ͬ����',
			name : 'contractName',
			type : 'hidden'
		}, {
			display : '��ͬ���',
			name : 'contractCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '��������id',
			name : 'payConId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'payConName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '���κ������',
			name : 'checkMoney',
			tclass : 'txtmiddle',
			type : 'money'
		}, {
			display : '��������',
			name : 'checkDate',
			tclass : 'txtmiddle Wdate',
			type : 'date'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});

	//���ظ�������ѡ��ť
	objGrid.find("tr:first td").append("<input type='button' value='ѡ�񸶿�����' class='txt_btn_a' style='margin-left:10px;' onclick='selectPayCon();'/>");
}