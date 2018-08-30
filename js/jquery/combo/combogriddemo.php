<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>
            jquery combogrid demo
        </title>
        <meta http-equiv="content-type" content="text/html; charset=gbk" />
        <script type="text/javascript" src="../jquery-1.4.2.js"></script>
        <script type="text/javascript" src="../woo.js"></script>
        <script type="text/javascript" src="../component.js"></script>
        <script type="text/javascript" src="yxcombo.js"></script>
        <script type="text/javascript" src="yxcombogrid.js"></script>
		<script type="text/javascript" src="../../../js/jquery/dump.js"></script>
		<script type="text/javascript" src="../../../js/thickbox.js"></script>
		<script type="text/javascript" src="../../../js/jquery/grid/cgrid.js"></script>
		<script type="text/javascript" src="../../../js/jquery/grid/yxgrid.js"></script>
		<script type="text/javascript" src="../../../js/businesspage.js"></script>

		<link rel="stylesheet" type="text/css" href="../../../css/yxstyle.css" />
		<link rel="stylesheet" type="text/css" href="../../../js/jquery/style/yxgrid.css" />
		     <link type="text/css" href="../style/yxmenu.css" media="screen" rel="stylesheet"/>
		<link rel="stylesheet" href="../../../js/thickbox.css" type="text/css" media="screen" />
	    </head>
    <body>
<br>
<br>
<table class="form_main_table">
	<tr>
		<td class="main_tr_header" colspan="6">������Ϣ</td>
	</tr>

	<tr>
		<td class="form_text_left">��ϵ������</td>
		<td class="form_text_right" >
			<input type="text" id="cgrid" value="��ѡ��..." readOnly name="tName" >
		</td>
		<td class="form_text_left">ҵ����</td>
		<td class="form_text_right" >
			<input type="text" id="bcode" style="background:silver" readOnly name="bcode" >
		</td>
		<td class="form_text_left">����</td>
		<td class="form_text_right" >
			<input type="text" id="bcz" style="background:silver"  readOnly name="bcz" >
		</td>

	</tr>
</table>

    </body>

</html>
<script type="text/javascript">
	$("#cgrid").yxcombogrid({
				cbgridOptions : {
					id : "mycombogrid",
					textField:'name',
					url : 'http://localhost/dloa/oae/index1.php?model=supplierManage_formal_sfcontact&action=pageJson&parentId=2',
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '��ϵ������',
								name : 'name',
								sortable : true,
								// ���⴦���ֶκ���
								process : function(v, row) {
									return row.name;
								}
							}, {
								display : 'ҵ����',
								name : 'busiCode',
								sortable : true
							}, {
								display : '�����ַ',
								name : 'email',
								sortable : true
							}, {
								display : '����',
								name : 'plane',
								sortable : true
							}, {
								display : '����',
								name : 'fax',
								sortable : true
							}],

					boName : '��Ӧ����ϵ��',
					hiddenColumn : [{
								fieldId : "bcz",
								fieldName : "fax"
							}, {
								fieldId : "bcode",
								fieldName : "busiCode"
							}]

				}
				}
			);
 </script>