var show_page = function(page) {
	$("#logisticsGrid").yxgrid("reload");
};
$(function() {
	$("#logisticsGrid").yxgrid({
        model : 'mail_logistics_logistics',
        title : '物流公司基本信息',
        //列信息
        colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'companyName',
				display : '公司名称',
				sortable : true,
				width : 130
			}, {
				name : 'phone',
				display : '联系电话',
				sortable : true
			}, {
				name : 'rangeDelivery',
				display : '承运范围',
				sortable : true
			}, {
				name : 'speed',
				display : '货运速度',
				sortable : true
			}, {
				name : 'security',
				display : '货运安全性',
				sortable : true
			}, {
				name : 'address',
				display : '地址',
				sortable : true,
				width : 150
			}, {
				name : 'introduction',
				display : '公司简介',
				sortable : true,
				width : 150
			}, {
				name : 'isDefault',
				display : '发票邮寄默认公司',
				sortable : true,
				process : function(v){
					if(v == '1'){
						return '是';
					}else{
						return '否';
					}
				}
			}
		]
   });
});