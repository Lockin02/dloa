$(document).ready(function() {
	validate({
				"batchNum" : {
					required : true
				}
			});
		
$("#matterListInfo").yxeditgrid({
		objName : 'application[matter]',
		url : 'index1.php?model=stockup_application_applicationMatter&action=getJsonEdit',
        param : {
            appId : $("#id").val()
        },
		dir : 'ASC',
		realDel : true,
		colModel : [{
					display : '��Ʒ����',
					name : 'productName',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_stockupProducts({
							nameCol : 'productName',
							hiddenId : 'productListInfo_cmp_productId' + rowNum,
							isDown : true,
							height : 250,
							gridOptions : {
								action : 'jsonSelect',
								isTitle : true,
								event : {
									row_dblclick : function(e, row, data) {
										$("#matterListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"productId").val(data.id);
										$("#matterListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"productName").val(data.productName);
										$("#matterListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"productCode").val(data.productCode);
									}
								}
							}
						});
					}
				},{
					display : '��ƷID',
					name : 'productId',
					type:'hidden'
				},{
					display : '��ƷCODE',
					name : 'productCode',
					type:'hidden'
				},{
					display : '��������',
					name : 'stockupNum',
					type : 'txt',
					width : 50,
					validation : {
						required : true
					},
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
								if (isNaN(this.value)||this.value==0) {
									this.value = 1;
								}else{
								}
							}
						}
					}
				},{
					display : 'Ԥ�Ʒ������',
					name : 'expectAmount',
					type : 'money',
					width : 80,
					validation : {
						required : true
					},
					tclass : 'txtshort',
					process : function(v) {
						return moneyFormat2(v);
					}
				},{
					display : '�ۼ�������',
					name : 'stockNum',
					type : 'txt',
					width : 80,
					validation : {
						required : true
					}
				},{
					display : '�������',
					name : 'needsNum',
					type : 'txt',
					width : 80
				}]
	});


})
function showLink(url,type,id){
	var skey = "";
	$.ajax({
		type: "POST",
		url: "?model="+type+"&action=md5RowAjax",
		data: { "id" : id },
		async: false,
		success: function(data){
		   skey = data;
		}
	});
	showModalWin(url+ skey ,1);
}		

// �ύ����
function setAudit(thisVal) {
		$("#auditType").val(thisVal);
}