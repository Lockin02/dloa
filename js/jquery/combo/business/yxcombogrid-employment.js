/**
 * 下拉研发项目表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_employment', {
		isDown : false,
		setValue : function(rowData) {
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
		options : {
			hiddenId : 'id',
			nameCol : 'employmentCode',
			openPageOptions : {
				url : '?model=hr_recruitment_employment&action=selectEmployment',
				width : '750'
			},
			closeCheck : false,// 关闭状态,不可选择
			gridOptions : {
				showcheckbox : false,
				model : 'hr_recruitment_employment',
				//列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'employmentCode',
					display : '单据编号',
					sortable : true,
					width : 150,
					process : function(v, row) {
							return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
										+ row.id
										+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
										+ "<font color = '#4169E1'>"
										+ v
										+ "</font>"
										+ '</a>';
						}
				}, {
					name : 'name',
					display : '姓名',
					sortable : true
				}, {
					name : 'sex',
					display : '性别',
					sortable : true
				}, {
					name : 'nation',
					display : '民族',
					sortable : true
				}, {
					name : 'age',
					display : '年龄',
					sortable : true
				}, {
					name : 'highEducationName',
					display : '学历',
					sortable : true
				}, {
					name : 'highSchool',
					display : '毕业学校',
					sortable : true
				}, {
					name : 'professionalName',
					display : '专业',
					sortable : true
				}, {
					name : 'telephone',
					display : '固定电话',
					sortable : true
				}, {
					name : 'mobile',
					display : '移动电话',
					sortable : true
				}, {
					name : 'personEmail',
					display : '个人电子邮箱',
					sortable : true
				}, {
					name : 'QQ',
					display : 'QQ',
					sortable : true
				}],
				// 快速搜索
				searchitems : [{
						display : '单据编号',
						name : 'employmentCode'
					},{
						display : '姓名',
						name : 'name'
					}
				],
				// 默认搜索字段名
				sortname : "name",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);