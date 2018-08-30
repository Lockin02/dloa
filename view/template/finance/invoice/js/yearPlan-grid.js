var show_page = function(page) {
	$("#yearPlanGrid").yxgrid("reload");
};
$(function() {
   $("#yearPlanGrid").yxgrid({
      model : 'finance_invoice_yearPlan',
      title : '����Ʊ��ȼƻ�',
      //����Ϣ
      colModel : [{
            display : 'id',
   			name : 'id',
   			sortable : true,
   			hide : true
         },{
				name : 'year',
				display : '���',
				sortable : true,
				width : 80
         },{
				name : 'salesOne',
				display : '���۵�һ����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'salesTwo',
				display : '���۵ڶ�����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'salesThree',
				display : '���۵�������',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'salesFour',
				display : '���۵��ļ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'salesAll',
				display : '�����ܼ�',
				sortable : true,
				width : 90,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceOne',
				display : '�����һ����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceTwo',
				display : '����ڶ�����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceThree',
				display : '�����������',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceFour',
				display : '������ļ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceAll',
				display : '�����ܼ�',
				sortable : true,
				width : 90,
				process : function(v){
					return moneyFormat2(v);
				}
         }],
         toAddConfig : {
			formHeight : 500,
			formWidth : 900
         },
         toEditConfig : {
			formHeight : 500,
			formWidth : 900
         },
         toViewConfig : {
			formHeight : 500,
			formWidth : 900
         }
   });
});