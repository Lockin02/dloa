/**
 * �ʲ����ø����
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_charge', {
		options : {
			hiddenId : 'id',

			nameCol : 'billNo',

			gridOptions : {
				model : 'asset_daily_charge',

					// ��
				colModel : [{
                display : 'id',
                name : 'id',
                sortable : true,
                hide : true
            },
            {
                display : '���õ����',
                name : 'billNo',
                sortable : true
            },
            {
                display : '��������',
                name : 'chargeDate',
                sortable : true
            },
            {
                display : '���ò���id',
                name : 'deptId',
                sortable : true,
                hide : true
            },
            {
                display : '���ò�������',
                name : 'deptName',
                sortable : true
            },
            {
                display : '������Id',
                name : 'chargeManId',
                sortable : true,
                hide : true
            },
            {
                display : '������',
                name : 'chargeMan',
                sortable : true
            },{
                display : '����״̬',
                name : 'ExaStatus',
                sortable : true
            },{
                display : 'ǩ��״̬',
                name : 'isSign',
                sortable : true
            },
//            {
//                display : '����ʱ��',
//                name : 'ExaDT',
//                sortable : true
//            },
            {
                display : '��ע',
                name : 'remark',
                sortable : true
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