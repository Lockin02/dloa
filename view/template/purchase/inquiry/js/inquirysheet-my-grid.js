/**�ҵ�ѯ�۵� �����б�
 *2010-12-28 can
 */

var show_page = function(page) {
	$("#inquirysheetyMyGrid").yxsubgrid("reload");
};

//ɾ���ظ������IE�ļ��������⣩
function uniqueArray(a){
	temp = new Array();
	for(var i = 0; i < a.length; i ++){
		if(!contains(temp, a[i])){
			temp.length+=1;
			temp[temp.length-1] = a[i];
		}
	}
	return temp;
	}
	function contains(a, e){
	for(j=0;j<a.length;j++)if(a[j]==e)return true;
	return false;
}

$(function() {
	$("#inquirysheetyMyGrid").yxsubgrid({
		isTitle:true,
		title:'�ҵĲɹ�ѯ�۵�',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:true,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		action : 'myPageJson',
		param:{states:'0,1,2,4'},


			// ����Ϣ
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : 'ѯ�۵����',
				name : 'inquiryCode',
				sortable : true,
				process : function(v, row) {
					if(row.state=="4"||row.state=="3"){
						return v;
					}else{
						return "<font color=blue>" +v+"</font>";
					}
				}
			}, {
				display : '����״̬',
				name : 'ExaStatus',
				sortable : true,
				width:60
			}, {
				display : 'ѯ�۵�״̬',
				name : 'stateName',
				sortable : false,
				width:65
			},{
				display : 'ָ����Ӧ��',
				name : 'suppName',
				sortable : true,
				width:180
			},
			{
				display : '��Ӧ��ID',
				name : 'suppId',
				sortable : true,
				hide : true
			},
			{
				display : 'ָ��������',
				name : 'amaldarName',
				sortable : true
			}, {
				display : '�ɹ�Ա',
				name : 'purcherName',
				sortable : true,
				hide : true
			}, {
				display : 'ѯ������',
				name : 'inquiryBgDate',
				sortable : true
			}, {
				display : '���۽�ֹ����',
				name : 'inquiryEndDate',
				sortable : true
			}, {
				display : '��Ч����',
				name : 'effectiveDate',
				sortable : true
			}, {
				display : 'ʧЧ����',
				name : 'expiryDate',
				sortable : true
			}],
			searchitems : [{
				display : 'ѯ�۵����',
				name : 'inquiryCode'
			},{
				display : 'ָ����Ӧ��',
				name : 'suppName'
			},{
				display : '��������',
				name : 'productName'
			},{
				display : '���ϱ��',
				name : 'productNumb'
			}],
			sortname : 'c.state asc,c.createTime',
			// ���ӱ������
			subGridOptions : {
				url : '?model=purchase_inquiry_equmentInquiry&action=pageJson',
				param : [{
							paramId : 'parentId',
							colId : 'id'
						}],
				colModel : [{
							name : 'productNumb',
							display : '���ϱ��'
						}, {
							name : 'productName',
							width : 200,
							display : '��������'
						},{
							name : 'pattem',
							display : "����ͺ�"
						},{
							name : 'units',
							display : "��λ"
						},{
							name : 'amount',
							display : "ѯ������"
						},{
							name : 'purchTypeCn',
							display : "�ɹ�����"
						}]
			},

		comboEx:[{
			text:'ѯ�۵�״̬',
			key:'state',
			data:[{
			   text:'����',
			   value:'0'
			},{
			   text:'��ָ��',
			   value:'1'
			},{
			   text:'��ָ��',
			   value:'2'
			},{
			   text:'�ѹر�',
			   value:'3'
			},{
			   text:'�����ɶ���',
			   value:'4'
			}]
		},{
			text:'����״̬',
			key:'ExaStatus',
			data:[{
			   text:'δ�ύ',
			   value:'δ�ύ'
			},{
			   text:'��������',
			   value:'��������'
			},{
			   text:'���',
			   value:'���'
			}]
		}],

		//��չ��ť
		buttonsEx : [{
			name : 'return',
			text : '���ɶ���',
			icon : 'add',
			action : function(row, rows, grid) {
				if(rows){
					var checkedRowsIds=$("#inquirysheetyMyGrid").yxsubgrid("getCheckedRowIds");  //��ȡѡ�е�id
//					$.showDump(rows);
					var states=[];   //�ɹ�ѯ�۵�״̬����
					var suppIds=[];//ָ����Ӧ������
					$.each(rows,function(i,n){
	        			var o = eval( n );
						suppIds.push(o.suppId);
						states.push(o.state);
					});
					suppIds.sort();
					var uniqueId=uniqueArray(suppIds);
					var idLength=uniqueId.length;
					states.sort();
					var uniqueState=uniqueArray(states);
					var stateLength=uniqueState.length;
					if(stateLength==1&&uniqueState[0]==2){  //�жϵ��ݵ�״̬�Ƿ�Ϊ����ָ��������ֻ��һ��״̬
						if(idLength==1&&uniqueId[0]!=""){ //�ж��Ƿ�Ϊͬһ��Ӧ��
							$.ajax({				//ajax�ж��´�������Ƿ�Ϊͬһ�ɹ�����
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_equmentInquiry&action=isSameType",
			    		         data:{
			    		         	parentIds:checkedRowsIds
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
										parent.location = "index1.php?model=purchase_contract_purchasecontract&action=toAddByMore&inquiryId="+checkedRowsIds+ "&suppId=" +uniqueId[0];
			    		            }else{
			    		            	alert("����ͬʱ�´ﲻͬ���͵Ĳɹ�");
			    		            }
			    		         }
			    		     });
						}else{
							alert("��ѡ��Ӧ����ͬ�ĵĵ���");
						}
					}else{
						alert("��ѡ��ѯ�۵�״̬Ϊ'��ָ��'�ĵ���");
					}


				}else{
					alert("��ѡ����Ҫ���ɶ����ĵ��ݡ�");
				}

			}
		},{
			name : 'return',
			text : '�ر�',
			icon : 'delete',
			action : function(row, rows, grid) {
				if(rows){
					var checkedRowsIds=$("#inquirysheetyMyGrid").yxsubgrid("getCheckedRowIds");  //��ȡѡ�е�id
						if(confirm("ȷ��Ҫ�ر���")){
							$.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=closeBatch",
			    		         data:{
			    		         	ids:checkedRowsIds
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
			    		                alert('�رճɹ�!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
						}
				}else{
					alert("��ѡ��Ҫ�رյĵ���");
				}

			}
		}],

		//��չ�Ҽ�
		menusEx:[
			{  text:'�鿴',
			   icon:'view',
			   showMenuFn:function(row){
			   		if(row.ExaStatus=="δ�ύ"|row.ExaStatus=="��������"|row.ExaStatus=="���"){
			   			return true;
			   		}
			   		return false;
			   },
			   action:function(row,rows,grid){
			   		if(row){
						 parent.location = "?model=purchase_inquiry_inquirysheet&action=toRead&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},
			{
			 	text:'�޸�',
			 	icon:'edit',
			 	showMenuFn:function(row){
			 	   if(row.state==0||row.state==1&&row.ExaStatus=="δ�ύ"||row.ExaStatus=="���"){
			 	   		return true;
			 	   }
			 	   return false;
			 	},
			 	action:function(row,rows,grid){
			 	   if(row){
						parent.location = "?model=purchase_inquiry_inquirysheet&action=init&id="+ row.id+"&skey="+row['skey_'];
			 	   }
			 	}
			},
			{
			    text:'ɾ��',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if(row.state==0||row.state==1&&row.ExaStatus=="δ�ύ"){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("ȷ��Ҫɾ��?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=deletesInfo",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
//			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
			    		                alert('ɾ���ɹ�!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
//			    		     location='?model=purchase_inquiry_inquirysheet&action=deletesInfo&id='+row.id;
			    		}
			    	}
			    }
			},
			{
			    text:'�˻�����',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if((row.state==1&&row.ExaStatus=="���")||(row.state==2)){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("ȷ��Ҫ�˻�?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=backToTask",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
//			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
			    		                alert('�˻سɹ�!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
			    		}
			    	}
			    }
			},
//				{
//				text:'�ύ',
//				icon:'edit',
//				showMenuFn:function(row){
//					if(row.state==0){
//						return true;
//					}
//					return false;
//				},
//				action:function(row,rows,grid){
//					if(row){
//						if(confirm("ȷ��Ҫ�ύ��")){
//							 $.ajax({
//			    		         type:"POST",
//			    		         url:"?model=purchase_inquiry_inquirysheet&action=putInquiry",
//			    		         data:{
//			    		         	parentId:row.id
//			    		         },
//			    		         success:function(msg){
//			    		            if(msg==1){
////			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
//			    		                alert('�ύ�ɹ�!');
//			    		                show_page();
//			    		            }
//			    		         }
//			    		     });
////						 	location = "?model=purchase_inquiry_inquirysheet&action=putInquiry&parentId="+ row.id;
//						}
//					}
//				}
//			},
			{  text:'�鿴',    //��ָ���Ĳ鿴
			   icon:'view',
			   showMenuFn:function(row){
					if(row.ExaStatus=="���"){
						return true;
					}
					return false;
				},
			   action:function(row,rows,grid){
			   		if(row){
						parent.location = "?model=purchase_inquiry_inquirysheet&action=toView&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},
				{
			   text:'�ر�',       //�ڱ���ʹ�ָ����״̬�¹ر�ѯ�۵�����ԭ�ɹ������豸����
			   icon:'delete',
			   showMenuFn:function(row){
			   		if(row.state!=3&&row.ExaStatus!="��������"&&row.ExaStatus!="δ�ύ"){
			   			return true;
			   		}
			   		return false;
			   },
			   action:function(row,rows,grid){
			   		 if(row){
						if(confirm("ȷ��Ҫ�ر���")){
							$.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=closeMyInquiry",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
//			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
			    		                alert('�رճɹ�!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
//						 	location = "?model=purchase_inquiry_inquirysheet&action=closeMyInquiry&id="+ row.id;
						}
					}
			   }
			},
				{
			name : 'sumbit',
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == 'δ�ύ'&&row.state!=3 ||row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					parent.location = 'controller/purchase/inquiry/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&examCode=oa_purch_inquiry&formName=�ɹ�ѯ�۵�����';
				} else {
					alert("��ѡ��һ������");
				}
			}
		},
				{
			name : 'sumbit',
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(confirm('ȷ��Ҫ����������')){
					parent.location = 'controller/purchase/inquiry/ewf_index.php?actTo=delWork&billId='
							+ row.id
							+ '&examCode=oa_purch_inquiry&formName=�ɹ�ѯ�۵�����';
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���'|| row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_inquiry&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
				}
			}
		},{
				text:'���ɲɹ�����',
				icon:'add',
				showMenuFn:function(row){

					//��ʽ������ʱ�䷽��
//					Date.prototype.format = function(format){
//							var o = {
//								"M+" : this.getMonth()+1, //month
//								"d+" : this.getDate(), //day
//								"h+" : this.getHours(), //hour
//								"m+" : this.getMinutes(), //minute
//								"s+" : this.getSeconds(), //second
//								"q+" : Math.floor((this.getMonth()+3)/3), //quarter
//								"S" : this.getMilliseconds() //millisecond
//							}
//							if(/(y+)/.test(format)) {
//								format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
//							}
//
//							for(var k in o) {
//								if(new RegExp("("+ k +")").test(format)) {
//									format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
//								}
//							}
//							return format;
//
//					}
//
//				    var date1=new Date();
//					var date2=date1.format("yyyy-MM-dd");
					if(row.ExaStatus=="���"&&row.state==2){
					   return true;
					}
					return false;
				},
				action:function(row,rows,grid){

				   if(row){
							$.ajax({				//ajax�ж��´�������Ƿ�Ϊͬһ�ɹ�����
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_equmentInquiry&action=isSameType",
			    		         data:{
			    		         	parentIds:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
										parent.location = "?model=purchase_contract_purchasecontract&action=toAddPurchaseContract&inquiryId="+ row.id + "&suppId=" + row.suppId+"&skey="+row['skey_'];
			    		            }else{
			    		            	alert("����ͬʱ�´ﲻͬ���͵Ĳɹ�");
			    		            }
			    		         }
			    		     });
				   }
				}
			}]
	});
});