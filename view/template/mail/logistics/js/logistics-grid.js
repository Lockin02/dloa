var show_page = function(page) {
	$("#logisticsGrid").yxgrid("reload");
};
$(function() {
	$("#logisticsGrid").yxgrid({
        model : 'mail_logistics_logistics',
        title : '������˾������Ϣ',
        //����Ϣ
        colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'companyName',
				display : '��˾����',
				sortable : true,
				width : 130
			}, {
				name : 'phone',
				display : '��ϵ�绰',
				sortable : true
			}, {
				name : 'rangeDelivery',
				display : '���˷�Χ',
				sortable : true
			}, {
				name : 'speed',
				display : '�����ٶ�',
				sortable : true
			}, {
				name : 'security',
				display : '���˰�ȫ��',
				sortable : true
			}, {
				name : 'address',
				display : '��ַ',
				sortable : true,
				width : 150
			}, {
				name : 'introduction',
				display : '��˾���',
				sortable : true,
				width : 150
			}, {
				name : 'isDefault',
				display : '��Ʊ�ʼ�Ĭ�Ϲ�˾',
				sortable : true,
				process : function(v){
					if(v == '1'){
						return '��';
					}else{
						return '��';
					}
				}
			}
		]
   });
});