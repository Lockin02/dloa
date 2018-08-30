/*******************************************************************************
 * function zTreeClick(event, treeId, treeNode) { if (treeNode.isParent ==
 * false) { var pnVal = $("#productNames").attr("innerHTML"); var piVal =
 * $("#productIds").val(); if (treeNode.checkedNew == true) { if
 * (!changeStr(piVal, treeNode.id)) { $("#productNames").attr("innerHTML",
 * function() { return this.innerHTML + treeNode.name + ","; });
 * $("#productIds").attr("value", function() { return this.value + treeNode.id +
 * ","; }); } } else { if (changeStr(piVal, treeNode.id)) {
 * $("#productNames").attr("innerHTML", function() { return
 * this.innerHTML.replace(treeNode.name + ",", ""); });
 * $("#productIds").attr("value", function() { return this.value.replace("," +
 * treeNode.id + ",", ","); }); } } } }
 *
 * function changeStr(str, val) { var arrStr = str.split(","); for (a in arrStr) {
 * if (arrStr[a] == val) { return true; } } return false; }
 *
 * function subForm() { var productNames = $("#productNames").attr("innerHTML");
 * var productIds = $("#productIds").val(); if (productIds == null || productIds ==
 * ",") { alert('请选择供应产品'); return false; } else { return true; } }
 ******************************************************************************/

$(document).ready(function() {
//		$("#productNames").yxcombotree({
//			hiddenId : 'productIds',//隐藏控件id
//			treeOptions : {
//				checkable : true,//多选
//				url : "?model=stock_productinfo_producttype&action=getChildren",//获取数据url
//				param : ["name", "id"]//传递树参数属性
//			}
//		});
		$("#productNames").yxcombogrid_product({
				hiddenId : 'productIds',
				nameCol : 'productName',
				gridOptions : {
					isTitle:true,
					showcheckbox : true,
					event : {
						'row_dblclick' : function(e, row, data) {
							//alert(data.Prov);
						},
						'row_click' : function(e,row,data) {
							// alert(123)
						},
						'row_rclick' : function() {
							// alert(222)
						}
					}
				}
			});
});



//返回联系人添加硕，并可编辑联系人
function backToEditCont(){
	var parentId = document.getElementById('parentId').value;
	location = "?model=supplierManage_temporary_stcontact&action=tolinkmanlist&parentId="+parentId;

}