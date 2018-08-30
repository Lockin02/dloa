var show_page = function(page) {
	$("#formworkGrid").yxgrid("reload");
};
$(function() {
		//表头按钮数组
	buttonsArr = [{
			name : 'Add',
			text : "确认",
			icon : 'add',
			action : function(rowData, rows, rowIds, g) {
					if (rows) {		
						//如果父窗口存在该函数，则调用方法填充内容
						if(typeof(self.parent.fillTemp)!='undefined'){
							self.parent.fillTemp(rowData.formworkName,rowData.formworkContent);
							self.parent.tb_remove();
						}
						else{
						    var type = $("#type").val();
						    $.ajax({
							url:'?model=hr_formwork_formwork&action=formworkdeployEdit',
							type:'POST',
							data:{ids:rowIds,type:type},
							success:function(data){
								parent.location.reload();
								self.parent.tb_remove();								
							}
						    });
						}

					} else {
						alert('请先选择记录');
					}
			}
		}];
	$("#formworkGrid").yxgrid({
		model : 'hr_formwork_formwork',
		param : {"isUse" : "0"},
		title : '人资模板设置',
	    isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : true,
		isAddAction : false,

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
			width : 200
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
		},{
			name : 'formworkContent',
			display : '模版内容',
			hide : true
		}],
        buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "模板名称",
			name : 'formworkName'
		}]
	});
});