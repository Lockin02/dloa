var show_page = function(page) {
	$("#countryGrid").yxgrid("reload");
};
$(function() {
			$("#countryGrid").yxgrid({
				      model : 'system_procity_country',
               	title : '������Ϣ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'countryCode',
                  					display : '���ұ���',
                  					sortable : true
                              },{
                    					name : 'countryName',
                  					display : '��������',
                  					sortable : true,
                  					width:200
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
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
				action : 'toEdit'
			},

				/**
				 * ��������
				 */
				searchitems : [{
							display : '���ұ���',
							name : 'countryCode'
						},{
							display : '��������',
							name : 'countryName'
						}]
 		});
 });