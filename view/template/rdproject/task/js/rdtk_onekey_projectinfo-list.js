Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var prjId = -1;
	var filetree;
	var fileCombo;
	var taskGrid = {
		xtype : 'projectinfocombogrid',
		urlAction : 'index1.php?model=rdproject_project_rdproject&action=',
		listUrl : 'pageJsonByOnekey',
		listeners : {
			'dblclick' : function(e) { // mydelAll();
				var record = this.getSelectionModel().getSelected();
				// alert(record.get('name'))
				$("#projectName").val(record.get('projectName'));// ��Ŀ����
				$("#projectId").val(record.get('id'));// ��ĿId
				$("#projectCode").val(record.get('projectCode'));// ��Ŀ����
				$("#planName").val('');// �ƻ�����
				$("#planCode").val('');// �ƻ�ID
				$("#planId").val('');// �ƻ�����
				// Ext.get().reload()
//				 $('#belongPlan').show();

				var projectId = record.get('id');
				if (filetree) {
					fileCombo.reset();
					filetree.loader.baseParams['projectId'] = projectId;
					if (filetree.rendered) {
						filetree.root.reload();
					}
				} else {
					filetree = new Ext.ux.tree.MyTree({
						url : '?model=rdproject_uploadfile_uploadfiletype&action=tree&projectId='
								+ projectId + "&parentId=",
						rootId : -1,
						rootVisible : false,
						listeners : {
							click : function(node) {
								uploadfile.addPostParam("typeId", node.id);
								uploadfile.addPostParam("typeName", node.text);
							}
						}
					});
				}
				if (!fileCombo) {
					fileCombo = new Ext.ux.combox.ComboBoxTree({
						emptyText : '��ѡ�񸽼�����...',
						applyTo : 'uploadfiletype',
						hiddenField : 'uploadfiletypeId',
						tree : filetree
					});
				}

				var planGridCmp = Ext.getCmp('planGrid');
				if (planGridCmp) {
					planGridCmp.store.proxy = new Ext.data.HttpProxy({
						url : 'index1.php?model=rdproject_plan_rdplan&action=pageJsonByOnekey&projectId='
								+ projectId
					});
					planGridCmp.store.reload();
				} else {
					planGrid.listUrl = 'pageJsonByOnekey&projectId='
							+ projectId
				}

			}
		}
	};
	var planGrid = {
		id : 'planGrid',
		xtype : 'planinfocombogrid',
		urlAction : 'index1.php?model=rdproject_plan_rdplan&action=',
		listUrl : 'pageJsonByOnekey&projectId=' + prjId,
		listeners : {
			'dblclick' : function(e) { // mydelAll();
				var record = this.getSelectionModel().getSelected();
				$("#planName").val(record.get('planName'));// �ƻ�����
				$("#planId").val(record.get('planId'));// �ƻ�ID
			}
		}
	};

	var plg = new Ext.ux.combox.MyGridComboBox({
		applyTo : 'planName',
		gridName : 'planName',// ���������ʾ������
		gridValue : 'id',
		hiddenFieldId : 'planId',
		myGrid : planGrid
	});
	var pg = new Ext.ux.combox.MyGridComboBox({
		applyTo : 'projectName',
		gridName : 'projectName',// ���������ʾ������
		gridValue : 'id',
		hiddenFieldId : 'projectId',
		myGrid : taskGrid
	});
	if (pg.getValue() != '') {
		var projectId = $("#projectId").val();
		filetree = new Ext.ux.tree.MyTree({
			url : '?model=rdproject_uploadfile_uploadfiletype&action=tree&projectId='
					+ projectId + "&parentId=",
			rootId : -1,
			rootVisible : false,
			listeners : {
				click : function(node) {
					uploadfile.addPostParam("typeId", node.id);
					uploadfile.addPostParam("typeName", node.text);
				}
			}
		});
		fileCombo = new Ext.ux.combox.ComboBoxTree({
			emptyText : '��ѡ�񸽼�����...',
			applyTo : 'uploadfiletype',
			hiddenField : 'uploadfiletypeId',
			tree : filetree
		});
	}

//	var teamGrid = {
//		id : 'teamGrid',
//		xtype : 'teamComboGrid',
//		urlAction : 'index1.php?model=rdproject_team_rdmember&action=',
//		listUrl : 'pageJson&projectId=' + $('#projectId').val(),
//		width : 200,
//		listeners : {
//			'dblclick' : function(e) { // mydelAll();
//				var record = this.getSelectionModel().getSelected();
//				$("#chargeId").val(record.get('chargeId'));// �ƻ�����
//				$("#chargeName").val(record.get('chargeName'));// �ƻ�����
//				mailTo();
//				$('#chargeName').focus();
//			}
//		}
//	};
//	var tg = new Ext.ux.combox.MyGridComboBox({
//		listWidth : 200,
//		applyTo : 'chargeName',
//		gridName : 'memberName',// ���������ʾ������
//		gridValue : 'memberId',
//		hiddenFieldId : 'chargeId',
//		myGrid : teamGrid
//	});

//	var teamsGrid = {
//		id : 'teamGrid',
//		xtype : 'teamComboGrid',
//		selectType : 'check',
//		urlAction : 'index1.php?model=rdproject_team_rdmember&action=',
//		listUrl : 'pageJson&projectId=' + $('#projectId').val(),
//		width : 500,
//		listeners : {
//			'dblclick' : function(e) { // mydelAll();
//			},
//			'click' : function(e){
//				$('#withinActName').focus();
//			}
//		}
//	};
//	var tgs = new Ext.ux.combox.MyGridComboBox({
//		listWidth : 500,
//		applyTo : 'withinActName',
//		gridName : 'memberName',// ���������ʾ������
//		gridValue : 'memberId',
//		hiddenFieldId : 'withinActId',
//		myGrid : teamsGrid
//	});

});
//function arrayCon(arr1, arr2) {
//	for (var i = 0; i < arr1.length; i++) {
//		for (var j = 0; j < arr2.length; j++) {
//			if (arr1[i] == arr2[j] || arr1[i] == '') {
//				arr1.splice(i, 1); // ����splice����ɾ��Ԫ�أ��ӵ�i��λ�ã���ȡ����Ϊ1��Ԫ��
//				i--;
//				break;
//			}
//		}
//	}
//	// alert(arr1.length)
//	for (var i = 0; i < arr2.length; i++) {
//		arr1.push(arr2[i]);
//	}
//	return arr1;
//}
//function actUserFun() {
//	//������id�ַ���
//	var withinId = $('#withinActId').val();
//	var withoutId = $('#withoutActId').val();
//	withinIdArr = withinId.split(',');
//	withoutIdArr = withoutId.split(',');
//	var actUserIdArr = new Array();
//	actUserIdArr = arrayCon(withinIdArr,withoutIdArr);
//	var actUserIdStr = actUserIdArr.join(',');
//
//	//������name�ַ���
//	var withinName = $('#withinActName').val();
//	var withoutName = $('#withoutActName').val();
//	withinNameArr = withinName.split(',');
//	withoutNameArr = withoutName.split(',');
//	var userNameArr = new Array();
//	userNameArr = arrayCon(withinNameArr,withoutNameArr);
//	var userNameStr = userNameArr.join(',');
//
//	$('#actUserId').val(actUserIdStr);
//	$('#userName').val(userNameStr);
//	mailTo();
//}