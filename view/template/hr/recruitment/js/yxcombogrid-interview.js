/**
 * 下拉评估项目表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_interview', {
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
					searchName : 'employmentCode',
					openPageOptions : {
										url : '?model=hr_recruitment_employment&action=selectEmp',
										width : '500'
									},
					closeCheck : false,// 关闭状态,不可选择
					closeAndStockCheck:false,//关闭且校验库存
					gridOptions : {
						showcheckbox : true,
						model : 'hr_recruitment_employment',
						//列信息
						colModel : [{
		            					name : 'name',
		              					display : '姓名',
		              					width:60,
		              					sortable : true
		                          },{
		            					name : 'employmentCode',
		              					display : '单据编号',
		              					width:120,
		              					sortable : true,
										process : function(v, row) {
											return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
														+ row.id
														+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
														+ "<font color = '#4169E1'>"
														+ v
														+ "</font>"
														+ '</a>';
										}
		                          },{
		            					name : 'sex',
		              					display : '性别',
		              					width:50,
		              					sortable : true
		                          },{
		                				name : 'mobile',
		              					display : '电话'
		                          }],
						// 快速搜索
						searchitems : [{
									display : '姓名',
									name : 'name'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);