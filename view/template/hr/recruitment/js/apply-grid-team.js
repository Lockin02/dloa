var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {
	$("#applyGrid").yxgrid({
		model : 'hr_recruitment_apply',
		action : "teamPageJsonList",
		title : '增员申请',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		param : {
			stateArr : '2,3,4,7'
		},
		isOpButton : false,
		bodyAlign : 'center',
		customCode : 'hr_recruitment_apply_dept_grid',
		event : {
			afterload : function(data,g){
				$("#deptLeadFlag").val(g.deptLeadFlag);
			}
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
			width : 140,
			process : function(v ,row){
				if(row.id>0) {
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_apply&action=toView&id=" + row.id +"\",1)'>" + v + "</a>";
				} else {
					return "";
				}
			}
		},{
			name : 'stateC',
			display : '单据状态',
			width : 60
		},{
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width:40
		},{
			name : 'formManName',
			display : '填表人',
			width : 70,
			sortable : true
		},{
			name : 'resumeToName',
			display : '接口人',
			width : 70,
			sortable : true
		},{
			name : 'deptNameO',
			display : '直属部门',
			width : 70,
			sortable : true
		},{
			name : 'deptNameS',
			display : '二级部门',
			width : 70,
			sortable : true
		},{
			name : 'deptNameT',
			display : '三级部门',
			width : 70,
			sortable : true
		},{
			name : 'deptNameF',
			display : '四级部门',
			width : 70,
			sortable : true
		},{
			name : 'workPlace',
			display : '工作地点',
			width : 70,
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
			name : 'positionNote',
			display : '职位备注',
			sortable : true,
			width : 180,
			process : function(v,row){
				var tmp = '';
				if (row.developPositionName) {
					tmp += row.developPositionName + '，';
				}
				if (row.network) {
					tmp += row.network + '，';
				}
				if (row.device) {
					tmp += row.device;
				}
				return tmp;
			}
		},{
			name : 'positionLevel',
			display : '级别',
			width : 70,
			sortable : true
		},{
			name : 'projectGroup',
			display : '所在项目组',
			width : 100,
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
			name : 'formDate',
			display : '填表日期',
			width : 80,
			sortable : true
		},{
			name: 'ExaDT',
			display : '申请通过时间',
			width : 120,
			sortable : true,
			process : function (v ,row) {
				if (row.state >= 1 && row.state <= 7) {
					return v;
				} else {
					return '';
				}
			}
		},{
			name : 'assignedDate',
			display : '下达日期',
			width : 80,
			sortable : true
		},{
			name : 'addType',
			display : '增员类型',
			sortable : true
		},{
			name : 'leaveManName',
			display : '离职/换岗人姓名',
			sortable : true
		},{
			name : 'needNum',
			display : '需求人数',
			width : 60,
			sortable : true,
			process : function(v ,row) {
				if(v == "") {
					return 0;
				} else {
					return v;
				}
			}
		},{
			name : 'entryNum',
			display : '已入职人数',
			width : 60,
			sortable : true,
			process : function(v ,row) {
				if(v == "") {
					return 0;
				} else {
					return v;
				}
			}
		},{
			name : 'beEntryNum',
			display : '待入职人数',
			width : 60,
			sortable : true,
			process : function(v ,row) {
				if(v == "") {
					return 0;
				} else {
					return v;
				}
			}
		},{
			name : 'ingtryNum',
			display : '在招聘人数',
			width : 60,
			sortable : true,
			process : function(v ,row) {
				return row.needNum - row.entryNum - row.beEntryNum;
			}
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
			name : 'userName',
			display : '录用名单',
			sortable : true,
			width : 200,
			process : function (v ,row) {
				if (v == '') {
					return row.employName;
				} else if (row.employName == '') {
					return v;
				} else {
					return v + ',' + row.employName;
				}
			}
		},{
			name : 'applyReason',
			display : '需求原因',
			width : 200,
			sortable : true
		},{
			name : 'workDuty',
			display : '工作职责',
			width : 200,
			sortable : true
		},{
			name : 'jobRequire',
			display : '任职要求',
			width : 200,
			sortable : true
		}],

		comboEx:[{
			text:'单据状态',
			key:'state',
			data:[{
				text:'招聘中',
				value:'2'
			},{
				text:'暂停',
				value:'3'
			},{
				text:'完成',
				value:'4'
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

		toViewConfig : {
			toViewFn : function(p,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=hr_recruitment_apply&action=toView&id=" + get[p.keyField]+"&ExaStatus="+get['ExaStatus'],'1');
				}
			}
		},

		menusEx : [{
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row){
				var deptLeadFlag = $("#deptLeadFlag").val();
				if(row.ExaStatus == "完成" && deptLeadFlag == 1) {
					return true;
				}
				return false;
			},
			action : function(row,rows,grid) {
				if(row){
					if(row.deptId == '130' || row.postType == 'YPZW-WY') {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&isAudit=no" + "&skey=" + row['skey_'] ,1);
					} else {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&skey=" + row['skey_'] ,1);
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_apply&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text : '暂停',
			icon : 'delete',
			showMenuFn: function(row) {
				var deptLeadFlag=$("#deptLeadFlag").val();
				if (row.state == 2&&deptLeadFlag==1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id="
						+ row.id + "&state=3"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '取消',
			icon : 'delete',
			showMenuFn: function(row) {
				var deptLeadFlag=$("#deptLeadFlag").val();
				if (row.state == 2&&deptLeadFlag==1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id="
						+ row.id + "&state=7"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '启用',
			icon : 'add',
			showMenuFn: function(row) {
				var deptLeadFlag=$("#deptLeadFlag").val();
				if (row.state == 3&&deptLeadFlag==1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id="
						+ row.id + "&state=2"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '启用暂停记录',
			icon : 'view',
			showMenuFn: function(row) {
				if (row.stopStart != '') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=toViewStartstop&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
				}
			}
		},{
			text : '取消招聘原因',
			icon : 'view',
			showMenuFn: function(row) {
				if (row.state == 7) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=toViewCancel&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
				}
			}
		}],

		searchitems : [{
			display : "单据编号",
			name : 'formCode'
		},{
			display : '填表人',
			name : 'formManName'
		},{
			display : '接口人',
			name : 'resumeToNameSearch'
		},{
			display : "直属部门",
			name : 'deptNameO'
		},{
			display : "二级部门",
			name : 'deptNameS'
		},{
			display : "三级部门",
			name : 'deptNameT'
		},{
			display : "四级部门",
			name : 'deptNameF'
		},{
			display : "职位类型",
			name : 'postTypeName'
		},{
			display : "需求职位",
			name : 'positionName'
		},{
			display : "工作地点",
			name : 'workPlaceSearch'
		},{
			display : "级别",
			name : 'positionLevelSearch'
		},{
			display : "所在项目组",
			name : 'projectGroupSearch'
		},{
			display : '填表时间',
			name : 'formDate'
		},{
			display : '申请通过时间',
			name : 'ExaDTSea'
		},{
			display : '增员类型',
			name : 'addType'
		},{
			display : '离职/换岗人姓名',
			name : 'leaveManName'
		},{
			display : '招聘负责人',
			name : 'recruitManName'
		},{
			display : '招聘协助人',
			name : 'assistManNameSearch'
		},{
			display : '录用名单',
			name : 'userName'
		},{
			display : '需求原因',
			name : 'applyReasonSearch'
		},{
			display : '工作职责',
			name : 'workDutySearch'
		},{
			display : '任职要求',
			name : 'jobRequireSearch'
		}]
	});
});