var show_page = function(page) {
	$("#schemeDetailGrid").yxgrid("reload");
};
$(function() {
			$("#schemeDetailGrid").yxgrid({
						model : 'hr_tutor_schemeDetail',
						title : '��ʦ����ģ����ϸ',
						isOpButton : false,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'parentId',
									display : '����ID',
									sortable : true
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
									name : 'createId',
									display : '������Id',
									sortable : true
								}, {
									name : 'createName',
									display : '����������',
									sortable : true
								}, {
									name : 'createTime',
									display : '����ʱ��',
									sortable : true
								}, {
									name : 'updateId',
									display : '�޸���Id',
									sortable : true
								}, {
									name : 'updateName',
									display : '�޸�������',
									sortable : true
								}, {
									name : 'updateTime',
									display : '�޸�ʱ��',
									sortable : true
								}, {
									name : 'sysCompanyName',
									display : 'ϵͳ��˾����',
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