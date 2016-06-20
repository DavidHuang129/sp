{include file="./site_common.tpl"}
<style type="text/css">
body {
	background:#686868;
}

img.responed {
	display: block;
}

.handler {
	position:relative;
}

.fromdiv {
	background:#c4c4c4;
	padding:5px 20px;
	text-align:center;
}

.formdiv2 {
	padding-bottom:30px;
}

.t2 {
	background:url('{resource_url("img/new_pg2/btn_bg.jpg")}') repeat-x left center;
	color:#cb3232;
	font-size:14px;
	font-weight:bold;
	height:37px;
	text-align:center;
	text-indent:0;
	box-sizing:border-box;
	border:1px solid #e0e0e0;
	border-radius:5px;
    -webkit-border-radius:5px;
    -moz-border-radius:5px;
    -o-border-radius:5px;
    -webkit-appearance: none;
	width:22%;
	color:#feffff;
	position: relative;
	float:right;
}

.fromdiv input.t1 {
	height:37px;
	line-height:37px;
	border:1px solid #c1b3b3;
	width: 73%;
	
}

.formdiv2 .t2 {
	color:#fa8f03;
}

@media (max-width: 640px) {
	.t2 {
		font-size:13px;
	}
}

</style>
	<div id="wrap">
		<div>
			<div class="hide">10万模拟资金 0基础3步学会贵金属投资</div>
   			<img class="responed" src="{resource_url('img/new_pg2/pic1.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg2/pic2.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg2/pic3.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">为什么投资贵金属</div>
   			<div class="hide">简单:0基础3步学会炒白银，不上班也能有钱花，快乐做自己</div>
   			<div class="hide">快速:连续操盘一个月，快速赚取人生第一桶金，成功与财富块人一步</div>
   			<div class="hide">自由:白天上班，晚上炒银自由赚外快，工作赚取两不误</div>
   			<div class="hide">赚取:抓住一次非农行情，严格操作，行情波动可达100点</div>
   			<img class="responed" src="{resource_url('img/new_pg2/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg2/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg2/pic6.jpg')}"/>
   		</div>
		<div>
   			<img class="responed" src="{resource_url('img/new_pg2/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg2/pic8.jpg')}"/>
   		</div>
   		<div class="fromdiv" >
   			<a name="md1"></a>
   			<div class="hide">免费申请模拟账户，领取50万操盘资金</div>
			{form_open(site_url('index/index/'|cat:$siteIndex|cat:'#md1'),'id="registerForm1"')}
			{include file="./site_form_hidden.tpl"}
			<input class="t1" type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/>
			<input class="t2" type="submit" name="tj" value="模拟开户"/>
			<div class="tiparea">{form_error('mobile')}</div>
			</form>
		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg2/pic9.jpg')}"/>
   		</div>
   		<div class="fromdiv formdiv2" >
   			<a name="md2"></a>
   			<div class="hide">您已等不急，跃跃欲试，马上开了实盘账户</div>
			{form_open(site_url('index/index/'|cat:$siteIndex|cat:'#md2'),'id="registerForm2"')}
			{include file="./site_form_hidden.tpl"}
			<input class="t1" type="text" name="mobile" value="{set_value('mobile')}" placeholder="请输入您的手机号码"/>
			<input class="t2" type="submit" name="tj" value="实盘开户"/>
			<div class="tiparea">{form_error('mobile')}</div>
			</form>
		</div>
   		<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg2/pic10.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg2/pic11.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	<script>
	$(function(){
		{include file="./site_alert.tpl"}
		
		$('#registerForm1').validate({
	        errorPlacement: function(error, element){
	        	//console.log(error);
	        	//console.log(element);
	            error.appendTo(element.parent().find(".tiparea"));
	        },
	        onfocusout:false,
		    onkeyup:false,
	        rules : {
	        	mobile: {
	                required : true,
	                phoneChina:true,
	            }
	            
	        },
	        messages : {
	        	mobile: {
	                required : '手机号码不能为空',
	           }
	        }
	    });
		
		$('#registerForm2').validate({
	        errorPlacement: function(error, element){
	        	//console.log(error);
	        	//console.log(element);
	            error.appendTo(element.parent().find(".tiparea"));
	        },
	        onfocusout:false,
		    onkeyup:false,
	        rules : {
	        	mobile: {
	                required : true,
	                phoneChina:true,
	            }
	            
	        },
	        messages : {
	        	mobile: {
	                required : '手机号码不能为空',
	           }
	        }
	    });
	
	});	
	</script>
</body>
</html>