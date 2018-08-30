/** Ext�����ط���**** */

Ext.lib.Ajax.getConnectionObject = function() { // ���ͬ������ 3.x�Ѿ��Ѹ÷���ȥ����...
	var activeX = ['MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP', 'Microsoft.XMLHTTP'];
	function createXhrObject(transactionId) {
		var http;
		try {
			http = new XMLHttpRequest();
		} catch (e) {
			for (var i = 0; i < activeX.length; ++i) {
				try {
					http = new ActiveXObject(activeX[i]);
					break;
				} catch (e) {
				}
			}
		} finally {
			return {
				conn : http,
				tId : transactionId
			};
		}
	}

	var o;
	try {
		if (o = createXhrObject(Ext.lib.Ajax.transactionId)) {
			Ext.lib.Ajax.transactionId++;
		}
	} catch (e) {
	} finally {
		return o;
	}
}
// ----------

Ext.applyIf(Array.prototype, {
			indexRecordOf : function(o) {
				for (var i = 0, len = this.length; i < len; i++) {
					if (this[i].data.id == o.data.id)
						return i;
				}
				return -1;
			},
			/**
			 * ��������Ϊrecord
			 */
			removeRecord : function(o) {
				var index = this.indexRecordOf(o);
				if (index != -1) {
					this.splice(index, 1);
				}
				return this;
			}
		});

/** ****Ext���ط�������******* */

/**
 * ��ȡ��ǰʱ�䲢���õ�һ���ؼ��ϣ��ö�̬���¶���ʱ��
 */
function getCurrentTime() {
	Ext.getCmp('curTime').setValue(new Date().toLocaleString() + ' ����'
			+ '��һ����������'.charAt(new Date().getDay()));
}

/**
 * �����ά�����ֵ���ض�ά����ڶ���Ԫ�أ���һ����ֵ���ڶ�������ʾֵ��һ�����ھ�̬�����ֵ䣩
 * 
 * @param {}
 *            val
 * @param {}
 *            arr
 * @return {}
 */
var arrFunction = function(val, arr) {
	return arr[val - 1][1];
}

/**
 * ���ڼ���
 * 
 * @param {}
 *            type ����
 * @param {}
 *            NumDay ���
 * @param {}
 *            vdate ����
 * @return {}
 */
function addDate(type, NumDay, vdate) {
	var date = new Date(vdate);
	type = parseInt(type) // ����
	var lIntval = parseInt(NumDay)// ���
	switch (type) {
		case 6 :// ��
			date.setYear(date.getYear() + lIntval)
			break;
		case 7 :// ����
			date.setMonth(date.getMonth() + (lIntval * 3))
			break;
		case 5 :// ��
			date.setMonth(date.getMonth() + lIntval)
			break;
		case 4 :// ��
			date.setDate(date.getDate() + lIntval)
			break
		case 3 :// ʱ
			date.setHours(date.getHours() + lIntval)
			break
		case 2 :// ��
			date.setMinutes(date.getMinutes() + lIntval)
			break
		case 1 :// ��
			date.setSeconds(date.getSeconds() + lIntval)
			break;
		default :

	}
	return date;
}

/**
 * ͳһ�Ĵ���ص�����
 * 
 * @param {}
 *            form
 * @param {}
 *            action
 */
var doFailure = function(form, action) {
	var message = null;
	if (action == null)
		return;
	if (action.message)
		message = action.message;
	else if (action.result.message)
		message = action.result.message;

	Ext.Msg.info({
				success : false,
				message : message ? message : '������δ��Ӧ�����Ժ����ԣ�'
			});
}

var extUtil = {
	getJson : function(data) {
		return eval("(" + data + ")")
	},
	objectToJson : function(object) {// ת������Ϊjson�ַ���
		Class.forName("Ext.util.JSON");
		return Ext.encode(object);
	},
	htmlDecode : function(A) {
		var B = String(A).replace(/&amp;/g, "&").replace(/&gt;/g, ">").replace(
				/&lt;/g, "<").replace(/&quot;/g, '"');
		B = B.replace(/\n/g, "<br/>");
		return !A ? A : B
	},
	markInvalid : function(A, C) {
		for (var B = 0; B < A.getCount(); B++) {
			if (C.fieldErrors[A.itemAt(B).name]) {
				A.itemAt(B).markInvalid(C.fieldErrors[A.itemAt(B).name])
			} else {
				if (C.fieldErrors[A.itemAt(B).id]) {
					A.itemAt(B).markInvalid(C.fieldErrors[A.itemAt(B).id])
				}
			}
			if (A.itemAt(B).items) {
				this.markInvalid(A.itemAt(B).items, C)
			}
		}
	},
	renderDate : function(val) {
		// if (Ext.util.Format) {
		// return Ext.util.Format.date(B, A)
		// }
		var d = new Date(val);
		return d.format('Y-m-d');
	},
	renderTime : function(val) {
		var d = new Date(val);
		return d.format('Y-m-d H i s');
	},
	renderFile : function(A) {
		if (A === true) {
			return '<img src="images/icons/file.gif" align="center" valign="absmiddle"/>'
		} else {
			return ""
		}
	},
	renderGender : function(A) {
		if ((true === A) || ("true" === A)) {
			return "��"
		} else {
			if ((false === A) || ("false" === A)) {
				return "Ů"
			}
		}
	},
	renderOfferState : function(A) {
		for (var C = 0; C < SliverData.offerState.length; C++) {
			for (var B = 0; B < SliverData.offerState[C].length; B++) {
				if (A === SliverData.offerState[C][1]) {
					return SliverData.offerState[C][0]
				}
			}
		}
	},
	renderOrderState : function(A) {
		for (var C = 0; C < SliverData.orderState.length; C++) {
			for (var B = 0; B < SliverData.orderState[C].length; B++) {
				if (A === SliverData.orderState[C][1]) {
					return SliverData.orderState[C][0]
				}
			}
		}
	},
	gridCnMoney : function(A) {
		A = (Math.round((A - 0) * 100)) / 100;
		A = (A == Math.floor(A)) ? A + ".00" : ((A * 10 == Math.floor(A * 10))
				? A + "0"
				: A);
		A = String(A);
		var E = A.split(".");
		var D = E[0];
		var B = E[1] ? "." + E[1] : ".00";
		var C = /(\d+)(\d{3})/;
		while (C.test(D)) {
			D = D.replace(C, "$1,$2")
		}
		A = D + B;
		if (A.charAt(0) == "-") {
			return '-��' + A.substr(1)
		}
		return '��' + A
	},
	cnMoney : function(A) {
		A = (Math.round((A - 0) * 100)) / 100;
		A = (A == Math.floor(A)) ? A + ".00" : ((A * 10 == Math.floor(A * 10))
				? A + "0"
				: A);
		A = String(A);
		var E = A.split(".");
		var D = E[0];
		var B = E[1] ? "." + E[1] : ".00";
		var C = /(\d+)(\d{3})/;
		while (C.test(D)) {
			D = D.replace(C, "$1,$2")
		}
		A = D + B;
		if (A.charAt(0) == "-") {
			return "-��" + A.substr(1)
		}
		return "��" + A
	},
	weekOfDate : function(B, C) {
		if (typeof C === "number") {
			var A = new Date(B);
			A.setDate(A.getDate() + C - A.getDay());
			return A
		} else {
			return null
		}
	},
	yesOrNoArr : [{
				dataName : '��',
				dataCode : 1
			}, {
				dataName : '��',
				dataCode : 0
			}],
	yesOrNo : function(val) {
		return val == 1 || true ? '��' : '��';
	},
	toolTip : function(val, metadata) {
		if (metadata)
			metadata.attr = 'style="white-space:normal;"';
		if (val)
			return '<span ext:qtip="' + val + '">'
					+ (val.length > 30 ? val.substring(0, 30) : val)
					+ '</span>';
	},
	gridDataDic : function(val, arr) {
		for (var i = 0; i < arr.length; i++) {
			if (arr[i].dataCode == val) {
				return arr[i].dataName;
				break;
			}
		}
		return '';
	},
	gridProvince : function(val, arr) {
		for (var i = 0; i < arr.length; i++) {
			if (arr[i].id == val) {
				return arr[i].provinceName;
				break;
			}
		}
		return '';
	},
	gridManager : function(val, arr) {
		for (var i = 0; i < arr.length; i++) {
			if (arr[i].id == val) {
				return arr[i].teamMemberName;
				break;
			}
		}
		return '';
	},
	gridAptitude : function(val, arr) {
		for (var i = 0; i < arr.length; i++) {
			if (arr[i].id == val) {
				return arr[i].typeName;
				break;
			}
		}
		return '';
	},
	changeCs : function(cs, val, fn) {
		val = fn ? '<b>' + val + '<b>' : val;
		return cs ? "<span style='color:" + cs + ";'>" + val + "</span>" : val;
	},
	warColorArray : [{
				dataCode : 1,
				dataName : '��ɫ',
				dataCss : 'red'
			}, {
				dataCode : 2,
				dataName : '���ɫ',
				dataCss : '#6C0404'
			}, {
				dataCode : 3,
				dataName : '��ɫ',
				dataCss : '#418848'
			}, {
				dataCode : 4,
				dataName : '��ɫ',
				dataCss : '#FFFC00'
			}, {
				dataCode : 5,
				dataName : '��ɫ',
				dataCss : '#9C6ACA'
			}]

};



