var show_page = function() {
	$("#qualityapplyGrid").yxgrid("reload");
};
$(function() {
	var detailStatusArr=$("#detailStatusArr").val();
	var relDocType = $("#relDocType").val();
	// ------- �˵���ť ------- //
	var confirmBtn = {
		text : '����ȷ��',
		icon : 'add',
		action: function(row,rows) {
			if(row){
				var newIdArr = [];
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].detailStatus !== '4'){
						alert('���� ��'+ rows[i].productName +'�� ����״̬��Ϊ ��δ���������ܽ��д˲���');
						return false;
					}
					newIdArr.push(rows[i].id);
				}
				if('undefined'==typeof(newIdArr)){
					alert('����δ֪������ˢ�¸ô���');
					show_page();
					return false;
				}

				if(confirm("ȷ��Ҫ����"+newIdArr.length+"������ȷ�ϣ�")){
					idStr = newIdArr.toString();
					$.ajax({
						url:'?model=produce_quality_qualityapplyitem&action=ajaxReceive&ids=' + idStr,
						type:'get',
						dataType:'json',
						success:function(msg){
							if(msg==1){
								show_page();
								alert('�����ɹ�');
							}else
								alert('����ʧ��');
						},
						error:function(){
							alert('����������ʧ��');
						}
					});
				}
			}else{
				alert('��ѡ��һ������');
			}
		}
	};// ȷ�Ͻ���
	var submitQualityBtn = {
		text : '�ʼ�',
		icon : 'add',
		showMenuFn : function(row) {
			if(row.status != '3'){
				return true;
			}
			return false;
		},
		action: function(row,rows) {
			if(row){
				var tempObjType = '';
				var newIdArr = [];
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].detailStatus !== '4'){
						alert('���� ��'+ rows[i].productName +'�� ����״̬��Ϊ ��δ���������ܽ��д˲���');
						return false;
					}

					if(rows[i].receiveStatus !== '1'){
						alert('���ȶ����� ��'+ rows[i].productName +'�����н���ȷ�Ϻ��ٽ����ʼ����');
						return false;
					}

					if(tempObjType!="" && rows[i].objType != tempObjType){
						alert('��ͬԴ�����͵��ʼ�������ϸ��������һ���ʼ�����');
						return false;
					}

					newIdArr.push(rows[i].id);
				}
				if('undefined'==typeof(newIdArr)){
					alert('����δ֪������ˢ�¸ô���');
					show_page();
					return false;
				}
				if(''==newIdArr){
					alert('��ѡ��һ������');
					show_page();
					return false;
				}
				idStr = newIdArr.toString();

				var chkPass = true;// ��Ӽ����� PMS2386
				if(row.relDocType == 'ZJSQDLBF'){// ���ϱ�����Ҫ���������
					chkPass = false;
					var chkResult = chkIsAllRelativeSelected(idStr);
					chkPass = chkResult;
				}
				if(chkPass){
					if(confirm("ȷ��Ҫ����"+(idStr.split(',')).length+"���ʼ죿")){
						$.ajax({
							url:'?model=produce_quality_qualitytask&action=ajaxTask&itemId='+ idStr+'&docType='+row.relDocType,
							type:'get',
							dataType:'json',
							success:function(msg){
								if(msg==1){
									show_page();
									alert('�����ɹ�');
								}else if(msg==0){
									alert('����ʧ��');
								}else if(row.relDocType == 'ZJSQDLBF'){
									alert('�����뵥��'+msg+'������,������ֹ��');
								}
							},
							error:function(){
								alert('����������ʧ��');
							}
						});
					}
				}
			}else{
				alert('��ѡ��һ������');
			}
		}
	};// �ύ�ʼ�
	var confirmPassBtn = {
		text : '�ʼ����',
		icon : 'edit',
		showMenuFn : function(row) {
			if(row.status != '3' && row.relDocType != 'ZJSQDLBF'){
				return true;
			}
			return false;
		},
		action: function(row,rows,idArr ) {
			if(row){
				var relDocTypeArr = [];//��ʼ��Դ����������
				var newIdArr = [];
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].detailStatus !== '4'){
						alert('���� ��'+ rows[i].productName +'�� ����״̬��Ϊ ��δ���������ܽ��д˲���');
						return false;
					}
					if(rows[i].receiveStatus !== '1'){
						alert('���ȶ����� ��'+ rows[i].productName +'�����н���ȷ�Ϻ��ٽ����ʼ���в���');
						return false;
					}
					//����Դ����������
					if(relDocTypeArr.indexOf(rows[i].relDocType) == -1){//�����в����ڸ�Ԫ�أ��򷵻�-1
						relDocTypeArr.push(rows[i].relDocType);
					}
					newIdArr.push(rows[i].id);
				}
				relDocTypeStr = relDocTypeArr.toString();
				idStr = newIdArr.toString();
				showThickboxWin("?model=produce_quality_qualityapplyitem&action=toConfirmPass&id="
					+ idStr + "&relDocType=" + relDocTypeStr
					+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}else{
				alert('��ѡ��һ������');
			}
		}
	};// �ʼ����
	var backBtn = {
		text : '���',
		icon : 'delete',
		action: function(row,rows) {
			if(row){
				var newIdArr = [];
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].detailStatus !== '4'){
						alert('���� ��'+ rows[i].productName +'�� ����״̬��Ϊ ��δ���������ܽ��д˲���');
						return false;
					}
					newIdArr.push(rows[i].id);
				}
				if('undefined'==typeof(newIdArr)){
					alert('����δ֪������ˢ�¸ô���');
					show_page();
					return false;
				}
				var alertMsg = "ȷ��Ҫ����"+newIdArr.length+"����أ�";
				if(row.relDocType == 'ZJSQDLBF'){// ���ϱ�����Ҫ���������
					alertMsg = "ȷ������"+newIdArr.length+"������,������ͬԭ���������ʼ������¼һͬ���?";
				}
				if(confirm(alertMsg)){
					idStr = newIdArr.toString();
					$.ajax({
						url:'?model=produce_quality_qualityapplyitem&action=ajaxBack&ids=' + idStr,
						type:'get',
						dataType:'json',
						success:function(msg){
							if(msg==1){
								show_page();
								alert('�����ɹ�');
							}else
								alert('����ʧ��');
						},
						error:function(){
							alert('����������ʧ��');
						}
					});
				}
			}else{
				alert('��ѡ��һ������');
			}
		}
	};// ���
	var buttonMenu = [];
	buttonMenu.push(confirmBtn);
	buttonMenu.push(submitQualityBtn);
	if(relDocType != 'ZJSQDLBF'){
		buttonMenu.push(confirmPassBtn);
	}
	buttonMenu.push(backBtn);
	// ------- ./�˵���ť ------- //

	if(detailStatusArr!=4){
		buttonMenu=[];
	}
	$("#qualityapplyGrid").yxgrid({
		model : 'produce_quality_qualityapply',
		action : 'jsonDetail',
		title : '�ʼ������豸��ϸ',
		param : {
			detailStatusArr:detailStatusArr,
			relDocTypeArr:relDocType
		},
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		// ����Ϣ
		colModel : [{
            name : 'id',
            display : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'relDocType',
            display : 'Դ������',
            sortable : true,
            width : 90,
            datacode : 'ZJSQDYD'
        }, {
            name : 'relDocId',
            display : 'Դ��id',
            sortable : true,
            hide : true
        }, {
            name : 'relDocCode',
            display : 'Դ�����',
            sortable : true,
            width : 110
        }, {
            name : 'docCode',
            display : '���뵥��',
            sortable : true,
            process : function(v,row){
                if(row.status == "3" || row.status == "2" || row.relDocType == "ZJSQDLBF"){
                    return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
                }else{
                    return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toQualityView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
                }
            },
            width : 80
        }, {
            name : 'contractId',
            display : '��ͬid',
            sortable : true,
            hide : true
        }, {
            name : 'contractCode',
            display : '��ͬ���',
            sortable : true,
//            process: function (v, row) {
//            	if(row.contractTypeCode == 'HTLX-XSHT' || row.contractTypeCode == 'HTLX-FWHT' ||
//            		row.contractTypeCode == 'HTLX-ZLHT' || row.contractTypeCode == 'HTLX-YFHT'){
//                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
//                    + row.contractId
//                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
//                    + "<font color = '#4169E1'>"
//                    + v
//                    + "</font>"
//                    + '</a>';
//            	}
//            	return v;
//           },
           width : 100
        }, {
            name : 'customerId',
            display : '�ͻ�id',
            sortable : true,
            hide : true
        }, {
            name : 'customerName',
            display : '�ͻ�����',
            sortable : true,
            width : 100
        }, {
            name : 'status',
            display : '����״̬',
            sortable : true,
            width : 70,
            process : function(v){
                switch(v){
                    case '0' : return "��ִ��";
                    case '1' : return "����ִ��";
                    case '2' : return "ִ����";
                    case '3' : return "�ѹر�";
                    default : return '<span class="red">�Ƿ�״̬</span>';
                }
            },
            hide : true
        }, {
            name : 'supplierName',
            display : '��Ӧ��',
            sortable : true,
            width : 130,
            hide : true
        }, {
            name : 'productCode',
            display : '���ϱ��',
            width : 80
        }, {
            name : 'productName',
            display : '��������',
            width : 120
        }, {
            name : 'pattern',
            display : '�ͺ�'
        }, {
            name : 'unitName',
            display : '��λ',
            width : 50
        }, {
            display : '���κ�',
            name : 'batchNum'
        }, {
            display : '���к�',
            name : 'serialName'
        }, {
            name : 'checkTypeName',
            display : '�ʼ췽ʽ',
            width : 60
        }, {
            name : 'qualityNum',
            display : '��������',
            width : 60
        }, {
            name : 'assignNum',
            display : '���´�����',
            width : 60
        }, {
            name : 'complatedNum',
            display : '�ʼ������',
            width : 65
        },{
            name : 'standardNum',
            display : '�ϸ�����',
            width : 60
        }, {
            name : 'receiveStatus',
            display : '����ȷ��',
            sortable : true,
            process : function(v) {
                switch(v){
                	case '0' : return '��';
                    case '1' : return '��';
                }
            },
            width : 70
        }, {
			name : 'receiveId',
			display : '����ȷ����id',
			sortable : true,
			hide : true
		}, {
			name : 'receiveName',
			display : '����ȷ����',
			sortable : true,
			width : 90
		}, {
			name : 'receiveTime',
			display : '����ȷ��ʱ��',
			sortable : true,
			width : 130
		},{
            name : 'detailStatus',
            display : '������',
            width : 60,
            process : function(v){
                switch(v){
                    case "0" : return "�ʼ����";
                    case "1" : return "���ִ���";
                    case "2" : return "������";
                    case "3" : return "�ʼ����";
                    case "4" : return "δ����";
                    case "5" : return "�𻵷���";
                    default : return "";
                }
            }
        },{
            name : 'applyUserName',
            display : '������',
            width : 90,
            sortable : true
        }, {
            name : 'applyUserCode',
            display : '������Code',
            hide : true,
            sortable : true
        }, {
            name : 'createTime',
            display : '����ʱ��',
            width : 130,
            hide : true,
            sortable : true
        }, {
            name : 'closeUserName',
            display : '�ر���',
            hide : true,
            sortable : true
        }, {
            name : 'closeUserId',
            display : '�ر���id',
            hide : true,
            sortable : true
        }, {
            name : 'closeTime',
            display : '�ر�ʱ��',
            hide : true,
            width : 130,
            sortable : true
        }, {
            name : 'dealUserName',
            display : '������',
            width : 80
        },{
            name : 'dealTime',
            display : '����ʱ��',
            width : 130
        },{
            name : 'passReason',
            display : '����ԭ��',
            width : 130
        }],
		buttonsEx : buttonMenu,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toQualityView',
			formWidth : 900,
			formHeight : 500,
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				if(row.status == "3" || row.status == "2" || row.relDocType == "ZJSQDLBF"){
					showThickboxWin("?model=produce_quality_qualityapply&action=toView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}else{
					showThickboxWin("?model=produce_quality_qualityapply&action=toQualityView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		},
		searchitems : [{
			display : "���ݱ��",
			name : 'docCodeSearch'
		},{
			display : "Դ�����",
			name : 'relDocCodeSearch'
		},{
			display : "������",
			name : 'createNameSearch'
		},{
			display : "��Ӧ��",
			name : 'supplierNameSearch'
		},{
			display : "��������",
			name : 'iProductNameSearch'
		},{
			display : "���ϱ��",
			name : 'iProductCodeSearch'
		}],
		sortname : 'i.dealTime'
	});
});

// ���ѡ�е�ID�����Ƿ������ͬԴ��û�б�ѡ�е�����
function chkIsAllRelativeSelected(ids){
	var chkResult = $.ajax({
		url:'index1.php?model=produce_quality_qualityapplyitem&action=chkIsAllRelativeSelected&ids='+ids,
		type : "POST",
		async : false
	}).responseText;
	if(chkResult != 'false'){
		var chkResultArr = eval("(" + chkResult + ")");
		var failDocCode = '';
		$.each(chkResultArr,function(){
			var obj = $(this)[0];
			if(parseInt(obj.totalItmesNum) != parseInt(obj['itemIds']['length'])){
				failDocCode += obj.docCode+',';
			}
		});
		if(failDocCode != ''){
			failDocCode = failDocCode.substring(0,failDocCode.length-1);
			alert("�������뵥��"+failDocCode+"����������δѡ�С�");
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}