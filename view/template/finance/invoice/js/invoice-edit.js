var invoiceTypeArr = [];
$(function(){
	var thisObjType = $("#objTypeHidden").val();

	//��Ʊ����
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
		$("#thisColor").attr("class","red").html("<font>[���ַ�Ʊ]</font>");
	}

	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			hiddenId : 'TO_ID',
			mode : 'check',
			formCode : 'invoice'
		});
	}



	/*
	 * ��������ֵ����ݵ�����ѡ��
	 */
	function addDataToSelect1(data, selectId) {
		for (var i = 0, l = data.length; i < l; i++) {
			$("#" + selectId).append("<option title='" + data[i].text
				+ "' value='" + data[i].text + "'>" + data[i].text
				+ "</option>");
		}
	}

	// ��ȡʡ��URL
	var provinceUrl = "?model=system_procity_province&action=getProvinceNameArr";
	//��ȡʡ��
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

	//��Ʊ����
	initIncomeCheck();
});

//��Ⱦ��������ӱ�
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		param : { 'incomeId' : $("#id").val(),'incomeType' : '2'},
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