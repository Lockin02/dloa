/**
 * ����ʹ�����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_usematters', {
		options : {
			hiddenId : 'id',
			nameCol : 'matterName',
			searchName : 'matterNameSearch',
			width : 550,
			gridOptions : {
				showcheckbox : false,
				model : 'system_stamp_stampmatter',
				action : 'jsonSelect',
				param : { 'status' : 1 },
				// ����Ϣ
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						name : 'matterName',
						display : '��������',
						sortable : true,
						width : 180
					},{
						name : 'stamp_cId',
						display : '�������Id',
						sortable : true,
						hide : true
					},{
						name : 'stamp_cName',
						display : '�������',
						sortable : true,
						hide : true
					},{
						name : 'directions',
						display : '�ر�����',
						sortable : true,
						width : 180
					}, {
						name : 'needAudit',
						display : '�Ƿ���Ҫ����',
						sortable : true,
						width : 80,
						process : function(v){
							if(v == 1){
								return '��';
							}
							else{
								return '��';
							}
						}
					}
				],
				// ��������
				searchitems : [{
						display : '��������',
						name : 'matterNameSearch'
					}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "DESC"
					}
				}
			});
})(jQuery);