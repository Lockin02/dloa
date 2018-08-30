var show_page = function(page) {
	$("#serialnoGrid").yxgrid("reload");
};
$(function() {
	//Ĭ�������������༭��ɾ����ť
	var addShow = false;
	var editShow = false;
	var deleteShow = false;
	//��ȡ����Ȩ��
	$.ajax({
		type : 'POST',
		url : '?model=stock_serialno_serialno&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				addShow = true;//��ʾ������ť
			}
		}
	});
	//��ȡ�༭Ȩ��
	$.ajax({
		type : 'POST',
		url : '?model=stock_serialno_serialno&action=getLimits',
		data : {
			'limitName' : '�༭Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				editShow = true;//��ʾ�༭��ť
			}
		}
	});
	//��ȡɾ��Ȩ��
	$.ajax({
		type : 'POST',
		url : '?model=stock_serialno_serialno&action=getLimits',
		data : {
			'limitName' : 'ɾ��Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				deleteShow = true;//��ʾɾ����ť
			}
		}
	});
	$("#serialnoGrid").yxgrid({
		model : 'stock_serialno_serialno',
		title : '�������к�̨��',
		isAddAction : addShow,
		isViewAction : false,
		isEditAction : editShow,
		isDelAction : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'sequence',
					display : '�������к�',
					sortable : true,
					width : 150
				}, {
					name : 'seqStatus',
					display : '���к�״̬',
					sortable : true,
					process : function(v, row) {
						if (v == "0") {
							return "�����";
						} else if (v == "1") {
							return "�ѳ���";
						}
					}
				}, {
					name : 'productId',
					display : '����id',
					sortable : true,
					hide : true
				}, {
					name : 'productCode',
					display : '���ϱ��',
					sortable : true
				}, {
					name : 'productName',
					display : '��������',
					width : 200,
					sortable : true

				}, {
					name : 'pattern',
					display : '����ͺ�',
					sortable : true
				}, {
					name : 'stockId',
					display : '�ֿ�id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '�ֿ�����',
					sortable : true
				}, {
					name : 'stockCode',
					display : '�ֿ����',
					sortable : true,
					hide : true
				}, {
					name : 'batchNo',
					display : '���κ�',
					sortable : true

				}, {
					name : 'shelfLife',
					display : '������',
					sortable : true,
					hide : true
				}, {
					name : 'prodDate',
					display : '�������ɹ�������',
					sortable : true,
					hide : true
				}, {
					name : 'validDate',
					display : '��Ч����',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true
				}, {
					name : 'inDocId',
					display : '��ⵥid',
					sortable : true,
					hide : true
				}, {
					name : 'inDocCode',
					display : '��ⵥ���',
					sortable : true
				}, {
					name : 'inDocItemId',
					display : '����嵥id',
					sortable : true,
					hide : true
				}, {
					name : 'outDocCode',
					display : '���ⵥ���',
					sortable : true
				}, {
					name : 'outDocId',
					display : '���ⵥid',
					sortable : true,
					hide : true
				}, {
					name : 'outDocItemId',
					display : '���ⵥ�嵥id',
					sortable : true,
					hide : true
				}, {
					name : 'relDocType',
					display : 'Դ������',
					sortable : true,
					process : function(v) {
						if (v == 'oa_sale_order' || v == 'oa_contract_contract') {
							return "���ۺ�ͬ";
						} else if (v == 'oa_borrow_borrow') {
							return "������";
						} else if (v == 'oa_present_present') {
							return "����";
						} else if ((v == 'rdproject')) {
							return "�з��ɹ�";
						} else if ((v == 'oa_contract_exchangeapply')) {
							return "��������";
						}else if (v == 'stock'){
							return "����";
						}else if (v == 'oa_service_accessorder'){
							return "���������";
						}else if (v == 'independent'){
							return "��������";
						}
					}
				}, {
					name : 'relDocId',
					display : 'Դ��id',
					sortable : true,
					hide : true
				}, {
					name : 'relDocCode',
					display : 'Դ�����',
					sortable : true,
					process : function(v, row) {
					   if(row.relDocType=='oa_contract_contract' || row.relDocType=='oa_borrow_borrow' || row.relDocType=='oa_present_present')
						return '<a href="javascript:void(0)" ' +
								'onclick="relDocView(\''+row.relDocType+'\',\''+v+'\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
					   else
					    return v;
					}
				}, {
					name : 'relDocName',
					display : 'Դ������',
					sortable : true,
					hide : true
				}],
		toEditConfig : {
			formWidth : 700,
			formHeight : 300
		},
		toAddConfig : {
			action : 'toAddBatch',
			formWidth : 800,
			formHeight : 500
		},
		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function() {
				if (deleteShow) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("ȷ��Ҫɾ��?")) {
					$.ajax({
						type : "POST",
						url : "?model=stock_serialno_serialno&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('ɾ���ɹ���');
							} else {
								alert('ɾ��ʧ��!');
							}
						}
					});
				}
			}
		}],
		searchitems : [{
					name : 'likesequence',
					display : '���к�'
				}, {
					name : 'relDocCode',
					display : 'Դ�����'
				}, {
					display : '���κ�',
					name : 'batchNo'
				}, {
					name : 'productName',
					display : '��������'

				}, {
					name : 'productCode',
					display : '���ϱ��'

				}, {
					name : 'likeinDocCode',
					display : '��ⵥ���'

				}, {
					name : 'likeoutDocCode',
					display : '���ⵥ���'

				}]
	});
});

