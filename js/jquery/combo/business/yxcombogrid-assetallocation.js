/**
 * �ʲ����ø����
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_allocation', {
		options : {
			hiddenId : 'id',

			nameCol : 'billNo',

			gridOptions : {
				model : 'asset_daily_allocation',

					// ��
				colModel : [ {
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
								   display:'��������',
								   name : 'moveDate',
					               sortable : true


								},{
								   display:'��������Id',
								   name : 'outDeptId',
					               sortable : true,
									hide : true

								},
					            {
					                display : '��������',
					                name : 'outDeptName',
					                sortable : true
					            },
					            {
					                display : '���벿��id',
					                name : 'inDeptId',
					                sortable : true,
									hide : true
					            },{
								   display:'���벿��',
								   name : 'inDeptName',
					               sortable : true

								},
					            {
					                display : '����������',
					                name : 'proposer',
					                sortable : true
					            },
					            {
					                display : '����ȷ����',
					                name : 'recipient',
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
									name : 'isSign',
									display : '�Ƿ�ǩ��',
									sortable : true
								},{
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

				sortorder : "ASC",
				title : '�ʲ�������Ϣ'
			}
		}
	});
})(jQuery);