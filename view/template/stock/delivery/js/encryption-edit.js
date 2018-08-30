$(document).ready(function() {

	$("#equInfo").yxeditgrid({
		objName : 'encryption[equ]',
		url : '?model=stock_delivery_encryptionequ&action=listJson',
		param : {
			parentId : $("#id").val(),
		},

		isAdd : false,

		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '合同发货清单从表ID',
			name : 'equId',
			type : 'hidden'
		},{
			display : '物料编号',
			name : 'productCode',
			type : 'statictext'
		},{
			display : '物料编号隐藏添加用',
			name : 'productCode',
			type : 'hidden'
		},{
			display : '物料名称',
			name : 'productName',
			type : 'statictext'
		},{
			display : '物料名称隐藏添加用',
			name : 'productName',
			type : 'hidden'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '规格型号',
			name : 'pattern',
			type : 'statictext'
		},{
			display : '规格型号隐藏添加用',
			name : 'pattern',
			type : 'hidden'
		},{
			display : '单位名称',
			name : 'unitName',
			type : 'statictext'
		},{
			display : '单位名称隐藏添加用',
			name : 'unitName',
			type : 'hidden'
		},{
			display : '需求数量',
			name : 'needNum',
			type : 'statictext'
		},{
			display : '需求数量隐藏添加用',
			name : 'needNum',
			type : 'hidden'
		},{
			display : '已执行数量',
			name : 'finshNum',
			type : 'statictext'
		},{
			display : '已执行数量隐藏添加用',
			name : 'finshNum',
			type : 'hidden'
		},{
			display : '加密锁任务数量',
			name : 'produceNum',
			tclass : 'txtshort',
			process : function($input ,rowData) {
				var produceNum = rowData.needNum - rowData.finshNum;
				$input.change(function () {
					if ($(this).val() > produceNum) {
						alert('不能大于（需求数量-已执行数量）');
						$input.val(produceNum).focus();
					}
				});
				$input.val(produceNum);
			},
			validation : {
				custom : ['onlyNumber']
			}
		},{
			display : '加密锁预计完成时间',
			name : 'planFinshDate',
			tclass : 'txtshort',
			type : 'date',
			validation : {
				required : true
			},
			readonly : true
		},{
			display : '备注',
			name : 'remark'
		}]
	});
});

//检查数据有效性
function checkData() {
	var $produceNumObj = $("#equInfo").yxeditgrid("getCmpByCol" ,"produceNum");
	for (var i = 0 ;i < $produceNumObj.length ;i++) {
		if ($produceNumObj[i].value <= 0) {
			return false;
		}
	}
	return true;
}

//直接下达
function toSubmit(){
	document.getElementById('form1').action="?model=stock_delivery_encryption&action=edit&issued=true";
}