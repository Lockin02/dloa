/** Ext的重载方法**** */

Ext.lib.Ajax.getConnectionObject = function() { // 添加同步请求 3.x已经把该方法去掉了...
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
			 * 数组里面为record
			 */
			removeRecord : function(o) {
				var index = this.indexRecordOf(o);
				if (index != -1) {
					this.splice(index, 1);
				}
				return this;
			}
		});

/** ****Ext重载方法结束******* */

/**
 * 获取当前时间并设置到一个控件上，用动态更新顶部时间
 */
function getCurrentTime() {
	Ext.getCmp('curTime').setValue(new Date().toLocaleString() + ' 星期'
			+ '日一二三四五六'.charAt(new Date().getDay()));
}

/**
 * 传入二维数组跟值返回二维数组第二个元素（第一个是值，第二个是显示值，一般用于静态数据字典）
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
 * 日期计算
 * 
 * @param {}
 *            type 类型
 * @param {}
 *            NumDay 间隔
 * @param {}
 *            vdate 日期
 * @return {}
 */
function addDate(type, NumDay, vdate) {
	var date = new Date(vdate);
	type = parseInt(type) // 类型
	var lIntval = parseInt(NumDay)// 间隔
	switch (type) {
		case 6 :// 年
			date.setYear(date.getYear() + lIntval)
			break;
		case 7 :// 季度
			date.setMonth(date.getMonth() + (lIntval * 3))
			break;
		case 5 :// 月
			date.setMonth(date.getMonth() + lIntval)
			break;
		case 4 :// 天
			date.setDate(date.getDate() + lIntval)
			break
		case 3 :// 时
			date.setHours(date.getHours() + lIntval)
			break
		case 2 :// 分
			date.setMinutes(date.getMinutes() + lIntval)
			break
		case 1 :// 秒
			date.setSeconds(date.getSeconds() + lIntval)
			break;
		default :

	}
	return date;
}

/**
 * 统一的错误回调函数
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
				message : message ? message : '服务器未响应，请稍后再试！'
			});
}

var extUtil = {
	getJson : function(data) {
		return eval("(" + data + ")")
	},
	objectToJson : function(object) {// 转换对象为json字符串
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
			return "男"
		} else {
			if ((false === A) || ("false" === A)) {
				return "女"
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
			return '-￥' + A.substr(1)
		}
		return '￥' + A
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
			return "-￥" + A.substr(1)
		}
		return "￥" + A
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
				dataName : '是',
				dataCode : 1
			}, {
				dataName : '否',
				dataCode : 0
			}],
	yesOrNo : function(val) {
		return val == 1 || true ? '是' : '否';
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
				dataName : '红色',
				dataCss : 'red'
			}, {
				dataCode : 2,
				dataName : '深红色',
				dataCss : '#6C0404'
			}, {
				dataCode : 3,
				dataName : '绿色',
				dataCss : '#418848'
			}, {
				dataCode : 4,
				dataName : '黄色',
				dataCss : '#FFFC00'
			}, {
				dataCode : 5,
				dataName : '紫色',
				dataCss : '#9C6ACA'
			}]

};



/*
 * 对javascript进行扩展包括[Function,Date,String,Number,Array]
 * 
 * Copyright (C) 2009 Ext Technology 广东同望科技股份有限公司 (http://www.Ext.com.cn)
 * 
 * @requires Ext.js @author chenjs, chenjs@Ext.com.cn
 */

/**
 * @class Function 对原生的JavaScript函数原型进行功能扩展，扩展的功能可用于任意一个函数
 */
