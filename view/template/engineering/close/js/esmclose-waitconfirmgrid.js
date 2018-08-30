var show_page = function() {
	$("#esmcloseGrid").yxgrid("reload");
};

$(function() {
	$("#esmcloseGrid").yxgrid({
		model: 'engineering_close_esmclose',
		action: 'waitConfirmJson',
		title: '项目关闭确认',
		isAddAction: false,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		isOpButton: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width: 130
		}, {
			name: 'projectName',
			display: '项目名称',
			sortable: true,
			width: 150
		}, {
			name: 'applyName',
			display: '申请人',
			sortable: true
		}, {
			name: 'applyDate',
			display: '申请日期',
			sortable: true,
			width: 80
		}, {
			name: 'ruleName',
			display: '名目',
			sortable: true
		}, {
			name: 'content',
			display: '描述',
			sortable: true,
			width: 300
		}, {
			name: 'status',
			display: '状态',
			sortable: true,
			process: function(v) {
				return v == "1" ? "已确认" : "未确认";
			},
			width: 70
		}],
		buttonsEx: [{
			text: "确认完成",
			icon: 'add',
			action: function (row, rows) {
				if (row) {
					var newIdArr = [];
					for (var i = 0; i < rows.length; i++) {
						if (rows[i].status != '0') {
							alert('单据 [' + rows[i].detailId + '] 不是未确认状态，不能进行操作');
							return false;
						} else {
							newIdArr.push(rows[i].detailId);
						}
					}
					if (newIdArr.length > 0 && confirm('确认进行此操作么？')) {
						$.ajax({
							type: "POST",
							url: "?model=engineering_close_esmclosedetail&action=confirm",
							data: {
								ids: newIdArr.toString()
							},
							success: function (msg) {
								if (msg == "1") {
									alert('确认成功！');
									show_page();
								} else {
									alert('确认失败!');
								}
							}
						});
					}
				} else {
					alert('请先选择至少一条记录');
				}
			}
		}],
		//过滤数据
		comboEx: [{
			text: '状态',
			key: 'dStatus',
			value: 0,
			data: [{
				text: '已确认',
				value: '1'
			}, {
				text: '未确认',
				value: '0'
			}]
		}],
		searchitems: [{
			display: "项目编号",
			name: 'projectCode'
		},{
			display: "申请人",
			name: 'applyName'
		}]
	});
});