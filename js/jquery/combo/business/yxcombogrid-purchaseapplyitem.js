/**
 * �����ɹ��������뵥��Ʒ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_purchaseapplyitem', {
			options : {
				hiddenId : 'materialId',
				nameCol : 'materialName',
				gridOptions : {
				showcheckbox : false,
				model : 'quality_apply_purchaseapplyitem',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : '���뵥id',
                  					hide : true
                              },{
                    					name : 'materialCode',
                  					display : '���ϱ���',
                  					sortable : true
                              },{
                    					name : 'materialName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'materialId',
                  					display : '����id',
                  					hide : true
                              },{
                    					name : 'businessType',
                  					display : '��������',
                  					hide : true
                              },{
                    					name : 'businessCode',
                  					display : '������',
                  					hide : true
                              },{
                    					name : 'businessId',
                  					display : '����id',
                  					hide : true
                              },{
                    					name : 'batchNum',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'cost',
                  					display : '�ݹ���',
                  					sortable : true
                              },{
                    					name : 'storageNum',
                  					display : '��������',
                  					sortable : true,
                  					width:50
                              },{
                    					name : 'subCheckNum',
                  					display : '�ۼƼ�������',
                  					hide : true
                              }],
				// ��������
				searchitems : [{
							display : '��Ʒ����',
							name : 'materialName'
						}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);