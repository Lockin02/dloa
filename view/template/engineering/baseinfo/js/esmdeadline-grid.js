var show_page = function() {
	$("#grid").yxgrid("reload");
};

$(function() {
	//��񲿷ִ���
	$("#grid").yxgrid({
		model : 'engineering_baseinfo_esmdeadline',
		title : '��־���ֹ���� - {��ֹ�·�} �� {��ֹ��} �� Ϊ�ϸ�����־��ֹ����� ',
		isDelAction : false,
        isAddAction : false,
        isViewAction : false,
		//����Ϣ
		colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'month',
            display : '��ֹ�·�',
            sortable : true,
            width : 80
        }, {
            name : 'day',
            display : '��ֹ��',
            sortable : true,
            width : 80
        }, {
            name : 'saveDayForSer',
            display : '���񱣻���',
            sortable : true,
            width : 80
        }, {
            name : 'saveDayForPro',
            display : '��Ʒ������',
            sortable : true,
            width : 80
        }, {
            name : 'useRange',
            display : 'Ӧ�÷�Χ',
            sortable : true,
            width : 150
        }, {
            name : 'useRangeId',
            display : 'Ӧ�÷�ΧID',
            sortable : true,
            width : 150,
            hide : true
        }, {
            name : 'remark',
            display : '��ע',
            sortable : true,
            width : 300
        }],
        toEditConfig: {
            action: 'toEdit'
        },
		sortorder : "ASC",
		sortname : "month"
	});
});