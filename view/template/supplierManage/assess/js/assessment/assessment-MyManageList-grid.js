// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assessGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".assessGrid").yxgrid({
		model : 'supplierManage_assess_assessment',
		action : 'saaPJMyManage',
		title:'�Ҹ������������',
		isViewAction : false,
		//isEditAction : false,
//		isToolBar : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '״̬Key',
					name : 'status',
					sortable : true,
					hide : true
				}, {
					display : '������������',
					name : 'assesName',
					sortable : true,
					width : 200
				}, {
					display : '������',
					name : 'createName',
					sortable : true,
					width : 200
				}, {
					display : '��������',
					name : 'assesTypeName',
					sortname : 'c.assesType',
					sortable : true,
					width : 150
				}, {
					display : '����Ԥ�ƿ�ʼ����',
					name : 'beginDate',
					sortable : true,
					width : 150
				}, {
					display : '����Ԥ�ƽ�������',
					name : 'endDate',
					sortable : true,
					width : 150
				}, {
					display : '��������ʱ��',
					name : 'createTime',
					sortable : true,
					width : 170
				}, {
					display : '״̬',
					name : 'statusC',
					sortname : 'c.status',
					sortable : true,
					width : 100
				}],
		// ��չ��ť
//		buttonsEx : [{
//					name : 'saaStart',
//					text : "����",
//					icon : 'edit',
//					action : function(row,rows,grid) {
//						if(row && isStruts(rows, ["1"] )  ){
//							if( confirm("ȷ����������������"+ row.assesName +"����") ){
//								parent.location="?model=supplierManage_assess_assessment&action=saaStart&assId="+row.id;
//							}
//						}else{
//							alert("��ѡ��һ�����ݲ���ѡ�е�����״ֻ̬���Ǳ���״̬");
//						}
//					}
//				}, {
//					separator : true
//				},{
//					name : 'close',
//					text : "�������",
//					icon : 'edit',
//					action : function(row,rows,grid) {
//						//$.showDump(row);
//						if(row && isStruts(rows, ["2"] ) ){
//							//if( confirm("ȷ����ɴ�����������"+ row.assesName +"����") ){
//								parent.location="?model=supplierManage_assess_assessment&action=assToClose&assId="+row.id;
//							//}
//						}else{
//							alert("��ѡ��һ������,����ѡ�е�����״ֻ̬����ִ���У�");
//						}
//					}
//				}],
		// ��չ�Ҽ��˵�
		menusEx : [{
					text : '�鿴',
					icon : 'view',
					action : function(row,rows,grid) {
						if(row){
						showOpenWin("?model=supplierManage_assess_assessment&action=saaViewTab&assId="+row.id+"&skey="+row['skey_'] );
						}else{
						   alert("��ѡ��һ������");
						}
					}

				},{
					name : 'saaStart',
					text : "����",
					icon : 'edit',
					action : function(row,rows,grid) {
						if(row && isStruts(rows, ["1"] )  ){
							if( confirm("ȷ����������������"+ row.assesName +"����") ){
								parent.location="?model=supplierManage_assess_assessment&action=saaStart&assId="+row.id;
							}
						}else{
							alert("��ѡ��һ�����ݲ���ѡ�е�����״ֻ̬���Ǳ���״̬");
						}
					}
				}, {
					name : 'close',
					text : "�������",
					icon : 'edit',
					action : function(row,rows,grid) {
						//$.showDump(row);
						if(row && isStruts(rows, ["2"] ) ){
							//if( confirm("ȷ����ɴ�����������"+ row.assesName +"����") ){
								parent.location="?model=supplierManage_assess_assessment&action=assToClose&assId="+row.id;
							//}
						}else{
							alert("��ѡ��һ������,����ѡ�е�����״ֻ̬����ִ���У�");
						}
					}
				}],
		// ��������
		searchitems : [{
					display : '������������',
					name : 'assesName',
					isdefault : true
				}, {
					display : '������',
					name : 'createName'
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '��������',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳��
		sortorder : "ASC",
		// �����չ��Ϣ
		toAddConfig : {
			text : '�������',
			toAddFn : function(p,g) {
					parent.location="?model=supplierManage_assess_assessment&action=saaToAdd";
				}
		},
		toViewConfig : {
				text : '�鿴����',
				toViewFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if( rowObj ){
						showOpenWin("?model=supplierManage_assess_assessment&action=saaViewTab&assId="+rowObj.data('data').id );
					}else{
						alert("������ѡ��һ������");
					}
				}
		},
		toEditConfig : {
				text : '�༭',
				toEditFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if( rowObj && rowObj.data('data').status=="1" ){
						parent.location="?model=supplierManage_assess_assessment&action=saaToEdit&assId="+rowObj.data('data').id;
					}else{
						alert("ֻ��״̬Ϊ��������ݿ��Ա༭������ʧ��");
					}
				}
		},
		toDelConfig : {
				text : 'ɾ��',
				toDelFn : function(p, g) {
					var rowIds = g.getCheckedRowIds();
					var rows = g.getCheckedRows();
					if (rowIds[0]) {
						if( isStruts(rows, ["1"] ) ){
							if (window.confirm("ȷ��Ҫɾ��?")) {
								$.ajax({
											type : "POST",
											url : "?model=" + p.model + "&action=ajaxdeletes",
											data : {
												id : g.getCheckedRowIds()
														.toString()
												// ת������,������ʽ
											},
											success : function(msg) {
												if (msg == 1) {
													g.reload();
													alert('ɾ���ɹ���');
												}
											}
										});
							}
						}else{
							alert("ֻ��״̬Ϊ��������ݿ���ɾ��������ʧ��");
						}
					} else {
						alert('��ѡ��һ�м�¼��');
					}
				}
			}
	});

	/**
	 * �ж�״̬
	 */
	function isStruts(rows,arr){
		for( row in rows ){
			if( rows[row]['status'] && !arr.in_array(rows[row]['status']) ){
				return false;
			}
		}
		return true;
	}
});