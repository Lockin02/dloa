var show_page = function(page) {
    $("#usednameGrid").yxgrid("reload");
};
$(function() {
    $("#usednameGrid").yxgrid({
        model : 'supplierManage_formal_usedname&suppId='+$("#suppId").val(),
               	title : '供应商曾用名',
                isAddAction : false,
                isDelAction : false,
                isEditAction : false,
                isViewAction : false,
                isToolBar : false,
                isOpButton : false,
                showcheckbox: false,
                isTitle: false,
        //列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'usedName',
                  					display : '旧供应商名称',
                                    width:200,
                  					sortable : true
                              },{
                    					name : 'newName',
                  					display : '新供应商名称',
                                    width:200,
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '修改人',
                                    width:80,
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '修改时间',
                                    width:200,
                  					sortable : true
                              }],
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "曾用名",
					name : 'usedName'
				}]
 		});
 });