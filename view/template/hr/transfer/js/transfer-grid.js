var show_page = function(page) {
	$("#transferGrid").yxgrid("reload");
};

//删除重复项（考虑IE的兼容性问题）
function uniqueArray(a){
	temp = new Array();
	for(var i = 0 ;i < a.length ;i ++){
		if(!contains(temp ,a[i])) {
			temp.length += 1;
			temp[temp.length - 1] = a[i];
		}
	}
	return temp;
}

function contains(a, e){
	for(j = 0 ;j < a.length ;j++) {
		if(a[j] == e) {
			return true;
		}
	}
	return false;
}

$(function() {
	//表头按钮数组
	buttonsArr = [{
		name : 'return',
		text : '更新员工档案',
		icon : 'edit',
		action : function(row, rows, grid) {
			if(rows) {
				var checkedRowsIds=$("#transferGrid").yxgrid("getCheckedRowIds");  //获取选中的id
				var states=[];   //采购询价单状态数组
				$.each(rows,function(i,n){
					var o = eval( n );
					states.push(o.status);
				});
				states.sort();
				var uniqueState=uniqueArray(states);
				var stateLength=uniqueState.length;
				if(stateLength==1&&uniqueState[0]==4){  //判断单据的状态是否为“档案待更新”并且只有一种状态
					if(window.confirm("确认更新?")){
						$.ajax({
							type:"POST",
							url:"?model=hr_transfer_transfer&action=updatePersonInfo",
							data:{
								transferIds:checkedRowsIds
							},
							success:function(msg){
								if(msg==1){
									alert('更新成功!');
									show_page();
								}else{
									alert('更新失败!');
									show_page();
								}
							}
						});
					}
				}else{
					alert("请选择状态为'档案待更新'的单据");
				}
			}else{
				alert("请选择单据。");
			}
		}
	}];

	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_transfer_transfer&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	excelOutArr2 = {
		name : 'exportOut',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_transfer_transfer&action=toExport"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
		}
	};


	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutArr2);
			}
		}
	});

	$("#transferGrid").yxgrid({
		model : 'hr_transfer_transfer',
		title : '调动记录',
		isAddAction:true,
		isEditAction:false,
		isViewAction:false,
		isDelAction:false,
		isOpButton:false,
		bodyAlign:'center',
		param : {
			'status' : '1,2,3,4,5,6,7'
		},
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			width:120,
			process : function(v ,row) {
				if(row.status == 4) {
					return "<img src='images/icon/icon139.gif'/><a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +"\"'>" + v + "</a>";
				} else {
					return "<a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +"\"'>" + v + "</a>";
				}
			}
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			width : 80
		},{
			name : 'userName',
			display : '员工姓名',
			sortable : true,
			width : 70
		},{
			name : 'stateC',
			display : '单据状态',
			width : 70
		},{
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 70
		},{
			name : 'entryDate',
			display : '入职日期',
			sortable : true,
			width : 80
		},{
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 80
		},{
			name : 'isCompanyChangeC',
			display : '是否公司变动',
			sortable : false,
			width : 70
		},{
			name : 'isDeptChangeC',
			display : '是否部门变动',
			sortable : false,
			width : 70
		},{
			name : 'isJobChangeC',
			display : '是否职位变动',
			sortable : false,
			width : 70
		},{
			name : 'isAreaChangeC',
			display : '是否区域变动',
			sortable : false,
			width : 70
		},{
			name : 'isClassChangeC',
			display : '是否人员分类变动',
			sortable : false,
			width : 100
		},{
			name : 'preUnitName',
			display : '调动前公司',
			sortable : true,
			width : 80
		},{
			name : 'preBelongDeptName',
			display : '调动前所属部门',
			sortable : true,
			width : 80
		},{
			name : 'preDeptNameS',
			display : '调动前二级部门',
			sortable : true,
			width : 80
		},{
			name : 'preDeptNameT',
			display : '调动前三级部门',
			sortable : true,
			width : 80
		},{
			name : 'preDeptNameF',
			display : '调动前四级部门',
			sortable : true,
			width : 80
		},{
			name : 'afterUnitName',
			display : '调动后公司',
			sortable : true,
			width : 80
		},{
			name : 'afterBelongDeptName',
			display : '调动后所属部门',
			sortable : true,
			width : 80
		},{
			name : 'afterDeptNameS',
			display : '调动后二级部门',
			sortable : true,
			width : 80
		},{
			name : 'afterDeptNameT',
			display : '调动后三级部门',
			sortable : true,
			width : 80
		},{
			name : 'afterDeptNameF',
			display : '调动后四级部门',
			sortable : true,
			width : 80
		},{
			name : 'preJobName',
			display : '调动前职位',
			sortable : true,
			width : 80
		},{
			name : 'afterJobName',
			display : '调动后职位',
			sortable : true,
			width : 80
		},{
			name : 'preUseAreaName',
			display : '调动前归属区域',
			sortable : true,
			width : 80
		},{
			name : 'afterUseAreaName',
			display : '调动后归属区域',
			sortable : true,
			width : 80
		},{
			name : 'prePersonClass',
			display : '调动前人员分类',
			sortable : true
		},{
			name : 'afterPersonClass',
			display : '调动后人员分类',
			sortable : true
		},{
			name : 'managerName',
			display : '申请人',
			sortable : true,
			width : 60
		},{
			name : 'reason',
			display : '调动原因',
			sortable : true,
			width : 130,
			align : 'left'
		}],

		lockCol:['formCode','userNo','userName'],//锁定的列名

		buttonsEx: buttonsArr,

		toAddConfig : {
			formHeight : 550,
			formWidth : 900
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},

		//拓展右键菜单
		menusEx:[{
			text:'查看',
			icon:'view',
			action:function(row) {
				if(row){
					showThickboxWin("?model=hr_transfer_transfer&action=toViewJobTran&id="
						+ row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
				}
			}
		},{
			text:'提交审批',
			icon:'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "未提交" || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action:function(row){
				if(row){
					location = "?model=hr_transfer_transfer&action=toConfirm&id="+ row.id;
				}
			}
		},{
			text:'更新员工档案',
			icon:'edit',
			showMenuFn:function(row){
				if(row.ExaStatus == "完成" && row.status == 4) {
					return true;
				}
				return false;
			},
			action:function(row,rows,grid){
				if(row){
					if(window.confirm("确认更新?")){
						$.ajax({
							type:"POST",
							url:"?model=hr_transfer_transfer&action=updatePersonInfo",
							data:{
								transferIds:row.id
							},
							success:function(msg){
								if(msg==1){
									alert('更新成功!');
									show_page();
								}else{
									alert('更新失败!');
									show_page();
								}
							}
						});
					}
				}
			}
		},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_personnel_transfer&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text:'填写交接内容',
			icon:'edit',
			showMenuFn : function(row) {
				if (row.employeeOpinion == 1 && row.status == 3) {
					return true;
				}
				return false;
			},
			action:function(row){
				if(row){
					location = "?model=hr_transfer_transfer&action=toLeaderView&type=hrmanager&id="+ row.id;
				}
			}
		}],

		comboEx:[{
			text:'审批状态',
			key:'ExaStatus',
			data:[{
				text:'未提交',
				value:'未提交'
			},{
				text:'部门审批',
				value:'部门审批'
			},{
				text:'完成',
				value:'完成'
			}]
		},{
			text:'单据状态',
			key:'status',
			data:[{
				text:'未审核',
				value:'1'
			},{
				text:'已审核',
				value:'7'
			},{
				text:'员工待确认',
				value:'2'
			},{
				text:'员工已确认',
				value:'3'
			},{
				text:'档案待更新',
				value:'4'
			},{
				text:'完成',
				value:'6'
			}]
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '单据编号',
			name : 'formCode'
		},{
			display : '员工编号',
			name : 'userNoSearch'
		},{
			display : '员工姓名',
			name : 'userNameSearch'
		},{
			display : '入职日期',
			name : 'entryDate'
		},{
			display : '申请日期',
			name : 'applyDate'
		},{
			display : '调动前公司',
			name : 'preUnitName'
		},{
			display : '调动前所属部门',
			name : 'preBelongDeptName'
		},{
			display : '调动前二级部门',
			name : 'preDeptNameS'
		},{
			display : '调动前三级部门',
			name : 'preDeptNameT'
		},{
			display : '调动前四级部门',
			name : 'preDeptNameF'
		},{
			display : '调动后公司',
			name : 'afterUnitName'
		},{
			display : '调动后所属部门',
			name : 'afterBelongDeptName'
		},{
			display : '调动后二级部门',
			name : 'afterDeptNameS'
		},{
			display : '调动后三级部门',
			name : 'afterDeptNameT'
		},{
			display : '调动后四级部门',
			name : 'afterDeptNameF'
		},{
			display : '调动前职位',
			name : 'preJobName'
		},{
			display : '调动后职位',
			name : 'afterJobName'
		},{
			display : '调动前归属区域',
			name : 'preUseAreaName'
		},{
			display : '调动后归属区域',
			name : 'afterUseAreaName'
		},{
			display : '调动前人员分类',
			name : 'prePersonClass'
		},{
			display : '调动后人员分类',
			name : 'afterPersonClass'
		},{
			display : '申请人',
			name : 'managerName'
		},{
			display : '调动原因',
			name : 'reason'
		}]
	});
});