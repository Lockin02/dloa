//���年ѡ����
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
		title : '�豸����',
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
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'budgetTypeName',
			display : '��������',
			sortable : true,
			width : 100
		}, {
			name : 'equCode',
			display : '���ϱ��',
			sortable : true,
			width : 100
		}, {
			name : 'equName',
			display : '��������',
			sortable : true,
			width : 100
		}, {
			name : 'pattern',
			display : '����ͺ�',
			sortable : true,
			width : 100
		}, {
			name : 'brand',
			display : 'Ʒ��',
			sortable : true,
			width : 60
		}, {
			name : 'quotedPrice',
			display : '����',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'useEndDate',
			display : '������Ч��',
			sortable : true
		}, {
			name : 'unitName',
			display : '��λ',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 200
		}, {
			name : 'latestPrice',
			display : '���²ɹ��۸�',
			sortable : true,
			width : 80,
			process : function(v){
			   return "XXX";
			}
		}, {
			name : 'useStatus',
			display : '�Ƿ�����',
			sortable : true,
			process:function(v){
			   if(v == '0' || v == ''){
			      return "�ر�";
			   }else if(v == '1'){
			      return "����";
			   }
			}
		}],
		buttonsEx : [{
			name : 'Add',
			text : "ȷ��ѡ��",
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
					alert('����ѡ���¼');
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
			display : "��������",
			name : 'budgetTypeName'
		}, {
			display : "�豸����",
			name : 'equName'
		}]
	});
});