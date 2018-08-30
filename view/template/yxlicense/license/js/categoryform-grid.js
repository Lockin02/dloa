var show_page = function(page) {
	$("#categoryformGrid").yxgrid("reload");
};
$(function() {
	var id=$("#id").val();
	var buttonsArr = [];
	var btnIsUse = true;	
	$("#categoryformGrid").yxgrid({
				model : 'yxlicense_license_categoryform',
				title : '������Ϣ',
				param :{
					itemId : id,
					'dir' : 'ASC'
				},
				isAddAction : btnIsUse,
				isDelAction : btnIsUse,
				isEditAction : btnIsUse,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'formName',
							display : '����',
							sortable : true,
							width : 200
						}, {
							name : 'isHideTitle',
							display : '�Ƿ����ر�ͷ',
							sortable : true,
							process : function(v, row) {
								switch(v){
									case "1" : return "<span>��</span>";break;
									case "0" : return "<span>��</span>";break;
								}
							}
						}],
				
				toViewConfig : {
					action : 'toView',
					formHeight : 450,
					formWidth : 500
				},
				toAddConfig : {
					action : 'toAdd&id='+id,
					formHeight : 400,
					formWidth : 500
				},
				toEditConfig : {
					action : 'toEdit&id='+id,
					formHeight : 450,
					formWidth : 500
				},
				menusEx : [{
						text : '���ܱ�ע��Ϣ',
						icon : 'view',
						action : function(row) {
							showThickboxWin("?model=yxlicense_license_categorytips&action=toTips&id="
													+ row.id
													+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=650");
								}						
							}],				
				searchitems : [{
					display : "����",
					name : 'formName'
				}]
			});
});