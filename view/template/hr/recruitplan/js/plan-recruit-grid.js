var show_page = function(page) {
	$("#planGrid").yxgrid("reload");
};

$(function() {
	buttonsArr = [];

	// 表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitplan_plan&action=toImport"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};
	buttonsArr.push(excelOutArr);
	/*
	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_attendance&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});
	*/

	$("#planGrid").yxgrid({
		isEditAction : false,
		isDelAction : false,
		isAddAction:false,
		model : 'hr_recruitplan_plan',
		title : '招聘计划',
		buttonsEx:buttonsArr,
		param:{
			'ExaStatus':'完成'
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
			width : 130,
			process : function(v ,row) {
				//if(row.viewType==1){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitplan_plan&action=toView&id=" + row.id +"\")'>" + v + "</a>";
				/*}else{
					return "";
				}*/
			}
		},{
			name : 'stateC',
			display : '单据状态',
			width : 60
		},{
			name : 'ExaStatus',
			display : '审批状态',
			width : 60
		},{
			name : 'formManName',
			display : '填表人',
			width : 70,
			sortable : true
		},{
			name : 'deptName',
			display : '需求部门',
			sortable : true
		},{
			name : 'postTypeName',
			display : '职位类型',
			width : 80,
			sortable : true
		},{
			name : 'positionName',
			display : '需求职位',
			sortable : true
		},{
			name : 'isEmergency',
			display : '是否紧急',
			sortable : true,
			width : 60,
			process : function(v ,row) {
				if(v == "1") {
					return "是"
				}else if(v == "0") {
					return "否";
				}else{
					return "";
				}
			}
		},{
			name : 'hopeDate',
			display : '希望到岗时间',
			sortable : true
		},{
			name : 'addType',
			display : '增员类型',
			sortable : true
		},{
			name : 'needNum',
			display : '需求人数',
			width : 60,
			sortable : true
		},{
			name : 'entryNum',
			display : '已入职人数',
			width : 60,
			sortable : true
		},{
			name : 'beEntryNum',
			display : '待入职人数',
			width : 60,
			sortable : true
		},{
			name : 'recruitManName',
			display : '招聘负责人',
			width : 70,
			sortable : true
		},{
			name : 'assistManName',
			display : '招聘协助人',
			sortable : true,
			width : 200
		},{
			name : 'applyRemark',
			display : '进度备注',
			sortable : true,
			width : 300
		}],

		comboEx:[{
			text:'单据状态',
			key:'state',
			data:[{
				text:'未下达',
				value:'1'
			},{
				text:'招聘中',
				value:'2'
			},{
				text:'暂停',
				value:'3'
			},{
				text:'完成',
				value:'4'
			},{
				text:'关闭',
				value:'5'
			},{
				text:'挂起',
				value:'6'
			},{
				text:'取消',
				value:'7'
			}]
		},{
			text:'是否紧急',
			key:'isEmergency',
			data:[{
				text:'是',
				value:'1'
			},{
				text:'否',
				value:'0'
			}]
		}],


		menusEx : [{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回'
					|| row.ExaStatus == '完成'
					|| row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitplan_plan&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text : '暂停',
			icon : 'delete',
			showMenuFn: function(row) {
				if (row.state ==2)
					return true;
				else
					return false;
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitplan_plan&action=tochangeState&id="
						+ row.id+"&state="+3
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '挂起',
			icon : 'delete',
			showMenuFn: function(row) {
				if (row.state ==2)
					return true;
				else
					return false;
			},
			action : function(row) {
				if(row) {
					showThickboxWin("?model=hr_recruitplan_plan&action=tochangeState&id="
						+ row.id+"&state="+6
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '取消',
			icon : 'delete',
			showMenuFn: function(row) {
				if (row.state == 2)
					return true;
				else
					return false;
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitplan_plan&action=tochangeState&id="
						+ row.id+"&state="+7
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '启用',
			icon : 'add',
			showMenuFn: function(row) {
				if (row.state == 3 || row.state == 6 || row.state == 9) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row) {
					if(window.confirm("确认要启用吗?")) {
						$.ajax({
							type:"POST",
							url:"?model=hr_recruitplan_plan&action=changeState",
							data:{
								id:row.id,
								state:2
							},
							success:function(msg) {
								if(msg == 1) {
									alert('启用成功!');
									show_page();
								}else{
									alert('启用失败!');
									show_page();
								}
							}
						});
					}
				}
			}
		}],

		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=hr_recruitplan_plan&action=toView&id=" + get[p.keyField]);
				}
			}
		},

		searchitems : [{
			display : "单据编号",
			name : 'formCode'
		},{
			display : '填表人',
			name : 'formManName'
		},{
			display : "需求部门",
			name : 'deptName'
		},{
			display : "职位类型",
			name : 'postTypeName'
		},{
			display : "需求职位",
			name : 'positionName'
		},{
			display : "希望到岗时间",
			name : 'hopeDate'
		},{
			display : "增员类型",
			name : 'addType'
		},{
			display : "需求人数",
			name : 'needNum'
		},{
			display : '已入职人数',
			name : 'entryNum'
		},{
			display : '待入职人数',
			name : 'beEntryNum'
		},{
			display : '招聘负责人',
			name : 'recruitManName'
		},{
			display : '招聘协助人',
			name : 'assistManName'
		},{
			display : '进度备注',
			name : 'applyRemark'
		}]
	});
});