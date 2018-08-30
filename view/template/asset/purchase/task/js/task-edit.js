$(function() {
	$("#taskTable").yxeditgrid({
		objName : 'task[taskItem]',
		url : '?model=asset_purchase_task_taskItem&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '规格',
			name : 'pattem',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '供应商',
			name : 'supplierName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '采购数量',
			name : 'purchAmount',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '任务数量',
			name : 'taskAmount',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'readOnlyTxtItem',
			type : 'money'
		}, {
			display : '金额',
			name : 'moneyAll',
			tclass : 'readOnlyTxtItem',
			type : 'money'
		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort datehope'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'readOnlyTxtItem'
		}]
	})
	// 选择人员组件
	$("#purcherName").yxselect_user({
		hiddenId : 'purcherId'
	});

	//日期联动
	$("#arrivaDate").bind("focusout",function(){
		var dateHope=$(this).val();
		$.each($(':input[class^="txtshort datehope"]'),function(i,n){
			$(this).val(dateHope);
		})
	});

	/**
	 * 验证信息
	 */
	validate({
		"sendName" : {
			required : true
		},
		"sendTime" : {
			custom : ['date']
		},
		"purcherName" : {
			required : true
		},
		"acceptDate" : {
			custom : ['date']
		},
		"endDate" : {
			custom : ['date']
		}
	});

});

