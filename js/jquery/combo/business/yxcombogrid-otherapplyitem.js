/**
 * ���������������뵥��Ʒ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_otherapplyitem', {
			options : {
				hiddenId : 'materialId',
				nameCol : 'materialName',
				gridOptions : {
				showcheckbox : false,
				model : 'quality_apply_otherapplyitem',
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
				    					name : 'pattern',
				  					display : '����ͺ�',
				  					sortable : true
				              },{
				    					name : 'batchNum',
				  					display : '����',
				  					hide : true
				              },{
				    					name : 'cost',
				  					display : '�ݹ���',
				  					hide : true
				              },{
				    					name : 'storageNum',
				  					display : '��������',
				  					sortable : true
				              },{
				    					name : 'subCheckNum',
				  					display : '�ۼƼ�������',
				  					sortable : true
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