/*
 * ��javascript������չ����[Function,Date,String,Number,Array]
 * 
 * Copyright (C) 2009 Ext Technology �㶫ͬ���Ƽ��ɷ����޹�˾ (http://www.Ext.com.cn)
 * 
 * @requires Ext.js @author chenjs, chenjs@Ext.com.cn
 */

/**
 * @class Function ��ԭ����JavaScript����ԭ�ͽ��й�����չ����չ�Ĺ��ܿ���������һ������
 */
Ext.apply(Function.prototype, {

			/**
			 * <p>
			 * ����һ���µĺ������µĺ�����ԭ�����Ļ��������һ��<b>������</b>���º���������ʱ���������������false����ִ��ԭ����������������true�����ִ��ԭ������
			 * </p>
			 * <p>
			 * <b>ע�⣺</b>ԭ������û�б��ı䡣
			 * </p>
			 * <p>
			 * ���Ǻ�����AOPʵ�֡�
			 * </p>
			 * 
			 * @addon
			 * @param {Function}
			 *            fn ������
			 * @param {Object}
			 *            scope (��ѡ��) ��������ִ��������Ĭ�ϵ���������ԭ������������
			 * @return {Function} һ���µĺ���
			 */
			createInterceptor : function(fn, scope) {
				if (typeof fn != "function") {
					return this;
				}
				var method = this;
				return function() {
					fn.target = this;
					fn.method = method;
					if (fn.apply(scope || this || window, arguments) === false) {
						return;
					}
					return method.apply(this || window, arguments);
				};
			},

			/**
			 * <p>
			 * ����һ���µĺ������µĺ�����ԭ�����Ļ��������һ��<b>���ú���</b>�����ú����Ĳ����б��ԭ����һ�������ú����ķ���ֵ���ᱻ����
			 * </p>
			 * <p>
			 * <b>ע�⣺</b>ԭ������û�б��ı䡣
			 * </p>
			 * <p>
			 * ���Ǻ�����AOPʵ�֡�
			 * </p>
			 * 
			 * @param {Function}
			 *            fn ���ú���
			 * @param {Object}
			 *            scope (��ѡ��) ���ú�����ִ��������Ĭ�ϵ���������ԭ������������
			 * @return {Function} һ���µĺ���
			 */
			createSequence : function(fn, scope) {
				if (typeof fn != "function") {
					return this;
				}
				var method = this;
				return function() {
					var retval = method.apply(this || window, arguments);
					fn.apply(scope || this || window, arguments);
					return retval;
				};
			},
			/**
			 * ����һ���µĺ������µĺ����ĺ������ԭ����һ�������ǲ����б��Ϊ�Զ���Ĳ����б��º����൱��һ��<b>�ص�����</b>��
			 * 
			 * @param {mixed}
			 *            args �º����Ĳ���
			 * @return {Function} һ���µĺ���
			 */
			createCallback : function(/* args... */) {
				// make args available, in function below
				var args = arguments;
				var method = this;
				return function() {
					return method.apply(window, args);
				};
			},

			/**
			 * ����һ���µĺ������µĺ����൱�ڽ�ԭ����ί�ɵ��µ���������ִ�У�ͬʱ�����б�����޸ġ�
			 * 
			 * @param {Object}
			 *            obj ����ѡ�ģ��µ�������
			 * @param {Array}
			 *            args ����ѡ�ģ����ڸ���ԭ�����Ĳ���
			 * @param {Boolean/Number}
			 *            appendArgs ����ѡ�ģ����Ϊ�棬��ڶ������� args
			 *            �����ڸ���ԭ�����Ĳ���������׷�ӵ�ԭ�����б����
			 * @return {Function} һ���µĺ���
			 */
			createDelegate : function(obj, args, appendArgs) {
				var method = this;
				return function() {
					var callArgs = args || arguments;
					if (appendArgs === true) {
						callArgs = Array.prototype.slice.call(arguments, 0);
						callArgs = callArgs.concat(args);
					} else if (typeof appendArgs == "number") {
						callArgs = Array.prototype.slice.call(arguments, 0); // copy
						// arguments
						// first
						var applyArgs = [appendArgs, 0].concat(args); // create
						// method
						// call
						// params
						Array.prototype.splice.apply(callArgs, applyArgs); // splice
						// them
						// in
					}
					return method.apply(obj || window, callArgs);
				};
			},
			/**
			 * �������ӳ�ִ�У�����ʵ��ʹ����������Ըı亯����ִ��������Ͳ����б�
			 * 
			 * @param {Number}
			 *            millis �ӳٺ����������Ϊ0��������ִ��
			 * @param {Object}
			 *            obj ����ѡ�ģ��µ�������
			 * @param {Array}
			 *            args ����ѡ�ģ����ڸ���ԭ�����Ĳ���
			 * @param {Boolean/Number}
			 *            appendArgs ����ѡ�ģ����Ϊ�棬��ڶ������� args
			 *            �����ڸ���ԭ�����Ĳ���������׷�ӵ�ԭ�����б����
			 * @return {Number} �ӳ�ִ�д����ID�����ֵ������clearTimeout
			 */
			defer : function(millis, obj, args, appendArgs) {
				var fn = this.createDelegate(obj, args, appendArgs);
				if (millis) {
					return setTimeout(fn, millis);
				}
				fn();
				return 0;
			}
		});

/**
 * @class Array ��ԭ����JavaScript����ԭ�ͽ��й�����չ����չ�Ĺ��ܿ���������һ������
 */
