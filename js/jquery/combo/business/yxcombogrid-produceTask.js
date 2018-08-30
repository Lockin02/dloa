/**
 * �����������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_produceTask', {
		options : {
			hiddenId : 'id',
			nameCol : 'documentCode',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_task_producetask',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docType',
					display : '������',
					process : function(v, row) {
							if(v=="product"){
								return "��������";
							}else{
								return "ί��ӹ�����";
							}
					},
					sortable : true
				}, {
					name : 'documentCode',
					display : '���ݺ�',
					sortable : true
				}, {
					name : 'taskReqCode',
					display : '����������',
					sortable : true
				}, {
					name : 'pProduceNum',
					display : 'Ԥ����������',
					sortable : true
				},{
					name : 'hasCheckNum',
					display : '���ʼ�����',
					sortable : true,
					hide : true
				}, {
					name : 'taskReqId',
					display : '��������id',
					sortable : true,
					hide : true
				}],
				// ��������
				searchitems : [{
					display : '���ݺ�',
					name : 'documentCode'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);