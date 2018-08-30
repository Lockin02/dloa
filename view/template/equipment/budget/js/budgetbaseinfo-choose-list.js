//定义勾选数组
	var checkedArr = [];

var show_page = function(page) {
	$("#budgetbaseinfoGrid").yxgrid("reload");
};
function baoremove(dx){
   for(i=0;i<checkedArr.length;i++){
       if(checkedArr[i] == dx){
         checkedArr.splice(i,1);
       }
   }
}

$(function() {
	var flag = $("#flag").val();
	if (flag == "all") {
		var flagTF = true;
	} else {
		var flagTF = false;
	}
	$("#budgetTypeTree").yxtree({
		url : '?model=equipment_budget_budgetType&action=getTreeData',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var goodsbaseinfoGrid = $("#budgetbaseinfoGrid").data('yxgrid');
				goodsbaseinfoGrid.options.param['budgetTypeId'] = treeNode.id;
				$("#parentName").val(treeNode.name);
				$("#parentId").val(treeNode.id);
				goodsbaseinfoGrid.reload();
			}
		}
	});

	$("#budgetbaseinfoGrid").yxgrid({
		model : 'equipment_budget_budgetbaseinfo',
		param : {
//			goodsTypeId : -1,
			useStatus : '1'
		},
		showcheckbox : true,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		title : '设备管理',
		event : {
		  'row_check' : function(p1,p2,p3,row){
		  	   var isChecked = $("#chk_" + row.id).is(":checked");
		  	  if(isChecked){
		  	     checkedArr.push(row.id);
		  	  }else{
                 baoremove(row.id);
		  	  }
		   },
		  "afterloaddata" : function() {
				if(checkedArr != ''){
			      for(i=0;i<checkedArr.length;i++){
			      	var checkedId = checkedArr[i];
				    $("input[id='chk_" + checkedId + "']").trigger('click', [true]);
				}
			   }
			}
		},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'budgetTypeName',
			display : '所属分类',
			sortable : true,
			width : 100
		}, {
			name : 'equCode',
			display : '物料编号',
			sortable : true,
			width : 100
		}, {
			name : 'equName',
			display : '物料名称',
			sortable : true,
			width : 100
		}, {
			name : 'pattern',
			display : '规格型号',
			sortable : true,
			width : 100
		}, {
			name : 'brand',
			display : '品牌',
			sortable : true,
			width : 60
		}, {
			name : 'quotedPrice',
			display : '报价',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'useEndDate',
			display : '报价有效期',
			sortable : true
		}, {
			name : 'unitName',
			display : '单位',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 200
		}, {
			name : 'latestPrice',
			display : '最新采购价格',
			sortable : true,
			width : 80,
			process : function(v){
			   return "XXX";
			}
		}, {
			name : 'useStatus',
			display : '是否启用',
			sortable : true,
			process:function(v){
			   if(v == '0' || v == ''){
			      return "关闭";
			   }else if(v == '1'){
			      return "启用";
			   }
			}
		}],
		buttonsEx : [{
			name : 'Add',
			text : "确认选择",
			icon : 'add',
			action : function(rowData, rows, rowIds, g) {
				if (checkedArr) {
					checkedStr = checkedArr.toString();
					parent.window.returnValue = checkedStr;
					// $.showDump(outJson);
					if(window.opener){
					  window.opener.returnValue = checkedStr;
					}window.returnValue = checkedStr;
					parent.window.close();
				} else {
					alert('请先选择记录');
				}
			}
		}],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=equipment_budget_budgetbaseinfo&action=toAdd&parentName="
						+ $("#parentName").val()
						+ "&parentId="
						+ $("#parentId").val()

						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
			}
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 400,
			formWidth : 750
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 400,
			formWidth : 750
		},
		searchitems : [{
			display : "所属分类",
			name : 'budgetTypeName'
		}, {
			display : "设备名称",
			name : 'equName'
		}]
	});
});