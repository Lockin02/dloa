/**
 * 下拉客户表格组件
 */
(function ($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmproject', {
		isDown: true,
		setValue: function (rowData) {
			if (rowData) {
				var t = this, p = t.options, el = t.el;
				p.rowData = rowData;
				if (p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData.idArr;
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData.text;
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				} else if (!p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData[p.valueCol];
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData[p.nameCol];
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				}
			}
		},
		options: {
			hiddenId: 'projectId',
			nameCol: 'projectCode',
			searchName: 'projectSearch',
			openPageOptions: {
				url: '?model=engineering_project_esmproject&action=pageSelect',
				width: '750'
			},
			gridOptions: {
				showcheckbox: false,
				title: "项目信息",
				isTitle: true,
				model: 'engineering_project_esmproject',
				action: 'jsonSelect',
				// 列信息
				colModel: [{
					display: '项目名称',
					name: 'projectName',
					width: 140
				}, {
					display: '项目编号',
					name: 'projectCode',
					width: 130
				}, {
					display: '所在区域',
					name: 'officeName',
					width: 70
				}, {
					display: '项目经理',
					name: 'managerName',
					width: 80
				}, {
					display: '部门ID',
					name: 'deptId',
					hide: true
				}, {
					display: '部门名称',
					name: 'deptName',
					width: 80,
					hide: true
				}, {
					display: '预计结束日期',
					name: 'planDateClose',
					hide: true,
					width: 80
				}, {
					display: '项目状态',
					name: 'statusName',
					width: 80
				}],
				// 快速搜索
				searchitems: [{
					display: '区域',
					name: 'officeName'
				}, {
					display: '项目编号',
					name: 'projectCodeSearch'
				}, {
					display: '项目名称',
					name: 'projectName'
				}, {
					display: '项目经理',
					name: 'managerName'
				}],
				// 默认搜索字段名
				sortname: "id",
				// 默认搜索顺序
				sortorder: "DESC"
			}
		}
	});
})(jQuery);