var show_page = function(page) {
	$("#proborrowGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [
//	{
//		text : "����",
//		icon : 'delete',
//			action : function(row) {
//				var listGrid= $("#proborrowGrid").data('yxsubgrid');
//				listGrid.options.extParam = {};
//				$("#proborrowGrid tr").attr('style',"background-color: rgb(255, 255, 255)");
//				listGrid.reload();
//			}
//		},{
//			name : 'advancedsearch',
//			text : "�߼�����",
//			icon : 'search',
//			action : function(row) {
//				showThickboxWin("?model=projectmanagent_borrow_borrow&action=search&gridName=proborrowGrid"
//				        + "&gridType=yxsubgrid"
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
//			}
//		},
			{
			name : 'Add',
			// hide : true,
			text : "����",
			icon : 'add',

			action : function(row) {
				showModalWin('?model=projectmanagent_borrow_borrow&action=toProAdd');
			}
		}]
	$("#proborrowGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'MyBorrowPageJson',
		param : {
			'limits' : 'Ա��'
		},
		title : '�ҵĽ�����',
		//��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		customCode : 'proborrowlist',
		//����Ϣ
		colModel : [
	     {
	    	display : '����Ԥ��',
			name : 'endDate',
			sortable : true,
			width:30,
			process : function(v,row){
				if(row.backStatus == '1'){
				  return "<img src='images/icon/icon073.gif'></img>";
				}else
	    	 	if(v){
	    	 		var date=new Date();
	    	 		var time=date.format('yyyy-MM-dd');
	    	 		if(v<time)
	    	 			return "<a href='?model=projectmanagent_penalty_borrowPenalty&action=toMyPage' target='_blank''><img src='images/icon/icon070.gif'></img></a>";
	    	 		if(v>time)
	    	 			return "<img src='images/icon/green.gif'></img>";
	    	 		if(v=time)
	    	 			return "<img src='images/icon/hblue.gif'></img>";
	    	 	}
			}
		 },{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '���',
			sortable : true,
			width : 180,
			process : function(v,row){
			    if(row.isExceed == '1'){
                     return "<span class='red'>"+ v + "</span>";
			    }else{
			         return v;
			    }
			}
		}, {
			name : 'Type',
			display : '����',
			sortable : true,
			width : 60,
			hide : true
		}, {
			name : 'limits',
			display : '��Χ',
			sortable : true,
			width : 60
		},{
			name : 'timeType',
			display : '��������',
			sortable : true,
			width : 60
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 90,
				process: function (v,row) {
					if(row.lExaStatus != '���������'){
						return v;
					}else{
						return '���������';
					}
				}
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'beginTime',
			display : '��ʼ����',
			sortable : true,
			width : 80
		}, {
			name : 'closeTime',
			display : '��ֹ����',
			sortable : true,
			width : 80
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true,
				process : function(v,row) {
					if(row['ExaStatus'] == '��������'){
						return '';
					}else{
						return v;
					}
				}
		},{
			name : 'DeliveryStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == 'WFH') {
					return "δ����";
				} else if (v == 'YFH') {
					return "�ѷ���";
				} else if (v == 'BFFH') {
					return "���ַ���";
				} else if (v == 'TZFH') {
					return "ֹͣ����";
				}
			},
			width : 80
		}, {
			name : 'status',
			display : '����״̬',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "����";
  				}else if(v == '1'){
  					return "���ֹ黹";
  				}else if(v == '2'){
  					return "�ر�";
  				}else if(v == '3'){
  				    return "�˻�";
  				}else if(v == '4'){
  				    return "����������"
  				}else if(v == '5'){
  				    return "ת��ִ�в�"
  				}else if(v == '6'){
  				    return "ת��ȷ����"
  				}
  			}
		}
		,{
			name : 'backStatus',
			display : '�黹״̬',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "δ�黹";
  				}else if(v == '1'){
  					return "�ѹ黹";
  				}else if(v == '2'){
  					return "���ֹ黹";
  				}
  			}
		}
		, {
			name : 'reason',
			display : '��������',
			sortable : true,
			width : 200
		},{
			name : 'renew',
			display : '�������',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 200
		}, {
			name : 'objCode',
			display : 'ҵ����',
			width : 120
		}],
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : 'δ����',
				value : 'δ����'
			}, {
				text : '���������',
				value : '���������'
			}, {
				text : '��������',
				value : '��������'
			}, {
				text : '���',
				value : '���'
			}]
		},{
			text : '״̬',
			key : 'status',
			data : [{
				text : '����',
				value : '0'
			}, {
				text : '�ر�',
				value : '2'
			}, {
				text : '�˻�',
				value : '3'
			}, {
				text : '����������',
				value : '4'
			}, {
				text : 'ת��ִ�в�',
				value : '5'
			}, {
				text : 'ת��ȷ����',
				value : '6'
			}]
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrowequ&action=listPageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'borrowId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
				name : 'productNo',
				width : 200,
				display : '��Ʒ���',
				process : function(v,row){
					return v+"&nbsp;&nbsp;K3:"+row['productNoKS'];
				}
			},{
				name : 'productName',
				width : 200,
				display : '��Ʒ����',
				process : function(v,row){
					return v+"&nbsp;&nbsp;K3:"+row['productNameKS'];
				}
			}, {
			    name : 'number',
			    display : '��������',
				width : 80
			}, {
			    name : 'executedNum',
			    display : '��ִ������',
				width : 80
			}, {
			    name : 'applyBackNum',
			    display : '������黹����'
			}, {
			    name : 'backNum',
			    display : '�ѹ黹����',
				width : 80
			}]
		},
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
//						alert(data.collection[i].Code);
//						//�ж���������ѵ��ڵ���ʾ��ɫ
//						var myDate = new Date();
//						var newDate = myDate.getFullYear()+"-"+(myDate.getMonth()+1)+"-"+myDate.getDate();
//                        if(formatDate(newDate) > formatDate(data.collection[i].closeTime) && data.collection[i].status != '2'){
//                        	$('#row' + data.collection[i].Code).css('color', 'red');
//                        }
						 //�ж����Ϊ�ֹ�ȷ���е� Ϊ��ɫ
						if(data.collection[i].tostorage == 1){
							$('#row' + data.collection[i].id).css('color', 'blue');
						}
					}
				}
			}
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���',
			name : 'Code'
		},{
			display : '������',
			name : 'createName'
		},{
			display : '��������',
			name : 'createTime'
		},{
		    display : 'K3��������',
		    name : 'productNameKS'
		},{
		    display : 'ϵͳ��������',
		    name : 'productName'
		},{
		    display : 'K3���ϱ���',
		    name : 'productNoKS'
		},{
		    display : 'ϵͳ���ϱ���',
		    name : 'productNo'
		},{
			display : '���к�',
			name : 'serialName2'
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_borrow_borrow&action=proViewTab&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '����ȷ��' || row.ExaStatus == '���' || row.ExaStatus == '��������'  || row.ExaStatus == '���������'|| row.ExaStatus == '����' || row.tostorage == '1' ) {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=projectmanagent_borrow_borrow&action=proEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {

//			text : '�黹����',
//			icon : 'add',
//            showMenuFn : function(row) {
//				if(row.backStatus == 1 || row.status == 3 || row.status == 6 || row.ExaStatus == 'δ����' ||row.ExaStatus == '���'  ||  row.ExaStatus == '��������' || row.ExaStatus == '���������' ){
//                   return false;
//                }
//                  return true;
//
//			},
//			action : function(row) {
//                showOpenWin('?model=projectmanagent_borrowreturn_borrowreturn&action=toAdd&id=' + row.id);
//			}
//		}
//		,{

			text : '���',
			icon : 'edit',
            showMenuFn : function(row) {
				if (row.ExaStatus == '����ȷ��' || row.ExaStatus == '��������' || row.ExaStatus == '���' || row.ExaStatus == '����' || row.ExaStatus == '���������' || row.lExaStatus == '���������' || row.ExaStatus == 'δ����' || row.status == '4'||row.status == '5'||row.status == '6') {
					return false;
				}
				return true;
			},
			action : function(row) {
				     location='?model=projectmanagent_borrow_borrow&action=toChange&change=proChange&id='+ row.id + "&skey="+row['skey_'];
			}
		}
		// ,{
		// 	text : '�ύ���',
		// 	icon : 'add',
		// 	showMenuFn : function(row) {
		// 		if (row.ExaStatus == '����ȷ��' || row.ExaStatus == '���' || row.ExaStatus == '��������' || row.ExaStatus == '����' || row.tostorage == '1' || row.timeType == '���ڽ���' || row.ExaStatus == '���������') {
		// 			return false;
		// 		}
		// 		return true;
		// 	},
		// 	action : function(row, rows, grid) {
		// 		if (row) {
		// 			showThickboxWin('controller/projectmanagent/borrow/ewf_proborrow.php?actTo=ewfSelect&billId='
		// 					+ row.id
		// 					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
		// 		}
		// 	}
		// }
		,{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == 'δ����') {
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				if (window.confirm(("ȷ���ύ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrow_borrow&action=ajaxSubForm",
						data : {
							id : row.id
						},
						success : function(msg) {
							if(msg != ""){
								alert(msg);
							}else{
								alert("�ύʧ�ܡ�������!");
							}
							$("#proborrowGrid").yxsubgrid("reload");
						}
					});
				}
			}
		}
		,{
				text : '�������',
				icon : 'view',
				showMenuFn : function(row) {
				if (row.ExaStatus == '����' || row.timeType == '���ڽ���' ) {
					return false;
				}
				return true;
			},
				action : function(row) {
				         showThickboxWin('controller/projectmanagent/borrow/readview.php?itemtype=oa_borrow_borrow&pid='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '����ȷ��' || row.ExaStatus == '���' || row.ExaStatus == '��������' || row.ExaStatus == '���������' || row.ExaStatus == '����'  || row.tostorage == '1') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrow_borrow&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#proborrowGrid").yxsubgrid("reload");
							}
						}
					});
				}
			}

		},{
			text : '����',
			icon : 'add',
            showMenuFn : function(row) {
				if(row.backStatus == 1 || row.status == 3 || row.status == 6 || row.ExaStatus == 'δ����' ||row.ExaStatus == '���'  ||  row.ExaStatus == '��������' || row.ExaStatus == '���������' ){
                   return false;
                }
                  return true;

			},
			action : function(row, rows, grid) {
				if (row) {
					if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=borrowRenew&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
					} else {
						alert("��ѡ��һ������");
					}
				}
			}
		}
		,{
			text : 'ת��',
			icon : 'edit',
            showMenuFn : function(row) {
				if(row.backStatus == 1 || row.status == 3 || row.status == 6 || row.ExaStatus == 'δ����' || row.ExaStatus == '��������' || row.ExaStatus == '���' || row.ExaStatus == '���������' ){
                   return false;
                }
                  return true;

			},
			action : function(row, rows, grid) {
				if (row) {
					if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=subtenancyApply&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
					} else {
						alert("��ѡ��һ������");
					}
				}
			}
		},{
			text : 'ת���޸�',
			icon : 'edit',
            showMenuFn : function(row) {
				if(row.status == 6){
                   return true;
                }
                  return false;

			},
			action : function(row, rows, grid) {
				if (row) {
					if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=subtenancyEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
					} else {
						alert("��ѡ��һ������");
					}
				}
			}
		},{
			text : 'ת��ȷ��',
			icon : 'add',
            showMenuFn : function(row) {
				if(row.status == 6){
                   return true;
                }
                  return false;

			},
			action : function(row, rows, grid) {
				if (row) {
					if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=subtenancyAff&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
					} else {
						alert("��ѡ��һ������");
					}
				}
			}
		}
		],
		buttonsEx : buttonsArr
	});

});
/**
 * ʱ�����ĸ�ʽ��;
 */
Date.prototype.format = function(format) {
    /*
     * eg:format="YYYY-MM-dd hh:mm:ss";
     */
    var o = {
        "M+" :this.getMonth() + 1, // month
        "d+" :this.getDate(), // day
        "h+" :this.getHours(), // hour
        "m+" :this.getMinutes(), // minute
        "s+" :this.getSeconds(), // second
        "q+" :Math.floor((this.getMonth() + 3) / 3), // quarter
        "S" :this.getMilliseconds()
    // millisecond
    }

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "")
                .substr(4 - RegExp.$1.length));
    }

    for ( var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]
                    : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}