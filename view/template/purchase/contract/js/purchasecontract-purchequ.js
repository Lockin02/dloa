// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#equListGrid").yxgrid("reload");
};

//�鿴������Ϣ
function viewOrder(id,skey) {
	showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&id="+id+"&skey="+skey);
}
$(function() {
    var assessType=$("#assessType").val();
    var suppId=$("#suppId").val();
    var assesYear=$("#assesYear").val();
    var assesQuarter=$("#assesQuarter").val();
			$("#equListGrid").yxgrid({
						//�������url�����ô����url������ʹ��model��action�Զ���װ
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'purchEquJson',
						title : '�ɹ�������ϸ',
						isToolBar : false,
						showcheckbox : false,
						param:{"suppId":suppId,"assessType":assessType,"assesYear":assesYear,"assesQuarter":assesQuarter},

						//����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '��������',
									name : 'createTime',
									sortable : true,
									width:80
								}, {
									display : '���ݱ��',
									name : 'hwapplyNumb',
									sortable : true,
									width : '150'
								}
								,{
									display : '��Ӧ��',
									name : 'suppName',
									sortable : true,
									width : '200'
								}
                            ,{
                                display : '���ϱ���',
                                name : 'productNumb',
                                width : '65',
                                sortable : true
                            }
								, {
									display : '��������',
									name : 'productName',
									sortable : true,
									width : '200',
									process : function(v,row){
											if((DateDiff(row.today,row.dateHope)<2||DateDiff(row.today,row.dateHope)==2)&&DateDiff(row.today,row.dateHope)>0){
												return "<font color=red>"+v+"</font>";;
											}else{
												return v;
											}
									}
								},{
									display : '��������',
									name : 'amountAll',
									sortable :��true,
									width : '65',
									process : function(v,row){
										if(parseInt(row.amountAll)!=parseInt(row.amountIssued)){
											return "<font color=blue>"+moneyFormat2(v)+"</font>";
										}else{
											return moneyFormat2(v);
										}
									}
								},{
									display : '�������',
									name : 'amountIssued',
									sortable :��true,
									width : '65',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '��������',
									name : 'arrivalNum',
									sortable :��true,
									width : '65',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '�ʼ췽ʽ',
									name : 'checkTypeName',
									sortable :��false,
									width : '60',
									process : function(v,row){
											if(v=="ȫ��"||v=="���"){
												return "<font color=red>"+v+"</font>";;
											}else{
												return v;
											}
									}
								},{
									display : 'Ԥ�Ƶ���ʱ��',
									name : 'dateHope',
									sortable : true
								},{
									display : '�ɹ�����',
									name : 'purchType',
									sortable : true
								}
								],
						//��չ�Ҽ��˵�
						menusEx : [
						],
						//��������
						searchitems : [
								{
									display : '���ݱ��',
									name : 'hwapplyNumb'
								},
								{
									display : '��������',
									name : 'orderTime'
								},
								{
									display : '��Ӧ��',
									name : 'suppName'
								},
								{
									display : '���ϱ���',
									name : 'searchProductNumb'
								},
								{
									display : '��������',
									name : 'searchPproductName'
								}
								],
						// title : '�ͻ���Ϣ',
						//ҵ���������
//						boName : '��Ӧ����ϵ��',
						//Ĭ�������ֶ���
						sortname : "updateTime",
						//Ĭ������˳��
						sortorder : "DESC",
						//��ʾ�鿴��ť
						isViewAction : false,
						isAddAction : true,
						isEditAction : false,
						isDelAction : false
					});

		});