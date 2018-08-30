var show_page = function(page) {
	$("#rewardGrid").yxgrid("reload");
};
$(function() {
	$("#rewardGrid").yxgrid({
		model : 'hr_tutor_reward',
		title : '导师奖励管理',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=hr_tutor_reward&action=toView&id="
						+ data.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
//		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_rewardlist',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'code',
			display : '编号',
			sortable : true,
			width : 120,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=hr_tutor_reward&action=toView&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'name',
			display : '方案名称',
			sortable : true,
			width : 170
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 70
		}, {
			name : 'isGrant',
			display : '奖励发放状态',
			sortable : true,
			process: function (v,row){
			   if(v=='0'){
			      return "未发放";
			   }else if(v==1){
			      return "已发放";
			   }
			}
		}, {
			name : 'isPublish',
			display : '奖励信息是否发布',
			sortable : true,
			process: function (v){
			   if(v=='0'){
			      return "否";
			   }else if(v==1){
			      return "是";
			   }
			},
			width :100
		},{
			name : 'createName',
			display : '申请人',
			sortable : true,
			width : 70
		}, {
			name : 'createTime',
			display : '申请时间',
			sortable : true,
			width :80
		}],
		toAddConfig : {
			formWidth:900
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		// 扩展右键菜单
		menusEx : [{
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_tutor_reward&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text : '确认发布',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成'&&row.isPublish=='0') {
					return true;
				}
				return false;
			},
			action:function(row){
				if(window.confirm(("确定要发布奖励相关信息?"))){
					$.ajax({
						type : "POST",
						url : "?model=hr_tutor_reward&action=publish",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('发布成功！');
								$("#rewardGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		},{
			text : '确认奖励发放',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isGrant == '0'&&row.ExaStatus =="完成") {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=hr_tutor_reward&action=toGrant&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}

		}],
				// 默认搜索字段名
				sortname : "code",
				// 默认搜索顺序
				sortorder : "asc",
		searchitems : [{
			display : "编号",
			name : 'code'
		},{
			display : "方案名称",
			name : 'name'
		},{
		    display : "部门名称",
		    name : 'dept'
		},{
		    display : "申请人",
		    name : 'createName'
		}]
	});
});