$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_ereturndetail&action=listJson',
		param : {
			'mainId' : $("#id").val(),
			'status' : '0'
		},
		isAdd : false,
		hideRowNum : true,
		objName : 'ereturn[item]',
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
			display : '设备类型',
			name : 'resourceTypeName',
			isSubmit : true,
			type : 'statictext',
			width : 150
		}, {
			name : 'resourceName',
			display : '设备名称',
			isSubmit : true,
			type : 'statictext',
			width : 150
		}, {
			name : 'coding',
			display : '机身码',
			isSubmit : true,
			type : 'statictext',
			width : 150
		}, {
			name : 'dpcoding',
			display : '部门编码',
			isSubmit : true,
			type : 'statictext',
			width : 100
		}, {
			name : 'number',
			display : '申请数量',
			tclass : 'readOnlyTxt',
			readonly : true,
			width : 60
		}, {
			name : 'confirmNumV',
			display : '已归还数量',
			tclass : 'readOnlyTxt',
			readonly : true,
			width : 60,
			process:function($input,row){
				$input.val(row.confirmNum);
			}
		}, {
			name : 'confirmNum',
			display : '已归还数量',
			process:function($input,row){
				$input.val(row.number);
			},
			type : 'hidden'
		}, {
			name : 'remainNum',
			display : '剩余数量',
			type : 'hidden'
		}, {
			name : 'returnNum',
			display : '归还数量',
			width : 60,
			process:function($input,row){
				$input.val(row.remainNum);
			},
			event : {
				blur : function(){
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					//累加已归还数量
					var confirmNumV = g.getCmpByRowAndCol(rowNum,'confirmNumV').val();
					var confirmNumObj = g.getCmpByRowAndCol(rowNum,'confirmNum');
					confirmNumObj.val(accAdd(confirmNumV,$(this).val()));
				}
			}
		}, {
			name : 'unit',
			display : '单位',
			isSubmit : true,
			type : 'statictext',
			width : 100
		}, {
			name : 'id',
			display : 'id',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'borrowItemId',
			display : '借用单明细Id',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceListId',
			display : 'resourceListId',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceId',
			display : '设备ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '设备类型ID',
			isSubmit : true,
			type : 'hidden'
		}]
	});
	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({
		"applyUser" : {
			required : true
		},
		"applyDate" : {
			required : true
		},
		"areaId" : {
			required : true
		}
	});
});

//确认时进行相关验证
function checkSubmit() {
	var objGrid = $("#importTable");
	//是否允许提交
	var submit = true;
	var returnNumArr = objGrid.yxeditgrid('getCmpByCol','returnNum');
	if(returnNumArr.length > 0){
	    //是否勾选了物料
	    var isChecked = false;
		returnNumArr.each(function(){
	    	var rowNum = $(this).data("rowNum");//当前行数
	        if(objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"isChecked").attr('checked')){//只验证勾选的物料
	        	isChecked = true;
	    		var remainNum = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"remainNum").val();
	    		var returnNum = $(this).val();
	            if (!isNum(returnNum)) {
	                alert("归还数量" + "<" + returnNum + ">" + "填写有误!");
	                $(this).focus();
	                submit = false;
	            }
	            if (returnNum*1 > remainNum*1) {
	                alert("归还数量不能大于" + remainNum);
	                $(this).focus();
	                submit = false;
	            }
	        }
	    });
	    if(isChecked == false){
	        alert('请至少勾选一个设备进行确认');
	        return false;
	    }
	}
    return submit;
}