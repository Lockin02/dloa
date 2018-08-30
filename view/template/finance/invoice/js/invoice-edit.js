var invoiceTypeArr = [];
$(function(){
	var thisObjType = $("#objTypeHidden").val();

	//开票类型
	invoiceTypeArr = getData('CPFWLX');
	changeInvType('invoiceType');
	
	$("#customerName").yxcombogrid_customer({
		hiddenId :  'customerId',
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#managerName").val(data.AreaLeader);
					$("#managerId").val(data.AreaLeaderId);
					$("#invoiceUnitType").val(data.TypeOne);
					$("#invoiceUnitProvince").val(data.Prov);
				}
			}
		}
	});

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'invoice'
	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	$("#managerName").yxselect_user({
		hiddenId : 'managerId'
	});

	var invnumber = $("#invnumber").val();

	for( var i = 1; i <= invnumber ; i++ ){
		$("#invoiceEquName"+ i).yxcombogrid_datadict({
			hiddenId :  'invoiceEquId'+ i,
			gridOptions : {
				param : {"parentCode":"KPXM"},
				showcheckbox : false,
				isTitle : true,
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							var thisObj = $("#invoiceEquName"+ i);
							$("#invoiceEquId" + i).val(data.dataCode);
							$("#invoiceEquName" + i).val(data.dataName);
							thisObj.attr('readonly',"readonly");
							if(data.dataCode == "QT"){
								thisObj.attr('readonly',"");
								thisObj.val("");
								thisObj.focus();
							}
						};
					}(i)
				}
			}
		});
	}

	var isRed = $("#isRed").val();
	if(isRed == 1){
		$("#thisColor").attr("class","red").html("<font>[红字发票]</font>");
	}

	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			hiddenId : 'TO_ID',
			mode : 'check',
			formCode : 'invoice'
		});
	}



	/*
	 * 添加数据字典数据到下拉选择
	 */
	function addDataToSelect1(data, selectId) {
		for (var i = 0, l = data.length; i < l; i++) {
			$("#" + selectId).append("<option title='" + data[i].text
				+ "' value='" + data[i].text + "'>" + data[i].text
				+ "</option>");
		}
	}

	// 获取省的URL
	var provinceUrl = "?model=system_procity_province&action=getProvinceNameArr";
	//获取省份
	$.ajax({
	    type : 'POST',
	    url : provinceUrl,
	    async: false,
	    success : function(data){
	    	var dataArr = eval( "(" + data + ")");
			addDataToSelect1(dataArr,'invoiceUnitProvince');
		}
	});

	$("#invoiceUnitProvince").val($("#customerProvince").val());

	//发票核销
	initIncomeCheck();
});

//渲染到款核销从表
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		param : { 'incomeId' : $("#id").val(),'incomeType' : '2'},
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