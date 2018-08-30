$(document).ready(function() {
	$("#rentalPropertyCode").trigger("change"); //触发租车性质事件

	//是否使用油卡支付
	$("#isCardPay").val($("#isCardPayVal").val());


	$("#feeInfo").yxeditgrid({
		objName : 'register[fee]',
		isAddAndDel : false,
		url : '?model=outsourcing_vehicle_registerfee&action=listJson',
		param : {
			dir : 'ASC',
			registerId : $("#id").val()
		},
		colModel : [{
			name : 'contractId',
			display : '合同ID',
			type : 'hidden'
		},{
			name : 'orderCode',
			display : '合同编号',
			type : 'hidden'
		},{
			name : 'feeName',
			display : '费用名称',
			type : 'statictext',
			align : 'left',
			width : '25%'
		},{
			name : 'feeName',
			display : '费用名称后台用',
			type : 'hidden'
		},{
			name : 'feeAmount',
			display : '费用金额',
			type : 'statictext',
			width : '15%',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'yesOrNo',
			display : '是否选择',
			type : 'checkbox',
			checkVal : 1,
			width : '10%'
		},{
			name : 'feeAmount',
			display : '费用金额后台用',
			type : 'hidden'
		},{
			name : 'remark',
			display : '备  注',
			type : 'statictext',
			align : 'left',
			width : '50%'
		},{
			name : 'remark',
			display : '备注后台用',
			type : 'hidden'
		}]
	});
});

//直接提交
function toSubmit(type){
	if(checkData() == 'pass'){
		switch(type){
			case 'sub':
				document.getElementById('form1').action="?model=outsourcing_vehicle_register&action=edit&isSub=1";
				$('#form1').submit();
				break;
			default:
				document.getElementById('form1').action="?model=outsourcing_vehicle_register&action=edit";
				$('#form1').submit();
				break;
		}
	}
}