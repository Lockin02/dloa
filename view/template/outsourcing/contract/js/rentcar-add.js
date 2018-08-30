$(document).ready(function() {
	setSignCompany(); //签约公司下拉渲染

	$("#vehicleInfo").yxeditgrid({
		objName : 'rentcar[vehicle]',
		isFristRowDenyDel : true,
		colModel : [{
			name : 'carModelCode',
			display : '车型',
			width : '15%',
			type : 'select',
			datacode : 'WBZCCX' // 数据字典编码
		},{
			name : 'carNumber',
			display : '车牌号',
			width : '10%',
			validation : {
				required : true
			},
			process : function ($input) {
				$input.change(function () {
					if ($.trim($(this).val()) != '') {
						$.ajax({
							type : 'POST',
							url : '?model=outsourcing_contract_vehicle&action=checkByCarNumber',
							data : {
								carNumber : $.trim($(this).val())
							},
							async : false,
							success : function (data) {
								if (data == 'true') {
									alert('该车牌号已经存在合同！');
								}
							}
						});
					}
				});
			}
		},{
			name : 'driver',
			display : '驾驶员',
			width : '10%',
			validation : {
				required : true
			}
		},{
			name : 'idNumber',
			display : '驾驶员身份证号',
			width : '25%',
			validation : {
				required : true
			}
		},{
			name : 'displacement',
			display : '排量、使用何种汽油',
			width : '15%'
		},{
			name : 'oilCarUse',
			display : '油卡抵充',
			width : '10%',
			type : 'select',
			value : '否',
			options : [{
				name : "是",
				value : "是"
			},{
				name : "否",
				value : "否"
			}]
		},{
			name : 'oilCarAmount',
			display : '油卡金额',
			width : '15%',
			type : 'money'
		}]
	});

	$("#feeInfo").yxeditgrid({
		objName : 'rentcar[fee]',
		isAddOneRow : false,
		colModel : [{
			name : 'feeName',
			display : '费用名称',
			width : 110,
			validation : {
				required : true
			}
		},{
			name : 'feeAmount',
			display : '费用金额',
			width : 110,
			validation : {
				custom : ['money']
			}
		},{
			name : 'remark',
			display : '备  注',
			type : 'textarea',
			width : 220,
			rows : 2
		}]
	});
});

//直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_contract_rentcar&action=add&actType=actType";
}