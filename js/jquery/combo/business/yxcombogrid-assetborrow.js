/**
 * �ʲ����ø����
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_borrow', {
		options : {
			hiddenId : 'id',

			nameCol : 'billNo',

			gridOptions : {
				model : 'asset_daily_borrow',

					// ��
				colModel : [{
					                display : 'id',
					                name : 'id',
					                sortable : true,
					                hide : true
					            },
					            {
					                display : '�����',
					                name : 'billNo',
					                sortable : true
					            },{
								   display:'���ÿͻ�id',
								   name : 'borrowCustomeId',
					               sortable : true,
					                hide:true

								},{
								   display:'���ÿͻ�',
								   name : 'borrowCustome',
					               sortable : true

								},
					            {
					                display : '���ò���id',
					                name : 'deptId',
					                sortable : true,
					                hide:true
					            },
					            {
					                display : '���ò���',
					                name : 'deptName',
					                sortable : true
					            },
					            {
					                display : '����������id',
					                name : 'chargeManId',
					                sortable : true,
					                hide:true
					            },{
								   display:'����������',
								   name : 'chargeMan',
					               sortable : true

								},
					            {
					                display : '��������',
					                name : 'borrowDate',
					                sortable : true
					            },
					            {
					                display : 'Ԥ�ƹ黹ʱ��',
					                name : 'predictDate',
					                sortable : true
					            }, {
					                display : '������id',
					                name : 'reposeManId',
					                sortable : true,
					                hide:true
					            },{
								    display:'������',
									name : 'reposeMan',
					                sortable : true

								},
					            {
					                display : '��ע',
					                name : 'remark',
					                sortable : true,
									hide : true
					            }, {
									name : 'ExaStatus',
									display : '����״̬',
									sortable : true
								}, {
									name : 'ExaDT',
									display : '����ʱ��',
									sortable : true,
									hide : true
								}, {
									name : 'createName',
									display : '������',
									sortable : true,
									hide : true
								}, {
									name : 'createId',
									display : '������id',
									sortable : true,
									hide : true
								}, {
									name : 'createTime',
									display : '��������',
									sortable : true,
									hide : true
								}, {
									name : 'updateName',
									display : '¼����',
									sortable : true,
					                hide:true
								}, {
									name : 'updateId',
									display : '�޸���id',
									sortable : true,
									hide : true
								}, {
									name : 'updateTime',
									display : '�޸�����',
									sortable : true,
									hide : true
								}],

				/**
				 * ��������
				 */
				searchitems : [{
					display : '�����',
					name : 'billNo'
				}],
				sortorder : "ASC",
				title : '�ʲ�������Ϣ'
			}
		}
	});
})(jQuery);