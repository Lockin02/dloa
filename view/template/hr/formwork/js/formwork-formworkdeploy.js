var show_page = function(page) {
	$("#formworkGrid").yxgrid("reload");
};
$(function() {
		//��ͷ��ť����
	buttonsArr = [{
			name : 'Add',
			text : "ȷ��",
			icon : 'add',
			action : function(rowData, rows, rowIds, g) {
					if (rows) {		
						//��������ڴ��ڸú���������÷����������
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
						alert('����ѡ���¼');
					}
			}
		}];
	$("#formworkGrid").yxgrid({
		model : 'hr_formwork_formwork',
		param : {"isUse" : "0"},
		title : '����ģ������',
	    isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : true,
		isAddAction : false,

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formworkName',
			display : 'ģ������',
			sortable : true,
			width : 200
		}, {
			name : 'isUse',
			display : '�Ƿ�����',
			sortable : true,
			process : function(v,row){
			   if(v == '0'){
			      return "����";
			   }else if(v == '1'){
			      return "ͣ��";
			   }
			}
		},{
			name : 'formworkContent',
			display : 'ģ������',
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
			display : "ģ������",
			name : 'formworkName'
		}]
	});
});