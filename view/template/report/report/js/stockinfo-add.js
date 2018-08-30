//$(function() {
//	objTypeArr = getData('ZCWLLX');
//	addDataToCheckbox(objTypeArr, 'netWorkCode' , 6 , 1 ,'netWork');
//});
function checkChooseNewWork(){
	var thisTempName = [];
	var arr = document.getElementsByName("newWorkStr");
      for (var i=0; i<arr.length; i++)
        if(arr[i].checked){
             thisTempName.push(arr[i].value);
			 var tempStrName = thisTempName.toString();
        }
        if(tempStrName == undefined){
        	$("#netWork").val("");
        }
       $("#netWork").val(tempStrName);
}
function checkChoosesoftWare(){
	var thisTempName = [];
	var arr = document.getElementsByName("softWareStr");
      for (var i=0; i<arr.length; i++)
        if(arr[i].checked){
             thisTempName.push(arr[i].value);
			 var tempStrName = thisTempName.toString();
        }
        if(tempStrName == undefined){
        	$("#software").val("");
        }
       $("#software").val(tempStrName);
}

$(document).ready(function() {
	renderingProduct();
	$("#budgetTypeName").yxcombotree({
		hiddenId : 'parentId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
				},
				"node_change" : function(event, treeId, treeNode) {

				}
			},
			url : "?model=equipment_budget_budgetType&action=getTreeData"
		}
	});
	// ����������Ϣ ѡ����������
//	$("#budgetTypeName").yxcombotree({
//		hiddenId : 'budgetTypeId',
//		treeOptions : {
//			event : {
//				"node_click" : function(event, treeId, treeNode) {
//				},
//				"node_change" : function(event, treeId, treeNode) {
//
//				}
//			},
//			url : "?model=equipment_budget_budgetType&action=getTreeData"
//		}
//	});
	validate({
		"equName" : {
			required : true
		},
		"budgetTypeName" : {
			required : true
		}
	});
})
//ѡ��������Ⱦ
function renderingProduct() {
	//ѡ������
	$("#equCode").yxcombogrid_product({// �����ϱ��
		hiddenId : 'equId',
		nameCol : 'equCode',
		event : {
			'clear' : function() {
				clearInfo()
			}
		},
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					assignment(data)
				}
			}
		}
	});
	$("#equName").yxcombogrid_product({// ����������
		hiddenId : 'equId',
		nameCol : 'equName',
		event : {
			'clear' : function() {
				clearInfo()
			}
		},
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					assignment(data)
				}
			}
		}
	});
	$("#linkequNames").yxcombogrid_product("remove");
	$("#linkequNames").yxcombogrid_product({// ����������
		hiddenId : 'linkequIds',
		nameCol : 'productName',
		event : {
			'clear' : function() {
				clearInfo()
			}
		},
		gridOptions : {
			showcheckbox : true,
			event : {
//				'row_dblclick' : function(e, row, data) {
//					assignment(data)
//				}
			}
		}
	});
}
//ȡ��������Ⱦ
function removeProduct() {
	var flag = $("#equCode").yxcombogrid_product("getIsRender");
	if (flag) {
		$("#equCode").yxcombogrid_product("remove");
		$("#equCode").attr('readonly', false);
		$("#equName").yxcombogrid_product("remove");
		$("#equName").attr('readonly', false);
//		$("#linkequNames").yxcombogrid_product("remove");
//		$("#linkequNames").attr('readonly', false);
		clearInfo()

		$("#stockNum").attr('class',"txt");
        $("#stockNum").attr('readOnly',false);
        $("#oldNum").attr('class',"txt");
        $("#oldNum").attr('readOnly',false);
        $("#demandNum").attr('class',"txt");
        $("#demandNum").attr('readOnly',false);
	} else {
		renderingProduct();
		clearInfo()

		$("#stockNum").attr('class',"readOnlyTxtNormal");
        $("#stockNum").attr('readOnly',true);
        $("#oldNum").attr('class',"readOnlyTxtNormal");
        $("#oldNum").attr('readOnly',true);
        $("#demandNum").attr('class',"readOnlyTxtNormal");
        $("#demandNum").attr('readOnly',true);
	}
}
//��������ѡ����
function removeLinkEqu(){
	var flag = $("#linkequNames").yxcombogrid_product("getIsRender");
	if(flag){
		$("#tagName").html("(����)");
		$("#linkequType").val("1");
		$("#linkequNames").val("");
		$("#linkequIds").val("");
		$("#linkequNames").yxcombogrid_product("remove");
		$("#linkequNames").yxcombotree("remove");
	    // ����������Ϣ ѡ����������
		$("#linkequNames").yxcombotree({
			hiddenId : 'linkequIds',
			treeOptions : {
				event : {
					"node_click" : function(event, treeId, treeNode) {
						// alert(treeId)
						$("#arrivalPeriod").val(treeNode.submitDay);
					},
					"node_change" : function(event, treeId, treeNode) {
						// alert(treeId)
					}
				},
				url : "?model=stock_productinfo_producttype&action=getTreeDataByParentId"
			}
		});
	}else{
		$("#tagName").html("(����)");
		$("#linkequType").val("0");
		$("#linkequNames").val("");
		$("#linkequIds").val("");
		$("#linkequNames").yxcombogrid_product("remove");
		$("#linkequNames").yxcombogrid_product({// ����������
			hiddenId : 'linkequIds',
			nameCol : 'productName',
			event : {
				'clear' : function() {
					clearInfo()
				}
			},
			gridOptions : {
				showcheckbox : true,
				event : {
	//				'row_dblclick' : function(e, row, data) {
	//					assignment(data)
	//				}
				}
			}
		});
	}

}

//�������
function clearInfo() {
	$("#equName").val("");
	$("#equCode").val("");
//	$("#pattern").val("");
//	$("#unitName").val("");
//	$("#brand").val("");
//	$("#remark").val("");
//	$("#quotedPrice").val("");
//	$("#quotedPrice_v").val("");
//	$("#useEndDate").val("");
}
//���ݸ�ֵ
function assignment(data) {
	$("#equCode").val(data.productCode);
	$("#equName").val(data.productName);
	$("#pattern").val(data.pattern);
	$("#unitName").val(data.unitName);
	$("#brand").val(data.brand);
	$("#remark").val(data.remark);
	$("#purTime").val(data.purchPeriod);

}
