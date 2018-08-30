var show_page = function(page) {
	$("#bookToViewGrid").yxgrid("reload");
};
$(function() {
	$("#bookToViewGrid").yxgrid( {
						model : 'hr_bookToView_bookToView',
						title : '图书借阅信息',
						param :{
							"userId":$("#userID").val()
						},
						isOpButton : false,
						showcheckbox:false,
						bodyAlign:'center',
						// 列信息
						colModel : [
						             {
									name : 'userNo',
									display : '员工编号',
									sortable : true,
									width:'70'
								}, {
									name : 'userName',
									display : '员工姓名',
									sortable : true,
									width:'60'
								},{
									name : 'belongDeptName',
									display : '所属部门',
									sortable : true,
									width:'100'
								},{
									name : 'BR_PASS2',
									display : '状态',
									sortable : true,
									width:'80',
									process: function(v) {
									if (v == "0") {
										return '申请';
									} else if (v == "1") {
										return '阅读';
									} else if (v == "2") {
										return '已归还';
									}else if (v == "3") {
										return '归还中';
									}
								}
								},
								{
									name : 'ISBN',
									display : '编号',
									sortable : true,
									width:'120'
								}, {
									name : 'BOOK_NAME',
									display : '书名',
									sortable : true,
									width:'300'
								},{
									name : 'BR_SDATE',
									display : '借阅时间',
									sortable : true,
									width:'80'
								}, {
									name : 'BR_EDATE',
									display : '归还时间',
									sortable : true,
									width:'80'
								}, {
									name : 'CHECK_EDATE',
									display : '核对日期',
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