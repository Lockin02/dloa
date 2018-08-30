$(document).ready(function() {
	$("#items").yxeditgrid({
		objName : 'produceapply[items]',
		url : '?model=produce_apply_produceapplyitem&action=listJson',
		param : {
			mainId : $("#id").val(),
			isTemp : 0
		},

		isAddAndDel : false,

		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '发货清单id',
			name : 'relDocItemId',
			type : 'hidden'
		},{
			display : '物料编码',
			name : 'productCode',
			type : 'statictext'
		},{
			display : '物料名称',
			name : 'productName',
			type : 'statictext'
		},{
			display : '规格型号',
			name : 'pattern',
			type : 'statictext'
		},{
			display : '单位名称',
			name : 'unitName',
			type : 'statictext'
		},{
			display : '需求数量',
			name : 'needNum',
			type : 'statictext'
		},{
			display : '已下达数量',
			name : 'exeNum',
			type : 'statictext'
		},{
			display : '申请数量',
			name : 'produceNum',
			tclass : 'txtshort',
			process : function($input ,rowData) {
				$input.change(function () {
					if (parseInt($(this).val()) > parseInt(rowData.needNum)) {
						alert('申请数量不能大于需求数量！');
						$input.val(rowData.needNum).focus();
					} else if (parseInt($(this).val()) < parseInt(rowData.exeNum)) {
						alert('申请数量不能小于已下达数量！');
						$input.val(rowData.exeNum).focus();
					}
				});
			},
			validation : {
				custom : ['onlyNumber']
			}
		},{
			display : '期望交货时间',
			name : 'planEndDate',
			tclass : 'txtshort',
			type : 'date',
			readonly : true
		},{
			display : '备注',
			name : 'remark',
			type : 'textarea',
			rows : 2
		}]
	});

	validate({
		"changeReason" : {
			required : true
		}
	});
});