function relDocView(relDocType,relDocCode){
    //Դ�� �鿴�˵�����
	var viewArr = new Array();
	viewArr['oa_contract_contract'] = "?model=contract_contract_contract&action=toViewTab&id=";
	viewArr['oa_borrow_borrow_c'] = "index1.php?model=projectmanagent_borrow_borrow&action=toViewTab&id=";
	viewArr['oa_borrow_borrow_p'] = "index1.php?model=projectmanagent_borrow_borrow&action=proViewTab&id=";
	viewArr['oa_present_present'] = "index1.php?model=projectmanagent_present_present&action=viewTab&id=";
//   if(viewArr[relDocType]){
	 var relDocTypeRel = relDocType;//����Ȩ��
	 if(viewArr[relDocType] || relDocType== "oa_borrow_borrow"){
      //ajax ���ӱ�Ż�ȡԴ��id
		var relDocId = $.ajax({
			    type : 'POST',
			    url : "?model=stock_serialno_serialno&action=ajaxRelDocIdByCode",
			    data:{
			        relDocType : relDocType,
			        relDocCode : relDocCode
			    },
			    async: false,
			    success : function(data){
				}
			}).responseText;
		//����Ա��/�ͻ���������
		if(relDocType=='oa_borrow_borrow'){
		   var borrowType = $.ajax({
				    type : 'POST',
				    url : "?model=stock_serialno_serialno&action=ajaxGetLimitsById",
				    data:{
				        relDocId : relDocId
				    },
				    async: false,
				    success : function(data){
					}
				}).responseText;
		   relDocType = relDocType + "_" + borrowType;
		}
	    if(typeof(relDocId) != 'undefined' && relDocId != ""){
	    	//�ж�Ȩ��
	    	var limit = $.ajax({
				    type : 'POST',
				    url : "?model=stock_serialno_serialno&action=ajaxlimit",
				    data:{
				        relDocId : relDocId,
				        relDocType : relDocTypeRel
				    },
				    async: false,
				    success : function(data){
					}
				}).responseText;
			if(limit == '1'){
			   showModalWin(viewArr[relDocType]
				+ relDocId
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}else{
               alert("Ȩ�޲��㣬�������Ա���롾"+limit+"������Ȩ�ޡ�");
			}
	    }else{
	       alert("δ�ҵ�Դ��������Ϣ��");
	    }
   }else{
       alert("Դ�����ʹ�������ϵ����Ա")
   }
}





