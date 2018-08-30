var show_page = function(page) {
	$("#formworkGrid").yxgrid("reload");
};
$(function() {
		//表头按钮数组
	buttonsArr = [{
			name : 'add',
			text : "新增",
			icon : 'add',
			action : function(row) {
				showModalWin("?model=hr_formwork_formwork&action=toAdd"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		}];
	$("#formworkGrid").yxgrid({
		model : 'hr_formwork_formwork',
		title : '人资模板设置',
	    isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// 扩展右键菜单

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
					showModalWin('?model=hr_formwork_formwork&action=toView&id='
							+ row.id + "&skey=" + row['skey_']);
			}
		},{
			text : '编辑模板',
			icon : 'edit',
			action : function(row) {
					showModalWin('?model=hr_formwork_formwork&action=toEdit&id='
							+ row.id + "&skey=" + row['skey_']);
			}
		}, {
			text : '删除',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_formwork_formwork&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#formworkGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		}],
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formworkName',
			display : '模板名称',
			sortable : true,
			process : function(v, row) {
					return '<a href="javascript:void(0)" title="点击查看简历" onclick="javascript:showModalWin(\'?model=hr_formwork_formwork&action=toView&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
				}
		}, {
			name : 'isUse',
			display : '是否启用',
			sortable : true,
			process : function(v,row){
			   if(v == '0'){
			      return "启用";
			   }else if(v == '1'){
			      return "停用";
			   }
			}
		}],
        buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "搜索字段",
			name : 'XXX'
		}]
	});
});