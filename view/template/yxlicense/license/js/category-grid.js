var show_page = function(page) {
	$("#categoryGrid").yxgrid("reload");
};
$(function() {
	var id=$("#id").val();
	var name=$("#name").val();
	var isUse = $("#isUse").val();
	var buttonsArr = [];
	var btnIsUse = true;
	
	if(isUse == 1)
		var btnIsUse = false;
	$("#categoryGrid").yxgrid({
				model : 'yxlicense_license_category',
				title : 'license������Ϣ',
				param :{
					licenseId : id,
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
							name : 'categoryName',
							display : 'lincense��������',
							sortable : true,
							width : 200
						}, {
							name : 'appendDesc',
							display : '��չ����',
							sortable : true,
							width : 200
						}, {
							name : 'orderNum',
							display : '����',
							sortable : true
						}, {
							name : 'showType',
							display : '��ʾ����',
							sortable : true,
							process : function(v, row) {
								switch(v){
									case "1" : return "<span>�б���ʾ</span>";break;
									case "2" : return "<span>������ʾ</span>";break;
									case "3" : return "<span>����ʾ</span>";break;
									case "4" : return "<span>ֱ������</span>";break;
									case "5" : return "<span>��д���</span>";break;
								}
							}
						}, {
							name : 'lineFeed',
							display : '�������',
							sortable : true
						}, {
							name : 'isHideTitle',
							display : '�Ƿ����ر���',
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
					formHeight : 350,
					formWidth : 800
				},
				toAddConfig : {
					action : 'toAdd&id='+id,
					formHeight : 350,
					formWidth : 800
				},
				toEditConfig : {
					action : 'toEdit',
					formHeight : 350,
					formWidth : 800
				},
				searchitems : [{
					display : "lincense��������",
					name : 'categoryName'
				}]
			});
});