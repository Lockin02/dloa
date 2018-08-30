var invoiceTypeArr = [];
$(function(){
	//开票类型
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

	//获取省份
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

	//发票核销
	initIncomeCheck();

    // 显示非人民币开票提示
    setCurrencyShowTips();
});

//发票登记 表单验证
function checkformApply(){
	var remainMoney = $("#remainMoney").val()*1;
	var invoiceMoney = $("#invoiceMoney").val()*1;
	if(remainMoney < invoiceMoney){
		if(!confirm('开票金额('+ invoiceMoney + ')大于可开金额('+ remainMoney  +')，确定要录入发票吗？')){
			return false;
		}
	}
	return checkform();
}

//渲染到款核销从表
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		objName : 'invoice[incomeCheck]',
		title : '开票核销',
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
			display : '合同id',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '合同名称',
			name : 'contractName',
			type : 'hidden'
		}, {
			display : '合同编号',
			name : 'contractCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '付款条件id',
			name : 'payConId',
			type : 'hidden'
		}, {
			display : '付款条件',
			name : 'payConName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '本次核销金额',
			name : 'checkMoney',
			tclass : 'txtmiddle',
			type : 'money'
		}, {
			display : '核销日期',
			name : 'checkDate',
			tclass : 'txtmiddle Wdate',
			type : 'date'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});

	//加载付款条件选择按钮
	objGrid.find("tr:first td").append("<input type='button' value='选择付款条件' class='txt_btn_a' style='margin-left:10px;' onclick='selectPayCon();'/>");
}