//ѡ���Ʒ����
$(function(){
     $("#parentName").yxcombotree({
     	hiddenId:'parentId',
 		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
					$("#parentCode").val(treeNode.code);
				}
			},
			url : "?model=engineering_baseinfo_resource&action=getChildren"
		}
	});

	// ��̬���Ԥ����Ŀ����
	$("#budgetName").yxcombogrid_budget({
		hiddenId : 'budgetId',
		width : 600,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			param  : {'status' : 0 ,'isLeaf' : 1},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#budgetCode").val(data.budgetCode);
				}
			}
		}
	});
//�����ֵ����
$(function(){
  	resourceNatureArr = getData('GCXMZYXZ');
	addDataToSelect(resourceNatureArr, 'resourceNature');
});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"resourceCode" : {
			required : true
		},
		"resourceName" : {
			required : true
		},
		"parentName" : {
			required : true
		}
	});

	/**
	 * ���Ψһ����֤
	 */
	var url = "?model=engineering_baseinfo_resource&action=checkRepeat";
	$("#resourceCode").ajaxCheck({
		url : url,
		alertText : "* �ñ���Ѵ���",
		alertTextOk : "* �ñ�ſ���"
	});
});
