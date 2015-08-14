

<?php if ($is_logged && 1 != 1) { ?>
<link rel="stylesheet" href="catalog/view/theme/zbj/stylesheet/chat/chatservice.css">
<link rel="stylesheet" href="catalog/view/theme/zbj/stylesheet/chat/perfect-scrollbar.css">
<script src="catalog/view/theme/zbj/js/chat/jquery.mousewheel.js"></script>
<script src="catalog/view/theme/zbj/js/chat/perfect-scrollbar.js"></script>

<script>
      jQuery(document).ready(function ($) {
        "use strict";
        $('#chat-user').perfectScrollbar({suppressScrollX: true});
      });
    </script>
    <div id="zbj_talking" style="right: 0px; bottom: 5px;">
        <div class="zbj_chat_bd">
            <div class="icon_talking"></div>
            <div class="zbj_newChat">点击咨询</div>
        </div>
    </div>
    <div id="chat-box" class="chat-box" style="right: 0px; bottom: 5px;">
        <div id="chat_content_head" class="chat_content_head">
            <div class="chat-user-title">
                <span class="user_img">
                    <!--<img src="http://d01.res.meilishuo.net/ap/a/b7/ca/6ca3b168ce6ab2838ea4d911456b_178_178.cc.jpg" alt="">-->
                </span>
                <span class="chat-user-name"><a href="javascript:void(0);">请先选择一个联系人！</a></span>
            </div>
            <div class="chat-header-min">
                <a class="head_conbtn hcb_min" onclick="slideup_box()"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <div class="chat_content_body">
            <div id="chat-user" class="chat-user-list">
                <ul>
                    <!--
                    <li class="zbj_server_name active" id="chat_user_1" data-userID="1">
                        <div class="avatar">
                            <a>
                                <img class="gray" style="height:20px;width:20px;" src="http://d01.res.meilishuo.net/ap/a/b7/ca/6ca3b168ce6ab2838ea4d911456b_178_178.cc.jpg">
                            </a>
                        </div>
                        <div class="zbj_nickname">吴永江</div>
                        <div class="btn_con">
                            <a class="del"><i class="fa fa-times-circle"></i></a>
                        </div>
                    </li>
                    <li class="zbj_server_name" id="chat_user_2" data-userID="2">
                        <div class="avatar">
                            <a>
                                <img style="height:20px;width:20px;" src="http://d01.res.meilishuo.net/ap/a/b7/ca/6ca3b168ce6ab2838ea4d911456b_178_178.cc.jpg">
                            </a>
                        </div>
                        <div class="zbj_nickname">吴永江</div>
                        <div class="btn_con">
                            <a class="del"><i class="fa fa-times-circle"></i></a>
                        </div>
                    </li>
                    -->
                    <?php foreach ($chat_users as $user) { ?>
                    <li class="zbj_server_name" id="chat_user_<?php echo $user['id']; ?>" data-userID="<?php echo $user['id']; ?>">
                        <div class="avatar">
                            <a>
                                <img style="height:20px;width:20px;" src="<?php echo $user['avatar']; ?>">
                            </a>
                        </div>
                        <div class="zbj_nickname"><?php echo $user['name']; ?></div>
                        <div class="btn_con">
                            <a class="del"><i class="fa fa-times-circle"></i></a>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="mnkf_dialog">
                <div class="mnkf_dialog_content" id="mnkf_dialog_content">
                    <!--
                    <div class="chat-message" id="message_list">
                        <div class="mnkf_dialog_receive">
                            <p class="other_name">吴永江&nbsp;10:48:10</p>
                                <div class="msgbox">你好呀，这是一个测你好呀，这是一个测试，测试纹波你好呀，这是一测试纹波你好呀，这是一个测试，测试纹波试，测试测试纹波你好呀，这是一个测试，测试纹波试，测试测试纹波你好呀，这是一个测试，测试纹波试，测试个测试，测试纹波试，测试纹波
                                    <em class="dialog_arrow"></em>
                                </div>
                        </div>
                        <div class="mnkf_dialog_send">
                            <p class="my_name">我&nbsp;11:05:34</p>
                            <div class="msgbox">
                                你好呀你好呀me 这是什么东西聊天工具两天你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具你好呀你好呀me 这是什么东西聊天工具两天聊天工具聊天工具
                                <em class="dialog_arrow"></em>
                            </div>
                        </div>
                    </div>
                    -->
                    <?php foreach ($chat_users as $user) { ?>
                    <div class="chat-message" id="message_list_<?php echo $user['id']; ?>">
                        <?php if (isset($chat_historys[$user['id']])) { ?>
                            <?php foreach ($chat_historys[$user['id']] as $message) { ?>
                                <?php if ($message['is_self']) { ?>
                                    <div class="mnkf_dialog_send">
                                        <p class="my_name">我&nbsp;<?php echo $message['date']; ?></p>
                                        <div class="msgbox">
                                            <?php echo $message['text']; ?>
                                            <em class="dialog_arrow"></em>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="mnkf_dialog_receive">
                                        <p class="other_name"><?php echo $message['name']; ?>&nbsp;<?php echo $message['date']; ?></p>
                                        <div class="msgbox">
                                            <?php echo $message['text']; ?>
                                            <em class="dialog_arrow"></em>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <?php } ?>

                </div>
                <div class="mnkf_dialog_operating" id="mnkf_dialog_operating"></div>
                <div class="mnkf_dialog_ibox">
                    <textarea name="textarea" id="live-message-text" placeholder="请输入消息内容..."></textarea>
                </div>
                <div class="mnkf_dialog_foot">
                    <p></p>
                    <div class="mnkf_send">
                        <a class="s_btn" href="javascript:void(0)" onclick="sendmsg();">发送</a>
                        <a class="st_arrow" href="javascript:void(0)" onclick="showPostKey(event);"><em></em></a>
                        <ul id="send_btn_choose" style="display:none;">
                            <li id="line1-left" class="curr" node-type="wbim_enter_send_li" onclick="setpostkey(1)"><a href="javascript:void(0)">按Enter键发送</a></li>
                            <li id="line2-left" class="" onclick="setpostkey(2)"><a href="javascript:void(0)">按Ctrl+Enter键发送</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    var postkey = 1; //发送消息的热键  1：ENTER 2：CTRL+ENTER
    function showPostKey(e) {
        document.getElementById('send_btn_choose').style.display = 'block';
        var evt = e || window.event;
        try {
            evt.stopPropagation ? evt.stopPropagation() : (evt.cancelBubble=true);
        } catch(e){

        }
    }

    function slideup_box(){
        $('#chat-box').slideUp('fast');
        $('#zbj_talking').show();
    }

    function setpostkey(args){
        showpkset();
        if(args==1)
        {
            if(!($('#line1-left').hasClass('curr'))){
                $('#line1-left').addClass('curr');
                $('#line2-left').removeClass('curr');
            }
        }
        else if(args==2)
        {
            if(!($('#line2-left').hasClass('curr'))){
                $('#line2-left').addClass('curr');
                $('#line1-left').removeClass('curr');
            }
        }

        postkey = args;
    }

    function showpkset(){
        $(document).click(function(){
            $('#send_btn_choose').css('display','none');
        });
    }

    function scrollToBottom(){ // 滚动条置底
        try{
            setTimeout(function() {
               document.getElementById('mnkf_dialog_content').scrollTop = document.getElementById('mnkf_dialog_content').scrollHeight;
            }, 1);
        } catch(e) {

        }
    }

    function sendmsg(){
        //send message code

        if ($('#live-message-text').val().length == 0 || $('#live-message-text').hasClass('disabled')) return false;

        var _sendTo = $('.zbj_server_name.active').first().data('userid');
        if (typeof _sendTo == 'undefined' || !_sendTo) {
            alert('请先选择一个联系人！');
            return false;
        }
        var _date = new Date();
        var _dateStr = _date.getFullYear();
        _dateStr += '-' + ((_date.getMonth()+1) < 10 ? '0'+(_date.getMonth()+1) : (_date.getMonth()+1));
        _dateStr += '-' + (_date.getDate() < 10 ? '0'+_date.getDate() : _date.getDate());
        _dateStr += ' ' + (_date.getHours() < 10 ? '0'+_date.getHours() : _date.getHours());
        _dateStr += ':' + (_date.getMinutes() < 10 ? '0'+_date.getMinutes() : _date.getMinutes());
        var _idKey = 'send-' + _date.getMinutes() + _date.getSeconds() + _date.getMilliseconds();
        var _msg = $('#live-message-text').val();

        $('#live-message-text').val('');

        var _html = '<div class="mnkf_dialog_send">'
        _html += '<p class="my_name" id="'+_idKey+'">我&nbsp;Sending...</p>';
        _html += '<div class="msgbox">';
        _html +=  _msg.replace('>', '&gt;').replace('<', '&lt;');
        _html += '<em class="dialog_arrow"></em>';
        _html += '</div></div>';

        if ($('#message_list_'+_sendTo).length == 0) {
            $('#mnkf_dialog_content').append('<div class="chat-message" id="message_list_'+_sendTo+'"></div>');
        }

        $('#message_list_'+_sendTo).append(_html);
        scrollToBottom();

        $.ajax({
            url: 'index.php?route=account/chat/send',
            type: 'post',
            data: 'to_id='+_sendTo+'&text='+_msg,
            dataType: 'json',
            beforeSend: function() {
                $('#live-message-text').addClass('disabled');
            },
            complete: function() {
                $('#live-message-text').removeClass('disabled');
            },
            success: function(json) {
                if (json['error']) {
                    //alert(json['error']);
                    console.log(json['error']);
                    $('#'+_idKey).html('我&nbsp;Failed');
                }

                if (json['success']) {
                    $('#'+_idKey).html('我&nbsp;'+_dateStr);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                $('#'+_idKey).html('我&nbsp;Failed');
            }
        });

    }

function getLiveMessage() {
    $.ajax({
        url: 'index.php?route=account/chat',
        type: 'get',
        dataType: 'json',
        beforeSend: function() {
            //$('#live-message-send').addClass('disabled');
        },
        complete: function() {
            //$('#live-message-send').removeClass('disabled');
        },
        success: function(json) {
            if (json['error']) {
                //alert(json['error']);
                console.log(json['error']);
            }

            if (json['message']) {
                $.each(json['message'], function(i){
                    var _html = '';
                    _html += '<div class="mnkf_dialog_receive">'
                    _html += '<p class="other_name">' + json['message'][i]['name'] + '&nbsp;' + json['message'][i]['date'] + '</p>';
                    _html += '<div class="msgbox">' + json['message'][i]['text'];
                    _html += '<em class="dialog_arrow"></em></div></div>';

                    if ($('#message_list_'+json['message'][i]['id']).length == 0) {
                        $('#mnkf_dialog_content').append('<div class="chat-message" id="message_list_'+json['message'][i]['id']+'"></div>');
                    }
                    $('#message_list_'+json['message'][i]['id']).append(_html);

                    if ($('#chat_user_' + json['message'][i]['id']).length == 0) {
                        var _li = '<li class="zbj_server_name" id="chat_user_'+json['message'][i]['id']+'" data-userID="'+json['message'][i]['id']+'">';
                        _li += '<div class="avatar"><a>';
                        _li += '<img style="height:20px;width:20px;" src="'+json['message'][i]['avatar']+'">';
                        _li += '</a></div>';
                        _li += '<div class="zbj_nickname">'+json['message'][i]['name']+'</div>';
                        _li += '<div class="btn_con">';
                        _li += '<a class="del"><i class="fa fa-times-circle"></i></a>';
                        _li += '</div></li>';
                        $('.chat-user-list ul').append(_li);

                        $('.chat-box .chat-user-list ul li').hover(function(){
                            $(this).find('.btn_con').show();
                        },function(){
                            $(this).find('.btn_con').hide();
                        });
                    }
                    var chat_user_newmsg = $('#chat_user_' + json['message'][i]['id']);
                    chat_user_newmsg.addClass('new');
                    if(!chat_user_newmsg.hasClass('active')){
                        var newMsgTimer = setInterval(function(){
                            if(chat_user_newmsg.hasClass('active')){
                                clearInterval(newMsgTimer);
                            }
                            chat_user_newmsg.find('.zbj_nickname').animate({'opacity':0},150,function(){
                                 chat_user_newmsg.find('.zbj_nickname').animate({'opacity':1},150);
                            });
                        },300);
                        
                    }

                    if(!$('#zbj_talking').is(':hidden')){
                        var newMsgTimer1 = setInterval(function(){
                            $('#zbj_talking .zbj_newChat').animate({'opacity':0},150,function(){
                                 $(this).animate({'opacity':1},150);
                            });
                        },300);
                    }
                    $('#zbj_talking').click(function(){
                        clearInterval(newMsgTimer1);
                    })

                });
                if ($('.zbj_server_name.active').length == 0) {
                     $('.zbj_server_name').first().trigger('click');
                 }

                scrollToBottom();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

function activeLiveChat(obj) {
    if ($('#chat_user_' + $(obj).data('user')).length == 0) {
        var _li = '<li class="zbj_server_name" id="chat_user_'+$(obj).data('user')+'" data-userID="'+$(obj).data('user')+'">';
        _li += '<div class="avatar"><a>';
        _li += '<img style="height:20px;width:20px;" src="'+$(obj).data('avatar')+'">';
        _li += '</a></div>';
        _li += '<div class="zbj_nickname">'+$(obj).data('name')+'</div>';
        _li += '<div class="btn_con">';
        _li += '<a class="del"><i class="fa fa-times-circle"></i></a>';
        _li += '</div></li>';
        $('.chat-user-list ul').append(_li);

        $('.chat-box .chat-user-list ul li').hover(function(){
            $(this).find('.btn_con').show();
        },function(){
            $(this).find('.btn_con').hide();
        });
    }

    $('#chat_user_' + $(obj).data('user')).trigger('click');
    if($('#zbj_talking').css('display') != 'none') $('#zbj_talking').trigger('click');
}



    $(document).ready(function(){
        $('#zbj_talking').click(function(){
            $('#chat-box').show();
            $(this).hide();
            scrollToBottom();
        });

        $('.chat-box .chat-user-list ul li').hover(function(){
            $(this).find('.btn_con').show();
        },function(){
            $(this).find('.btn_con').hide();
        });

        $(document).delegate('.chat-box .chat-user-list ul li', 'click', function(){
            $(this).addClass('active').siblings().removeClass('active');
        });
        /*$('.chat-box .chat-user-list ul li').click(function(){
            $(this).addClass('active').siblings().removeClass('active');
        });*/
        
        showpkset();
        //enter 发送  ctrl+enter发送设置
        $("#live-message-text").keydown(function(evt){
            var keynum = evt.which;
            if(postkey==1)
            {
                if(!evt.shiftKey  && !evt.ctrlKey && (keynum ==13 || keynum ==10))
                {
                    sendmsg();
                    return false;
                }
                else if(evt.ctrlKey && (keynum ==10 || keynum ==13))
                {
                    evt.keyCode = 13;
                    evt.which = 10;
                    return true;
                }
            }
            else
            {
                if(evt.ctrlKey && (keynum==10 || keynum==13))
                {
                    sendmsg();
                    return false;
                }
            }
            return true;
        }).mousedown(function(){
            //close_alt_boxes();
        });

        $(document).delegate('li.zbj_server_name', 'click', function(){
            var _userId = $(this).data('userid');

            if ($('.chat_content_head .chat-user-title .user_img img').length == 0) {
                $('.chat_content_head .chat-user-title .user_img').append('<img />');
            }
            $('.chat_content_head .chat-user-title .user_img img').first().attr('src', $(this).find('.avatar img').first().attr('src'));
            $('.chat_content_head .chat-user-title .chat-user-name a').first().html($(this).find('.zbj_nickname').first().html());

            $('.chat-message').hide();
            $('#message_list_'+_userId).show();
            scrollToBottom();
            $(this).removeClass('new');
        });
        $(document).delegate('.chat-box .chat-user-list ul li .btn_con','click',function(){

            var Li = $('.chat-box .chat-user-list ul li');
            var liLength = Li.length;
            var this_userId =  $(this).parent('li').data('userid');
            var nextAct_li =$(this).parent('li').index();
            if(liLength>1){
                nextAct_li = (nextAct_li == liLength-1)? nextAct_li-1:nextAct_li;
                $(this).parent('li').remove();
                $('#message_list_'+this_userId).remove();
                $('.chat-box .chat-user-list ul li.zbj_server_name').eq(nextAct_li).click();
            }else{
                $(this).parent('li').remove();
                $('#message_list_'+this_userId).remove();
                $('.chat_content_head .chat-user-title .chat-user-name a').html('请先选择一个联系人！');
                slideup_box()
            }
            
            return false;

        });
        //拖拉
         (function(){
            var params = {
                left: 0,
                top: 0,
                currentX: 0,
                currentY: 0,
                flag: false
            };
            //拖拽的实现
            var startDrag = function(bar, target, callback){
                //o是移动对象
                bar.onmousedown = function(event){
                    params.flag = true;
                    if(!event){
                        event = window.event;
                        //防止IE文字选中
                        bar.onselectstart = function(){
                            return false;
                        }  
                    }
                    var e = event;
                    params.currentX = e.clientX;
                    params.currentY = e.clientY;
                    params.left = document.getElementById('chat-box').offsetLeft;
                    params.top = document.getElementById('chat-box').offsetTop;

                };
                document.onmouseup = function(){
                    params.flag = false;
                };
                document.onmousemove = function(event){
                    var e = event ? event: window.event;
                    if(params.flag){
                        var nowX = e.clientX, nowY = e.clientY;
                        var disX = nowX - params.currentX, disY = nowY - params.currentY;
                        target.style.left = parseInt(params.left) + disX + "px";
                        target.style.top = parseInt(params.top) + disY + "px";
                    }
                    
                    if (typeof callback == "function") {
                        callback(parseInt(params.left) + disX, parseInt(params.top) + disY);
                    }
                }   
            };
            var box = document.getElementById('chat-box');
            var boxheader = document.getElementById('chat_content_head');
            startDrag(boxheader,box);

            })();  



        getLiveMessage();
        var _chat = setInterval(function(){
            getLiveMessage();
        }, 30000);

    });
</script>
<?php } ?>
<footer>
<div id="footer">
  <div class="container">
    <div class="footer">
      <div class="footer_left">
          <span class="tel_ico"></span>
        <ul class="list-unstyled" style="float:left">
            <li class="tel_all"><p>400-630-1999</p></li>
            <li>客服邮箱：kf@zhubaojie.com</li>
            <li>ICP备案号：京ICP备14053557号</li>
            <li><?php echo $powered; ?></li>
        </ul>
      </div>
        <div class="footer_right">
                <div class="footlist">
                    <h5>买家帮助</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo $link_buyer_newer; ?>">新手指南</a></li>
                        <!--<li><a href="index.php?route=information/sitemap">网站地图</a></li>-->
                        <!--<li><a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=fE1MSk9KRE1NREQ8DQ1SHxMR">意见反馈</a></li>-->
                        <li><a href="<?php echo $contact; ?>">意见反馈</a></li>
                        <li><a href="<?php echo $link_buyer_help; ?>">帮助中心</a></li>
                    </ul>
                </div>
                <div class="footlist">
                    <h5>卖家帮助</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo $link_seller_newer; ?>">商家入驻</a></li>
                        <li><a href="<?php echo $link_seller_service; ?>">商家推广</a></li>
                        <li><a href="<?php echo $link_seller_help; ?>">帮助中心</a></li>
                    </ul>
                </div>
                <div class="footlist">
                    <h5>关于我们</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo $link_about_us; ?>">关于我们</a></li>
                        <li><a href="<?php echo $link_contact_us; ?>">联系我们</a></li>
                        <li><a href="<?php echo $link_join_us; ?>">加入我们</a></li>
                    </ul>
                </div>
                <div class="footlist">
                    <h5>关注我们</h5>
                    <ul class="list-unstyled payatten-us">
                        <li><a href="http://t.sina.com.cn/zbjvip"><span class="i_sina"> </span>新浪微博</a></li>
                        <li><a href="http://t.qq.com/zbjvip"><span class="i_tx"> </span>腾讯微博</a></li>
                        <li><a href="http://user.qzone.qq.com/1063681188/"><span class="i_qzone"> </span>QQ空间</a></li>
                    </ul>
                </div>
        
                <div class="footlist">
                    <h5>在线沟通</h5>
                    <ul class="list-unstyled">
                        <li>
                            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=1063681188&amp;site=qq&amp;menu=yes"><img border="0" src="/catalog/view/theme/zbj/image/button_111.gif" alt="点击这里给我发消息" title="点击这里给我发消息"></a>
                        </li>
                        <li>
                            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=1063681188&amp;site=qq&amp;menu=yes"><img border="0" src="/catalog/view/theme/zbj/image/button_111.gif" alt="点击这里给我发消息" title="点击这里给我发消息"></a>
                        </li>
                        <li>
                            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=1063681188&amp;site=qq&amp;menu=yes"><img border="0" src="/catalog/view/theme/zbj/image/button_111.gif" alt="点击这里给我发消息" title="点击这里给我发消息"></a>
                        </li>
                    </ul>
                </div>
                <div class="footlist">
                    <h5>珠宝街微信服务号</h5>
                    <div class="twodim-code">
                        <img width="115" height="115" alt="珠宝街服务号二维码" title="珠宝街微信服务号" src="/catalog/view/theme/zbj/image/wx.jpg">
                    </div>
                </div>
        </div>
      </div>
  </div>
</div>
<div id="gotop">
    <a title="返回顶部" onclick="goTop();return false;" href="javascript:;">返回顶部</a>
</div>
<script>
    function goTop(){
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    }
</script>
</footer>

</body></html>