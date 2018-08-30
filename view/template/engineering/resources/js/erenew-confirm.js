$(document).ready(function() {
	//从表信息
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_erenewdetail&action=listJson',
		param : {
			'mainId' : $("#id").val(),
			'status' : '0'
		},
		isAdd : false,
		hideRowNum : true,
		objName : 'erenew[item]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '确认',
			name : 'isChecked',
			type : 'checkbox',
			checkVal : '1',
			process : function ($input ,rowData) {
				var rowNum = $input.data("rowNum");
				$("#importTable_cmp_isChecked" + rowNum).attr('checked' ,'checked');
			}
		}, {
			display : 'id',
			name : 'id',
			isSubmit : true,
			type: 'hidden'
		}, {
			display : 'borrowItemId',
			name : 'borrowItemId',
			isSubmit : true,
			type: 'hidden'
		}, {
			display : 'resourceListId',
			name : 'resourceListId',
			isSubmit : true,
			type: 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '设备类型ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			display : '设备类型',
			width : 100,
			name : 'resourceTypeName',
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'resourceId',
			display : '设备ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceName',
			width : 120,
			display : '设备名称',
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'coding',
			display : '机身码',
			width : 120,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'dpcoding',
			display : '部门编码',
			width : 100,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'number',
			display : '数量',
			width : 60,
			isSubmit : true,
			type : 'statictext'
		}, {
			name : 'unit',
			display : '单位',
			width : 60,
			isSubmit : true,
			type : 'statictext'
		}, {
            name : 'beginDate',
            display : '预计开始日期',
            width : 80,
            isSubmit : true,
            type : 'statictext'
        }, {
            name : 'endDate',
            display : '预计归还日期',
            width : 80,
            isSubmit : true,
            type: 'statictext'
        }, {
			name : 'remark',
			display : '备注',
			width : 120,
			isSubmit : true,
			type : 'statictext'
		}]
	});
});

//确认时进行相关验证
function checkSubmit() {
	var objGrid = $("#importTable");

	var resourceIdArr = objGrid.yxeditgrid('getCmpByCol','resourceId');
	if(resourceIdArr.length == 0){
		alert("设备信息不能为空!");
		return false;
	}
    //是否勾选了物料
    var isChecked = false;
    resourceIdArr.each(function(){
        if(objGrid.yxeditgrid("getCmpByRowAndCol",$(this).data("rowNum"),"isChecked").attr('checked')){
        	isChecked = true;
        }
    });
    if(isChecked == false){
        alert('请至少勾选一个设备进行确认');
        return false;
    }
}