Ext.applyIf(Array.prototype, {

			/**
			 * ����ĳ��Ԫ����������ƥ��ĵ�һ��λ�ã�����Ҳ������򷵻�-1����Ԫ����һ�������������ͣ�������Ǿ���һ�£�===�������ҵ���
			 * 
			 * @param {Mixed}
			 *            elt Ҫ���ҵ�Ԫ��
			 * @param {Number}
			 *            from ��ʼ����λ�ã�Ĭ��Ϊ0
			 * @return {Number} Ԫ���������е�λ�ã�����Ҳ������򷵻�-1
			 */
			indexOf : function(elt, from) {
				var len = this.length;
				var from = Number(arguments[1]) || 0;
				from = (from < 0) ? Math.ceil(from) : Math.floor(from);
				if (from < 0)
					from += len;
				for (; from < len; from++) {
					if (from in this && this[from] === elt)
						return from;
				}
				return -1;
			},

			/**
			 * ����ĳ��Ԫ����������ƥ������һ��λ�ã�����Ҳ������򷵻�-1����Ԫ����һ�������������ͣ�������Ǿ���һ�£�===�������ҵ���
			 * 
			 * @param {Mixed}
			 *            elt Ҫ���ҵ�Ԫ��
			 * @param {Number}
			 *            from ��ʼ����λ�ã�Ĭ��Ϊ0
			 * @return {Number} Ԫ���������е�λ�ã�����Ҳ������򷵻�-1
			 */
			lastIndexOf : function(elt, from) {
				var len = this.length;
				var from = Number(arguments[1]);
				if (isNaN(from)) {
					from = len - 1;
				} else {
					from = (from < 0) ? Math.ceil(from) : Math.floor(from);
					if (from < 0)
						from += len;
					else if (from >= len)
						from = len - 1;
				}
				for (; from > -1; from--) {
					if (from in this && this[from] === elt)
						return from;
				}
				return -1;
			},

			/**
			 * ��֤������������飬��ÿһ���������<b>�ȽϺ���</b>ִ�У������һ����������ʹ�ñȽϺ�������true����ú�������true��ע���{@link #every}����������
			 * 
			 * @param {Function}
			 *            fn �ȽϺ���������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope �ȽϺ���ִ�е�������
			 * @return {Boolean} �з��ϱȽϺ����ıȽϹ�����������򷵻�true�����򷵻�false
			 */
			some : function(fn, scope) {
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				for (var i = 0; i < len; i++) {
					if (i in this && fn.call(scope || window, this[i], i, this))
						return true;
				}
				return false;
			},

			/**
			 * ӳ��ת��������������飬��ÿһ��������ͨ��<b>ӳ�亯��</b>����ӳ��ת������������鷵�ء�ԭ���鲻��ı䡣
			 * 
			 * @param {Function}
			 *            fn ӳ�亯��������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope ӳ�亯��ִ�е�������
			 * @return {Array} ����ӳ�亯��ת����������
			 */
			map : function(fn, scope) {
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				var res = new Array(len);
				for (var i = 0; i < len; i++) {
					if (i in this)
						res[i] = fn.call(scope || window, this[i], i, this);
				}
				return res;
			},

			/**
			 * �������飬������ͨ��<b>���˺���</b>���й��ˣ���������������������µ����鷵�ء�ԭ���鲻��ı䡣
			 * 
			 * @param {Function}
			 *            fn ���˺��������Ϲ��������򷵻�true������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope ���˺���ִ�е�������
			 * @return {Array} �������˵õ���������
			 */
			filter : function(fn, scope) {
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				var res = new Array();
				for (var i = 0; i < len; i++) {
					if (i in this) {
						var val = this[i]; // in case fun mutates this
						if (fn.call(scope || window, val, i, this))
							res.push(val);
					}
				}
				return res;
			},

			/**
			 * ��֤������������飬���ÿһ�����������<b>�ȽϺ���</b>��Ҫ�󣨼��ȽϺ�������true������ú�������true��ע���{@link #some}����������
			 * 
			 * @param {Function}
			 *            fn �ȽϺ���������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope �ȽϺ���ִ�е�������
			 * @return {Boolean} �����е�ÿһ����������ϱȽϺ����ıȽϹ����򷵻�true�����򷵻�false
			 */
			every : function(fn, scope) {
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				var thisp = arguments[1];
				for (var i = 0; i < len; i++) {
					if (i in this && !fn.call(thisp, this[i], i, this))
						return false;
				}
				return true;
			},

			/**
			 * �������飬��ÿһ��������������������λ����Ϊ��������<b>�ص�����</b>ִ�С�
			 * 
			 * @param {Function}
			 *            fn �ص�����������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope �ص�����ִ�е�������
			 * @return {}
			 */
			forEach : function(fn, scope) {
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				for (var i = 0; i < len; i++) {
					if (i in this)
						fn.call(scope || window, this[i], i, this);
				}
			},

			/**
			 * ��������ɾ��ָ����������
			 * 
			 * @param {Object}
			 *            o Ҫɾ����������
			 * @return {Array} ���鱾��
			 */
			remove : function(o) {
				var index = this.indexOf(o);
				if (index != -1) {
					this.splice(index, 1);
				}
				return this;
			},

			/**
			 * ����ȥ���ظ�Ԫ��
			 * 
			 * @return {}
			 */
			strip : function() {
				var tmp = [];
				var length = this.length;
				for (var i = 0; i < length; i++) {
					var push = true;
					for (var j = i + 1; j < length; j++) {
						// alert(this[j].id+"----"+this[i].id)
						if (this[j] === this[i] || this[j].id == this[i].id
								|| this[j].data.id == this[i].data.id) {
							push = false;
							break;
						}
					}
					if (push) {
						tmp.push(this[i])
					}
				}
				this.length = tmp.length;
				for (var i = 0; i < tmp.length; i++) {
					this[i] = tmp[i];
				}
				return tmp;
			}
		});

/**
 * @class String ��ԭ����JavaScript�ַ���ԭ�ͽ��й�����չ����չ�Ĺ��ܿ���������һ������
 */
