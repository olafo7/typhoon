var CNOA_wf_form_checker =  new (function(){
	var detail_list = {}, ckme = this;;

	var formPanelId = "";

	var elIsSelected = function(el) {
		vl = el.val();
		el = el.get(0);
		var selection, version=$.browser.version;
		if (!$.browser.msie) {
			if (el.selectionStart != undefined) {
				selection = el.value.substr(el.selectionStart, el.selectionEnd - el.selectionStart);
			}
		} else {
			if(parseInt(version.substr(0, version.indexOf('.')), 10) > 8){
				selection = el.value.substr(el.selectionStart, el.selectionEnd - el.selectionStart);
			}else{
				if (window.getSelection) {
					selection = window.getSelection();
				} else if (document.getSelection) {
					selection = document.getSelection();
				} else if (document.selection) {
					selection = document.selection.createRange().text;
				}
			}
		}
		if(selection.length<1){
			return false;
		}
		if(selection.length <= vl.length){
			return true;
		}
	};

	this.eventList = {
		'keyup':{},
		'change':{},
		'blur':{}
	};

	var addEvent = function(event, etargetid, key, func){
		if(event.indexOf(" ") != -1){
			event = event.split(" ");
			for (var i=0; i<event.length; i++){
				ckme.eventList[event[i]][key] = {tid:etargetid,func:func};
			}
		}else{
			ckme.eventList[event][key] = {tid:etargetid,func:func};
		}
	};

	var removeEvent = function(event, key, func){
		delete ckme.eventList[event][key];
	};
	
	var checkMust = function(){
		var pass = false;
		
		//找出所有input/textarea/select/的must="true"的元素
		var selector = ".wf_form_content " + 
					   "input:text[must=true]" + 
					   ",textarea[must=true]" + 
					   ",input:button[must=true]" + 
					   ",select[must=true]" + 
					   ",input:hidden[must=true][mustfor=radio]" + 
					   ",input:hidden[must=true][mustfor=signature]" + 
					   ",input:hidden[must=true][mustfor=button]" + 
					   ",input:[must=true][mustfor=choice]" + 
					   ",input:hidden[must=true][mustfor=checkbox]";
		var els = $(selector);
		
		for(var i=0;i<els.length;i++){
			var nowEl = $(els[i]);
			//如果做了隐藏处理，则不判断是否必填
			if(nowEl.parents("span[class=group][showhide=hide]").length <= 0){
				//判断type=radio的类型
				if(nowEl.attr("type") == 'hidden' && nowEl.attr("mustfor") == 'radio'){
					var radio = $(".wf_form_content input:radio[name=" + nowEl.attr("musttarget")+"]:checked");
					if(radio.length<=0){
						CNOA.msg.alert(lang('radioControls') + ": ["+nowEl.attr("foroname") + "]" + lang('pleaseSelectOne1'));
						return false;
					}
				}

				//判断type=button的类型
				else if(nowEl.attr("type") == 'button' && nowEl.attr("must") == 'true'){
					var attachArr 	= nowEl.closest('tbody'),
						dataAttach  = nowEl.attr('data-attach'),
					 	tra 		= attachArr.find('tr'),
						len 			= 0;
					$(tra).each(function(){
						var dataAttach2 = $(this).attr('data-attach');
						if (dataAttach2 == dataAttach) {
							len++;
						};
					})
					if (len<1) {
						CNOA.msg.alert(lang('controls') + ": ["+nowEl.val() + "]" + lang('isRequired'), function(){
							nowEl.focus();
						});
						return false;
					};
				}

				//判断type=radio的类型
				else if(nowEl.attr("type") == 'hidden' && nowEl.attr("mustfor") == 'checkbox'){
					var checkbox = $(".wf_form_content input:checkbox[gid=" + nowEl.attr("musttarget")+"]:checked");
					if(checkbox.length<=0){
						CNOA.msg.alert(lang('multipleChoice') + ": ["+nowEl.attr("foroname") + "]" + lang('pleaseSelectOne1'));
						return false;
					}
				}
				//判断其它
				else{
					if(Ext.isEmpty(nowEl.val())){
						CNOA.msg.alert(lang('controls') + ": ["+nowEl.attr("oname") + "]" + lang('isRequired'), function(){
							nowEl.focus();
						});
						return false;
					}
				}
			}
		}
		
		//找出所有input/textarea/select/的isvalid="false"的元素
		var selector = ".wf_form_content " + 
					   "input[isvalid=false]" + 
					   ",textarea[isvalid=false]" + 
					   ",select[isvalid=false]";
		var els = $(selector);
		for(var i=0;i<els.length;i++){
			var nowEl = $(els[i]);
			if(nowEl.attr("otype")=='calculate'){
				CNOA.msg.alert(lang('controls') + ": ["+nowEl.attr("oname") + "]" + lang('calculationNotCorrect'), function(){
					nowEl.focus();
				});
			}else{
				CNOA.msg.alert(lang('controls') + ": ["+nowEl.attr("oname") + "]" + lang('fillIncorrect'), function(){
					nowEl.focus();
				});
			}
			
			return false;
		}
		
		return true;
	};
	
	var bindMoneyConvert = function(){
		var converters = $(".wf_form_content input:hidden:[moneyconvert=true]");
		converters.each(function(){
			var th = this;
			var from = $("#"+$(this).attr('from'));
			var to   = $("#"+$(this).attr('to'));
			var fromcount = $(this).attr('fromcount');
			to.attr('value', AmountInWords(cleanSymbol(to.val()), fromcount)).change();
			var events = 'moneyconvert_'+$(this).attr('from')+'__'+$(this).attr('to');
			
			to.attr('value', AmountInWords(cleanSymbol(from.val()), fromcount));
			
			addEvent('keyup change', from.attr('id'), events, function(){
				var newVla = AmountInWords(cleanSymbol(from.val()), fromcount);
				if(typeof(newVla) == 'object'){
					newVla = newVla.msg;
					from.attr('isvalid', false);
				}else{
					from.attr('isvalid', true);
				}
				to.attr('value', newVla).change();
			});
		});
	};
	
	var changeAutoFormat = function(el, change, isReturn){
		var format = el.attr("autoformat");
		var val = el.val();
		var value = "";
		switch(format){
			case "#":
				value = formatNumber(val, false, 0, false, false);
				break;
			case "#,###":
				value = formatNumber(val, true, 0, false, false);
				break;
			case "#.#":
				value = formatNumber(val, false, 1, false, false);
				break;
			case "#.##":
				value = formatNumber(val, false, 2, false, false);
				break;
			case "#.###":
				value = formatNumber(val, false, 3, false, false);
				break;
			case "#,###.#":
				value = formatNumber(val, true, 1, false, false);
				break;
			case "#,###.##":
				value = formatNumber(val, true, 2, false, false);
				break;
			case "#,###.###":
				value = formatNumber(val, true,3, false, false);
				break;
			case "#.0":
				value = formatNumber(val, false, 1, true, false);
				break;
			case "#.00":
				value = formatNumber(val, false, 2, true, false);
				break;
			case "#.000":
				value = formatNumber(val, false, 3, true, false);
				break;
			case "#,###.0":
				value = formatNumber(val, true, 1, true, false);
				break;
			case "#,###.00":
				value = formatNumber(val, true, 2, true, false);
				break;
			case "#,###.000":
				value = formatNumber(val, true, 3, true, false);
				break;
			case "#.####":
				value = formatNumber(val, false, 4, false, false);
				break;
			default:
				value = validateDateTime(val, format);
				break;
		}
		
		if (isReturn) {
			return value;
		}

		
		
		el.attr('isvalid', true);
		if(value === false){
			el.attr('isvalid', false);
			if (el.attr("otype") != 'calculate') {
				//CNOA.msg.alert("控件: ["+el.attr("oname") + "]填写不正确，请检查！", function(){
				//	el.focus();
				//});
			}
		}else{
			//如果值为0，则不处理
			if(value != 0){
				el.attr('isvalid', true);
				el.val(value);
				if(change){
					el.change();
				}
			}
		}
	};
	/**
	 * @已经移除
	 * @linxiaoqing 
	 * @2012-11-23
	 */
	var formatAutoFormat = function(){
		return;
		var fields = $(".wf_form_content input:[autoformat]");
		fields.each(function(){
			changeAutoFormat($(this), true);
		});
	};
	/**
	 * @已经移除
	 * @linxiaoqing 
	 * @2012-11-23
	 */
	var bindAutoFormat = function(){
		return;
		var fields = $(".wf_form_content input:[autoformat]");
		fields.each(function(){
			var field = $(this, true);
			changeAutoFormat(field);
			field.unbind('blur.b keyup.b').bind('blur.b keyup.b', function(){
				changeAutoFormat($(this), true);
			});
		});
	};

	var bindMinMaxNumber = function(){
		var fields = $(".wf_form_content input[maxnum]");
		fields.each(function(){
			var field = $(this, true);
			field.unbind('blur.c keyup.i').bind('blur.c keyup.i', function(){
				var maxnum = field.attr('maxnum'),
					maxnumtext = field.attr('maxnumtext'),
					value = $(this).val().replace(/,/ig, '');
				
				//alert(parseInt(value, 10) + "|" + parseInt(maxnum, 10));

				if(parseInt(value, 10) > parseInt(maxnum, 10)){
					CNOA.msg.alert(lang('controls') + ": [" + field.attr("oname") + "]"+ lang('valueCanNotDaYu') + (maxnumtext?("["+maxnumtext+"]"):"")+lang('value') +"："+maxnum+"，" + lang('pleaseCheck'), function(){
						field.focus();
					});
				}
			});
		});
	};
	
	/**
	 * 整理带有符号的值 转化成正规的纯数字
	 * @param {string} val
	 */
	var cleanSymbol = function(val){
		val = val + "";
		try{
			val = val.replace(/,/g, '');
		}catch(e){alert(e);
			val = 0;
		}
		return Number(val);
	};
	
	/**
	 * 明细表->点击移除列时,把该行的数据先设置为0，然后再移除列
	 */
	var removeRow = function(tr){
		tr.children("td").children("input").each(function(){
			$(this).val(0).change();
		});
		
		tr.remove();
	};
	
	/**
	 * 创建明细表增加/移除按钮
	 */
	var bindDetailtable = function(){
		var detail_trs = $(".wf_form_content tr[class=detail-line]");

		//删除table的border样式(防止添加/删除按钮出现边框)
		detail_trs.parents('table').css("border", "none");
		//detail_trs.parents('table').width(parseInt(detail_trs.parents('table').attr('width'), 10)+20);
		
		//遍历出所有detailid:
		var dtel = [];
		detail_trs.each(function(){
			dtel[$(this).attr('detail')] = dtel[$(this).attr('detail')] ? dtel[$(this).attr('detail')]+1 : 1
		});
		detail_trs.each(function(){
			detail_list['d_'+$(this).attr('detail')] = {
				id: $(this).attr('detail'),
				linemax: dtel[$(this).attr('detail')],	//自动序列号
				rowmax: dtel[$(this).attr('detail')]	//行号(隐藏)
			};
		});
		
		//var clickType = "add";
		//遍历所有的明细表
		detail_trs.each(function(){
			var th = $(this),
				detailid = th.attr('detail'),
				trHtml = th.attr('outerHTML');
				
			/* 绑定表单载入时已有的行 */
			//绑定删除按钮事件
			th.find("img[class=wf_row_jian]").unbind('click').bind('click', function(){
				var parent = th.parent();
				removeRow(th);
				
				//重排序号
				//生成自动序列号
				var j = 1;
				var rows = parent.children('tr[detail]').each(function(){
					var rw = $(this);
					var snumber1 = rw.find("input[snumber]");
					try{snumber1.attr('value', snumber1.attr('snumber').replace("{x}", j));}catch(e){}
					detail_list['d_'+detailid].linemax = j;
					j++;
				});
				
				//自动格式化控件:[整型/浮点型/日期型/时间型]
				//改成实时格式化，不需要再格式化了
				//bindAutoFormat();
				
				//明细表 - 行计算控件(横向小计等复杂公式计算)
				bindRowCalculate();

			});
			
			//绑定添加按钮事件
			th.find("img[class=wf_row_jia]").bind('click', function(){
				//获取本行表单数据
				var data = [];
				th.find("input,textarea,select").each(function(){
					var ipt = $(this);
					data.push({
						id: ipt.attr('id'),
						checked: ipt.attr('checked'),
						value: ipt.attr('value')
					});
				});
				
				detail_list['d_'+detailid].linemax = detail_list['d_'+detailid].linemax + 1;
				detail_list['d_'+detailid].rowmax = detail_list['d_'+detailid].rowmax + 1;
				
				var tr1 = $(trHtml);
			
				tr1.attr('rownum', detail_list['d_'+detailid].rowmax);
				tr1.insertAfter(th.parent().children("tr[class=detail-line][detail="+detailid+"]:last"));
				
				//生成自动序列号
				var snumber = tr1.find("input[snumber]");
				try{
					snumber.attr('value', snumber.attr('snumber').replace("{x}", detail_list['d_'+detailid].linemax));
				}catch(e){}
				
				//还原本行数据
				for(var i=0;i<data.length;i++){
					try{
						th.find("#"+data[i].id).attr('value', data[i].value);
						if(data[i].checked){
							th.find("#"+data[i].id).attr('checked', data[i].checked);
						}
					}catch(e){}
				}
				rmax = detail_list['d_'+detailid].rowmax;
							
				//重新设置input的name属性
				tr1.find("input,textarea,select").each(function(){
					var input = $(this),
						inputname = input.attr("name").replace("wf_detail_1_", "wf_detail_" + rmax+"_").replace("wf_detailC_1_", "wf_detailC_" + rmax + "_").replace("wf_detailJ_1_", "wf_detailJ_" + rmax + "_").replace("wf_detailbid_1", "wf_detailbid_" + rmax),
						inputid = input.attr("id").replace("wf_detail_1", "wf_detail_" + rmax).replace("wf_detailbid_1", "wf_detailbid_" + rmax);
						
					input.attr('name', inputname);
					input.attr('id', inputid);
					try{
						inputtoid = input.attr("to").replace("wf_detail_1", "wf_detail_" + rmax).replace("wf_detailbid_1", "wf_detailbid_" + rmax);
						input.attr('to', inputtoid);
					}catch(e){}
					
					if(input.attr('gid')){
						var inputgid = input.attr("gid").replace("wf_detail_1", "wf_detail_" + rmax);
						input.attr('gid', inputgid);
					}
					if(input.attr("detailbindid")){
						input.val('');
					}
					if(input.parent("label")){
						input.parent("label").attr('for', input.attr("id").replace("wf_detail_1", "wf_detail_" + rmax));
					}
					
					var tag		= $(this).attr("tagName");
					var type	= $(this).attr("type");
					var dvalue	= $(this).attr("dvalue");
					if(tag == "INPUT" && type == "hidden"){
						//隐藏的表单
						if($(this).attr("moneyconvert") == 'true'){
							input.attr('from', input.attr('from').replace("wf_detail_1", "wf_detail_" + rmax));
							input.attr('to', input.attr('to').replace("wf_detail_1", "wf_detail_" + rmax));
							bindMoneyConvert();
						}
					}
					
					//输入框
					if((tag == "INPUT" && type == "text") || tag == "TEXTAREA"){
						if(dvalue != undefined){
							$(this).val(dvalue);
						}else{
							if($(this).attr("otype") !== "macro"){
								$(this).val("");
							};
						};
					}
					//多选框
					if(tag == "INPUT" && (type == "checkbox" || type == "radio")){
						if(dvalue == "true"){
							$(this).attr("checked", true);
						}else{
							$(this).attr("checked", false);
						};
					}
					//下拉框
					if(tag == "SELECT"){
						$(this).find("option").each(function(){
							if($(this).attr("dvalue") == "true"){
								$(this).attr("selected", "selected")
							}else{
								$(this).removeAttr("selected")
							};
						});
					}
				});
				
				//生成删除图标
				tr1.find("img[class=wf_row_jia]").attr("src", "cnoa/resources/images/cnoa/wf-dt-jian.gif").unbind('click').bind('click', function(){
					removeRow(tr1);
					//重排序号
					//生成自动序列号
					var j = 1;
					var rows = th.parent().children("tr[class=detail-line][detail="+detailid+"]").each(function(){
						var rw = $(this);
						var snumber1 = rw.find("input[snumber]");
						try{snumber1.attr('value', snumber1.attr('snumber').replace("{x}", j));}catch(e){}
						detail_list['d_'+detailid].linemax = j;
						j++;
					});
				}).attr('ext:qtip', lang('delThisLink')).show();
				
				//明细表 - 行计算控件(横向小计等复杂公式计算)
				bindOneRowCalculate(tr1, rmax);
				
				//明细表 - 列相加(给input:hidden:columnsum=true的表单传送列相加信息)
				//bindColumnSum();
			});
		});
	};

	var bindElementSelectEvent = function(){
		//使用冒泡法解决效率问题
		$("#"+formPanelId).bind('click.selectall', function(e){
			try {
				if (e.target.tagName == "INPUT" || e.target.tagName == "TEXTAREA") {
					var el = $(e.target);
					if(el.attr('isnum') == 'true'){
						if (!elIsSelected(el)) {
							el.select();
						}
					}
				}
			}catch(e){}				
		});
	};
	
	/**
	 * 找出该radio中所要显示和隐藏的控件 
	 */
	var initRadioCheckboxShowHide = function(){
		var els = $(".wf_form_content input:hidden:[showhide=true]");
		var flag = true; //不存在勾选时控制默认隐藏
		els.each(function(){
			var shows, hides, stmp, htmp;
			
			shows = stmp = $(this).attr('display').split(",");
			hides = htmp = $(this).attr('undisplay').split(",");

			// if($(this).attr('fromtype')=='checkbox' && $(this).attr('checkboxck') == '0'){
			// 	hides = stmp;
			// 	shows = htmp;
			// }
			if($(this).attr('fromtype')=='checkbox' && $(this).attr('checkboxck') == '1'){
				flag = false; //存在默认勾选是，不需要默认隐藏
				try{
					for(var i=0;i<shows.length;i++){
						//cdump('$(".wf_form_content span[class=group][oname='+shows[i]+']")');
						$(".wf_form_content span[class=group][oname="+shows[i]+"]").attr("showhide", "show").show();
					}
				}catch(e){}
				try{
					for(var i=0;i<hides.length;i++){
						//cdump('$(".wf_form_content span[class=group][oname='+hides[i]+']")');
						$(".wf_form_content span[class=group][oname="+hides[i]+"]").attr("showhide", "hide").hide();
					}
				}catch(e){}
			}
		});
		if(flag){
			$(".wf_form_content span[class=group]").each(function(){
				$(this).attr("showhide", "hide").hide();
			});
		}
	}; 
	
	var to1bits = function (flt) {    
		if(parseFloat(flt) == flt){
			return Math.round(flt * 1000000) / 1000000;    
		}
	};
	
	var roundResult = function(result, roundtype, baoliu){
		if(roundtype == 'round'){
			return result.toFixed(baoliu);
		}else if(roundtype == 'add'){
			var diejiaNum = 1;
			for(var i=0; i<baoliu;i++){
				diejiaNum = diejiaNum*10;
			}
			result = Math.ceil(result*diejiaNum)/diejiaNum;
			return result;
		}else{
			return result;
		}
	};
	
	/**
	 * 初始化计算控件[包括明细表外的控件和明细表的列相加]
	 */
	var bindCalculate = function(){
		var calculates = $(".wf_form_content input:hidden:[calculate=true][detail=false]");
		
		var getValue = function(gongshi, roundType){
			var string = "";
			//是否是日期计算
			var isDateCal = true;
			//组合计算公式
			for(var i=0;i<gongshi.length;i++){
				if(gongshi[i].indexOf("wf|") != -1){
					var val = $("#wf_field_"+gongshi[i].replace("wf|", "")).val();

					//如果是日期计算
					if(/^[0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2} [0-9]{2,2}:[0-9]{2,2}$/ig.test(val)){
						val = strtotime(val);
					}else{
						isDateCal = false;
					}

					if(val != undefined){
						val = cleanSymbol(val);
						string += (val == '' ? 0 : val);
					}else{
						string += 0;
					}
				}else if(gongshi[i].indexOf("wfd|") != -1){
					var val = $("#wf_detail_column_"+gongshi[i].replace("wfd|", "")).val();
					if(val != undefined){
						val = cleanSymbol(val);
						string += (val == '' ? 0 : val);
					}else{
						string += 0;
					}
				}else{
					string += gongshi[i];
				}
			}
			try{
				//计算公式计算
				eval("var rt = "+string+";");
				rt = to1bits(rt);

				//如果是日期计算
				if(isDateCal){
					if(roundType == 'hour'){
						rt = secondtohour(rt);
					}else if(roundType == 'day'){
						rt = secondtoday(rt);
					}else if(roundType == 'dayhour'){
						rt = secondtodayhour(rt);
					}
				}
			}catch(e){
				var rt = lang('calculationError');
			}
			return rt;
		};
		
		calculates.each(function(){
			var to				= $("#"+$(this).attr('to'));
			var gongshi			= Ext.decode($(this).attr('gongshi'));
			var fields			= [];
			var detailFields	= [];
			var string = "";
			var roundtype		= $(this).attr('roundtype');
			var baoliu			= $(this).attr('baoliu');

			//提取计算控件需要的条件控件
			for (var i = 0; i < gongshi.length; i++) {
				if(gongshi[i].indexOf("wf|") != -1){
					//明细表外的控件
					fields.push(gongshi[i].replace("wf|", ""));
				}else if(gongshi[i].indexOf("wfd|") != -1){
					//明细表内的控件
					detailFields.push(gongshi[i].replace("wfd|", ""));
				}
			}
			//try{
			//	to.attr('value', getValue(gongshi)).change();
			//}catch(e){}
			
			//第一次加载的时候，处理一下，让计算得以顺利进行
			try{$("#wf_field_"+fields[0]).change();}catch(e){}
			
			//第一次加载的时候，处理一下，让计算得以顺利进行
			try{$("#wf_detail_column_"+detailFields[0]).change();}catch(e){}
			
			//对条件控件添加事件[明细表外的控件]
			for(var i=0; i<fields.length; i++) {
				var field = $("#wf_field_"+fields[i]);
				var events = 'keyup.c.'+fields[i]+'.'+to.attr('id')+' change.c.'+fields[i]+'.'+to.attr('id');
				field.unbind(events).bind(events, function() {
					try{
						var result = roundResult(getValue(gongshi,roundtype), roundtype, baoliu);
						if (roundtype == 2){
							if(!Ext.isEmpty($(to).attr("autoformat"))){
								$(to).val(result);
								result = changeAutoFormat($(to), false, true);
							}
						}
						to.attr('value', result).change();
					}catch(e){}
				});
				field.change();
			}
			//对条件控件添加事件[明细表内的控件](明细表的总计隐藏hidden影响的外部计算控件)
			for(var i=0; i<detailFields.length; i++) {
				var field = $("#wf_detail_column_"+detailFields[i]);
				var events = 'keyup.d.'+detailFields[i]+'.'+to.attr('id')+' change.d.'+detailFields[i]+'.'+to.attr('id');
				field.unbind(events).bind(events, function() {
					try{
						var result = roundResult(getValue(gongshi), roundtype, baoliu);
						if (roundtype == 2){
							if(!Ext.isEmpty($(to).attr("autoformat"))){
								$(to).val(result);
								result = changeAutoFormat($(to), false, true);
							}
						}
						to.attr('value', result).change();
					}catch(e){}
				});
			}
		});
	};
	
	var bindOneRowCalculate = function(tr, row){
		var cals	= tr.find("input[otype=calculate]");
		
		var getValue = function(gongshi, cu){
			var string = "";
			for(var i=0; i<gongshi.length; i++){
				if (gongshi[i].indexOf("wf|") != -1) {
					//明细表外的条件控件
					var val = $("#wf_field_"+gongshi[i].replace("wf|", "")).val();
					if(val != undefined){
						val = cleanSymbol(val);
						string += (val == '' ? 0 : val);
					}else{
						string += 0;
					}
				}else if(gongshi[i].indexOf("wfd|") != -1){
					//明细表内的条件控件
					var val = $("#wf_detail_"+row+"_"+gongshi[i].replace("wfd|", "")).val();
					if(val != undefined){
						val = cleanSymbol(val);
						string += (val == '' ? 0 : val);
					}else{
						string += 0;
					}
				}else if(gongshi[i].indexOf("wfo|") != -1){
					//其他明细表内的条件控件 
					var val = $("#wf_detail_column_"+gongshi[i].replace("wfo|", "")).val();
					if(val != undefined){
						val = cleanSymbol(val);
						string += (val == '' ? 0 : val);
					}else{
						string += 0;
					}
				}else{
					string += gongshi[i];
				}
			}
			
			try{
				//计算公式计算
				eval("var rt = "+string+";");
				rt = to1bits(rt);
			}catch(e){
				var rt = lang('calculationError');
			}

			if(isNaN(rt)){
				rt = 0;
			};
			if(rt == Infinity){
				rt = 0;
			}
			return rt;
		};
		
		cals.each(function(){
			var cu = $(this);
			var gongshi = Ext.decode(cu.attr('gongshi'));
			var roundtype		= cu.attr('roundtype');
			var baoliu			= cu.attr('baoliu');

			outFields	= [];
			inFields	= [];
			otherFields	= [];
			for(var i=0; i<gongshi.length; i++){
				if (gongshi[i].indexOf("wf|") != -1) {
					//明细表外的条件控件
					outFields.push(gongshi[i].replace("wf|", ""));
				}else if(gongshi[i].indexOf("wfd|") != -1){
					//明细表内的条件控件
					inFields.push(gongshi[i].replace("wfd|", ""));
				}else if(gongshi[i].indexOf("wfo|") != -1){
					//其他明细表内的条件控件
					otherFields.push(gongshi[i].replace("wfo|", ""));
				}
			}
			cu.attr('value', getValue(gongshi, cu, tr)).change();
			
			//明细表内的条件控件
			for(var k=0; k<inFields.length; k++){
				//明细表内的条件控件
				var field = $("#wf_detail_"+row+"_"+inFields[k]);
				var events = 'keyup.e.'+cu.attr('id')+'_'+inFields[k] + ' change.e.'+cu.attr('id')+ '_'+inFields[k];
				//alert(events);
				field.unbind(events).bind(events, function(){
					var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
					cu.attr('value', result).change();
				});
			}
			
			for(var j=0; j<outFields.length; j++){
				//明细表外的条件控件
				var field = $("#wf_field_"+outFields[j]);
				var events = 'keyup.f.'+outFields[j]+'.'+cu.attr('id')+' change.f.'+outFields[j]+'.'+cu.attr('id');
				field.unbind(events).bind(events, function(){
					var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
					cu.attr('value', result).change();
				});
			}
			
			for(var l=0; l<otherFields.length; l++){
				//其他明细表内的条件控件
				var field = $("#wf_detail_column_"+otherFields[l]);
				var events = 'keyup.g.'+otherFields[l]+'.'+cu.attr('id')+' change.g.'+otherFields[l]+'.'+cu.attr('id');
				field.unbind(events).bind(events, function(){
					var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
					cu.attr('value', result).change();
				});
			}
		});
	};
	
	/**
	 * 初始化明细表内的控件
	 */
	var bindRowCalculate = function(){
		//找出所有行
		var detail_trs = $(".wf_form_content tr[class=detail-line]");

		var getValue = function(gongshi, to, tr){
			var row = tr.attr('rownum');
			var string = "";
			for(var i=0; i<gongshi.length; i++){
				if (gongshi[i].indexOf("wf|") != -1) {
					//明细表外的条件控件
					var val = $("#wf_field_"+gongshi[i].replace("wf|", "")).val();
					if(val != undefined){
						val = cleanSymbol(val);
						string += (val == '' ? 0 : val);
					}else{
						string += 0;
					}
				}else if(gongshi[i].indexOf("wfd|") != -1){
					//明细表内的条件控件
					var val = $("#wf_detail_"+row+"_"+gongshi[i].replace("wfd|", "")).val();
					if(val != undefined){
						val = cleanSymbol(val);
						string += (val == '' ? 0 : val);
					}else{
						string += 0;
					}
				}else if(gongshi[i].indexOf("wfo|") != -1){
					//其他明细表内的条件控件 
					var val = $("#wf_detail_column_"+gongshi[i].replace("wfo|", "")).val();
					if(val != undefined){
						val = cleanSymbol(val);
						string += (val == '' ? 0 : val);
					}else{
						string += 0;
					}
				}else{
					string += gongshi[i];
				}
			}
			
			try{
				//计算公式计算
				eval("var rt = "+string+";");
				rt = to1bits(rt);
			}catch(e){
				var rt = lang('calculationError');
			}

			if(isNaN(rt)){
				rt = 0;
			};
			if(rt == Infinity){
				rt = 0;
			}
			return rt;
		};
		
		detail_trs.each(function(){
			var tr		= $(this);
			var cuAll	= tr.find("input[otype=calculate]");
			var rn		= tr.attr('rownum');
			cuAll.each(function(){
				var cu = $(this);
				var gongshi = Ext.decode(cu.attr('gongshi'));
				var roundtype		= cu.attr('roundtype');
				var baoliu			= cu.attr('baoliu');
			
				outFields	= [];
				inFields	= [];
				otherFields	= [];
				for(var i=0; i<gongshi.length; i++){
					if (gongshi[i].indexOf("wf|") != -1) {
						//明细表外的条件控件
						outFields.push(gongshi[i].replace("wf|", ""));
					}else if(gongshi[i].indexOf("wfd|") != -1){
						//明细表内的条件控件
						inFields.push(gongshi[i].replace("wfd|", ""));
					}else if(gongshi[i].indexOf("wfo|") != -1){
						//其他明细表内的条件控件
						otherFields.push(gongshi[i].replace("wfo|", ""));
					}
				}

				//cu.attr('value', getValue(gongshi, cu, tr)).change();
				//明细表内的条件控件
				for(var k=0; k<inFields.length; k++){
					//明细表内的条件控件
					var field = $("#wf_detail_"+rn+"_"+inFields[k]);
					var events = 'keyup.e.'+cu.attr('id')+'_'+inFields[k] + ' change.e.'+cu.attr('id')+ '_'+inFields[k];
					//alert(events);
					field.unbind(events).bind(events, function(){
						var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
						cu.attr('value', result).change();
					});
				}
				
				for(var j=0; j<outFields.length; j++){
					//明细表外的条件控件
					var field = $("#wf_field_"+outFields[j]);
					var events = 'keyup.f.'+outFields[j]+'.'+cu.attr('id')+' change.f.'+outFields[j]+'.'+cu.attr('id');
					field.unbind(events).bind(events, function(){
						var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
						cu.attr('value', result).change();
					});
				}
				
				for(var l=0; l<otherFields.length; l++){
					//其他明细表内的条件控件
					var field = $("#wf_detail_column_"+otherFields[l]);
					var events = 'keyup.g.'+otherFields[l]+'.'+cu.attr('id')+' change.g.'+otherFields[l]+'.'+cu.attr('id');
					field.unbind(events).bind(events, function(){
						var result = roundResult(getValue(gongshi, cu, tr), roundtype, baoliu);
						cu.attr('value', result).change();
					});
				}
			});
		});
	};
	
//	var bindOneColumnSum = function(tr, row){
//		tr.find("input[otype=calculate]").each(function(){
//			var gongshi = Ext.decode($(this).attr('gongshi'));
//			for(var i=0; i<gongshi.length; i++){
//				if(gongshi[i].indexOf("wfd|") != -1){
//					var field = gongshi[i].replace('wfd|', '');
//					var columnSum = $("#wf_detail_column_"+field);
//					var fieldText = "#wf_detail_"+row+"_"+field;
//					var events = 'keyup.h.'+fieldText+'.'+field+' change.h.'+fieldText+'.'+field;
//					$(fieldText).bind(events, function(){
//						
//					});
//				};
//			};
//		});
//	}
	
	/**
	 * 初始化明细表列相加计算控件
	 * 只在表单载入时调用一次
	 * <input type="hidden" detailid="2498" columnsum="true" columnid="329" id="wf_detail_column_329">
	 * 100行数据大概3秒左右
	 */
	var initColumnSum = function(){
		//var D = new Date();
		//cdump(D.getHours()+":"+D.getMinutes()+":"+D.getSeconds()+":"+D.getMilliseconds());
		var columns	= $(".wf_form_content input:[columnsum=true]");

		columns.each(function(){
			var hidden = $(this);
			var columnid = hidden.attr("columnid");
			var detailid = hidden.attr("detailid");
			var trs = $(".wf_form_content tr:[class=detail-line][detail="+detailid+"]");
			trs.find("input:[name^=wf_detail_][name$=_"+columnid+"],input:[name^=wf_detailJ_][name$=_"+columnid+"]").each(function(){
				var string = "0";
				var val = $(this).val();
					string += "+";
					string += val ? cleanSymbol(val) : 0;

				try{
					//计算公式计算
					eval("var rt = "+string+";");
					rt = to1bits(rt);
				}catch(e){
					var rt = lang('calculationError');
				}
				hidden.attr('value', rt);
			});
		});
		//var D = new Date();
		//cdump(D.getHours()+":"+D.getMinutes()+":"+D.getSeconds()+":"+D.getMilliseconds());
	}
	/**
	 * 计算列相加
	 */
	var bindColumnSum = function(event){
		var nowel = $(event.target),
			fieldid = nowel.attr('field'),
			tr = nowel.parents("tr:[class=detail-line]"),
			detailtableid = tr.attr("detail"),
			hidden = $("input[detailid="+detailtableid+"][columnsum=true][columnid="+fieldid+"]");
		if(hidden.length > 0){
			var string = "0";
			var els = $("tr[detail]").find("input[field="+fieldid+"]").each(function(){
				var val = $(this).val();
				string += "+";
				string += val ? cleanSymbol(val) : 0;
			});
			try{
				//计算公式计算
				eval("var rt = "+string+";");
				rt = to1bits(rt);
			}catch(e){
				var rt = lang('calculationError');
			}
			hidden.attr('value', rt).change();
		}
	};
	
	/**
	 * 初始化明细表列相加计算控件,包括添加和移除
	 * @已经移除
	 * @linxiaoqing 
	 * @2012-11-23
	 */
	var makeColumnSumChange = function(){
		var columns	= $(".wf_form_content input:[columnsum=true]");
		
		//处理列累加
		columns.each(function(){
			var hidden = $(this);
			var columnid = hidden.attr("columnid");
			var detailid = hidden.attr("detailid");
			var trs = $(".wf_form_content tr:[class=detail-line][detail="+detailid+"]");
			setTimeout(function(){
				trs.find("[name^=wf_detail_1_"+columnid+"]").change();
				trs.find("[name^=wf_detailJ_1_"+columnid+"]").change();
			}, 50);
		});
	};

	var bindQueryButton = function(uFlowId, flowId){
		//办公用品
		var button	= $(".wf_form_content img:[class=wf_list_query][query=true]");
		var bindfuncs = [];
		button.each(function(){
			bindfuncs.push($(this).attr('querykey'));
			$(this).click(function(){
				var wfquerywindow = new CNOA.wf.query($(this).attr('detail'), $(this).attr('bindfunc'));
				wfquerywindow.show();
			})
		});
		for(var i=0, len=$.unique(bindfuncs).length; i<len; ++i){
			loadJs('app/wf/scripts/query.'+bindfuncs[i]+'.js', true);
		}
		//其他（车辆、会议...）
		var bindfuncs = $(".wf_form_content a[otype=bindfunc]");
		bindfuncs.each(function(){
			var val = $(this).attr('value');
			if ( val == 'salaryApprove' ) {
				//若绑定字段为薪酬管理，则带审批字段ID 以及 uFlowId。否则获取不了数据。
				var salaryApproveId = 0;
				salaryApproveId = $(".wf_form_content a[otype=salaryApproveId]").attr('value');
				loadJs('app/wf/scripts/query.businessData.js', true, function(){
					eval("queryBusinessData('" + val + "','" + salaryApproveId + "','" + uFlowId + "');");
				});
			} else if ( val == 'userReadlySubmit' || val == 'userSubmit') {
				var userCid = 0;
				userCid = $(".wf_form_content a[otype=userCid]").attr('value');
				loadJs('app/wf/scripts/query.businessData.js', true, function(){
					eval("queryBusinessData('" + val + "','" + userCid + "','" + uFlowId + "');");
				});
			}else {
				loadJs('app/wf/scripts/query.businessData.js', true, function(){
					eval("queryBusinessData('" + val + "','" + "" + "','" + uFlowId + "','" + flowId + "');");
				});
			}
		})
	};

	this.bindMinMaxNumber = bindMinMaxNumber;
	
	/**
	 * 绑定签章
	 */
	var bindSignature = function(){
		var img = $("img[signaturetype=graph]");
		var btnSignature = $(".wf_form_content input:[type=button][otype=signature]");
		var hasgraph = false;
		var haselectron = false;
		btnSignature.each(function(){
			var signaturetype = $(this).attr('signaturetype');
			if(signaturetype == 'graph'){
				hasgraph = true;
			}
			if(signaturetype == 'electron'){
				haselectron = true;
			}
		});
		if(hasgraph){
			loadJs('cnoa/app/wf/scripts/signature.graph.js', true);
		}
		if(haselectron){
			loadJs('cnoa/app/wf/scripts/signature.electron.js', true, function(){
				CNOA_wf_signature_electron = new CNOA_signature_electronClass();
			});
		}
		
		btnSignature.each(function(){
			var me = $(this);
			var signaturetype = $(this).attr('signaturetype');
			me.unbind('click').bind('click', function(){
				if(signaturetype == 'graph'){
					CNOA_wf_signature_graph = new CNOA_signature_graphClass(me);
					CNOA_wf_signature_graph.show();
				}else if(signaturetype == 'electron'){
					CNOA_wf_signature_electron.show(me);
				}
			});
		});
		img.each(function(){
			var me = $(this);
			var signaturetype = $(this).attr('signaturetype');
			loadJs('cnoa/app/wf/scripts/signature.'+signaturetype+'.js', true, function(){
				CNOA_wf_signature_graph = new CNOA_signature_graphClass(me);
				CNOA_wf_signature_graph.bindImg(me);
			});
		});
	};
	
	//数据源控件
	var bindDatasource = function(){
		var ds = $('input[otype=datasource]');
		if(ds.length>0){
			loadJs('cnoa/app/wf/scripts/query.datasource.js', true, function(){
				ds.each(function(){
					$(this).click(function(){
						var wfquerywindow = new CNOA.wf.datasource($(this).attr('fieldId'), $(this).attr('datasource'), $(this).attr('maps'));
						wfquerywindow.show();
					});

					var maps = Ext.decode($(this).attr('maps')) || {};
					//兼容旧数据格式(内容为对象)
					if(Ext.isObject(maps)){
						for(var m in maps){
							$('#'+maps[m]).attr("readOnly", "readOnly");
						}
					}
					//处理新数据格式(内容为数组)
					if(Ext.isArray(maps)){
						Ext.each(maps, function(v){
							if(v.editable != '1'){
								$('#'+v.des).attr("readOnly", "readOnly");
							}
						});
					}
				});
			});
		}
	}
	
	var bindSignData = function(){
		var sealData = $(".wf_form_content input[sealstoredata=true]");
		if(sealData.length > 0){
			//加载JS
			loadJs('cnoa/app/wf/scripts/signature.electron.js', true, function(){
				CNOA_wf_signature_electron = new CNOA_signature_electronClass();
				CNOA_wf_signature_electron.SetSealValue(sealData);
			});
		}
	};
	
	//部门预算申请控件
	var bindBudgetdept = function(){
		var budgetDept = $(".wf_form_content input[budgetDept=true]");
		if(budgetDept.length>0){
			budgetDept.click(function(){
				var me = this,
					th = $(me),
					id = th.attr('to'),
					sum = th.val(),
					deptName = th.attr('deptname'),
					deptId = $('[name=wf_budgetDept_'+id+']').val(),
					dept_ID = Ext.id(),
					budget_ID = Ext.id(),
					balance_ID = Ext.id(),
					balance;
				var getBalance = function(deptId){
					Ext.Ajax.request({
						url: "index.php?app=wf&func=flow&action=use&modul=getBalanceByDeptId",
						params: {deptId:deptId},
						success: function(response){

							balance = Ext.decode(response.responseText) || 0;
							Ext.getCmp(budget_ID).setDisabled(false);
							Ext.getCmp(balance_ID).setText("<b style='color:#ff0000'>"+lang('availableAmount')+"："+balance+"</b>", false);
						}
					});
				},
				getFieldsId = function(name){
					Ext.Ajax.request({
						url: "index.php?app=wf&func=flow&action=use&modul=getFieldsId",
						method: 'post',
						params: {fieldsId: id},
						success: function(response){
							var response = Ext.decode(response.responseText);
							if (response.failure) {
								CNOA.msg.alert(response.msg);
							} else {
								var fieldId = response.data.fieldId;
								$('#wf_field_' + fieldId).val(name);
							}
						}
					});
				},
				form = new Ext.form.FormPanel({
					padding: 10,
					labelAlign: 'right',
					labelWidth: 70,
					cls: 'cnoa_form',
					items:[
						{
							xtype: "textfield",
							readOnly: true,
							allowBlank: false,
							fieldLabel: lang('department'),
							emptyText: lang('pleaseSelectDept'),
							name: 'deptName',
							listeners: {
								'render': function(th){
									th.to = dept_ID;
								},
								'focus': function(th){
									people_selector('dept', th, false, getBalance);
								}
							}
	
						},{
							xtype: "hidden",
							name: "deptId",
							id: dept_ID
						},{
							xtype: 'compositefield',
							id: budget_ID,
							fieldLabel: lang('amountRequest'),
							allowBlank: false,
							disabled: true,
							items:[
								{
									xtype: 'textfield',
									allowBlank: false,
									regex: /^\d+$|^\d+.\d{0,4}$/,
									name: 'sum'
								},{
									xtype: 'label',
									id: balance_ID
								}
							]
						}
					]
				}),
				win = new Ext.Window({
					title: lang('deptBudget'),
					border: false,
					width: 380,
					height: 180,
					layout: 'fit',
					modal: true,
					items: form,
					buttons:[
						{
							text: lang('ok'),
							handler: function(){
								var f = form.getForm(),
									values = f.getValues(),
									sum = values.sum,
									deptName = values.deptName;
								
								if(sum==""){
									CNOA.msg.alert(lang('appAmountEmpty'));
									return ;
								}
								if(parseFloat(sum)>parseFloat(balance)){
									CNOA.msg.alert(lang('appMoneyNotExceed'));
									return ;
								}
								th.val(sum).change();
								th.attr('ext:qtip', lang('deptBudget') + '<br/>' +lang('department') + ":" +deptName+'<br/>'+lang('amount')+':'+sum);
								th.attr('deptname', deptName);
								$('input[name=wf_budgetDept_'+id+'][budgetDeptId=true]').val(f.findField('deptId').getValue());
								getFieldsId(deptName);
								win.close();
							}
						},{
							text: lang('cancel'),
							handler: function(){
								win.close();
							}
						}
					]
				}).show();
				form.getForm().setValues({sum:sum, deptId:deptId, deptName:deptName});
				if(deptId) getBalance(deptId);
			});
		}
	};
	//项目预算申请控件
	var bindBudgetproj = function(){
		var budgetProj = $(".wf_form_content input[budgetProj=true]");
		if(budgetProj.length>0){
			budgetProj.click(function(){
				var me = this,
					th = $(me),
					id = th.attr('to'),
					proj_sum = th.val(),
					projName = th.attr('projname'),
					projId = $('[name=wf_budgetProj_'+id+']').val(),
					deptId = $('[name=wf_budgetProj_dept_'+id+']').val(),
					proj_ID = Ext.id(),
					budget_proj_ID = Ext.id(),
					balance_proj_ID = Ext.id(),
					balance_proj;
				var getProjBalance = function(projId){
					Ext.Ajax.request({
						url: "index.php?app=wf&func=flow&action=use&modul=getBalanceByProjId",
						params: {projId:projId},
						success: function(response){
							balance_proj = Ext.decode(response.responseText) || 0;
							Ext.getCmp(budget_proj_ID).setDisabled(false);
							Ext.getCmp(balance_proj_ID).setText("<b style='color:#ff0000'>"+lang('availableAmount')+"："+balance_proj+"</b>", false);
						}
					});
				},
				getFieldsId = function(name){
					Ext.Ajax.request({
						url: "index.php?app=wf&func=flow&action=use&modul=getFieldsId",
						method: 'post',
						params: {fieldsId: id},
						success: function(response){
							var response = Ext.decode(response.responseText);
							if (response.failure) {
								CNOA.msg.alert(response.msg);
							} else {
								var fieldId = response.data.fieldId;
								$('#wf_field_' + fieldId).val(name);
							}
						}
					});
				},
				projform = new Ext.form.FormPanel({
					padding: 10,
					labelAlign: 'right',
					labelWidth: 70,
					cls: 'cnoa_form',
					items:[
						{
							xtype: "combo",
							editable: false,
							allowBlank: false,
							fieldLabel: '项目',
							typeAhead: true,
						    triggerAction: 'all',
						    lazyRender:true,
						    mode: 'local',
						    hiddenName: 'projId',
						    valueField: 'projId',
						    displayField: 'name',
						    id: proj_ID,
						    store: new Ext.data.Store({
						    	autoLoad: true,
						    	baseParams: {deptId: deptId},
						    	proxy:new Ext.data.HttpProxy({url: "index.php?app=wf&func=flow&action=use&modul=getProjCombo", disableCaching: true}),   
								reader:new Ext.data.JsonReader({totalProperty:"total",root:"data", fields:['projId', 'name']})
						    }),
						    listeners: {
						    	'select': function(combo, record, index){
						    		projId = record.json.projId;
						    		projform.getForm().setValues({proj_sum:proj_sum, projId:projId, projName:projName});
									if(projId) getProjBalance(projId);
						    	}
						    }
	
						},{
							xtype: 'compositefield',
							id: budget_proj_ID,
							fieldLabel: lang('amountRequest'),
							allowBlank: false,
							disabled: true,
							items:[
								{
									xtype: 'textfield',
									allowBlank: false,
									regex: /^\d+$|^\d+.\d{0,4}$/,
									name: 'proj_sum'
								},{
									xtype: 'label',
									id: balance_proj_ID
								}
							]
						}
					]
				}),
				proj_win = new Ext.Window({
					title: '项目预算',
					border: false,
					width: 380,
					height: 150,
					layout: 'fit',
					modal: true,
					items: projform,
					buttons:[
						{
							text: lang('ok'),
							handler: function(){
								var f = projform.getForm(),
									values = f.getValues(),
									proj_sum = values.proj_sum,
									proj_Id  = values.projId;
									projName = Ext.getCmp(proj_ID).getRawValue();
								if(proj_sum==""){
									CNOA.msg.alert(lang('appAmountEmpty'));
									return ;
								}
								if(parseFloat(proj_sum)>parseFloat(balance_proj)){
									CNOA.msg.alert(lang('appMoneyNotExceed'));
									return ;
								}

								th.val(proj_sum).change();
								th.attr('ext:qtip', '项目预算:<br/>项目:'+projName+'<br/>金额:'+proj_sum);
								th.attr('projname', projName);
								$('input[name=wf_budgetProj_'+id+'][budgetProjId=true]').val(proj_Id);
								getFieldsId(projName);
								proj_win.close();
							}
						},{
							text: lang('cancel'),
							handler: function(){
								proj_win.close();
							}
						}
					]
				}).show();
				projform.getForm().setValues({proj_sum:proj_sum, projId:projId});
				Ext.getCmp(proj_ID).setRawValue(projName);
				if(projId) getProjBalance(projId);
			});
		}
	};

	//考勤请假
	var bindAttLeave = function(){
		var attLeave = $(".wf_form_content input[attLeave=true]");

		if(attLeave.length>0){
			attLeave.click(function(){
				var me = this,
					th = $(me),
					id = th.attr('to'),
					sum = th.val(),
					leaveId = $('[name=wf_attLeave_'+id+']').val(),
					leave_ID = Ext.id(),
					leave_ID = Ext.id();
					// balance_ID = Ext.id(),
					// balance;
				var getBalance = function(stime, etime){
					Ext.Ajax.request({
						url: "index.php?app=wf&func=flow&action=use&modul=getAttLeaveDays",
						method: 'post',
						params: {stime: stime, etime: etime, fieldsId: id},
						success: function(response){
							var response = Ext.decode(response.responseText);
							if (response.failure) {
								CNOA.msg.alert(response.msg);
							} else {
								var data = response.data,
									stime = data.stime,
									etime = data.etime,
									days  = data.days,
									fieldId = data.fieldId,
									value = stime + ' ' + lang('to') + ' ' + etime;

									th.val(value);
									$('input[name=wf_attLeave_'+id+'][attLeave=true]').val(value);
									$('#wf_field_' + fieldId).val(days);
									win.close();
							}
						}
					});
				},

				workStarTime = new Ext.ux.form.DateTimeField({
						fieldLabel: lang('startTime'),
						format:'Y-m-d H:i',
						value: new Date()
					}),

				workEndTime = new Ext.ux.form.DateTimeField({
						fieldLabel: lang('endTime'),
						format:'Y-m-d H:i',
						value: new Date()
					}),

				form = new Ext.form.FormPanel({
					padding: 10,
					labelAlign: 'right',
					labelWidth: 70,
					cls: 'cnoa_form',
					items:[
						workStarTime,
						workEndTime
					]
				}),
				win = new Ext.Window({
					title: lang('leaveTime'),
					border: false,
					width: 380,
					height: 150,
					layout: 'fit',
					modal: true,
					items: form,
					buttons:[
						{
							text: lang('ok'),
							handler: function(){
								var stime = workStarTime.getValue(),
									etime = workEndTime.getValue();

								getBalance(stime, etime);
							}
						},{
							text: lang('cancel'),
							handler: function(){
								win.close();
							}
						}
					]
				}).show();
			});
		}
	};
	
	
	//考勤出差
	var bindAttEvectionAllDays = function(){
		var attEvection = $(".wf_form_content input[attEvection=true]");

		if(attEvection.length>0){
			attEvection.click(function(){
				var me = this,
					th = $(me),
					id = th.attr('to'),
					sum = th.val(),
					leaveId = $('[name=wf_attEvection_'+id+']').val(),
					leave_ID = Ext.id(),
					leave_ID = Ext.id();
					// balance_ID = Ext.id(),
					// balance;
				var getBalance = function(stime, etime){
					Ext.Ajax.request({
						url: "index.php?app=wf&func=flow&action=use&modul=getAttEvectionDays",
						method: 'post',
						params: {stime: stime, etime: etime, fieldsId: id},
						success: function(response){
							var response = Ext.decode(response.responseText);
							if (response.failure) {
								CNOA.msg.alert(response.msg);
							} else {
								var data = response.data,
									stime = data.stime,
									etime = data.etime,
									days  = data.days,
									fieldId = data.fieldId,
									value = stime + ' ' + lang('to') + ' ' + etime;

									th.val(value);
									$('input[name=wf_attEvection_'+id+'][attEvection=true]').val(value);
									$('#wf_field_' + fieldId).val(days);
									win.close();
							}
						}
					});
				},

				workStarTime = new Ext.ux.form.DateTimeField({
						fieldLabel: lang('startTime'),
						format:'Y-m-d H:i',
						value: new Date()
					}),

				workEndTime = new Ext.ux.form.DateTimeField({
						fieldLabel: lang('endTime'),
						format:'Y-m-d H:i',
						value: new Date()
					}),

				form = new Ext.form.FormPanel({
					padding: 10,
					labelAlign: 'right',
					labelWidth: 70,
					cls: 'cnoa_form',
					items:[
						workStarTime,
						workEndTime
					]
				}),
				win = new Ext.Window({
					title: lang('evection'),
					border: false,
					width: 380,
					height: 150,
					layout: 'fit',
					modal: true,
					items: form,
					buttons:[
						{
							text: lang('ok'),
							handler: function(){
								var stime = workStarTime.getValue(),
									etime = workEndTime.getValue();

								getBalance(stime, etime);
							}
						},{
							text: lang('cancel'),
							handler: function(){
								win.close();
							}
						}
					]
				}).show();
			});
		}
	};


	//考勤时间
	var bindAttTime = function(){
		var attTime = $(".wf_form_content input[attTime=true]");

		if(attTime.length>0){
			attTime.click(function(){
				var me = this,
					th = $(me),
					id = th.attr('to'),
					sum = th.val(),
					leaveId = $('[name=wf_attTime_'+id+']').val(),
					leave_ID = Ext.id(),
					leave_ID = Ext.id();
					// balance_ID = Ext.id(),
					// balance;
				var getBalance = function(stime, etime){
					Ext.Ajax.request({
						url: "index.php?app=wf&func=flow&action=use&modul=getAttTime",
						method: 'post',
						params: {stime: stime, etime: etime, fieldsId: id},
						success: function(response){
							var response = Ext.decode(response.responseText);
							if (response.failure) {
								CNOA.msg.alert(response.msg);
							} else {
								var data = response.data,
									stime = data.stime,
									etime = data.etime,
									days  = data.days,
									fieldId = data.fieldId,
									value = stime + ' ' + lang('to') + ' ' + etime;

									hour  = data.hour,
									fieldId2 = data.fieldId2,

									th.val(value);
									$('input[name=wf_attTime_'+id+'][attTime=true]').val(value);
									$('#wf_field_' + fieldId).val(days);
									$('#wf_field_' + fieldId2).val(hour);
									win.close();
							}
						}
					});
				},

				workStarTime = new Ext.ux.form.DateTimeField({
						fieldLabel: lang('startTime'),
						format:'Y-m-d H:i',
						value: new Date()
					}),

				workEndTime = new Ext.ux.form.DateTimeField({
						fieldLabel: lang('endTime'),
						format:'Y-m-d H:i',
						value: new Date()
					}),

				form = new Ext.form.FormPanel({
					padding: 10,
					labelAlign: 'right',
					labelWidth: 70,
					cls: 'cnoa_form',
					items:[
						workStarTime,
						workEndTime
					]
				}),
				win = new Ext.Window({
					title: '考勤时间',
					border: false,
					width: 380,
					height: 150,
					layout: 'fit',
					modal: true,
					items: form,
					buttons:[
						{
							text: lang('ok'),
							handler: function(){
								var stime = workStarTime.getValue(),
									etime = workEndTime.getValue();

								getBalance(stime, etime);
							}
						},{
							text: lang('cancel'),
							handler: function(){
								win.close();
							}
						}
					]
				}).show();
			});
		}
	};

	/**/
	
	
	var initRichText = function(){
		var els = $(".wf_form_content input:hidden:[richtext=true]");
		if(els.length > 0){
			//加载JS
			loadJs('scripts/editor/nicedit.min.js', true, function(){
				els.each(function(){
					var from = $(this).attr("from");
					var fromel = $("#"+from);
					new nicEditor({maxHeight:fromel.height()+10}).panelInstance(from);
				});
			});
		}
	};

	this.formInitForView = function(){
		//初始化已签章数据
		bindSignData();
		
		//控制radio/checkbox初始显示隐藏
		initRadioCheckboxShowHide();

		initRichText();
	};
	
	this.formInit = function(id, uFlowId, flowId){
		formPanelId = id;

		//明细表 - 加减图标
		bindDetailtable();

		//绑定金额大写转小写
		bindMoneyConvert();
		
		//明细表 - 行计算控件(横向小计等复杂公式计算)
		bindRowCalculate();
		
		//明细表 - 列相加(给input:hidden:columnsum=true的表单计算出值)
		initColumnSum();
		
		//计算控件
		bindCalculate();
		
		//绑定查询按钮事件
		bindQueryButton(uFlowId, flowId);

		//绑定控件的最大值最小值检测事件
		bindMinMaxNumber();
		
		//签章控件
		bindSignature();
		
		//部门预算
		bindBudgetdept();

		//项目预算
		bindBudgetproj();

		//考勤请假
		bindAttLeave();
		
		//考勤出差
		bindAttEvectionAllDays();

		//考勤时间
		bindAttTime();
		
		//数据源控件
		bindDatasource();

		//绑定表单内容点击全选事件
		bindElementSelectEvent();
		
		//控制radio/checkbox初始显示隐藏
		initRadioCheckboxShowHide();

		//如果有多行文本富文本编辑器
		initRichText();
		
		//明细表 - 重新触发列相加
		makeColumnSumChange();

		$('#'+formPanelId).bind("keyup", function(event){
			try{
				for(var i in ckme.eventList.keyup){
					if(event.target.id == ckme.eventList.keyup[i].tid){
						ckme.eventList.keyup[i].func.call(this);
					}
				}
			}catch(e){
				//cdump("keyup error:"+e);
			}

			try{bindColumnSum(event);}catch(e){}

			//数字控件自动格式化
			var el = event.target;
			with(el){
				if(tagName == "INPUT" && type=="text"){
					if(!Ext.isEmpty($(el).attr("autoformat"))){
						changeAutoFormat($(el), true);
					}
				}
			}
		});
		$('#'+formPanelId).bind("change", function(event){
			try{
				for(var i in ckme.eventList.change){
					if(event.target.id == ckme.eventList.change[i].tid){
						ckme.eventList.change[i].func.call(this);
					}
				}
			}catch(e){
				//cdump("change error:"+e);
			}

			try{bindColumnSum(event);}catch(e){}
			var el = event.target;

			if ($(el).attr('otype') == 'calculate') {
				if(!Ext.isEmpty($(el).attr("autoformat"))){
					changeAutoFormat($(el), false)
				}
			}
		});
	};
	
	this.rsyncCheckbox = function(checkbox){
		var source = $(checkbox);
		var target = $("#"+$(checkbox).attr("checkboxid"));
		if(checkbox.checked){
			target.val(source.val());
		}else{
			target.val("");
		}
	}

	//第三个参数可能是checkbox对象本身，也可能是checkbox的id
	this.showHide = function(shows, hides, objcfg){
		var stmp,htmp;
		if(shows){
			stmp = shows = shows.split(",");
		}
		if(hides){
			htmp = hides = hides.split(",");
		}
		
		//如果是checkbox，在取消勾选的时候，反转显示/隐藏的组合
		if(objcfg.checkbox){
			if(objcfg.checkbox.checked == false){
				shows = htmp;
				hides = stmp;
			}
		}
		if(objcfg.checkboxid){
			checkbox = $("#"+objcfg.checkboxid).get(0);
			if(checkbox.checked == false){
				shows = htmp;
				hides = stmp;
			}
		}
		
		try{
			for(var i=0;i<shows.length;i++){
				//cdump('$(".wf_form_content span[class=group][oname='+shows[i]+']")');
				$(".wf_form_content span[class=group][oname="+shows[i]+"]").attr("showhide", "show").show();
			}
		}catch(e){}
		try{
			for(var i=0;i<hides.length;i++){
				//cdump('$(".wf_form_content span[class=group][oname='+hides[i]+']")');
				$(".wf_form_content span[class=group][oname="+hides[i]+"]").attr("showhide", "hide").hide();
			}
		}catch(e){}
	};
	
	this.check = function(){
		//格式化控件(有些控件不能用blur来自动格式化，如计算控件，因为是只读的，没有blur事件)
		//改成实时格式化，不需要再格式化了
		//formatAutoFormat();
		
		try{
			CNOA_wf_signature_electron.GetSealValue();
		}catch(e){}
		
		var pass = false;
		
		//检查必填
		var must = checkMust();
		
		//检查格式
		var a;
		
		if(must){
			pass = true;
		}
		
		return must;
	};
	
	this.formatAll = function(){
		//格式化控件(有些控件不能用blur来自动格式化，如计算控件，因为是只读的，没有blur事件)
		//改成实时格式化，不需要再格式化了
		//formatAutoFormat();
	};
})();

