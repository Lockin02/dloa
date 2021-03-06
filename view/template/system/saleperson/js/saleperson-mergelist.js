var show_page = function(page) {
	$("#mergeGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [  {
		text : "数据导入",
		icon : 'add',
		action : function(row) {
			showThickboxWin("?model=system_saleperson_saleperson&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	},{
		text : "切换视图",
		icon : 'view',
		action : function(row) {
           location='?model=system_saleperson_saleperson';
		}
	},{
		text : "新增",
		icon : 'add',
		action : function(row) {
			showModalWin("?model=system_saleperson_saleperson&action=toAdd"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")

		}
	}],
	$("#mergeGrid").yxgrid({
		model : 'system_saleperson_saleperson',
		action : 'mergeJson',
		title : '销售负责人管理',
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		// 扩展右键菜单
		menusEx : [{
			text : "查看",
			icon : 'view',
			action : function(row) {
				showModalWin("?model=system_saleperson_saleperson&action=toViewall"
				        + "&id="
				        + row.id
				        + "&ids="
				        + row.ids
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")

			}
		},{
			text : "修改",
			icon : 'edit',
			action : function(row) {
				showModalWin("?model=system_saleperson_saleperson&action=toEditAll"
				        + "&id="
				        + row.id
				        + "&ids="
				        + row.ids
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")

			}
		},{
			text : '删除',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("确定要删除 【"+row.personName+"】 的所以信息吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=system_saleperson_saleperson&action=ajaxdeletesPerson",
						data : {
							personId : row.personId
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#mergeGrid").yxgrid("reload");
							}else{
							   alert('删除失败！');
							}
						}
					});
				}
			}
		}],
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : 'ids',
			name : 'ids',
			sortable : true,
			hide : true
		}, {
			name : 'personName',
			display : '负责人名称',
			sortable : true
		}, {
			name : 'isDirector',
			display : '行业总监',
			sortable : true,
			process : function (v,row){
			   if(v == '0'){
			      return "否";
			   }else if(v == '1'){
			      return "是";
			   }
			},
			width : 50
		}, {
			name : 'personId',
			display : '负责人id',
			sortable : true,
			hide : true
		}, {
			name : 'deptName',
			display : '负责人部门',
			sortable : true
		}, {
			name : 'deptId',
			display : '负责人部门id',
			sortable : true,
			hide : true
		}, {
			name : 'country',
			display : '国家',
			sortable : true
		}, {
			name : 'province',
			display : '省份',
			sortable : true,
			width : 150
		}, {
			name : 'city',
			display : '城市',
			sortable : true,
			width : 180
		}, {
			name : 'businessBelongName',
			display : '归属公司',
			sortable : true
		}, {
			name : 'customerTypeName',
			display : '客户类型',
			sortable : true,
			width : 300
		}, {
            name : 'exeDeptName',
            display : '执行区域',
            sortable : true
        }],
        buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		// 审批状态数据过滤
//		comboEx : [{
//			text: "是否启用",
//			key: 'isUse',
//			value : '0',
//			data : [{
//				'text' : '启用',
//				'value' : '0'
//			}, {
//				'text' : '关闭',
//				'value' : '1'
//			}]
//		},{
//			text: "行业总监",
//			key: 'isDirector',
//			data : [{
//				'text' : '是',
//				'value' : '1'
//			}, {
//				'text' : '否',
//				'value' : '0'
//			}]
//		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '销售负责人',
			name : 'personNameSearch'
		},{
			display : '区域负责人',
			name : 'areaNameSearch'
		},{
			display : '客户类型',
			name : 'customerTypeSearch'
		},{
			display : '国家',
			name : 'countrySearch'
		},{
			display : '省份',
			name : 'provinceSearch'
		},{
			display : '城市',
			name : 'citySearch'
		}]
	});
});