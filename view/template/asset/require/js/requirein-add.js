$(document).ready(function() {
	//验证信息
	validate({
		"applyName" : {
			required : true
		}
	});
	//资产需求申请明细
	$("#allItemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		type : 'view',
		param : {
			mainId : $("#requireId").val(),
			type : 'add'
		},
		isAddOneRow : true,
		colModel : [{
			display : '设备名称',
			name : 'name',
			tclass : 'txt',
			width : 120
		}, {
			display : '设备描述',
			name : 'description',
			tclass : 'txt',
			width : 120
		}, {
			display : '物料id',
			name : 'productId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'txtshort',
			width : 120
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txtshort',
			width : 120
		}, {
			display : '物料金额',
			name : 'productPrice',
			tclass : 'txtshort',
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '物料品牌',
			name : 'brand',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txt',
			width : 60
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}]
	})
	//已下达明细
	$("#allreadyItemTable").yxeditgrid({
		url : '?model=asset_require_requireinitem&action=listJson',
		type : 'view',
		param : {
			requireId : $("#requireId").val()
		},
		isAddOneRow : true,
		colModel : [{
			display : '设备名称',
			name : 'name',
			tclass : 'txt',
			width : 120
		}, {
			display : '设备描述',
			name : 'description',
			tclass : 'txt',
			width : 120
		}, {
			display : '物料id',
			name : 'productId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'txtshort',
			width : 120
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txtshort',
			width : 120
		}, {
			display : '物料金额',
			name : 'productPrice',
			tclass : 'txtshort',
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '物料品牌',
			name : 'brand',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txt',
			width : 60
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}]
	})
	//本次下达明细
	$("#thisItemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=requireinJsonApply',
		objName : 'requirein[items]',
		param : {
			mainId : $("#requireId").val()
		},
		isAdd : false,
		isAddOneRow : true,
		colModel : [{
			display : 'requireItemId',
			name : 'requireItemId',
			type : "hidden"
		}, {
			display : '设备名称',
			name : 'name',
			validation : {
				required : true
			},
			width : 150,
			tclass : 'txt'
		}, {
			display : '设备描述',
			name : 'description',
			validation : {
				required : true
			},
			width : 150,
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txt',
			width : 60,
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件

					var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();
					if(!isNum($(this).val())){
						alert("数量输入不合法");
						$(this).focus().val(maxNum);
					}
					if($(this).val() *1 > maxNum *1){
						alert("数量不能大于剩余可申请数量" + maxNum);
						$(this).focus().val(maxNum);
					}
				}
			}
		}, {
			display : '最大数量',
			name : 'maxNum',
			type : "hidden"
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt',
			width : 150
		}, {
			display : '物料id',
			name : 'productId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 120
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '物料金额',
			name : 'productPrice',
			type : 'money',
			readonly : 'readonly',
			tclass : 'readOnlyTxtItem',
			width : 80
		}, {
			display : '物料品牌',
			name : 'brand',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]
	})
});
//保存/审核确认
function confirmSubmit(type) {
	if(type == 'audit'){
		if (confirm("你确定要提交需求吗?")) {
			$("#form1").attr("action",
					"?model=asset_require_requirein&action=add&actType=audit");
			$("#form1").submit();
		} else {
			return false;
		}
	}else{
		$("#form1").attr("action",
		"?model=asset_require_requirein&action=add");
		$("#form1").submit();
	}
}