Ext.apply(Function.prototype, {

			/**
			 * <p>
			 * 创建一个新的函数，新的函数在原函数的基础上添加一个<b>拦截器</b>。新函数被调用时，如果拦截器返回false则不再执行原函数，拦截器返回true则继续执行原函数。
			 * </p>
			 * <p>
			 * <b>注意：</b>原函数并没有被改变。
			 * </p>
			 * <p>
			 * 这是函数的AOP实现。
			 * </p>
			 * 
			 * @addon
			 * @param {Function}
			 *            fn 拦截器
			 * @param {Object}
			 *            scope (可选的) 拦截器的执行作用域，默认的作用域是原函数的作用域
			 * @return {Function} 一个新的函数
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
			 * 创建一个新的函数，新的函数在原函数的基础上添加一个<b>后置函数</b>。后置函数的参数列表和原函数一样。后置函数的返回值不会被处理。
			 * </p>
			 * <p>
			 * <b>注意：</b>原函数并没有被改变。
			 * </p>
			 * <p>
			 * 这是函数的AOP实现。
			 * </p>
			 * 
			 * @param {Function}
			 *            fn 后置函数
			 * @param {Object}
			 *            scope (可选的) 后置函数的执行作用域，默认的作用域是原函数的作用域
			 * @return {Function} 一个新的函数
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
			 * 创建一个新的函数，新的函数的函数体和原函数一样，但是参数列表改为自定义的参数列表。新函数相当于一个<b>回调函数</b>。
			 * 
			 * @param {mixed}
			 *            args 新函数的参数
			 * @return {Function} 一个新的函数
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
			 * 创建一个新的函数，新的函数相当于将原函数委派到新的作用域来执行，同时参数列表可以修改。
			 * 
			 * @param {Object}
			 *            obj （可选的）新的作用域
			 * @param {Array}
			 *            args （可选的）用于覆盖原函数的参数
			 * @param {Boolean/Number}
			 *            appendArgs （可选的）如果为真，则第二个参数 args
			 *            不用于覆盖原函数的参数，而是追加到原参数列表后面
			 * @return {Function} 一个新的函数
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
			 * 将函数延迟执行，根据实际使用情况，可以改变函数的执行作用域和参数列表。
			 * 
			 * @param {Number}
			 *            millis 延迟毫秒数，如果为0，则立即执行
			 * @param {Object}
			 *            obj （可选的）新的作用域
			 * @param {Array}
			 *            args （可选的）用于覆盖原函数的参数
			 * @param {Boolean/Number}
			 *            appendArgs （可选的）如果为真，则第二个参数 args
			 *            不用于覆盖原函数的参数，而是追加到原参数列表后面
			 * @return {Number} 延迟执行处理的ID，这个值可用于clearTimeout
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
 * @class Array 对原生的JavaScript数组原型进行功能扩展，扩展的功能可用于任意一个数组
 */
Ext.applyIf(Array.prototype, {

			/**
			 * 查找某个元素在数组中匹配的第一个位置，如果找不到，则返回-1。当元素是一个引用数据类型，则必须是绝对一致（===）才能找到。
			 * 
			 * @param {Mixed}
			 *            elt 要查找的元素
			 * @param {Number}
			 *            from 开始查找位置，默认为0
			 * @return {Number} 元素在数组中的位置，如果找不到，则返回-1
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
			 * 查找某个元素在数组中匹配的最后一个位置，如果找不到，则返回-1。当元素是一个引用数据类型，则必须是绝对一致（===）才能找到。
			 * 
			 * @param {Mixed}
			 *            elt 要查找的元素
			 * @param {Number}
			 *            from 开始查找位置，默认为0
			 * @return {Number} 元素在数组中的位置，如果找不到，则返回-1
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
			 * 验证数组项。遍历数组，将每一个数组项传入<b>比较函数</b>执行，如果有一个数组项能使得比较函数返回true，则该函数返回true。注意和{@link #every}方法的区别
			 * 
			 * @param {Function}
			 *            fn 比较函数，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 比较函数执行的作用域
			 * @return {Boolean} 有符合比较函数的比较规则的数组项则返回true，否则返回false
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
			 * 映射转换数组项。遍历数组，将每一个数组项通过<b>映射函数</b>进行映射转换，将结果数组返回。原数组不会改变。
			 * 
			 * @param {Function}
			 *            fn 映射函数，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 映射函数执行的作用域
			 * @return {Array} 经过映射函数转换的新数组
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
			 * 过滤数组，将数组通过<b>过滤函数</b>进行过滤，符合条件的数组项组成新的数组返回。原数组不会改变。
			 * 
			 * @param {Function}
			 *            fn 过滤函数，符合过滤条件则返回true，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 过滤函数执行的作用域
			 * @return {Array} 经过过滤得到的新数组
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
			 * 验证数组项。遍历数组，如果每一个数组项都符合<b>比较函数</b>的要求（即比较函数返回true），则该函数返回true。注意和{@link #some}方法的区别
			 * 
			 * @param {Function}
			 *            fn 比较函数，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 比较函数执行的作用域
			 * @return {Boolean} 数组中的每一个数组项都符合比较函数的比较规则，则返回true，否则返回false
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
			 * 遍历数组，以每一个数组项和数组项的索引位置作为参数传入<b>回调函数</b>执行。
			 * 
			 * @param {Function}
			 *            fn 回调函数，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 回调函数执行的作用域
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
			 * 从数组中删除指定的数组项
			 * 
			 * @param {Object}
			 *            o 要删除的数组项
			 * @return {Array} 数组本身
			 */
			remove : function(o) {
				var index = this.indexOf(o);
				if (index != -1) {
					this.splice(index, 1);
				}
				return this;
			},

			/**
			 * 数组去除重复元素
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
 * @class String 对原生的JavaScript字符串原型进行功能扩展，扩展的功能可用于任意一个数组
 */
Ext.applyIf(String, {

			/**
			 * 将传入字符串中的 ' 和 \ 这两个字符进行转义
			 * 
			 * @param {String}
			 *            string 要转义的字符串
			 * @return {String} 经过转义的字符串
			 * @static
			 */
			escape : function(string) {
				return string.replace(/('|\\)/g, "\\$1");
			},

			/**
			 * 在字符串的左边补上指定的字符 使用例子：
			 * 
			 * <pre><code>
			 * var s = String.leftPad('123', 5, '0');// '00123'
			 * </code></pre>
			 * 
			 * @param {String}
			 *            string 原始字符串
			 * @param {Number}
			 *            size 自定字符重复的次数
			 * @param {String}
			 *            char （可选的）指定的字符。默认为空字符串“ ”
			 * @return {String} 补上指定字符后的新字符串
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
			 * 字符串格式化，将字符串中的代号 {0}, {1}...替换为相应参数列表位置中的值 使用例子：
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
			 *            value1 用来代替代号{0}的值
			 * @param {String}
			 *            value2 。。。
			 * @return {String} 格式化过的字符串
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
			 * 将字符串在两个值之间切换。如果字符串和传入的第一个值相等，则返回另一个值。不等，则返回第一个值。当前字符串并没有被改变
			 * 
			 * <pre><code>
			 * // 交替切换排序方向
			 * sort = sort.toggle('ASC', 'DESC');
			 * 
			 * // 用来代替下面这个逻辑
			 * sort = (sort == 'ASC' ? 'DESC' : 'ASC');
			 * </code></pre>
			 * 
			 * @param {String}
			 *            value 用来和当前字符串比较的值
			 * @param {String}
			 *            另一个值
			 * @return {String} 符合逻辑的值
			 */
			toggle : function(value, other) {
				return this == value ? other : value;
			},

			/**
			 * 截掉当前字符串两头的空白。 使用例子:
			 * 
			 * <pre><code>
			 * var s = '  foo bar  ';
			 * alert('-' + s + '-'); //alerts &quot;- foo bar -&quot;
			 * alert('-' + s.trim() + '-'); //alerts &quot;-foo bar-&quot;
			 * </code></pre>
			 * 
			 * @return {String} 截取过的字符串
			 */
			trim : function() {
				var re = /^\s+|\s+$/g;
				return function() {
					return this.replace(re, "");
				}
			}

		});

/**
 * @class Number 对原生的JavaScript数字原型进行功能扩展，扩展的功能可用于任意一个数组
 */
Ext.applyIf(Number.prototype, {
			/**
			 * 将数值限制在给定的[min,max]之间，如果在给定的区间，则返回自身，如果小于min，则返回min，如果大于max，则返回max
			 * 
			 * @param {Number}
			 *            min 区间的最小值
			 * @param {Number}
			 *            max 区间的最大值
			 * @return {Number} 区间内的值
			 */
			constrain : function(min, max) {
				return Math.min(Math.max(this, min), max);
			}
		});
/*
 * 日期解析和格式化函数集
 * 
 * Copyright (C) 2009 Ext Technology 广东同望科技股份有限公司 (http://www.Ext.com.cn)
 * 
 * @requires Ext.js @author chenjs, chenjs@Ext.com.cn
 */

/**
 * @class Date
 * 
 * 实现一个日期解析和格式化的函数集，类似java的Date类。
 * 
 * <p>
 * 下列列表是当前支持的日期格式:
 * </p>
 * 
 * <pre>
 *  格式    描述	                                                        包含的值
 *  ------  ------------------------------------------------------      -----------------------
 *  d       月份的日期，两位的格式，如果小于10则第一位补0                  01 - 31
 *  D       一周的英文缩写，三位的格式                                    Mon - Sun
 *  j       月份的日期，首位不补零                                        1 - 31
 *  l       一周的完整英文                                                Sunday - Saturday
 *  N       一周的 ISO-8601标准的数字格式表示	                             1 (周一) - 7 (周日)
 *  S       英语中第几天的缩写前缀，两个字符                               st, nd, rd 或 th. 
 *  w       一周的数字表示                                                0 (周日) - 6 (周六)
 *  z       一年的第几天（从0开始）                                        0 - 364 (闰年到365)
 *  W       一年的ISO-8601标准的第几周，周从周一开始                       01 - 53
 *  F       月份的完整英文表示                                            January - December
 *  m       月份的数字表示，两位的格式，如果小于10则第一位补0               01 - 12
 *  M       月份的缩写英文表示，三位的格式                                 Jan - Dec
 *  n       月份的数字表示，首位不补零                                     1 - 12
 *  t       一个月的可能天数                                               28 - 31
 *  L       某年是否闰年                                                   闰年为1，否则为0
 *  o       ISO-8601标准表示年的数字。和（Y）类似，                         例如: 1998 或 2004
 *         但是如果该天所在的周属于另一年，则取另一年
 *  Y       年的完整数字表示, 4位数字	                                      例如: 1999 或 2003
 *  y       年的两位数字表示	                                              例如: 99 或 03
 *  a       小写的上午、下午表示                                            am 或 pm
 *  A       大写的上午、下午表示                                            AM 或 PM
 *  g       12小时制的表示，首位不补零                                      1 - 12
 *  G       24小时制的表示，首位不补零                                      0 - 23
 *  h       12小时制的表示，如果小于10则第一位补0                            01 - 12
 *  H       24小时制的表示，如果小于10则第一位补0                            00 - 23
 *  i       分钟的表示，前缀补零                                            00 - 59
 *  s       秒的表示，前缀补零                                              00 - 59
 *  u       毫秒的表示，前缀补零                                            001 - 999
 *  O       和格林威治时间（GMT）的差距，小时和分                            例如：+1030
 *  P       和格林威治时间（GMT）的差距，小时和分之间有冒号                   例如：-08:00
 *  T       时区缩写                                                        例如：EST, MDT, PDT ...
 *  Z       时区补偿，以秒表示。(UTC的西边为负，东边为正                      -43200 - 50400
 *  c       ISO 8601标准的日期格式                                           2009-10-24T15:19:21+08:00
 *  U       从Unix纪元开始到当前的秒数 (January 1 1970 00:00:00 GMT)         1193432466 or -2138434463
 * </pre>
 * 
 * 使用例子：
 * 
 * <pre><code>
 * 
 * var dt = new Date('10/24/2009 03:05:01 PM GMT-0600');
 * document.write(dt.format('Y-m-d')); // 2009-10-24
 * document.write(dt.format('F j, Y, g:i a')); // October 24, 2009, 3:05 pm
 * document.write(dt.format('l, \\t\\he jS of F Y h:i:s A')); // Saturday, the 24th of October 2009 03:05:01 PM
 * </code></pre>
 * 
 * 下面是一些常用的标准日期/时间模板。
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
 * 使用例子：
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
 * 根据给定的格式模板来格式化日期字符串。
 * 
 * @param {String}
 *            format 格式模板
 * @return {String} 经过格式化的日期
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
 * 使用指定格式模板来解析日期字符串。该日期字符串的每一部分如果不指定，则用默认的。 时间部分也可以指定，默认为0。
 * 必须保证传入的日期字符串要精确地匹配格式模板，否则解析将会出错。 使用例子：
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
 *            input 还没解析的日期字符串
 * @param {String}
 *            format 格式
 * @return {Date} 经过解析得到的日期
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
 * 获得当前日期的时区缩写。
 * 
 * @return {String} 缩写时区名称 (例如 'CST', 'PDT', 'EDT', 'MPST' ...).
 */
Date.prototype.getTimezone = function() {
	return this.toString().replace(
			/^.* (?:\((.*)\)|([A-Z]{1,4})(?:[\-+][0-9]{4})?(?: -?\d+)?)$/,
			"$1$2").replace(/[^A-Z]/g, "");
};

/**
 * 获取当前日期的GMT时间差距
 * 
 * @param {Boolean}
 *            colon 是否要在时和分之间加冒号，默认为false
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
 * 返回两个时间差距的毫秒值
 * 
 * @param {Date}
 *            date (可选的) 默认是当前时间
 * @return {Number} 相差的毫秒数
 * @member Date getElapsed
 */
Date.prototype.getElapsed = function(date) {
	return Math.abs((date || new Date()).getTime() - this.getTime());
};

/**
 * 获取一个日期是一年中的第几天。
 * 
 * @return {Number} 0 - 364 (闰年为365).
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
 * 获取一个日期在一年中的第几周。
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
 * 判断一个日期是否在闰年。
 * 
 * @return {Boolean}
 */
Date.prototype.isLeapYear = function() {
	var year = this.getFullYear();
	return ((year & 3) == 0 && (year % 100 || (year % 400 == 0 && year)));
};

/**
 * 获取一个月的第一天是星期几。
 * 
 * @return {Number} 0-6
 */
Date.prototype.getFirstDayOfMonth = function() {
	var day = (this.getDay() - (this.getDate() - 1)) % 7;
	return (day < 0) ? (day + 7) : day;
};

/**
 * 获取一个月的最后一天是星期几。
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
 * 获取一个月的第一天。
 * 
 * @return {Date}
 */
Date.prototype.getFirstDateOfMonth = function() {
	return new Date(this.getFullYear(), this.getMonth(), 1);
};

/**
 * 获取一个月的最后一天。
 * 
 * @return {Date}
 */
Date.prototype.getLastDateOfMonth = function() {
	return new Date(this.getFullYear(), this.getMonth(), this.getDaysInMonth());
};
/**
 * 获取一个月的天数。
 * 
 * @return {Number}
 */
Date.prototype.getDaysInMonth = function() {
	Date.daysInMonth[1] = this.isLeapYear() ? 29 : 28;
	return Date.daysInMonth[this.getMonth()];
};

/**
 * 获取当前日期的次序前缀的英文缩写。
 * 
 * @return {String} 'st, 'nd', 'rd' 或 'th'.
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
 * 月份英文表示组成的数组。通过重写该数组，达到I18N的效果。 Date.monthNames = ['一月', '二月', ...];
 * 
 * @type Array
 * @static
 */
Date.monthNames = ["January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"];

/**
 * 获得月份的缩写。
 * 
 * @param {Number}
 *            month 从零开始的月份
 * @return {String} 相应月份的缩写
 * @static
 */
Date.getShortMonthName = function(month) {
	return Date.monthNames[month].substring(0, 3);
}

/**
 * 星期几的英文表示组成的数组。通过重写该数组，达到I18N的效果。Date.dayNames = ['周日', '周一', ...];
 * 
 * @type Array
 * @static
 */
Date.dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday",
		"Friday", "Saturday"];

/**
 * 获取星期几的英文缩写。
 * 
 * @param {Number}
 *            day 从0开始的星期几
 * @return {String} 相应星期几的缩写
 * @static
 */
Date.getShortDayName = function(day) {
	return Date.dayNames[day].substring(0, 3);
}

// private
Date.y2kYear = 50;

/**
 * 月份缩写及对应的数字键值对组成的月份对象。月份缩写是大小写敏感的。通过重写这个数组，可以达到I18N的效果。Date.monthNumbers =
 * {'一月':0, '二月':1, ...};
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
 * 通过月份名称（缩写/全名）得到相应的数字表示。
 * 
 * @param {String}
 *            name 月份名称
 * @return {Number} 相应的数字
 * @static
 */
Date.getMonthNumber = function(name) {

	return Date.monthNumbers[name.substring(0, 1).toUpperCase()
			+ name.substring(1, 3).toLowerCase()];
}

/**
 * 克隆并返回一个日期。
 * 
 * @return {Date} 新的日期实例
 */
Date.prototype.clone = function() {
	return new Date(this.getTime());
};

/**
 * 清除一个日期的时间信息。
 * 
 * @param {Boolean}
 *            clone 该值为true则先克隆一份新的日期，清除操作基于新的日期
 * @return {Date} this 或 克隆的日期
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
 * 毫秒的常量表示
 * 
 * @static
 * @type String
 */
Date.MILLI = "ms";
/**
 * 秒的常量表示
 * 
 * @static
 * @type String
 */
Date.SECOND = "s";
/**
 * 分的常量表示
 * 
 * @static
 * @type String
 */
Date.MINUTE = "mi";
/**
 * 小时的常量表示
 * 
 * @static
 * @type String
 */
Date.HOUR = "h";
/**
 * 星期几的常量表示
 * 
 * @static
 * @type String
 */
Date.DAY = "d";
/**
 * 月份的常量表示
 * 
 * @static
 * @type String
 */
Date.MONTH = "mo";
/**
 * 年的常量表示
 * 
 * @static
 * @type String
 */
Date.YEAR = "y";

/**
 * 日期的基本相加运算。这个方法并不修改原始的日期对象，而是新克隆一个，在新的基础上运算。
 * 
 * 例子：
 * 
 * <pre><code>
 * //基本使用
 * var dt = new Date('10/29/2006').add(Date.DAY, 5);
 * document.write(dt); //'Fri Oct 06 2006 00:00:00'
 * 
 * //也可以是负数
 * var dt2 = new Date('10/1/2006').add(Date.DAY, -5);
 * document.write(dt2); // 'Tue Sep 26 2006 00:00:00'
 * 
 * //支持链式操作
 * var dt3 = new Date('10/1/2006').add(Date.DAY, 5).add(Date.HOUR, 8).add(
 * 		Date.MINUTE, -30);
 * document.write(dt3); // 'Fri Oct 06 2006 07:30:00'
 * </code></pre>
 * 
 * @param {String}
 *            interval 日期相关的常量表示，确定下一个参数的含义
 * @param {Number}
 *            value 加到日期中的数
 * @return {Date} 新的日期实例
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
 * 检查当前日期是否在给定的开始和结束日期之间。
 * 
 * @param {Date}
 *            start 开始日期
 * @param {Date}
 *            end 结束
 * @return {Boolean} 在给定日期之间则返回true，否则返回false
 */
Date.prototype.between = function(start, end) {
	var t = this.getTime();
	return start.getTime() <= t && t <= end.getTime();
}
