var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};
$(function() {

		//表头按钮数组
	var buttonsArr = [
//        {
//			name : 'view',
//			text : "高级查询",
//			icon : 'view',
//			action : function() {
//				showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
//			}
//        }
    ];
    //表头按钮数组
	var excelInArr = {
		name : 'exportIn',
		text : "导入人员信息",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_supplier_personnel&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	var excelOut = {
		name : 'exportOut',
		text : "导出人员信息",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_supplier_personnel&action=toExcellOut"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	};
//	$.ajax({
//		type : 'POST',
//		url : '?model=hr_personnel_personnel&action=getLimits',
//		data : {
//			'limitName' : '导入权限'
//		},
//		async : false,
//		success : function(data) {
//			if (data ==1) {
				buttonsArr.push(excelInArr);
				buttonsArr.push(excelOut);
//			}
//		}
//	});

	$("#personnelGrid").yxgrid({
		model : 'outsourcing_supplier_personnel',
		title : '供应商人员信息',
		isAddAction:true,
		isDelAction:false,
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
		},   {
			name : 'userAccount',
			display : 'OA账号',
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
			action : 'toListAdd',
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

		buttonsEx : buttonsArr,

									// 扩展右键菜单

		menusEx : [{
				text : '删除',
				icon : 'delete',
				action : function(row, rows, grid) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_supplier_personnel&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									$("#personnelGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			}],
		searchitems : [{
							display : "外包供应商",
							name : 'suppName'
						},{
							display : "姓名",
							name : 'userName'
						},{
							display : "联系电话",
							name : 'mobile'
						},{
							display : "邮箱",
							name : 'email'
						},{
							display : "毕业学校",
							name : 'highSchool'
						},{
							display : "专业",
							name : 'professionalName'
						},{
							display : "身份证号",
							name : 'identityCard'
						},{
							display : "技能类型",
							name : 'skillTypeName'
						},{
							display : "厂商经验列举",
							name : 'tradeList'
						},{
							display : "所获资质",
							name : 'certifyList'
						}]
	});
});