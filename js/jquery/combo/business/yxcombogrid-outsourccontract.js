/**
 * �����ɹ���ͬ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsourccontract', {
				options : {
					hiddenId : 'id',
					nameCol : 'outContractCode',
					gridOptions : {
						model : 'contract_outsourcing_outsourcing',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '������ͬ���',
									name : 'orderCode',
									width:100
													},{
						            name : 'orderName',
						            display : '��ͬ����',
						            sortable : true,
						            width : 130
						        },{
						            name : 'outContractCode',
						            display : '�����ͬ��',
						            sortable : true,
						            width : 130
						        },{
						            name : 'signCompanyName',
						            display : 'ǩԼ��˾',
						            sortable : true,
						            width : 130
						        }],
						// ��������
						searchitems : [{
									display : '�����ͬ��',
									name : 'outContractCode'
								}],
						// Ĭ�������ֶ���
						sortname : "outContractCode",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);