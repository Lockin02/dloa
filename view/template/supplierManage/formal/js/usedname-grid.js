var show_page = function(page) {
    $("#usednameGrid").yxgrid("reload");
};
$(function() {
    $("#usednameGrid").yxgrid({
        model : 'supplierManage_formal_usedname&suppId='+$("#suppId").val(),
               	title : '��Ӧ��������',
                isAddAction : false,
                isDelAction : false,
                isEditAction : false,
                isViewAction : false,
                isToolBar : false,
                isOpButton : false,
                showcheckbox: false,
                isTitle: false,
        //����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'usedName',
                  					display : '�ɹ�Ӧ������',
                                    width:200,
                  					sortable : true
                              },{
                    					name : 'newName',
                  					display : '�¹�Ӧ������',
                                    width:200,
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '�޸���',
                                    width:80,
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '�޸�ʱ��',
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
					display : "������",
					name : 'usedName'
				}]
 		});
 });