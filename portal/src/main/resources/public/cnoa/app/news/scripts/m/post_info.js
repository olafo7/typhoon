//判断当前客户端Android和ios
if(/android/ig.test(navigator.userAgent)){ //安卓
	var userAgent = 'android';
}else{ //ios或者其他
	var userAgent = 'ios';
}
//全局变量`函数
var myFns = {
	/*
	 *	方法: myFns.loaded()
	 *	功能: 初始化iScroll控件
	 */
	loaded:function(){
		myScroll = new IScroll('#wrapper', {
		scrollbars: false, //隐藏滚动条
		zoom: true, //缩放功能
	     mouseWheel: true,
	     wheelAction: 'zoom',
		preventDefault: false, //阻止默认事件
		preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
		});
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	},
	//加载等待效果
	showLoading:function(text){
		var opts = {
			lines: 13, // 画线数
			length: 11, // 每条线的长度
			width: 5, // 线厚度
			radius: 17, // 内圆半径
			corners: 1, // 角圆度(0....1)
			rotate: 0, // 旋转偏移
			color: '#FFF', // 颜色 例：#rgb 和 #rrggbb
			speed: 1, // 每秒轮
			trail: 60, // 余辉百分率
			shadow: false, // 是否渲染一个阴影
			hwaccel: false, // 是否使用硬件加速
			className: 'spinner', // CSS类分配给纺织
			zIndex: 2e9, // z-index（默认为2000000000）
			top: 'auto', // 在像素中相对于父的顶部位置
			left: 'auto' // 左位置相对于父在像素
		};
		var target = document.createElement("div");
		document.body.appendChild(target);
		var spinner = new Spinner(opts).spin(target);
		iosOverlay({
			text: text,
			spinner: spinner
		});
		return false;
	},
	/*
	 *	方法: myFns.replace_em(str)
	 *	功能: 切换QQ表情
	 */
	replace_em: function(str){
	    // str = str.replace(/\</g,'&lt;');
	    // str = str.replace(/\>/g,'&gt;');
	    // str = str.replace(/\n/g,'<br/>');
	    str = str.replace(/\[em_([0-9]*)\]/g,'<img style="width:16px;margin-bottom:6px;" src="resources/images/m/qqface/face/$1.gif" border="0" />');
	    return str;
	},
		/**
	 *	方法: myFns.is_images(ext)
	 *	功能: 判断一个值是否在数组中 图片
	 *	参数: <string> ext 文件后缀 
	 *	返回: <boolean> 返回true false
	 */
	is_images:function(ext){
		var ext = ext.toLowerCase(); //大写转小写
		var view = ['jpg','jpeg','png','gif','bmp']; //ios支持在线查看类型
		if($.inArray(ext,view) < 0){
			return false;
		}
		return true;
	},
	/*
	 *	方法: myFns.append_data(json,isEmpty);
	 *	功能: 追加数据到前台显示
	 *  参数: json  JSON数据   isEmpty 是否要清空数据
	 */
	append_data: function(json,isEmpty){
		if (isEmpty) {
			$('.bbs_content').empty();
		};
		$('.bbs_content').attr('data-user',json.username);
		var from = myFns.getUriString('type');
		if (json.success) {
			var str = '<div class="bbs_post" data-fid='+json.data.fid+' data-reply='+json.data.id+' data-pid='+json.data.id+'>';
				str += "<div class='face'>";
				str += "<img src="+json.data.face+">";
				str += '</div>';
				str += "<div class='post_about'>";
				str += '<a class="author" href="javascript:void(0)">'+json.data.postname+'</a> <span class="fname">'+json.data.fname+'</span>';
				if (from == 1) {
					str += '<botton type="botton" class="btn btn-danger btn-xs btnDel">删除</botton>';
				}
				str += '</div>';
				str += '<div class="post_title">'+json.data.title+'</div>';
				json.data.content = json.data.content.replace(/^"+|"$/g, "");
				json.data.content = unescape(json.data.content.replace(/\\/g,"%"));
	        	json.data.content = myFns.replace_em(json.data.content);
				str += '<div class="post_content">'+json.data.content+'</div>'; 
			 	// data-pswp-uid在每个相册中必须是唯一的，data-size好像没啥用  但是必填
				str += '<div class="my-gallery" data-pswp-uid="1">';
				if(json.data.attach.length > 0 ){
					$.each(json.data.attach,function(index,att_val){
						if (myFns.is_images(att_val['ext'])) {
							str += '<figure>';
							str += '<div>';
		                    str += '<a href="'+att_val['salf_url']+'" data-size="1920x1920">';
		                    str += '<img style="width:100%;" src="'+att_val['salf_url']+'">';
		                    str += '</a>';
		                    str += '</div>';
		                    str += '</figure>';
	                    }
                	})
				}
				str += '</div>';
				if (json.data.address) {
		        	str += '<div class="post_address">'+json.data.address+'</div>';
		        }
				str += '<div class="post_opt">';
				str += '<div class="post_time">'+json.data.posttime+'</div>';
				str += '<div class="post_reply"></div>';
				str += '<div class="post_replyAndLike" data-status="true">';
				if (json.data.like == 1) {  //如果自己已经点赞过的  就显示取消点赞 否则就显示点赞
					str += '<div class="like_post" data-status="0">取消</div>';
				} else {
					str += '<div class="like_post" data-status="1">点赞</div>';
				}
				str += '<div class="reply_post">评论</div>';
				str += '</div>';
				str += '</div>';
				//附件信息
				if(json.data.attach.length > 0 ){
					$.each(json.data.attach,function(index,array){
						str += '<div class="panel panel-default panel-attach-box" data-attachid="'+array['attachid']+'">';
						str += '<div class="panel-heading" data-name="'+array['name']+'" data-size="'+array['size']+'" data-ext="'+array['ext']+'">';
						str += '<span class="fileName">'+array.name+'</span>';
						str += '<div class="panel-attach">';
						var status   = userAgent === 'ios' ? 'style="display:none"' : '';//IOS端不提供下载
						var isimages = !myFns.is_images(array['ext']) ? 'style="display:none"' : '';
						var fileExt  = ['doc','docx','ppt','pptx','xls','xlsx','txt','pdf'];
						var fileExts = $.inArray(array['ext'].toLocaleLowerCase(), fileExt) == -1 ? 'style="display:none"' : '';
						str += '<botton type="botton" class="btn btn-info btn-xs btnBrowse" '+isimages+' data-src="'+array['url']+'">浏览</botton>';
						str += '<botton type="botton" class="btn btn-info btn-xs btnFileView" '+fileExts+' data-src="'+array['url']+'">查看</botton>';
						str += '<botton type="botton" class="btn btn-success btn-xs btnDownload" '+status+' data-src="'+array['url']+'">下载</botton>';
						str += '</div></div></div>';
					})
				}
				if (json.data.reply.length > 0 || json.data.likecount == 1) {   //如果评论为空  那么就隐藏三角符号
					str += '<div class="sj"><img src="resources/images/m/bbs/sj.png"></div>';
				}
				str += '<div class="reply reply_'+json.data.id+'">';
				if (json.data.likename) {  //如果点赞的人员存在则显示
					str += '<div class="like like_'+json.data.id+'">'
					str += '<img src="resources/images/m/bbs/like.png" />';
					str += '<span>( <label class="likecount">'+json.data.likecount+'</label> )</span>';
					str += '<span class="likename">'+json.data.likename+'</span>';
					str += '</div>';
				}
				if (json.data.reply.length > 0) {  //如果回复OR评论的人存在则显示
					$.each(json.data.reply, function(key,ele){
						var content = unescape(ele['content'].replace(/\\/g,"%"));
						content = content.replace(/^"+|"$/g, "");
						str += '<div class="replys_'+ele['id']+'" data-reply_count='+ele['count']+' data-rid='+ele['id']+'><span style="color:#3B70B7;">'+ele['replyUser']+ '</span>: ' +myFns.replace_em(content)+'</div>';
					})
				}
				
			$('.bbs_content').append(str);

			//初始化图片缩放控件
			ImagesZoom.init({
		    	"elem": ".btnBrowse"
			});

			// 刷新界面
			myScroll.refresh();
		}
	},
	/**
	 *	方法: myFns.getUriString(key)
	 *	功能: 获取url参数值
	 *	参数: <string> key URL键
	 *	返回: <string> URL 对应的键值
	 */
	getUriString: function(key){
		var reg = new RegExp("(^|&)"+ key +"=([^&]*)(&|$)");
	    var r = window.location.search.substr(1).match(reg);
	    if(r!=null)return  unescape(r[2]); return null;
	}
}

