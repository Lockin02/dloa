var show_page = function(page) {
	$("#bookToViewGrid").yxgrid("reload");
};
$(function() {
	$("#bookToViewGrid").yxgrid( {
						model : 'hr_bookToView_bookToView',
						title : 'ͼ�������Ϣ',
						param :{
							"userId":$("#userID").val()
						},
						isOpButton : false,
						showcheckbox:false,
						bodyAlign:'center',
						// ����Ϣ
						colModel : [
						             {
									name : 'userNo',
									display : 'Ա�����',
									sortable : true,
									width:'70'
								}, {
									name : 'userName',
									display : 'Ա������',
									sortable : true,
									width:'60'
								},{
									name : 'belongDeptName',
									display : '��������',
									sortable : true,
									width:'100'
								},{
									name : 'BR_PASS2',
									display : '״̬',
									sortable : true,
									width:'80',
									process: function(v) {
									if (v == "0") {
										return '����';
									} else if (v == "1") {
										return '�Ķ�';
									} else if (v == "2") {
										return '�ѹ黹';
									}else if (v == "3") {
										return '�黹��';
									}
								}
								},
								{
									name : 'ISBN',
									display : '���',
									sortable : true,
									width:'120'
								}, {
									name : 'BOOK_NAME',
									display : '����',
									sortable : true,
									width:'300'
								},{
									name : 'BR_SDATE',
									display : '����ʱ��',
									sortable : true,
									width:'80'
								}, {
									name : 'BR_EDATE',
									display : '�黹ʱ��',
									sortable : true,
									width:'80'
								}, {
									name : 'CHECK_EDATE',
									display : '�˶�����',
									sortable : true,
									width:'100'
								}],
						isViewAction:false,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						sortorder : "DESC",
						sortname : "a.BR_EDATE"
					});
		});