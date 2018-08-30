/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stampconfig', {
		options : {
			hiddenId : 'stampType',
			nameCol : 'stampType',
            valueCol : 'stampType',
			searchName : 'stampNameSearch',
			width : 650,
			gridOptions : {
				showcheckbox : false,
				model : 'system_stamp_stampconfig',
				action : 'jsonSelect',
				param : { 'status' : 1 },
				// ����Ϣ
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					},{
                        name : 'businessBelongId',
                        display : '��˾ID',
                        sortable : true,
                        hide : true
                    },{
                        name : 'businessBelongName',
                        display : '��˾',
						width : 150,
                        sortable : true
                    },{
                        name : 'typeId',
                        display : 'ӡ�����ID',
                        sortable : true,
                        hide : true
                    },{
                        name : 'typeName',
                        display : 'ӡ�����',
                        sortable : true,
						hide : true
                    },{
						name : 'stampType',
						display : 'ӡ������',
						sortable : true,
						width : 150
					},{
						name : 'legalPersonUsername',
						display : '��˾�����û���',
						sortable : true,
						hide : true
					},{
						name : 'legalPersonName',
						display : '��˾��������',
						sortable : true,
						width : 220,
						hide : true
					},
					{
						name : 'principalName',
						display : 'ӡ�¹���Ա',
						sortable : true,
						width : 200
					}, {
						name : 'principalId',
						display : 'ӡ�¹���Աid',
						sortable : true,
						hide : true
					},{
						name : 'remark',
						display : '��ע',
						sortable : true,
						hide : true,
						width : 250
					}
				],
				// ��������
				searchitems : [{
						display : '��˾',
						name : 'businessBelongNameSer'
					},{
						display : '������',
						name : 'stampNameSearch'
					}],
				//// Ĭ�������ֶ���
				//sortname : "id",
				//// Ĭ������˳��
				//sortorder : "DESC"
					}
				}
			});
})(jQuery);