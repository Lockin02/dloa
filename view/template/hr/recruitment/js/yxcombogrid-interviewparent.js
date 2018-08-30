/**
 * 下拉增员申请表格组件
 */
 (function($) {
 	$.woo.yxcombogrid.subclass('woo.yxcombogrid_interviewparent', {
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
 			nameCol : 'formCode',
 			searchName : 'formCode',
 			openPageOptions : {
 				url : '?model=hr_recruitment_apply&action=selectApply',
 				width : '1000'
 			},
			closeCheck : false,// 关闭状态,不可选择
			closeAndStockCheck:false,//关闭且校验库存
			gridOptions : {
				showcheckbox : true,
				model : 'hr_recruitment_apply',
				//列信息
				colModel : [{
					name : 'formCode',
					display : '单据编号',
					width:130,
					sortable : true,
					process : function(v, row) {
						return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_recruitment_apply&action=toView&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
					}
				},{
					name : 'deptName',
					display : '需求部门',
					width:100,
					sortable : true
				},{
					name : 'positionName',
					display : '需求职位'
				},{
					name : 'addType',
					display : '增员类型'
				},{
					name : 'workPlace',
					display : '工作地点'
				},{
					name : 'resumeToName',
					display : '接口人'
				},{
					name : 'positionNote',
					display : '职位备注',
					width : 150,
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
				}],

				// 快速搜索
				searchitems : [{
					display : '单据编号',
					name : 'formCode'
				}],

				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);