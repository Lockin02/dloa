$(function() {
	// ����
	$("#savePortlet").click(function() {
				var lastLeft;
				var savePanel = {};
				var saveColNum = 0;
				var baseNum = 0;
				$("[id^='portlet_']").each(function() {
							var panelId = this.id;
							var left = $(this).offset().left;
							if (lastLeft) {
								if (lastLeft != left) {
									baseNum++;
									saveColNum = baseNum;
									lastLeft = left;
								} else {
									saveColNum += colNum;
								}
							} else {
								lastLeft = left;
							}
							savePanel[panelId] = saveColNum;
						});
				$.ajax({
							url : '?model=system_portal_portletuser&action=saveOrder',
							type : "POST",
							data : {
								savePanel : savePanel
							},
							success : function() {
								alert("����ɹ�.");
							}
						});

			});
	// ���
	$("#addPortlet").click(function() {
		var url = "?model=system_portal_portlet&action=selectPortlet";
		var returnValue = showModalDialog(url, '',
				"dialogWidth:800px;dialogHeight:500px;");
		var ids = returnValue.val;
		var portletNames = returnValue.text;

		// �������
		var rIds = $.ajax({
					url : '?model=system_portal_portletuser&action=add',
					async : false,
					type : "POST",
					data : {
						portletIds : ids,
						portletNames : portletNames
					}
				}).responseText;

		var rIdArr = rIds.split(",");

		// ��ȡѡ���portlet���Ż���
		$.ajax({
					url : '?model=system_portal_portlet&action=listJson',
					type : "POST",
					data : {
						ids : ids
					},
					success : function(data) {
						data = eval("(" + data + ")");
						for (var i = 0, l = data.length; i < l; i++) {
							var p = data[i];
							var panel = {
								id : "portlet_" + rIdArr[i],
								title : p.portletName,
								url : p.url,
								height : p.height
							};
							portal.addPanel(panel);
							setColseBt(rIdArr[i]);
						}
					}
				});
	});
	// ����
	$("#setPortlet").click(function() {
		var url = "?model=system_portal_portlet&action=setPortlet";
		var returnValue = showModalDialog(url, '',
				"dialogWidth:800px;dialogHeight:500px;");
		var ids = returnValue.val;
		var portletNames = returnValue.text;
	});

	/** ***********��ť���ý���************************** */

	// ����portlet�رհ�ť����
	var setColseBt = function(panelId) {
		var closeBt = $("#portlet_" + panelId).find("[id=0]");
		closeBt.click(function() {
			return function(panelId) {
				$.ajax({
					url : '?model=system_portal_portletuser&action=ajaxdeletes',
					type : "POST",
					data : {
						id : panelId
					}
				});
			}(panelId)
		});

		//���ϷŴ�ť
		var bigBt=$('<span id="3" class="mini-tools-collapse" ></span>');
		closeBt.before(bigBt);
	}

	// var set = "100%";
	var portal = new mini.ux.Portal();
	// var customizeArr = ["30%", "", "30%"];
	// var colNum = customizeArr.length;
	// var data = array[];//�����еİٷֱ�
	var data = $.ajax({
				url : '?model=system_portal_usercustomize&action=listJson',
				type : "POST",
				async : false
			}).responseText;
	data = eval(data);
	data = data[0].customizeStr;
	var getcol = data.split(",");
	portal.set({
				style : "width: 100%;height:400px",
				columns : getcol
			});
	var colNum = getcol.length;
	portal.render(document.body);

	// ��ȡ��½�û���portlet
	$.ajax({
				url : '?model=system_portal_portletuser&action=getCurUserPortlets',
				type : "POST",
				data : {
					sort : 'portletOrder',
					dir : 'ASC'
				},
				success : function(data) {
					data = eval("(" + data + ")");
					for (var i = 0, l = data.length; i < l; i++) {

						var p = data[i];
						var column = p.portletOrder % colNum;
						var panel = {
							id : "portlet_" + p.id,
							oid : p.id,
							title : p.portletName,
							column : column,
							portletOrder : p.portletOrder,
							height : p.height
						};
						panel=portal.addPanel(panel);
						setColseBt(p.id);
						panel.load ( p.url );
					}

				}
			});

});
