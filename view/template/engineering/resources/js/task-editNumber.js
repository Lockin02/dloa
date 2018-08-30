$(function(){
	//邮件渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	$("#taskInfo").yxeditgrid({
		url : "?model=engineering_resources_taskdetail&action=listJson",
		param : {"taskId":$("#id").val()},
		tableClass : 'form_in_table',
		objName : 'task[detail]',
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
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
			width : 100,
			type : 'statictext'
		}, {
			display : '设备名称',
			name : 'resourceName',
			validation : {
				required : true
			},
			width : 120,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				var resourceTypeId = g.getCmpByRowAndCol(rowNum,'resourceTypeId').val();
				$input.yxcombogrid_esmdevice({
					hiddenId : g.el.attr('id')+ '_cmp_resourceId' + rowNum,
					width : 600,
					isFocusoutCheck : false,
					gridOptions : {
						showcheckbox : false,
						param : {typeid : resourceTypeId},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var resourceName = g.getCmpByRowAndCol(rowNum,'resourceName').val();
									var remark = g.getCmpByRowAndCol(rowNum,'remark').val();
									g.getCmpByRowAndCol(rowNum,'unit').val(rowData.unit);
									g.getCmpByRowAndCol(rowNum,'remark').val(remark+"(发货员将【"+resourceName+"】修改为【"+rowData.device_name+"】)");
								}
							})(rowNum)
						}
					}
				}).attr("readonly",true);
			}
		}, {
			display : '单位',
			name : 'unit',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			width : 50
		}, {
			display : '分配数量',
			name : 'number',
			width : 50
		}, {
			display : '最大分配数量',
			name : 'maxNumber',
			type : 'hidden'
		}, {
			display : '领用日期',
			name : 'planBeginDate',
			width : 70,
			type : 'statictext'
		}, {
			display : '归还日期',
			name : 'planEndDate',
			width : 70,
			type : 'statictext'
		}, {
			display : '使用天数',
			name : 'useDays',
			width : 50,
			type : 'statictext'
		}, {
			display : '备注',
			name : 'remark',
			width : 300
		}]
	});
});
//表单验证
function checkForm() {
	var objGrid = $("#taskInfo");
	//从表不允许为空
	if(objGrid.yxeditgrid("getCurShowRowNum") == 0){
		alert("任务分配详情不能为空!");
		return false;
	}
	var itemscount = objGrid.yxeditgrid("getCurRowNum");
	var num = 0;//计算分配数量为0的明细数量
	for (var i = 0; i < itemscount; i++) {
		//获取分配数量
		var numberObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"number");
		var number = numberObj.val();
		if(number == 0){
			num++;
		}
		//获取可分配最大数量
		var maxNumber = objGrid.yxeditgrid("getCmpByRowAndCol",i,"maxNumber").val();
		if(number == ""){
			alert("分配数量不能为空！");
			numberObj.focus();
			return false;
		}
		var re = /^[0-9]\d*$/;
		if(!re.test(number)){
			alert("分配数量必须为正整数！");
			numberObj.focus();
			return false;
		}
		if (parseInt(number) > parseInt(maxNumber)) {
			alert("分配数量不能大于"+maxNumber);
			numberObj.focus();
			return false;
		}
	}
	if(num == itemscount){
		alert("任务明细分配数量均为0，请回到发货任务列表进行【撤销任务】操作!");
		return false;
	}
	if(confirm('确认提交单据吗')){
		return true;
	}else{
		return false;
	}
}