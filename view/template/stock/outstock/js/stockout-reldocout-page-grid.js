var show_page = function(page) {
	$("#stockoutGrid").yxsubgrid("reload");
};

$(function() {
	var canPrintOutRecord = $("#canPrintOutRecord").val();
	var printBtn = (canPrintOutRecord == 1)? {
		name: 'print',
		text: "��",
		icon: 'search',
		action: function () {
			var responseText = $.ajax({
				url:'index1.php?model=stock_outstock_stockout&action=relDocOutJson&relDocType='+$("#relDocType").val()+'&docId='+$("#docId").val()+'&docType='+$("#docType").val(),
				type : "POST",
				async : false
			}).responseText;
			var responseData = eval("(" + responseText + ")");
			if(responseData.collection.length > 0){
				var docId = $("#docId").val();
				showThickboxWin("?model=stock_outstock_stockout&action=toPrintShipList&docId="+docId
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700");
			}else{
				alert("������س����¼!");
			}
		}
	} : {};
	var buttonArr = [
		printBtn
	];
			$("#stockoutGrid").yxsubgrid({
				model : 'stock_outstock_stockout',
				action : 'relDocOutJson&relDocType=' + $("#relDocType").val()
						+ '&docId=' + $("#docId").val() + '&docType='
						+ $("#docType").val(),
				title : '�����¼',
				isAddAction : false,
				isViewAction : false,
				isEditAction : false,
				isDelAction : false,
				showcheckbox : false,
				isRightMenu : false,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'isRed',
							display : '��������',
							sortable : true,
							width : 50,
							align : 'center',
							process : function(v, row) {
								if (row.isRed == '0') {
									return "<img src='images/icon/hblue.gif' />";
								} else {
									return "<img src='images/icon/hred.gif' />";
								}
							}
						}, {
							name : 'docCode',
							display : '���ݱ��',
							sortable : true
						}, {
							name : 'auditDate',
							display : '��������',
							sortable : true
						}, {
							name : 'docType',
							display : '��������',
							sortable : true,
							hide : true
						}, {
							name : 'relDocType',
							display : 'Դ������',
							sortable : true,
							datacode : "QTCKYDLX"
						}, {
							name : 'relDocId',
							display : 'Դ��id',
							sortable : true,
							hide : true
						}, {
							name : 'relDocName',
							display : 'Դ������',
							sortable : true,
							hide : true
						}, {
							name : 'relDocCode',
							display : 'Դ�����',
							sortable : true

						}, {
							name : 'contractId',
							display : '��ͬid',
							sortable : true,
							hide : true
						}, {
							name : 'contractName',
							display : '��ͬ/��������',
							sortable : true,
							hide : true
						}, {
							name : 'contractCode',
							display : '��ͬ/�������',
							sortable : true
						}, {
							name : 'stockId',
							display : '���ϲֿ�id',
							sortable : true,
							hide : true
						}, {
							name : 'stockCode',
							display : '���ϲֿ����',
							sortable : true,
							hide : true
						}, {
							name : 'stockName',
							display : '���ϲֿ�',
							sortable : true
						}, {
							name : 'customerName',
							display : '�ͻ�(��λ)����',
							sortable : true
						}, {
							name : 'customerId',
							display : '�ͻ�(��λ)id',
							sortable : true,
							hide : true
						}, {
							name : 'saleAddress',
							display : '������ַ',
							sortable : true,
							hide : true
						}, {
							name : 'linkmanId',
							display : '������ϵ��id',
							sortable : true,
							hide : true
						}, {
							name : 'linkmanName',
							display : '������ϵ��',
							sortable : true
						}, {
							name : 'linkmanTel',
							display : '������ϵ�˵绰',
							sortable : true,
							hide : true
						}, {
							name : 'pickingType',
							display : '��������',
							sortable : true,
							hide : true
						}, {
							name : 'deptName',
							display : '���ϲ�������',
							sortable : true,
							hide : true
						}, {
							name : 'deptCode',
							display : '���ϲ��ű���',
							sortable : true,
							hide : true
						}, {
							name : 'toUse',
							display : '������;',
							sortable : true,
							hide : true
						}, {
							name : 'salesmanCode',
							display : '����Ա���',
							sortable : true,
							hide : true
						}, {
							name : 'salesmanName',
							display : '����Ա',
							sortable : true
						}, {
							name : 'otherSubjects',
							display : '�Է���Ŀ',
							sortable : true,
							hide : true
						}, {
							name : 'docStatus',
							display : '����״̬',
							sortable : true,
							process : function(v, row) {
								if (v == "WSH") {
									return "δ���";
								} else {
									return "�����";
								}
							}
						}, {
							name : 'catchStatus',
							display : '����״̬',
							sortable : true,
							hide : true
						}, {
							name : 'remark',
							display : '��ע',
							sortable : true,
							hide : true
						}, {
							name : 'pickName',
							display : '����������',
							sortable : true,
							hide : true
						}, {
							name : 'pickCode',
							display : '�����˱���',
							sortable : true,
							hide : true

						}, {
							name : 'auditerCode',
							display : '����˱��',
							sortable : true,
							hide : true
						}, {
							name : 'auditerName',
							display : '���������',
							sortable : true,
							hide : true
						}, {
							name : 'custosCode',
							display : '�����˱��',
							sortable : true,
							hide : true
						}, {
							name : 'custosName',
							display : '����������',
							sortable : true,
							hide : true
						}, {
							name : 'chargeCode',
							display : '�����˱��',
							sortable : true,
							hide : true
						}, {
							name : 'chargeName',
							display : '����������',
							sortable : true,
							hide : true
						}, {
							name : 'acceptorCode',
							display : '�����˱��',
							sortable : true,
							hide : true
						}, {
							name : 'acceptorName',
							display : '����������',
							sortable : true,
							hide : true
						}, {
							name : 'accounterCode',
							display : '�����˱��',
							sortable : true,
							hide : true
						}, {
							name : 'accounterName',
							display : '����������',
							sortable : true,
							hide : true
						}, {
							name : 'createTime',
							display : '����ʱ��',
							sortable : true,
							hide : true
						}, {
							name : 'createName',
							display : '����������',
							sortable : true,
							hide : true
						}, {
							name : 'createId',
							display : '������',
							sortable : true,
							hide : true,
							hide : true
						}, {
							name : 'updateName',
							display : '�޸�������',
							sortable : true,
							hide : true
						}, {
							name : 'updateTime',
							display : '�޸�ʱ��',
							sortable : true,
							hide : true
						}, {
							name : 'updateId',
							display : '�޸���',
							sortable : true,
							hide : true
						}],
				// ���ӱ������
				subGridOptions : {
					url : '?model=stock_outstock_stockoutitem&action=pageJson',
					param : [{
								paramId : 'mainId',
								colId : 'id'
							}],
					colModel : [{
								name : 'productCode',
								display : '���ϱ��'
							}, {
								name : 'productName',
								width : 200,
								display : '��������'
							}, {
								name : 'actOutNum',
								display : "ʵ������"
							}, {
								name : 'serialnoName',
								display : "���к�",
								width : '500'
							}]
				},

				searchitems : [{
					display : '���ݱ��',
					name : 'docCode'
						// }, {
						// display : '�ֿ�����',
						// name : 'inStockName'
					}],
				buttonsEx: buttonArr,
				sortorder : "DESC"
			});
		});