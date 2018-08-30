$(function(){
    $("#taskInfo").yxeditgrid({
		url : "?model=engineering_resources_taskdetail&action=listJson",
        objName : 'task[item]',
		param : {
			'taskId' : $("#id").val(),
			'waitNumGt' : 0
		},
		isAdd : false,
		tableClass : 'form_in_table',
		colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        }, {
            display : 'applyDetailId',
            name : 'applyDetailId',
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
            width : 80,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '设备名称',
			name : 'resourceName',
            type : 'statictext',
            isSubmit : true
		}, {
			display : '单位',
			name : 'unit',
            width : 50,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '分配数量',
			name : 'number',
            width : 70,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '发货数量',
			name : 'exeNumber',
            width : 70,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '领用日期',
			name : 'planBeginDate',
            width : 70,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '归还日期',
			name : 'planEndDate',
            width : 70,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '使用天数',
			name : 'useDays',
			width : 50,
            type : 'statictext',
            isSubmit : true
		}, {
			display : '备注',
			name : 'remark',
            width : 120,
            type : 'statictext',
            isSubmit : true
		}, {
            display : '查询字段',
            name : 'searchKey',
            type : 'select',
            width : 80,
            options : [{
                'name' : '部门编码',
                'value' : 'dpcoding'
            },{
                'name' : '机身码',
                'value' : 'coding'
            },{
                'name' : '序号',
                'value' : 'id'
            }]
        }, {
            display : '查询内容',
            name : 'searchText',
            type : 'textarea',
            cols : 40,
            rows : 5
        }]
	});
});
//提交时验证
function checkForm(){
	//如果属于项目申请，验证项目是否已关闭
	var projectId = $("#projectId").val();
	if(projectId != "" && projectId != "0" && $("#taskInfo").yxeditgrid('getCurShowRowNum') > 0){
		var isClose = false;
		$.ajax({
			type: "POST",
			url: "?model=engineering_project_esmproject&action=isClose",
			data: {id: projectId},
			async: false,
			success: function(data) {
				if (data == "1") {
					isClose = true;
				}
			}
		});
		if(isClose){
			alert("项目已关闭，不能进行设备出库。请联系设备管理员是否要进行撤回操作。");
			return false;
		}
	}
}