Ext.applyIf(String, {

			/**
			 * �������ַ����е� ' �� \ �������ַ�����ת��
			 * 
			 * @param {String}
			 *            string Ҫת����ַ���
			 * @return {String} ����ת����ַ���
			 * @static
			 */
			escape : function(string) {
				return string.replace(/('|\\)/g, "\\$1");
			},

			/**
			 * ���ַ�������߲���ָ�����ַ� ʹ�����ӣ�
			 * 
			 * <pre><code>
			 * var s = String.leftPad('123', 5, '0');// '00123'
			 * </code></pre>
			 * 
			 * @param {String}
			 *            string ԭʼ�ַ���
			 * @param {Number}
			 *            size �Զ��ַ��ظ��Ĵ���
			 * @param {String}
			 *            char ����ѡ�ģ�ָ�����ַ���Ĭ��Ϊ���ַ����� ��
			 * @return {String} ����ָ���ַ�������ַ���
			 * @static
			 */
			leftPad : function(val, size, ch) {
				var result = new String(val);
				if (ch === null || ch === undefined || ch === '') {
					ch = " ";
				}
				while (result.length < size) {
					result = ch + result;
				}
				return result;
			},

			/**
			 * �ַ�����ʽ�������ַ����еĴ��� {0}, {1}...�滻Ϊ��Ӧ�����б�λ���е�ֵ ʹ�����ӣ�
			 * 
			 * <pre><code>
			 * var cls = 'my-class', text = 'Some text';
			 * var s = String.format('&lt;div class=&quot;{0}&quot;&gt;{1}&lt;/div&gt;', cls, text);
			 * // '&lt;div class=&quot;my-class&quot;&gt;Some text&lt;/div&gt;'
			 * </code></pre>
			 * 
			 * @param {String}
			 *            string
			 * @param {String}
			 *            value1 �����������{0}��ֵ
			 * @param {String}
			 *            value2 ������
			 * @return {String} ��ʽ�������ַ���
			 * @static
			 */
			format : function(format) {
				var args = Array.prototype.slice.call(arguments, 1);
				return format.replace(/\{(\d+)\}/g, function(m, i) {
							return args[i];
						});
			}
		});

Ext.applyIf(String.prototype, {

			/**
			 * ���ַ���������ֵ֮���л�������ַ����ʹ���ĵ�һ��ֵ��ȣ��򷵻���һ��ֵ�����ȣ��򷵻ص�һ��ֵ����ǰ�ַ�����û�б��ı�
			 * 
			 * <pre><code>
			 * // �����л�������
			 * sort = sort.toggle('ASC', 'DESC');
			 * 
			 * // ����������������߼�
			 * sort = (sort == 'ASC' ? 'DESC' : 'ASC');
			 * </code></pre>
			 * 
			 * @param {String}
			 *            value �����͵�ǰ�ַ����Ƚϵ�ֵ
			 * @param {String}
			 *            ��һ��ֵ
			 * @return {String} �����߼���ֵ
			 */
			toggle : function(value, other) {
				return this == value ? other : value;
			},

			/**
			 * �ص���ǰ�ַ�����ͷ�Ŀհס� ʹ������:
			 * 
			 * <pre><code>
			 * var s = '  foo bar  ';
			 * alert('-' + s + '-'); //alerts &quot;- foo bar -&quot;
			 * alert('-' + s.trim() + '-'); //alerts &quot;-foo bar-&quot;
			 * </code></pre>
			 * 
			 * @return {String} ��ȡ�����ַ���
			 */
			trim : function() {
				var re = /^\s+|\s+$/g;
				return function() {
					return this.replace(re, "");
				}
			}

		});

/**
 * @class Number ��ԭ����JavaScript����ԭ�ͽ��й�����չ����չ�Ĺ��ܿ���������һ������
 */
Ext.applyIf(Number.prototype, {
			/**
			 * ����ֵ�����ڸ�����[min,max]֮�䣬����ڸ��������䣬�򷵻��������С��min���򷵻�min���������max���򷵻�max
			 * 
			 * @param {Number}
			 *            min �������Сֵ
			 * @param {Number}
			 *            max ��������ֵ
			 * @return {Number} �����ڵ�ֵ
			 */
			constrain : function(min, max) {
				return Math.min(Math.max(this, min), max);
			}
		});
/*
 * ���ڽ����͸�ʽ��������
 * 
 * Copyright (C) 2009 Ext Technology �㶫ͬ���Ƽ��ɷ����޹�˾ (http://www.Ext.com.cn)
 * 
 * @requires Ext.js @author chenjs, chenjs@Ext.com.cn
 */

/**
 * @class Date
 * 
 * ʵ��һ�����ڽ����͸�ʽ���ĺ�����������java��Date�ࡣ
 * 
 * <p>
 * �����б��ǵ�ǰ֧�ֵ����ڸ�ʽ:
 * </p>
 * 
 * <pre>
 *  ��ʽ    ����	                                                        ������ֵ
 *  ------  ------------------------------------------------------      -----------------------
 *  d       �·ݵ����ڣ���λ�ĸ�ʽ�����С��10���һλ��0                  01 - 31
 *  D       һ�ܵ�Ӣ����д����λ�ĸ�ʽ                                    Mon - Sun
 *  j       �·ݵ����ڣ���λ������                                        1 - 31
 *  l       һ�ܵ�����Ӣ��                                                Sunday - Saturday
 *  N       һ�ܵ� ISO-8601��׼�����ָ�ʽ��ʾ	                             1 (��һ) - 7 (����)
 *  S       Ӣ���еڼ������дǰ׺�������ַ�                               st, nd, rd �� th. 
 *  w       һ�ܵ����ֱ�ʾ                                                0 (����) - 6 (����)
 *  z       һ��ĵڼ��죨��0��ʼ��                                        0 - 364 (���굽365)
 *  W       һ���ISO-8601��׼�ĵڼ��ܣ��ܴ���һ��ʼ                       01 - 53
 *  F       �·ݵ�����Ӣ�ı�ʾ                                            January - December
 *  m       �·ݵ����ֱ�ʾ����λ�ĸ�ʽ�����С��10���һλ��0               01 - 12
 *  M       �·ݵ���дӢ�ı�ʾ����λ�ĸ�ʽ                                 Jan - Dec
 *  n       �·ݵ����ֱ�ʾ����λ������                                     1 - 12
 *  t       һ���µĿ�������                                               28 - 31
 *  L       ĳ���Ƿ�����                                                   ����Ϊ1������Ϊ0
 *  o       ISO-8601��׼��ʾ������֡��ͣ�Y�����ƣ�                         ����: 1998 �� 2004
 *         ��������������ڵ���������һ�꣬��ȡ��һ��
 *  Y       ����������ֱ�ʾ, 4λ����	                                      ����: 1999 �� 2003
 *  y       �����λ���ֱ�ʾ	                                              ����: 99 �� 03
 *  a       Сд�����硢�����ʾ                                            am �� pm
 *  A       ��д�����硢�����ʾ                                            AM �� PM
 *  g       12Сʱ�Ƶı�ʾ����λ������                                      1 - 12
 *  G       24Сʱ�Ƶı�ʾ����λ������                                      0 - 23
 *  h       12Сʱ�Ƶı�ʾ�����С��10���һλ��0                            01 - 12
 *  H       24Сʱ�Ƶı�ʾ�����С��10���һλ��0                            00 - 23
 *  i       ���ӵı�ʾ��ǰ׺����                                            00 - 59
 *  s       ��ı�ʾ��ǰ׺����                                              00 - 59
 *  u       ����ı�ʾ��ǰ׺����                                            001 - 999
 *  O       �͸�������ʱ�䣨GMT���Ĳ�࣬Сʱ�ͷ�                            ���磺+1030
 *  P       �͸�������ʱ�䣨GMT���Ĳ�࣬Сʱ�ͷ�֮����ð��                   ���磺-08:00
 *  T       ʱ����д                                                        ���磺EST, MDT, PDT ...
 *  Z       ʱ�������������ʾ��(UTC������Ϊ��������Ϊ��                      -43200 - 50400
 *  c       ISO 8601��׼�����ڸ�ʽ                                           2009-10-24T15:19:21+08:00
 *  U       ��Unix��Ԫ��ʼ����ǰ������ (January 1 1970 00:00:00 GMT)         1193432466 or -2138434463
 * </pre>
 * 
 * ʹ�����ӣ�
 * 
 * <pre><code>
 * 
 * var dt = new Date('10/24/2009 03:05:01 PM GMT-0600');
 * document.write(dt.format('Y-m-d')); // 2009-10-24
 * document.write(dt.format('F j, Y, g:i a')); // October 24, 2009, 3:05 pm
 * document.write(dt.format('l, \\t\\he jS of F Y h:i:s A')); // Saturday, the 24th of October 2009 03:05:01 PM
 * </code></pre>
 * 
 * ������һЩ���õı�׼����/ʱ��ģ�塣
 * 
 * <pre><code>
 * Date.patterns = {
 * 	ISO8601Long : &quot;Y-m-d H:i:s&quot;,
 * 	ISO8601Short : &quot;Y-m-d&quot;,
 * 	ShortDate : &quot;n/j/Y&quot;,
 * 	LongDate : &quot;l, F d, Y&quot;,
 * 	FullDateTime : &quot;l, F d, Y g:i:s A&quot;,
 * 	MonthDay : &quot;F d&quot;,
 * 	ShortTime : &quot;g:i A&quot;,
 * 	LongTime : &quot;g:i:s A&quot;,
 * 	SortableDateTime : &quot;Y-m-d\\TH:i:s&quot;,
 * 	UniversalSortableDateTime : &quot;Y-m-d H:i:sO&quot;,
 * 	YearMonth : &quot;F, Y&quot;
 * };
 * </code></pre>
 * 
 * ʹ�����ӣ�
 * 
 * <pre><code>
 * var dt = new Date();
 * document.write(dt.format(Date.patterns.ShortDate));
 * </code></pre>
 */

// private
Date.parseFunctions = {
	count : 0
};
// private
Date.parseRegexes = [];
// private
Date.formatFunctions = {
	count : 0
};

// private
Date.prototype.dateFormat = function(format) {
	if (Date.formatFunctions[format] == null) {
		Date.createNewFormat(format);
	}
	var func = Date.formatFunctions[format];
	return this[func]();
};

/**
 * ���ݸ����ĸ�ʽģ������ʽ�������ַ�����
 * 
 * @param {String}
 *            format ��ʽģ��
 * @return {String} ������ʽ��������
 * @method
 */
Date.prototype.format = Date.prototype.dateFormat;

// private
Date.createNewFormat = function(format) {
	var funcName = "format" + Date.formatFunctions.count++;
	Date.formatFunctions[format] = funcName;
	var code = "Date.prototype." + funcName + " = function(){return ";
	var special = false;
	var ch = '';
	for (var i = 0; i < format.length; ++i) {
		ch = format.charAt(i);
		if (!special && ch == "\\") {
			special = true;
		} else if (special) {
			special = false;
			code += "'" + String.escape(ch) + "' + ";
		} else {
			code += Date.getFormatCode(ch);
		}
	}
	eval(code.substring(0, code.length - 3) + ";}");
};

// private
Date.getFormatCode = function(character) {
	switch (character) {
		case "d" :
			return "String.leftPad(this.getDate(), 2, '0') + ";
		case "D" :
			return "Date.getShortDayName(this.getDay()) + "; // get L10n
			// short day
			// name
		case "j" :
			return "this.getDate() + ";
		case "l" :
			return "Date.dayNames[this.getDay()] + ";
		case "N" :
			return "(this.getDay() ? this.getDay() : 7) + ";
		case "S" :
			return "this.getSuffix() + ";
		case "w" :
			return "this.getDay() + ";
		case "z" :
			return "this.getDayOfYear() + ";
		case "W" :
			return "String.leftPad(this.getWeekOfYear(), 2, '0') + ";
		case "F" :
			return "Date.monthNames[this.getMonth()] + ";
		case "m" :
			return "String.leftPad(this.getMonth() + 1, 2, '0') + ";
		case "M" :
			return "Date.getShortMonthName(this.getMonth()) + "; // get L10n
			// short
			// month
			// name
		case "n" :
			return "(this.getMonth() + 1) + ";
		case "t" :
			return "this.getDaysInMonth() + ";
		case "L" :
			return "(this.isLeapYear() ? 1 : 0) + ";
		case "o" :
			return "(this.getFullYear() + (this.getWeekOfYear() == 1 && this.getMonth() > 0 ? +1 : (this.getWeekOfYear() >= 52 && this.getMonth() < 11 ? -1 : 0))) + ";
		case "Y" :
			return "this.getFullYear() + ";
		case "y" :
			return "('' + this.getFullYear()).substring(2, 4) + ";
		case "a" :
			return "(this.getHours() < 12 ? 'am' : 'pm') + ";
		case "A" :
			return "(this.getHours() < 12 ? 'AM' : 'PM') + ";
		case "g" :
			return "((this.getHours() % 12) ? this.getHours() % 12 : 12) + ";
		case "G" :
			return "this.getHours() + ";
		case "h" :
			return "String.leftPad((this.getHours() % 12) ? this.getHours() % 12 : 12, 2, '0') + ";
		case "H" :
			return "String.leftPad(this.getHours(), 2, '0') + ";
		case "i" :
			return "String.leftPad(this.getMinutes(), 2, '0') + ";
		case "s" :
			return "String.leftPad(this.getSeconds(), 2, '0') + ";
		case "u" :
			return "String.leftPad(this.getMilliseconds(), 3, '0') + ";
		case "O" :
			return "this.getGMTOffset() + ";
		case "P" :
			return "this.getGMTOffset(true) + ";
		case "T" :
			return "this.getTimezone() + ";
		case "Z" :
			return "(this.getTimezoneOffset() * -60) + ";
		case "c" :
			for (var df = Date.getFormatCode, c = "Y-m-dTH:i:sP", code = "", i = 0, l = c.length; i < l; ++i) {
				var e = c.charAt(i);
				code += e == "T" ? "'T' + " : df(e); // treat T as a literal
			}
			return code;
		case "U" :
			return "Math.round(this.getTime() / 1000) + ";
		default :
			return "'" + String.escape(character) + "' + ";
	}
};

/**
 * ʹ��ָ����ʽģ�������������ַ������������ַ�����ÿһ���������ָ��������Ĭ�ϵġ� ʱ�䲿��Ҳ����ָ����Ĭ��Ϊ0��
 * ���뱣֤����������ַ���Ҫ��ȷ��ƥ���ʽģ�壬�������������� ʹ�����ӣ�
 * 
 * <pre><code>
 * //dt = 2009-10-24
 * var dt = new Date();
 * 
 * //dt = Thu May 25 2006 (today's month/day in 2006)
 * dt = Date.parseDate(&quot;2006&quot;, &quot;Y&quot;);
 * 
 * //dt = Sun Jan 15 2006 (all date parts specified)
 * dt = Date.parseDate(&quot;2006-01-15&quot;, &quot;Y-m-d&quot;);
 * 
 * //dt = Sun Jan 15 2006 15:20:01 GMT-0600 (CST)
 * dt = Date.parseDate(&quot;2006-01-15 3:20:01 PM&quot;, &quot;Y-m-d h:i:s A&quot;);
 * </code></pre>
 * 
 * @param {String}
 *            input ��û�����������ַ���
 * @param {String}
 *            format ��ʽ
 * @return {Date} ���������õ�������
 * @static
 */
Date.parseDate = function(input, format) {
	if (Date.parseFunctions[format] == null) {
		Date.createParser(format);
	}
	var func = Date.parseFunctions[format];
	return Date[func](input);
};

// private
Date.createParser = function(format) {
	var funcName = "parse" + Date.parseFunctions.count++;
	var regexNum = Date.parseRegexes.length;
	var currentGroup = 1;
	Date.parseFunctions[format] = funcName;

	var code = "Date."
			+ funcName
			+ " = function(input){\n"
			+ "var y = -1, m = -1, d = -1, h = -1, i = -1, s = -1, ms = -1, o, z, u, v;\n"
			+ "var d = new Date();\n" + "y = d.getFullYear();\n"
			+ "m = d.getMonth();\n" + "d = d.getDate();\n"
			+ "var results = input.match(Date.parseRegexes[" + regexNum
			+ "]);\n" + "if (results && results.length > 0) {";
	var regex = "";

	var special = false;
	var ch = '';
	for (var i = 0; i < format.length; ++i) {
		ch = format.charAt(i);
		if (!special && ch == "\\") {
			special = true;
		} else if (special) {
			special = false;
			regex += String.escape(ch);
		} else {
			var obj = Date.formatCodeToRegex(ch, currentGroup);
			currentGroup += obj.g;
			regex += obj.s;
			if (obj.g && obj.c) {
				code += obj.c;
			}
		}
	}

	code += "if (u)\n"
			+ "{v = new Date(u * 1000);}" // give top priority to UNIX time
			+ "else if (y >= 0 && m >= 0 && d > 0 && h >= 0 && i >= 0 && s >= 0 && ms >= 0)\n"
			+ "{v = new Date(y, m, d, h, i, s, ms);}\n"
			+ "else if (y >= 0 && m >= 0 && d > 0 && h >= 0 && i >= 0 && s >= 0)\n"
			+ "{v = new Date(y, m, d, h, i, s);}\n"
			+ "else if (y >= 0 && m >= 0 && d > 0 && h >= 0 && i >= 0)\n"
			+ "{v = new Date(y, m, d, h, i);}\n"
			+ "else if (y >= 0 && m >= 0 && d > 0 && h >= 0)\n"
			+ "{v = new Date(y, m, d, h);}\n"
			+ "else if (y >= 0 && m >= 0 && d > 0)\n"
			+ "{v = new Date(y, m, d);}\n"
			+ "else if (y >= 0 && m >= 0)\n"
			+ "{v = new Date(y, m);}\n"
			+ "else if (y >= 0)\n"
			+ "{v = new Date(y);}\n"
			+ "}return (v && (z || o))?\n" // favour UTC offset over GMT offset
			+ "    (z ? v.add(Date.SECOND, (v.getTimezoneOffset() * 60) + (z*1)) :\n" // reset
			// to
			// UTC,
			// then
			// add
			// offset
			+ "        v.add(Date.HOUR, (v.getGMTOffset() / 100) + (o / -100))) : v\n" // reset
			// to
			// GMT,
			// then
			// add
			// offset
			+ ";}";

	Date.parseRegexes[regexNum] = new RegExp("^" + regex + "$", "i");
	eval(code);
};

// private
Date.formatCodeToRegex = function(character, currentGroup) {
	/*
	 * currentGroup = position in regex result array g = calculation group (0 or
	 * 1. only group 1 contributes to date calculations.) c = calculation method
	 * (required for group 1. null for group 0.) s = regex string
	 */
	switch (character) {
		case "d" :
			return {
				g : 1,
				c : "d = parseInt(results[" + currentGroup + "], 10);\n",
				s : "(\\d{2})"
			}; // day of month with leading zeroes (01 - 31)
		case "D" :
			for (var a = [], i = 0; i < 7; a.push(Date.getShortDayName(i)), ++i); // get
			// L10n
			// short
			// day
			// names
			return {
				g : 0,
				c : null,
				s : "(?:" + a.join("|") + ")"
			};
		case "j" :
			return {
				g : 1,
				c : "d = parseInt(results[" + currentGroup + "], 10);\n",
				s : "(\\d{1,2})"
			}; // day of month without leading zeroes (1 - 31)
		case "l" :
			return {
				g : 0,
				c : null,
				s : "(?:" + Date.dayNames.join("|") + ")"
			};
		case "N" :
			return {
				g : 0,
				c : null,
				s : "[1-7]"
			}; // ISO-8601 day number (1 (monday) - 7 (sunday))
		case "S" :
			return {
				g : 0,
				c : null,
				s : "(?:st|nd|rd|th)"
			};
		case "w" :
			return {
				g : 0,
				c : null,
				s : "[0-6]"
			}; // javascript day number (0 (sunday) - 6 (saturday))
		case "z" :
			return {
				g : 0,
				c : null,
				s : "(?:\\d{1,3}"
			}; // day of the year (0 - 364 (365 in leap years))
		case "W" :
			return {
				g : 0,
				c : null,
				s : "(?:\\d{2})"
			}; // ISO-8601 week number (with leading zero)
		case "F" :
			return {
				g : 1,
				c : "m = parseInt(Date.getMonthNumber(results[" + currentGroup
						+ "]), 10);\n", // get L10n month number
				s : "(" + Date.monthNames.join("|") + ")"
			};
		case "m" :
			return {
				g : 1,
				c : "m = parseInt(results[" + currentGroup + "], 10) - 1;\n",
				s : "(\\d{2})"
			}; // month number with leading zeros (01 - 12)
		case "M" :
			for (var a = [], i = 0; i < 12; a.push(Date.getShortMonthName(i)), ++i); // get
			// L10n
			// short
			// month
			// names
			return {
				g : 1,
				c : "m = parseInt(Date.getMonthNumber(results[" + currentGroup
						+ "]), 10);\n", // get L10n month number
				s : "(" + a.join("|") + ")"
			};
		case "n" :
			return {
				g : 1,
				c : "m = parseInt(results[" + currentGroup + "], 10) - 1;\n",
				s : "(\\d{1,2})"
			}; // month number without leading zeros (1 - 12)
		case "t" :
			return {
				g : 0,
				c : null,
				s : "(?:\\d{2})"
			}; // no. of days in the month (28 - 31)
		case "L" :
			return {
				g : 0,
				c : null,
				s : "(?:1|0)"
			};
		case "o" :
		case "Y" :
			return {
				g : 1,
				c : "y = parseInt(results[" + currentGroup + "], 10);\n",
				s : "(\\d{4})"
			}; // 4-digit year
		case "y" :
			return {
				g : 1,
				c : "var ty = parseInt(results[" + currentGroup + "], 10);\n"
						+ "y = ty > Date.y2kYear ? 1900 + ty : 2000 + ty;\n",
				s : "(\\d{1,2})"
			}; // 2-digit year
		case "a" :
			return {
				g : 1,
				c : "if (results[" + currentGroup + "] == 'am') {\n"
						+ "if (h == 12) { h = 0; }\n"
						+ "} else { if (h < 12) { h += 12; }}",
				s : "(am|pm)"
			};
		case "A" :
			return {
				g : 1,
				c : "if (results[" + currentGroup + "] == 'AM') {\n"
						+ "if (h == 12) { h = 0; }\n"
						+ "} else { if (h < 12) { h += 12; }}",
				s : "(AM|PM)"
			};
		case "g" :
		case "G" :
			return {
				g : 1,
				c : "h = parseInt(results[" + currentGroup + "], 10);\n",
				s : "(\\d{1,2})"
			}; // 24-hr format of an hour without leading zeroes (0 - 23)
		case "h" :
		case "H" :
			return {
				g : 1,
				c : "h = parseInt(results[" + currentGroup + "], 10);\n",
				s : "(\\d{2})"
			}; // 24-hr format of an hour with leading zeroes (00 - 23)
		case "i" :
			return {
				g : 1,
				c : "i = parseInt(results[" + currentGroup + "], 10);\n",
				s : "(\\d{2})"
			}; // minutes with leading zeros (00 - 59)
		case "s" :
			return {
				g : 1,
				c : "s = parseInt(results[" + currentGroup + "], 10);\n",
				s : "(\\d{2})"
			}; // seconds with leading zeros (00 - 59)
		case "u" :
			return {
				g : 1,
				c : "ms = parseInt(results[" + currentGroup + "], 10);\n",
				s : "(\\d{3})"
			}; // milliseconds with leading zeros (000 - 999)
		case "O" :
			return {
				g : 1,
				c : [
						"o = results[",
						currentGroup,
						"];\n",
						"var sn = o.substring(0,1);\n", // get + / - sign
						"var hr = o.substring(1,3)*1 + Math.floor(o.substring(3,5) / 60);\n", // get
						// hours
						// (performs
						// minutes-to-hour
						// conversion
						// also,
						// just
						// in
						// case)
						"var mn = o.substring(3,5) % 60;\n", // get minutes
						"o = ((-12 <= (hr*60 + mn)/60) && ((hr*60 + mn)/60 <= 14))?\n", // -12hrs
						// <=
						// GMT
						// offset
						// <=
						// 14hrs
						"    (sn + String.leftPad(hr, 2, 0) + String.leftPad(mn, 2, 0)) : null;\n"]
						.join(""),
				s : "([+\-]\\d{4})"
			}; // GMT offset in hrs and mins
		case "P" :
			return {
				g : 1,
				c : [
						"o = results[",
						currentGroup,
						"];\n",
						"var sn = o.substring(0,1);\n", // get + / - sign
						"var hr = o.substring(1,3)*1 + Math.floor(o.substring(4,6) / 60);\n", // get
						// hours
						// (performs
						// minutes-to-hour
						// conversion
						// also,
						// just
						// in
						// case)
						"var mn = o.substring(4,6) % 60;\n", // get minutes
						"o = ((-12 <= (hr*60 + mn)/60) && ((hr*60 + mn)/60 <= 14))?\n", // -12hrs
						// <=
						// GMT
						// offset
						// <=
						// 14hrs
						"    (sn + String.leftPad(hr, 2, 0) + String.leftPad(mn, 2, 0)) : null;\n"]
						.join(""),
				s : "([+\-]\\d{2}:\\d{2})"
			}; // GMT offset in hrs and mins (with colon separator)
		case "T" :
			return {
				g : 0,
				c : null,
				s : "[A-Z]{1,4}"
			}; // timezone abbrev. may be between 1 - 4 chars
		case "Z" :
			return {
				g : 1,
				c : "z = results[" + currentGroup + "] * 1;\n" // -43200 <= UTC
						// offset <=
						// 50400
						+ "z = (-43200 <= z && z <= 50400)? z : null;\n",
				s : "([+\-]?\\d{1,5})"
			}; // leading '+' sign is optional for UTC offset
		case "c" :
			var df = Date.formatCodeToRegex, calc = [];
			var arr = [df("Y", 1), df("m", 2), df("d", 3), df("h", 4),
					df("i", 5), df("s", 6), df("P", 7)];
			for (var i = 0, l = arr.length; i < l; ++i) {
				calc.push(arr[i].c);
			}
			return {
				g : 1,
				c : calc.join(""),
				s : arr[0].s + "-" + arr[1].s + "-" + arr[2].s + "T" + arr[3].s
						+ ":" + arr[4].s + ":" + arr[5].s + arr[6].s
			};
		case "U" :
			return {
				g : 1,
				c : "u = parseInt(results[" + currentGroup + "], 10);\n",
				s : "(-?\\d+)"
			}; // leading minus sign indicates seconds before UNIX epoch
		default :
			return {
				g : 0,
				c : null,
				s : Ext.fm.escapeRe(character)
			};
	}
};

/**
 * ��õ�ǰ���ڵ�ʱ����д��
 * 
 * @return {String} ��дʱ������ (���� 'CST', 'PDT', 'EDT', 'MPST' ...).
 */
Date.prototype.getTimezone = function() {
	return this.toString().replace(
			/^.* (?:\((.*)\)|([A-Z]{1,4})(?:[\-+][0-9]{4})?(?: -?\d+)?)$/,
			"$1$2").replace(/[^A-Z]/g, "");
};

/**
 * ��ȡ��ǰ���ڵ�GMTʱ����
 * 
 * @param {Boolean}
 *            colon �Ƿ�Ҫ��ʱ�ͷ�֮���ð�ţ�Ĭ��Ϊfalse
 * 
 * @return {String} The 4-character offset string prefixed with + or - (e.g.
 *         '-0600').
 */
Date.prototype.getGMTOffset = function(colon) {
	return (this.getTimezoneOffset() > 0 ? "-" : "+")
			+ String.leftPad(Math
							.abs(Math.floor(this.getTimezoneOffset() / 60)), 2,
					"0") + (colon ? ":" : "")
			+ String.leftPad(this.getTimezoneOffset() % 60, 2, "0");
};

/**
 * ��������ʱ����ĺ���ֵ
 * 
 * @param {Date}
 *            date (��ѡ��) Ĭ���ǵ�ǰʱ��
 * @return {Number} ���ĺ�����
 * @member Date getElapsed
 */
Date.prototype.getElapsed = function(date) {
	return Math.abs((date || new Date()).getTime() - this.getTime());
};

/**
 * ��ȡһ��������һ���еĵڼ��졣
 * 
 * @return {Number} 0 - 364 (����Ϊ365).
 */
Date.prototype.getDayOfYear = function() {
	var num = 0;
	Date.daysInMonth[1] = this.isLeapYear() ? 29 : 28;
	for (var i = 0; i < this.getMonth(); ++i) {
		num += Date.daysInMonth[i];
	}
	return num + this.getDate() - 1;
};

/**
 * ��ȡһ��������һ���еĵڼ��ܡ�
 * 
 * @return {Number} 1 - 53
 */
Date.prototype.getWeekOfYear = function() {
	// adapted from http://www.merlyn.demon.co.uk/weekcalc.htm
	var ms1d = 864e5; // milliseconds in a day
	var ms7d = 7 * ms1d; // milliseconds in a week
	var DC3 = Date.UTC(this.getFullYear(), this.getMonth(), this.getDate() + 3)
			/ ms1d; // an Absolute Day Number
	var AWN = Math.floor(DC3 / 7); // an Absolute Week Number
	var Wyr = new Date(AWN * ms7d).getUTCFullYear();
	return AWN - Math.floor(Date.UTC(Wyr, 0, 7) / ms7d) + 1;
};

/**
 * �ж�һ�������Ƿ������ꡣ
 * 
 * @return {Boolean}
 */
Date.prototype.isLeapYear = function() {
	var year = this.getFullYear();
	return ((year & 3) == 0 && (year % 100 || (year % 400 == 0 && year)));
};

/**
 * ��ȡһ���µĵ�һ�������ڼ���
 * 
 * @return {Number} 0-6
 */
Date.prototype.getFirstDayOfMonth = function() {
	var day = (this.getDay() - (this.getDate() - 1)) % 7;
	return (day < 0) ? (day + 7) : day;
};

/**
 * ��ȡһ���µ����һ�������ڼ���
 * 
 * @return {Number} 0-6
 */
Date.prototype.getLastDayOfMonth = function() {
	var day = (this.getDay() + (Date.daysInMonth[this.getMonth()] - this
			.getDate()))
			% 7;
	return (day < 0) ? (day + 7) : day;
};

/**
 * ��ȡһ���µĵ�һ�졣
 * 
 * @return {Date}
 */
Date.prototype.getFirstDateOfMonth = function() {
	return new Date(this.getFullYear(), this.getMonth(), 1);
};

/**
 * ��ȡһ���µ����һ�졣
 * 
 * @return {Date}
 */
Date.prototype.getLastDateOfMonth = function() {
	return new Date(this.getFullYear(), this.getMonth(), this.getDaysInMonth());
};
/**
 * ��ȡһ���µ�������
 * 
 * @return {Number}
 */
Date.prototype.getDaysInMonth = function() {
	Date.daysInMonth[1] = this.isLeapYear() ? 29 : 28;
	return Date.daysInMonth[this.getMonth()];
};

/**
 * ��ȡ��ǰ���ڵĴ���ǰ׺��Ӣ����д��
 * 
 * @return {String} 'st, 'nd', 'rd' �� 'th'.
 */
Date.prototype.getSuffix = function() {
	switch (this.getDate()) {
		case 1 :
		case 21 :
		case 31 :
			return "st";
		case 2 :
		case 22 :
			return "nd";
		case 3 :
		case 23 :
			return "rd";
		default :
			return "th";
	}
};

// private
Date.daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

/**
 * �·�Ӣ�ı�ʾ��ɵ����顣ͨ����д�����飬�ﵽI18N��Ч���� Date.monthNames = ['һ��', '����', ...];
 * 
 * @type Array
 * @static
 */
Date.monthNames = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"];

/**
 * ����·ݵ���д��
 * 
 * @param {Number}
 *            month ���㿪ʼ���·�
 * @return {String} ��Ӧ�·ݵ���д
 * @static
 */
Date.getShortMonthName = function(month) {
	return Date.monthNames[month].substring(0, 3);
}

/**
 * ���ڼ���Ӣ�ı�ʾ��ɵ����顣ͨ����д�����飬�ﵽI18N��Ч����Date.dayNames = ['����', '��һ', ...];
 * 
 * @type Array
 * @static
 */
Date.dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday",
		"Friday", "Saturday"];

/**
 * ��ȡ���ڼ���Ӣ����д��
 * 
 * @param {Number}
 *            day ��0��ʼ�����ڼ�
 * @return {String} ��Ӧ���ڼ�����д
 * @static
 */
Date.getShortDayName = function(day) {
	return Date.dayNames[day].substring(0, 3);
}

// private
Date.y2kYear = 50;

/**
 * �·���д����Ӧ�����ּ�ֵ����ɵ��·ݶ����·���д�Ǵ�Сд���еġ�ͨ����д������飬���ԴﵽI18N��Ч����Date.monthNumbers =
 * {'һ��':0, '����':1, ...};
 * 
 * @type Object
 * @static
 */
Date.monthNumbers = {
	Jan : 0,
	Feb : 1,
	Mar : 2,
	Apr : 3,
	May : 4,
	Jun : 5,
	Jul : 6,
	Aug : 7,
	Sep : 8,
	Oct : 9,
	Nov : 10,
	Dec : 11
};

/**
 * ͨ���·����ƣ���д/ȫ�����õ���Ӧ�����ֱ�ʾ��
 * 
 * @param {String}
 *            name �·�����
 * @return {Number} ��Ӧ������
 * @static
 */
Date.getMonthNumber = function(name) {

	return Date.monthNumbers[name.substring(0, 1).toUpperCase()
			+ name.substring(1, 3).toLowerCase()];
}

/**
 * ��¡������һ�����ڡ�
 * 
 * @return {Date} �µ�����ʵ��
 */
Date.prototype.clone = function() {
	return new Date(this.getTime());
};

/**
 * ���һ�����ڵ�ʱ����Ϣ��
 * 
 * @param {Boolean}
 *            clone ��ֵΪtrue���ȿ�¡һ���µ����ڣ�������������µ�����
 * @return {Date} this �� ��¡������
 */
Date.prototype.clearTime = function(clone) {
	if (clone) {
		return this.clone().clearTime();
	}
	this.setHours(0);
	this.setMinutes(0);
	this.setSeconds(0);
	this.setMilliseconds(0);
	return this;
};

// private
// safari setMonth is broken
if (Ext.isSafari) {
	Date.brokenSetMonth = Date.prototype.setMonth;
	Date.prototype.setMonth = function(num) {
		if (num <= -1) {
			var n = Math.ceil(-num);
			var back_year = Math.ceil(n / 12);
			var month = (n % 12) ? 12 - n % 12 : 0;
			this.setFullYear(this.getFullYear() - back_year);
			return Date.brokenSetMonth.call(this, month);
		} else {
			return Date.brokenSetMonth.apply(this, arguments);
		}
	};
}

/**
 * ����ĳ�����ʾ
 * 
 * @static
 * @type String
 */
Date.MILLI = "ms";
/**
 * ��ĳ�����ʾ
 * 
 * @static
 * @type String
 */
Date.SECOND = "s";
/**
 * �ֵĳ�����ʾ
 * 
 * @static
 * @type String
 */
Date.MINUTE = "mi";
/**
 * Сʱ�ĳ�����ʾ
 * 
 * @static
 * @type String
 */
Date.HOUR = "h";
/**
 * ���ڼ��ĳ�����ʾ
 * 
 * @static
 * @type String
 */
Date.DAY = "d";
/**
 * �·ݵĳ�����ʾ
 * 
 * @static
 * @type String
 */
Date.MONTH = "mo";
/**
 * ��ĳ�����ʾ
 * 
 * @static
 * @type String
 */
Date.YEAR = "y";

/**
 * ���ڵĻ���������㡣������������޸�ԭʼ�����ڶ��󣬶����¿�¡һ�������µĻ��������㡣
 * 
 * ���ӣ�
 * 
 * <pre><code>
 * //����ʹ��
 * var dt = new Date('10/29/2006').add(Date.DAY, 5);
 * document.write(dt); //'Fri Oct 06 2006 00:00:00'
 * 
 * //Ҳ�����Ǹ���
 * var dt2 = new Date('10/1/2006').add(Date.DAY, -5);
 * document.write(dt2); // 'Tue Sep 26 2006 00:00:00'
 * 
 * //֧����ʽ����
 * var dt3 = new Date('10/1/2006').add(Date.DAY, 5).add(Date.HOUR, 8).add(
 * 		Date.MINUTE, -30);
 * document.write(dt3); // 'Fri Oct 06 2006 07:30:00'
 * </code></pre>
 * 
 * @param {String}
 *            interval ������صĳ�����ʾ��ȷ����һ�������ĺ���
 * @param {Number}
 *            value �ӵ������е���
 * @return {Date} �µ�����ʵ��
 */
Date.prototype.add = function(interval, value) {
	var d = this.clone();
	if (!interval || value === 0)
		return d;
	switch (interval.toLowerCase()) {
		case Date.MILLI :
			d.setMilliseconds(this.getMilliseconds() + value);
			break;
		case Date.SECOND :
			d.setSeconds(this.getSeconds() + value);
			break;
		case Date.MINUTE :
			d.setMinutes(this.getMinutes() + value);
			break;
		case Date.HOUR :
			d.setHours(this.getHours() + value);
			break;
		case Date.DAY :
			d.setDate(this.getDate() + value);
			break;
		case Date.MONTH :
			var day = this.getDate();
			if (day > 28) {
				day = Math.min(day, this.getFirstDateOfMonth().add('mo', value)
								.getLastDateOfMonth().getDate());
			}
			d.setDate(day);
			d.setMonth(this.getMonth() + value);
			break;
		case Date.YEAR :
			d.setFullYear(this.getFullYear() + value);
			break;
	}
	return d;
};

/**
 * ��鵱ǰ�����Ƿ��ڸ����Ŀ�ʼ�ͽ�������֮�䡣
 * 
 * @param {Date}
 *            start ��ʼ����
 * @param {Date}
 *            end ����
 * @return {Boolean} �ڸ�������֮���򷵻�true�����򷵻�false
 */
Date.prototype.between = function(start, end) {
	var t = this.getTime();
	return start.getTime() <= t && t <= end.getTime();
}
