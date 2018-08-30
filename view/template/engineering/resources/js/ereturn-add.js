$(document).ready(function() {
	// 人员渲染
	$("#applyUser").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [ true, "deptId", "deptName" ],
		formCode : 'resourceapply'
	});

	// 实例化邮寄公司
	$("#expressName").yxcombogrid_logistics({
		hiddenId : 'expressId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});

	// 从表初始化
	$("#importTable").yxeditgrid({
		url : '?model=engineering_device_esmdevice&action=selectdeviceInfo',
		param : {
			rowsId : $("#rowsId").val()
		},
		isAddAndDel : false,
		objName : 'ereturnDetail',
		tableClass : 'form_in_table',
		colModel : [ {
			display : '设备类型',
			name : 'resourceTypeName',
			type : 'statictext',
			width : 150,
			isSubmit : true
		}, {
			name : 'resourceName',
			display : '设备名称',
			type : 'statictext',
			width : 150,
			isSubmit : true
		}, {
			name : 'coding',
			display : '机身码',
			type : 'statictext',
			width : 150,
			isSubmit : true
		}, {
			name : 'dpcoding',
			display : '部门编码',
			type : 'statictext',
			width : 100,
			isSubmit : true
		}, {
			name : 'maxNum',
			display : '在借数量',
			tclass : 'readOnlyTxt',
			readonly : true,
			width : 80,
			process:function($input,row){
				$input.val(row.number);
			}
		}, {
			name : 'number',
			display : '归还数量',
			width : 80,
			isSubmit : true
		}, {
			name : 'unit',
			display : '单位',
			type : 'statictext',
			width : 80,
			isSubmit : true
		}, {
            name : 'resourceListId',
            display : 'resourceListId',
            isSubmit : true,
            type : 'hidden'
        }, {
            name : 'borrowItemId',
            display : '借用单明细id',
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
		} ]
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

// 提交确认改变隐藏值
function setStatus(thisType) {
	$("#status").val(thisType);
}

//保存,提交时进行相关验证
function checkSubmit() {
	var objGrid = $("#importTable");
	//获取设备的行数
	var curRowNum = objGrid.yxeditgrid("getCurShowRowNum");
	//验证归还数量
	for(var i = 0; i < curRowNum ; i++){
		var maxNum = objGrid.yxeditgrid("getCmpByRowAndCol",i,"maxNum").val();
		var numObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"number");
		var number = numObj.val();
		
        if (!isNum(number)) {
            alert("归还数量" + "<" + number + ">" + "填写有误!");
            numObj.focus();
            return false;
        }
        if (number*1 > maxNum*1) {
            alert("归还数量不能大于在借数量");
            numObj.focus();
            return false;
        }
	}
}