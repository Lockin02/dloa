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
 * ",") { alert('��ѡ��Ӧ��Ʒ'); return false; } else { return true; } }
 ******************************************************************************/

$(document).ready(function() {
//		$("#productNames").yxcombotree({
//			hiddenId : 'productIds',//���ؿؼ�id
//			treeOptions : {
//				checkable : true,//��ѡ
//				url : "?model=stock_productinfo_producttype&action=getChildren",//��ȡ����url
//				param : ["name", "id"]//��������������
//			}
//		});

		$("#productNames").yxcombogrid_product({
				hiddenId : 'productIds',
				nameCol : 'productName',
				height:400,
				gridOptions : {
					isTitle:true,
					showcheckbox : true,
					// ��������
					searchitems : [{
								display : '���ϱ���',
								name : 'productCode'
							}, {
								display : '��������',
								name : 'productName'
							}],
					event : {
						'row_rclick' : function() {
							// alert(222)
						}
					}
				}
			});
});