$(function(){

	// 初始化
	myFns.loaded();

	var formtype = myFns.getUriString('type');

	// 获取后台传回前台的数据
    function getJsonData(url){
    	var pid = myFns.getUriString('pid');
    	$.ajax({
			dataType: "json",
			type: 'post',
			url: url,
			data: {
				'pid': pid,
				'type': formtype
			},
			success: function(json){
				myFns.append_data(json,true);
				photo_swipes();
			}
		})
    }

    // 初始化数据
    getJsonData("m.php?app=news&func=bbs&action=bbs&task=getPostInfo");

    //ios文件在线浏览
	$(document).on('click','.btnFileView',function(){
		var fileSrc  = $(this).attr('data-src'); //文件地址
		var fileName = $(this).closest('.panel-heading').attr('data-name'); //文件名带后缀
		var fileExt  = $(this).closest('.panel-heading').attr('data-ext'); //文件后缀
		var seat = fileName.lastIndexOf("."); //查找某字符在字符串中最后一次出现的位置
		fileName = fileName.substring(0, seat); //纯文件名
		var host = window.location.host; //当前域名
		var fileAllSrc = host+'/'+fileSrc; //文件绝对路径
		var width = $(document).width(), height = $(window).height();
		if (myFns.isWeixn()) {
			if (userAgent == "android") {
				iospopWin.showWin(width, height, fileName, fileSrc, fileExt.toLocaleLowerCase());
			} else {
				iospopWin.showWin(width, height, fileName, fileSrc, fileExt.toLocaleLowerCase());
			}
		} else {
			try {
				CNOAApp.viewDocument(fileAllSrc, fileName, fileExt);
			} catch(e) {
				window.location.href = 'js://push_web_view_controller__'+fileAllSrc+'__'+fileName;
			}
		}
	})

	//下载文件
	$(document).on('click','.btnDownload',function(){
		var url = $(this).attr('data-src');
		window.location.href = url;
	})

	//删除帖子
	$(document).on('click','.btnDel',function(){
		var delPost = $(this);
		$.confirm({
		    title: '提示',
		    content: '确定删除这个帖子吗？',
		    animation: "top",
		    cancelButtonClass: 'btn-danger',
		    confirmButton: '确定',
			cancelButton: '取消',
		    confirm: function(){
		    	var pid = delPost.parents('.bbs_post').attr('data-pid');
		    	$.ajax({
					dataType: "json",
					type: "post",
					data: {
						'pid': pid
					},
					url: 'm.php?app=news&func=bbs&action=bbs&task=delPost',
					success: function(json){
						//添加成功跳转论坛中心
						setTimeout(function(){
							window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=myposts';
						},1400)
			  			myScroll.refresh();
					},
					complete: function(XMLHttpRequest, textStatus){
						$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
					}
				})
		    }
		});
	})

    // 点击评论显示评论和点赞
    $(document).on('touchstart','.post_reply',function(){
    	var status = $(this).next().attr('data-status');
    	$('.post_replyAndLike').removeClass('reply_choose');
    	if (status == 'true') {
    		$(this).next().addClass('reply_choose');
    		$(this).next().attr('data-status',false);
    	} else {
    		$('.post_replyAndLike').removeClass('reply_choose');
    		$(this).next().attr('data-status',true);
    	}
    })

    // 评论
    $(document).on('touchstart','.reply_post',function(){
    	$('#replyModal').modal('show');
    	var reply = $(this).parents('.bbs_post').attr('data-reply');
    	var fid = $(this).parents('.bbs_post').attr('data-fid');
    	$('#replyModal').attr('data-reply',reply);
    	$('#replyModal').attr('data-fid',fid);
    	$('.post_replyAndLike').removeClass('reply_choose');
    	$('.post_replyAndLike').attr('data-status',true);
    })

    // 初始化表情
    $('.emotion').qqFace({
        id : 'facebox', //表情盒子的ID
        assign:'saytext', //给那个控件赋值
        path:'resources/images/m/qqface/face/'    //表情存放的路径
    });

    // 提交回复OR评论的内容
    $(document).on('click','.sub_btn',function(){
    	var content = $("#saytext").val(); // 获取回复OR评论的内容
    	var nowUser = $('.bbs_content').attr('data-user'); //获取当前用户的用户名
        var pid 	= $('#replyModal').attr('data-reply');	//获取pid
        var fid 	= $('#replyModal').attr('data-fid');	//获取fid
        var count 	= $('#replyModal').attr('data-count');	//获取已回复总数
    	var str 	= '<div><span style="color:#3B70B7;">'+nowUser+'</span>: '+myFns.replace_em(content)+'</div>'; 
    	$('.reply_'+pid).append(str);
        var rlen = $('.reply_'+pid +' div').length;
        if (rlen == 1) {
        	var rstr = '<div class="sj"><img src="resources/images/m/bbs/sj.png"></div>';
        	$('.reply_'+pid).before(rstr);
        }
    	$('#replyModal').modal('hide'); // 隐藏回复OR评论模态框
    	$('#saytext').val('')
    	$.ajax({
			dataType: "json",
			type: "post",
			data: {
				'pid': pid,
				'fid':fid,
				'content': content
			},
			url: 'm.php?app=news&func=bbs&action=bbs&task=subMessage',
			success: function(json){

			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
    })

    // 点赞
    $(document).on('touchstart','.like_post',function(){
    	var status = $(this).attr('data-status');   //获取点赞的状态
    	var pid    = $(this).parents('.bbs_post').attr('data-pid'); //获取帖子的id
    	var like_post = $(this);
    	var reply  = like_post.parents('.bbs_post').find('.reply');  //获取帖子的评论框
    	var like   = like_post.parents('.bbs_post').find('.like');   //获取帖子的点赞框
    	var sj   = like_post.parents('.bbs_post').find('.sj');   //获取帖子的点赞框
    	if (status == '1') {
    		$(this).html('取消');
    		$(this).attr('data-status',0);
    	} else {
			$(this).html('点赞');
    		$(this).attr('data-status',1);
    	}
    	$.ajax({
			dataType: "json",
			type: "post",
			data: {
				'pid': pid,
				'status': status
			},
			url: 'm.php?app=news&func=bbs&action=bbs&task=editLike',
			success: function(json){
				var count = json.data.count,str,
					likename = json.data.likename;
					if (count == 0) {
						like.remove(); //没人点赞就清除点赞框
						if(reply.children().length == 0){
							sj.remove();
						}
					}
					if (count == 1) {
						like.remove();	//先清除再重新追加点赞框 避免多次点击取消
						sj.remove();
						sjstr = '<div class="sj"><img src="resources/images/m/bbs/sj.png"></div>';
					 	str = '<div class="like like_'+pid+'">'
						str += '<img src="resources/images/m/bbs/like.png" />';
						str += '<span>( <label class="likecount">'+count+'</label> )</span>';
						str += '<span class="likename">'+likename+'</span>';
						str += '</div>';
						reply.prepend(str);
						reply.before(sjstr);
					}
					like_post.parents('.bbs_post').find('.likecount').html(count);   //修改当前点赞的总人数
					like_post.parents('.bbs_post').find('.likename').html(likename); //修改当前点赞的人员
					$('.post_replyAndLike').removeClass('reply_choose');	//隐藏操作框
					$('.post_replyAndLike').attr('data-status',true);
					myScroll.refresh();
			},
			complete: function(XMLHttpRequest, textStatus){
				$(".ui-ios-overlay").removeClass("ios-overlay-show").addClass("ios-overlay-hide");
			}
		})
    })

    // 跳转至上一步
    $(document).on('touchstart','#btnBack',function(){
    	if (formtype == 1) {
    		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=myposts';
    	} else if(formtype == 2){
    		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=myreplys';
    	} else if(formtype == 3){
    		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=mylikes';
    	} else if(formtype == 4){
    		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=mycollects';
    	} else if(formtype == 'forumlist'){
    		var id = myFns.getUriString('id');
    		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=forumlist&id='+id;
    	} else if(formtype == 'hot'){
    		window.location.href = 'm.php?app=news&func=bbs&action=bbs&task=loadPage&from=hot';
    	}
    })

    // 附件图片查看
	function photo_swipes(){
		var initPhotoSwipeFromDOM = function(gallerySelector) {

		// 解析来自DOM元素幻灯片数据（URL，标题，大小...）
		// (children of gallerySelector)
		var parseThumbnailElements = function(el) {
		    var thumbElements = el.childNodes,
		        numNodes = thumbElements.length,
		        items = [],
		        figureEl,
		        linkEl,
		        size,
		        item,
				divEl;

		    for(var i = 0; i < numNodes; i++) {

		        figureEl = thumbElements[i]; // <figure> element

		        // 仅包括元素节点
		        if(figureEl.nodeType !== 1) {
		            continue;
		        }
				divEl = figureEl.children[0];
		        linkEl = divEl.children[0]; // <a> element
		        size = linkEl.getAttribute('data-size').split('x');

		        // 创建幻灯片对象
		        item = {
		            src: linkEl.getAttribute('href'),
		            w: parseInt(size[0], 10),
		            h: parseInt(size[1], 10)
		        };



		        if(figureEl.children.length > 1) {
		            // <figcaption> content
		            item.title = figureEl.children[1].innerHTML; 
		        }

		        if(linkEl.children.length > 0) {
		            // <img> 缩略图节点, 检索缩略图网址
		            item.msrc = linkEl.children[0].getAttribute('src');
		        } 

		        item.el = figureEl; // 保存链接元素 for getThumbBoundsFn
		        items.push(item);
		    }

		    return items;
		};

		// 查找最近的父节点
		var closest = function closest(el, fn) {
		    return el && ( fn(el) ? el : closest(el.parentNode, fn) );
		};

		// 当用户点击缩略图触发
		var onThumbnailsClick = function(e) {
		    e = e || window.event;
		    e.preventDefault ? e.preventDefault() : e.returnValue = false;

		    var eTarget = e.target || e.srcElement;

		    // find root element of slide
		    var clickedListItem = closest(eTarget, function(el) {
		        return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
		    });

		    if(!clickedListItem) {
		        return;
		    }

		    // find index of clicked item by looping through all child nodes
		    // alternatively, you may define index via data- attribute
		    var clickedGallery = clickedListItem.parentNode,
		        childNodes = clickedListItem.parentNode.childNodes,
		        numChildNodes = childNodes.length,
		        nodeIndex = 0,
		        index;

		    for (var i = 0; i < numChildNodes; i++) {
		        if(childNodes[i].nodeType !== 1) { 
		            continue; 
		        }

		        if(childNodes[i] === clickedListItem) {
		            index = nodeIndex;
		            break;
		        }
		        nodeIndex++;
		    }



		    if(index >= 0) {
		        // open PhotoSwipe if valid index found
		        openPhotoSwipe( index, clickedGallery );
		    }
		    return false;
		};

		// parse picture index and gallery index from URL (#&pid=1&gid=2)
		var photoswipeParseHash = function() {
		    var hash = window.location.hash.substring(1),
		    params = {};

		    if(hash.length < 5) {
		        return params;
		    }

		    var vars = hash.split('&');
		    for (var i = 0; i < vars.length; i++) {
		        if(!vars[i]) {
		            continue;
		        }
		        var pair = vars[i].split('=');  
		        if(pair.length < 2) {
		            continue;
		        }           
		        params[pair[0]] = pair[1];
		    }

		    if(params.gid) {
		        params.gid = parseInt(params.gid, 10);
		    }

		    return params;
		};

		var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
		    var pswpElement = document.querySelectorAll('.pswp')[0],
		        gallery,
		        options,
		        items;

		    items = parseThumbnailElements(galleryElement);

		    // 这里可以定义参数
		    options = {
		      barsSize: { 
		        top: 100,
		        bottom: 100
		      }, 

		        // define gallery index (for URL)
		        galleryUID: galleryElement.getAttribute('data-pswp-uid'),

		        getThumbBoundsFn: function(index) {
		            // See Options -> getThumbBoundsFn section of documentation for more info
		            var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
		                pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
		                rect = thumbnail.getBoundingClientRect(); 

		            return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
		        }

		    };

		    // PhotoSwipe opened from URL
		    if(fromURL) {
		        if(options.galleryPIDs) {
		            // parse real index when custom PIDs are used 
		            for(var j = 0; j < items.length; j++) {
		                if(items[j].pid == index) {
		                    options.index = j;
		                    break;
		                }
		            }
		        } else {
		            // in URL indexes start from 1
		            options.index = parseInt(index, 10) - 1;
		        }
		    } else {
		        options.index = parseInt(index, 10);
		    }

		    // exit if index not found
		    if( isNaN(options.index) ) {
		        return;
		    }

		    if(disableAnimation) {
		        options.showAnimationDuration = 0;
		    }

		    // Pass data to PhotoSwipe and initialize it
		    gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		    gallery.init();
		};

		// loop through all gallery elements and bind events
		var galleryElements = document.querySelectorAll( gallerySelector );

		for(var i = 0, l = galleryElements.length; i < l; i++) {
		    galleryElements[i].setAttribute('data-pswp-uid', i+1);
		    galleryElements[i].onclick = onThumbnailsClick;
		}

		// Parse URL and open gallery if it contains #&pid=3&gid=1
		var hashData = photoswipeParseHash();
		if(hashData.pid && hashData.gid) {
		    openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
		}
		};
		// execute above function
		initPhotoSwipeFromDOM('.my-gallery');
	}
})