function validateDateTime(datetime, fmt){
	if(datetime == '') {
		return '';
	}
	var dt = Date.parseDate(datetime, fmt);
	if(dt == undefined){
		return false;
	}else{
		return datetime;
	}
}

/**
 * 
 * @param {Object} fnumber 要转换的数值
 * @param {Boolean} fdivide 是否切割成千分位
 * @param {Number} fpoint 保留小数点后多少位
 * @param {Boolean} fpad 小数点后是否补零到满足位数
 * @param {Boolean} fround 结尾是否四舍五进
 *       formatNumber(b,       false,   3,      false, false);
 */
function formatNumber(fnumber, fdivide, fpoint, fpad, fround) {
	var fuhao = "";
	if(fnumber.indexOf("-") === 0){
		fuhao = "-";
		fnumber = fnumber.substring(1, fnumber.length);
	}
	if(fnumber != ''){
		if(!/^-?[\d|,]+(\.\d+)?$/.test(fnumber)){
			return false;
		}
	}
	
	var fnum = fnumber + '';
	var revalue = "";
	if (fnum == null) {
		if(fpad){
			for (var i = 0; i < fpoint; i++) revalue += "0";
			return "0." + revalue;
		}else{
			return "0";
		}
	}
	fnum = fnum.replace(/^\s*|\s*$/g, '');
	if (fnum == "") {
		if(fpad){
			for (var i = 0; i < fpoint; i++) revalue += "0";
			return "0." + revalue;
		}else{
			return "0";
		}
	}
	fnum = fnum.replace(/,/g, "");
	if (fround) {
		var temp = "0.";
		if(fpad){
			for (var i = 0; i < fpoint; i++) temp += "0";
		}		
		temp += "5";
		fnum = Number(fnum) + Number(temp);
		fnum += '';
	}
	var arrayf = fnum.split(".");
	if (fdivide) {
		if (arrayf[0].length > 3) {
			while (arrayf[0].length > 3) {
				revalue = "," + arrayf[0].substring(arrayf[0].length - 3, arrayf[0].length) + revalue;
				arrayf[0] = arrayf[0].substring(0, arrayf[0].length - 3);
			}
		}
	}
	
	revalue = arrayf[0] + revalue;
	
	if (arrayf.length == 2 && fpoint != 0) {
		arrayf[1] = arrayf[1].substring(0, (arrayf[1].length <= fpoint) ? arrayf[1].length: fpoint);
		
		var tmp = arrayf[1];

		if (arrayf[1].length < fpoint) if(fpad){for (var i = 0; i < fpoint - arrayf[1].length; i++){tmp += "0";}}
		revalue += "." + tmp;
	} else if (arrayf.length == 1 && fpoint != 0) {
		if(fpad){revalue += ".";for (var i = 0; i < fpoint; i++) revalue += "0";}
	}
	return fuhao + revalue;
}