var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};
$(function() {
	var suppId=$("#suppId").val();
	$("#personnelGrid").yxgrid({
		model : 'outsourcing_supplier_personnel',
		title : '供应商人员信息',
		isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		param:{'suppId':suppId},
		bodyAlign:'center',
		showcheckbox:false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'suppCode',
			display : '供应商编号',
			width:70,
			sortable : true,
			hide : true
		}, {
			name : 'suppName',
			display : '外包供应商',
			width:150,
			sortable : true
		},   {
			name : 'userName',
			display : '姓名',
			width:70,
			sortable : true
		}, {
			name : 'age',
			display : '年龄',
			width:50,
			sortable : true
		}, {
			name : 'mobile',
			display : '联系电话',
			width:100,
			sortable : true
		}, {
			name : 'email',
			display : '邮箱',
			width:120,
			sortable : true
		}, {
			name : 'highEducationName',
			display : '学历',
			width:70,
			sortable : true
		}, {
			name : 'highSchool',
			display : '毕业学校',
			width:120,
			sortable : true
		}, {
			name : 'professionalName',
			display : '专业',
			width:90,
			sortable : true
		}, {
			name : 'identityCard',
			display : '身份证号',
			width:150,
			sortable : true
		}, {
			name : 'workBeginDate',
			display : '开始工作时间',
			width:90,
			sortable : true
		}, {
			name : 'workYears',
			display : '从事网优工作年限',
			width:80,
			sortable : true
		},  {
			name : 'tradeList',
			display : '厂商经验列举',
			width:150,
			sortable : true
		}, {
			name : 'certifyList',
			display : '所获资质',
			width:150,
			sortable : true
		}, {
			name : 'remark',
			display : '工作经验介绍',
			width:200,
			align:'left',
			sortable : true
		}],
		lockCol:['userName','suppName'],//锁定的列名
		toAddConfig : {
			action : 'toAdd&suppId='+$("#suppId").val(),
			formWidth : 800,
			formHeight : 500
		},

		toEditConfig : {
			action : 'toEdit',
			formWidth : 800,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "搜索字段",
			name : 'XXX'
		}]
	});
});