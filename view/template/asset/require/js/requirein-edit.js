$(document).ready(function() {
	//验证信息
	validate({
		"applyName" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireinitem&action=listByRequireJson',
		objName : 'requirein[items]',
		title : '物料信息',
		param : {
			mainId : $("#id").val()
		},
		isAddOneRow : true,
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
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
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 60
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt',
			width : 200
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
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
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

/*
 * 保存/审核确认
 */
function confirmSubmit(type) {
	if(type == 'audit'){
		if (confirm("你确定要提交需求吗?")) {
			$("#form1").attr("action","?model=asset_require_requirein&action=edit&actType=audit");
			$("#form1").submit();
		} else {
			return false;
		}
	}else{
		$("#form1").attr("action","?model=asset_require_requirein&action=edit");
		$("#form1").submit();
	}
}

