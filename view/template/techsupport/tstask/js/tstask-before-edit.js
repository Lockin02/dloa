var objTypeArr = [];// ҵ����������
var objTypeArr2 = [];// ҵ����������
var objTypeArr3 = [];// ҵ����������
var tempArr = [];//��ʱ�������
var tempArrName = [];
var deliveryProductsArr = [];
var deliveryProductsNameArr = [];
var deliveryNeedNetArr = [];
var deliveryNeedNetNameArr = [];
var deliveryFunctionsArr = [];
var deliveryFunctionsNameArr = [];

$(function() {
	objTypeArr = getData('JLCP');
	objTypeArr2 = getData('JLWL');
	objTypeArr3 = getData('JLGN');

	addDataToCheckbox(objTypeArr, 'deliveryProducts' , 4 , 1 ,'deliveryProductsName');
	addDataToCheckbox(objTypeArr2, 'deliveryNeedNet' , 4 ,1 ,'deliveryNeedNetName');
	addDataToCheckbox(objTypeArr3, 'deliveryFunctions' , 5 ,1 ,'deliveryFunctionsName');


	if($("#customerId").val() != ""){
		toLink($("#customerId").val());
	}
});


$(function() {
	$("#objName").yxcombogrid_chance({
		hiddenId : 'objId',
		gridOptions : {
			param : {'status' : '0,5'},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#objCode").val(data.chanceCode);
					$("#trainAddress").val(data.address);
					$("#customerId").val(data.customerId);
					$("#customerName").val(data.customerName);
					toLink(data.customerId);
				}
			}
		}
	});

	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#cusLinkPhone").val(data.Tell);
					objAddress = $("#trainAddress");
					if(objAddress == ""){
						objAddress.val(data.Address);
					}
					toLink(data.id);
				}
			}
		}
	});

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});
});

function toLink(customerId){
	if(customerId == undefined ){
		customerId = null;
	}
	$("#cusLinkman").yxcombogrid_linkman('remove');
	$("#cusLinkman").yxcombogrid_linkman({
		hiddenId : 'cusLinkmanId',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			param : {'customerId' : customerId},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#cusLinkPhone").val(data.phone);
				}
			}
		}
	});
}

//�ı��״̬
function changeStatus(thisStatus){
	$("#status").val(thisStatus);
}