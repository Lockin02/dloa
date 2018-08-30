var show_page = function(page) {
	$("#mailrecordGrid").yxgrid("reload");
};

$(function() {
	if($("#hasTimeTask").val() == 1){
		thisTitle = '到款邮件记录 [定时任务已启用]';
	}else{
		thisTitle = '到款邮件记录 [定时任务未启用,请联系管理员启用]';
	}
	$("#mailrecordGrid").yxgrid({
		model: 'finance_income_mailrecord',
		title: thisTitle,
		isAddAction : false,
//		isEditAction : false,
//		isDelAction : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'incomeId',
			display: '关联单据id',
			sortable: true,
			hide :true
		},
		{
			name: 'incomeCode',
			display: '关联单据编号',
			sortable: true,
			width : 120
		},
		{
			name: 'sendIds',
			display: '收件人',
			sortable: true,
			hide :true
		},
		{
			name: 'sendNames',
			display: '收件人名称',
			sortable: true,
			width : 120
		},
		{
			name: 'copyIds',
			display: '抄送人',
			sortable: true,
			hide :true
		},
		{
			name: 'copyNames',
			display: '抄送人名称',
			sortable: true,
			width : 120
		},
		{
			name: 'secretIds',
			display: '密送人',
			sortable: true,
			hide :true
		},
		{
			name: 'secretNames',
			display: '密送人名称',
			sortable: true,
			hide :true
		},
		{
			name: 'title',
			display: '邮件标题',
			sortable: true
		},
		{
			name: 'content',
			display: '邮件内容',
			sortable: true,
			hide :true
		},
		{
			name: 'times',
			display: '发送次数',
			sortable: true,
			width : 80
		},
		{
			name: 'status',
			display: '状态',
			sortable: true,
			width : 80,
			process : function(v){
				if(v == 0){
					return '开启';
				}else{
					return '关闭';
				}
			}
		},
		{
			name: 'createOn',
			display: '创建时间',
			sortable: true
		},
		{
			name: 'lastMailTime',
			display: '最近发送时间',
			sortable: true
		}],

		//扩展右键菜单
		menusEx : [{
			text : '关闭',
			icon : 'delete',
			showMenuFn : function(row){
				if(row.status == '0' ){
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要关闭?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_income_mailrecord&action=changeStatus",
						data : {
							id : row.id,
							status : 1
						},
						success : function(msg) {
							if (msg == 1) {
								alert('关闭成功！');
								$("#mailrecordGrid").yxgrid("reload");
							}else{
								alert("关闭失败! ");
							}
						}
					});
				}
			}
		},{
			text : '启用',
			icon : 'add',
			showMenuFn : function(row){
				if(row.status == '1' ){
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要启动?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_income_mailrecord&action=changeStatus",
						data : {
							id : row.id,
							status : 0
						},
						success : function(msg) {
							if (msg == 1) {
								alert('启动成功！');
								$("#mailrecordGrid").yxgrid("reload");
							}else{
								alert("启动失败! ");
							}
						}
					});
				}
			}
		}],// 过滤数据
		comboEx : [{
			text : '状态',
			key : 'status',
			data : [{
				value : 0,
				text  : '开启'
			},{
				value : 1,
				text  : '关闭'
			}]
		}],
		searchitems : [{
			display : '关联单据号',
			name : 'incomeCodeSearch'
		},{
			display : '收件人',
			name : 'sendNames'
		}]
	});
});