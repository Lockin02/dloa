var show_page = function(page) {
	$("#yearPlanGrid").yxgrid("reload");
};
$(function() {
   $("#yearPlanGrid").yxgrid({
      model : 'finance_invoice_yearPlan',
      title : '财务开票额度计划',
      //列信息
      colModel : [{
            display : 'id',
   			name : 'id',
   			sortable : true,
   			hide : true
         },{
				name : 'year',
				display : '年度',
				sortable : true,
				width : 80
         },{
				name : 'salesOne',
				display : '销售第一季度',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'salesTwo',
				display : '销售第二季度',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'salesThree',
				display : '销售第三季度',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'salesFour',
				display : '销售第四季度',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'salesAll',
				display : '销售总计',
				sortable : true,
				width : 90,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceOne',
				display : '服务第一季度',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceTwo',
				display : '服务第二季度',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceThree',
				display : '服务第三季度',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceFour',
				display : '服务第四季度',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
         },{
				name : 'serviceAll',
				display : '服务总计',
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