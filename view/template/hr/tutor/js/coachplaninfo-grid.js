var show_page = function(page) {
	$("#coachplaninfoGrid").yxgrid("reload");
};
$(function() {
			$("#coachplaninfoGrid").yxgrid({
						model : 'hr_tutor_coachplaninfo',
						title : 'Ա�������ƻ���ϸ��',
						isOpButton : false,
						bodyAlign:'center',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'coachplanId',
									display : '�����ƻ�id',
									sortable : true
								}, {
									name : 'containMonth',
									display : '�����·�',
									sortable : true
								}, {
									name : 'fosterGoal',
									display : '����Ŀ��',
									sortable : true
								}, {
									name : 'fosterMeasure',
									display : '����������ʩ',
									sortable : true
								}, {
									name : 'reachinfoStu',
									display : '��������Ա����',
									sortable : true
								}, {
									name : 'remarkStu',
									display : '����˵����Ա����',
									sortable : true
								}, {
									name : 'reachinfoTut',
									display : '����������ʦ��',
									sortable : true
								}, {
									name : 'remarkTut',
									display : '����˵������ʦ��',
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