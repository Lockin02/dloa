var show_page = function(page) {
	$("#useStatusGrid").yxgrid("reload");
};
$(function() {
			$("#useStatusGrid").yxgrid({
				      model : 'asset_basic_useStatus',
               	title : 'ʹ��״̬',
				//����Ϣ
				colModel : [{
	         				display : 'id',
	         				name : 'id',
	         				sortable : true,
	         				hide : true
						},{
	                    	name : 'name',
	                  		display : 'ʹ��״̬',
	                  		sortable : true,
			                // ���⴦���ֶκ���
			                process : function(v, row) {
			                	if(v=='1'){
			                	return "ʹ����";
			                	}
			                	if(v=='2'){
			                	return "����";
			                	}
			                	if(v=='3'){
			                	return "ά����";
			                	}
			                	if(v=='4'){
			                	return "�ѳ���";
			                	}
			                	if(v=='5'){
			                	return "����";
			                	}
			                }
	                    },{
	                    	name : 'deprFlag',
	                  		display : '�Ƿ�����۾�',
	                  		sortable : true,
			                // ���⴦���ֶκ���
			                process : function(v, row) {
			                	if(v=='y'){
			                	return "��";
			                	}
			                	if(v=='n'){
			                	return "��";
			                	}
			                }
	                    },{
	                    	name : 'remark',
	                  		display : '��ע',
	                  		sortable : true,
	                  		width : 200
	                    }],
			toAddConfig : {
				formWidth : 700,
				/**
				 * ������Ĭ�ϸ߶�
				 */
				formHeight : 300
			},
			toViewConfig : {
				/**
				 * �鿴��Ĭ�Ͽ��
				 */
				formWidth : 700,
				/**
				 * �鿴��Ĭ�ϸ߶�
				 */
				formHeight : 300
			},
			toEditConfig : {
				/**
				 * �༭��Ĭ�Ͽ��
				 */
				formWidth : 700,
				/**
				 * �༭��Ĭ�ϸ߶�
				 */
				formHeight : 300
			}
 		});
 });