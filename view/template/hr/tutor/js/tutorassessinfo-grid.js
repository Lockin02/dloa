var show_page = function(page) {
	$("#tutorassessinfoGrid").yxgrid("reload");
};
$(function() {
			$("#tutorassessinfoGrid").yxgrid({
						model : 'hr_tutor_tutorassessinfo',
						title : '��ʦ���˱�----������ϸ',
						isOpButton : false,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'appraisal',
									display : '������Ŀ',
									sortable : true
								}, {
									name : 'coefficient',
									display : 'Ȩ��ϵ��',
									sortable : true
								}, {
									name : 'scaleA',
									display : '�����߶ȣ����㣩',
									sortable : true
								}, {
									name : 'scaleB',
									display : '�����߶ȣ����ã�',
									sortable : true
								}, {
									name : 'scaleC',
									display : '�����߶ȣ�һ�㣩',
									sortable : true
								}, {
									name : 'scaleD',
									display : '�����߶ȣ��ϲ',
									sortable : true
								}, {
									name : 'scaleE',
									display : '�����߶ȣ����',
									sortable : true
								}, {
									name : 'selfgraded',
									display : '��ʦ������',
									sortable : true
								}, {
									name : 'superiorgraded',
									display : 'ֱ���ϼ�����',
									sortable : true
								}, {
									name : 'staffgraded',
									display : 'Ա������',
									sortable : true
								}, {
									name : 'assistantgraded',
									display : '������������',
									sortable : true
								}, {
									name : 'hrgraded',
									display : 'HR����',
									sortable : true
								}],
						// ���ӱ������
						subGridOptions : {
							url : '?model=hr_tutor_NULL&action=pageItemJson',
							param : [{
										paramId : 'mainId',
										colId : 'id'
									}],
							colModel : [{
										name : 'XXX',
										display : '�ӱ��ֶ�'
									}]
						},

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "�����ֶ�",
									name : 'XXX'
								}]
					});
		});