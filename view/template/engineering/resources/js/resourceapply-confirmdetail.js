$(document).ready(function() {
	//已确认物料
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_resourceapplydet&action=listJson',
		param : {
			'mainId' : $("#id").val(),
			"isConfirm" : 1//确认了的物料
		},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			name : 'resourceTypeName',
			display : '设备类型'
		}, {
			name : 'resourceName',
			display : '设备名称'
		}, {
			name : 'number',
			display : '数量',
			width : 60
		}, {
			name : 'exeNumber',
			display : '已下达数量',
			width : 60
		}, {
			name : 'unit',
			display : '单位',
			width : 60
		}, {
			name : 'planBeginDate',
			display : '领用日期',
			width : 80
		}, {
			name : 'planEndDate',
			display : '归还日期',
			width : 80
		}, {
			name : 'useDays',
			display : '使用天数',
			width : 60
		}, {
			name : 'price',
			display : '单设备折旧',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			name : 'amount',
			display : '设备成本',
			align : 'right',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			name : 'remark',
			display : '备注'
		}]
	});


	//未确认物料
	$("#unconfirmDetial").yxeditgrid({
		url : "?model=engineering_resources_resourceapplydet&action=listJson",
		param : {
			'mainId' : $("#id").val(),
			"isNotConfirm" : 1//未确认物料
		},
		objName : 'resourceapply[resourceapplydet]',
		tableClass : 'form_in_table',
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			width : 80
		}, {
			display : '设备名称',
			name : 'resourceName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_esmdevice({
					hiddenId : g.el.attr('id')+ '_cmp_resourceId' + rowNum,
					width : 600,
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'resourceTypeName').val(rowData.deviceType);
									g.getCmpByRowAndCol(rowNum,'resourceTypeId').val(rowData.typeid);

									g.getCmpByRowAndCol(rowNum,'unit').val(rowData.unit);
									g.setRowColValue(rowNum, 'price', rowData.budgetPrice, true);
									//计算设备金额
									calResourceBatch(rowNum);
								}
							})(rowNum)
						}
					}
				}).attr("readonly",false);
			}
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 50
		}, {
			display : '数量',
			name : 'number',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 50
		}, {
			display : '领用日期',
			name : 'planBeginDate',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 90
		}, {
			display : '归还日期',
			name : 'planEndDate',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 90
		}, {
			display : '使用天数',
			name : 'useDays',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 60
		}, {
			display : '设备折价',
			name : 'price',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '预计成本',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '备注说明',
			name : 'remark',
			tclass : 'txtmiddle'
		}],
		event : {
			'reloadData' : function(e,g,data){
				if(!data){
					alert('没有需要确认的设备');
					window.close();
				}
			}
		}
	})
});

//计算设备金额 - 批量新增 - 复制 功能中使用
function calResourceBatch(rowNum){
	//从表前置字符串
	var beforeStr = "unconfirmDetial_cmp";
	//获取当前数量
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_resourceName"  + rowNum ).val() != "" && number != ""){
		//获取单价
		var price = $("#" + beforeStr +  "_price" + rowNum + "_v").val();
		//获取天数
		var useDays = $("#" + beforeStr +  "_useDays" + rowNum ).val();
		//计算单天设备金额
		var amount = accMul(number,price,2);

		//计算多天设备金额
		var amount = accMul(useDays,amount,2);

		setMoney(beforeStr +  "_amount" +  rowNum,amount,2);
	}
}

//表单验证
function checkForm(){
	var resourceIdArr = $("#unconfirmDetial").yxeditgrid('getCmpByCol','resourceId');
	var isAllConfirm = true;
	resourceIdArr.each(function(){
		if(this.value == "0"){
			isAllConfirm = false;
		}
	});
	if(isAllConfirm == false){
		alert('含有未确认的物料,不能提交表单');
		return false;
	}
	if(confirm('确认提交单据吗')){
		return true;
	}else{
		return false;
	}
}