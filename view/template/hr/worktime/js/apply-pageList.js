var show_page = function(page) {
	$("#applyGrid").yxgrid("reload");
};

$(function() {
	$("#butt").hide();
	$("#applyGrid").yxgrid({
		model : 'hr_worktime_apply',
		title : '法定节假日统计表',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		bodyAlign:'center',
		param:{
			ExaStatusArr:'部门审批,完成,打回'
		},

		buttonsEx : [{
			name : 'expport',
			text : "导出",
			icon : 'excel',
			action : function(row ,rows,idArr) {
				var sql = $("#applyGrid").data('yxgrid').getListSql();
				$("#sql").val(sql);
				$("#butt").click();
			}
		},{
			name : 'expport',
			text : "高级查询",
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=hr_worktime_apply&action=toSearch"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
			}
		}],

		menusEx : [{
			name : 'edit',
			text : '修改时间',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_worktime_apply&action=toChangeTime&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=800");
				}
			}
		},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_worktime_apply&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userAccount',
			display : '员工账户',
			sortable : true,
			hide : true
		},{
			name : 'applyCode',
			display : '申请单据',
			width : 180,
			sortable : true,
			process : function(v,row){
				return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showThickboxWin(\'?model=hr_worktime_apply&action=toView&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800\')">'
					+ "<font color = 'blue'>"
					+ v + "</font>" + '</a>';
			}
		},{
			name : 'userNo',
			display : '员工编号',
			width : 60,
			sortable : true
		},{
			name : 'userName',
			display : '员工姓名',
			width : 60,
			sortable : true
		},{
			name : 'deptName',
			display : '直属部门',
			width : 80,
			sortable : true
		},{
			name : 'deptNameS',
			display : '二级部门',
			width : 80,
			sortable : true
		},{
			name : 'deptNameT',
			display : '三级部门',
			width : 80,
			sortable : true
		},{
			name : 'deptNameF',
			display : '四级部门',
			width : 80,
			sortable : true
		},{
			name : 'jobName',
			display : '职位',
			width : 100,
			sortable : true
		},{
			name : 'applyDate',
			display : '申请日期',
			width : 70,
			sortable : true
		},{
			name : 'holiday',
			display : '加班时间',
			width : 100,
			sortable : true,
			process: function(v) {
				var str = v.split(',');
				var holiday = '';
				var holidayInfo = '';
				var rs = '';
				for(var i = 0 ;i < str.length ;i++) {
					holiday = str[i].substr(0 ,10);
					holidayInfo = str[i].substr(-1);
					if (holidayInfo == '1') {
						holidayInfo = '上午';
					}else if (holidayInfo == '2') {
						holidayInfo = '下午';
					}else if (holidayInfo == '3') {
						holidayInfo = '全天';
					} else {
						holidayInfo = '';
					}
					rs += holiday + '&nbsp&nbsp' + holidayInfo + '<br>';
				}
				return rs;
			}
		},{
			name : 'workBegin',
			display : '上班开始时间',
			width : 70,
			sortable : true
		},{
			name : 'beginIdentify',
			display : '开始上/下午',
			width : 65,
			sortable : true,
			process : function(v) {
				if(v == 1){
					return '上午';
				} else if(v == 2){
					return '下午';
				}
				return '';
			}
		},{
			name : 'workEnd',
			display : '上班结束时间',
			width : 70,
			sortable : true
		},{
			name : 'endIdentify',
			display : '结束上/下午',
			width : 65,
			sortable : true,
			process : function(v) {
				if(v == 1){
					return '上午';
				} else if(v == 2){
					return '下午';
				}
				return '';
			}
		},{
			name : 'dayNo',
			display : '天数',
			width: 40,
			sortable : true
		},{
			name : 'ExaStatus',
			display : '审批状态',
			width : 50,
			sortable : true
		},{
			name : 'workContent',
			display : '上班处理工作内容',
			width : 150,
			sortable : true,
			align : 'left'
		},{
			name : 'changeTimeReason',
			display : '备注',
			width : 150,
			sortable : true,
			align : 'left',
			process :　function(v) {
				if (v) {
					v = '修改时间原因：' + v;
				}
				return v;
			}
		},{
			display : '详情',
			sortable : true,
			width : 50,
			process :　function(v,row) {
				return '<a href="javascript:void(0)" title="点击查看单据" onclick="javascript:showThickboxWin(\'?model=hr_worktime_apply&action=toViewApproval&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800\')">'
					+ "<font color = 'blue'>"
					+ '查看' + "</font>" + '</a><br>';
			}
		}],

		lockCol:['userName','userNo'], //锁定的列名

		toViewConfig : {
			formHeight : 600,
			action : 'toView'
		},

		//下拉过滤
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '部门审批',
				value : '部门审批'
			},{
				text : '完成',
				value : '完成'
			},{
				text : '打回',
				value : '打回'
			}]
		}],

		searchitems : [{
			display : "申请单据",
			name : 'applyCodeS'
		},{
			display : "员工姓名",
			name : 'userNameS'
		},{
			display : "员工编号",
			name : 'userNoS'
		},{
			display : "职位",
			name : 'jobName'
		},{
			display : "直属部门",
			name : 'deptName'
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
			display : "申请日期",
			name : 'applyDate'
		},{
			display : "加班时间",
			name : 'holiday'
		}]
	});
});