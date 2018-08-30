function sub() {
	var dispose = $("input[name='deduct[deductDispose]']:checked").val();
	if (dispose == undefined) {
		alert("请选择处理方式");
		return false;
	}

	//核销金额和扣款金额验证
	var objGrid = $("#checkTable");
	var checkMoney = 0;
	var deductMoney = $("#deductMoney").val();
	var checkMoneyCols = objGrid.yxeditgrid("getCmpByCol","checkMoney");
	if (checkMoneyCols.length > 0) {
		checkMoneyCols.each(function(){
			checkMoney = accAdd(checkMoney,this.value,2);
		});
		if(checkMoney*1 != deductMoney*1){
			alert('核销金额与扣款金额不等');
			return false;
		}
	}

	return true;
}

$(function(){
	initIncomeCheck();
});

//渲染到款核销从表
function initIncomeCheck(){
	var objGrid = $("#checkTable");
	objGrid.yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		objName : 'deduct[incomeCheck]',
		title : '回款核销',
		param : { 'incomeId' : $("#id").val(),'incomeType' : 1 },
		tableClass : 'form_in_table',
		isAdd : false,
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
			display : 'incomeId',
			name : 'incomeId',
			type : 'hidden',
			value : $("#id").val()
		}, {
			display : 'incomeType',
			name : 'incomeType',
			type : 'hidden',
			value : 1
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
		},  {
			display : '未到款金额',
			name : 'unIncomMoney',
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
			type : 'money',
			event: {
				"blur" : function(v){
					var data = v.data;
					var rowNum = data.rowNum;
					var rowData = data.rowData;
					var inputVal = $("#checkTable_cmp_checkMoney"+rowNum).val();
					var unIncomMoney = $("#checkTable_cmp_unIncomMoney"+rowNum).val();
					console.log(Number(inputVal) > unIncomMoney);// 编辑页面需要处理
				}
			}
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

//选择合同付款条件
function selectPayCon(){
	showOpenWin("?model=contract_contract_receiptplan"
			+ "&action=selectPayCon"
			+ "&contractId="
			+ $("#contractId").val() ,1,500,900,$("#id").val());
}

/**
 * 设置付款内容
 * @return {Boolean}
 */
function setDatas(rows){
	var objGrid = $("#checkTable");
	for(var i = 0; i < rows.length ; i++){
		//判断付款条件是否已存在
		var payConIdArr = objGrid.yxeditgrid("getCmpByCol","payConId");
		var isExist = false;
		if(payConIdArr.length > 0){
			payConIdArr.each(function(){
				if(this.value == rows[i].id){
					isExist = true;
					alert('合同【'+ rows[i].contractCode + "】的付款条件【"+ rows[i].paymentterm +"("+ rows[i].payDT +" "+ moneyFormat2(rows[i].money) +")】已经存在于回款核销列表中,不能重复选择" );
					return false;
				}
			});
		}
		//如果已经重复了，就不能继续选择
		if(isExist){
			return false;
		}

		var unIncomMoney = rows[i].unIncomMoney_name;
		unIncomMoney = (unIncomMoney == undefined)? 0 : unIncomMoney.replaceAll(",","");

		//重新获取行数
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//新增行
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractId",rows[i].contractId);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractCode",rows[i].contractCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"contractName",rows[i].contractName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"payConId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"payConName",rows[i].paymentterm);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"checkDate",$("#thisDate").val());
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"unIncomMoney",Number(unIncomMoney));
	}
}