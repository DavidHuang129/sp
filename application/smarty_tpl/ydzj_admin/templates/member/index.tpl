{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="javascript:void(0);" class="current"><span>{#manage#}</span></a></li>
        {*<li><a href="{admin_site_url('member/add')}" ><span>{#add#}</span></a></li>*}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form class="formSearch" method="get" name="formSearch" id="formSearch" action="{admin_site_url('member')}">
    <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
            <td colspan="5">
                <div class="cityGroupWrap">
	                <select name="d1" class="citySelect">
	                    <option value="">{#choose#}</option>
		                {foreach from=$ds['d1'] item=item}
		                <option value="{$item['id']}" {if $smarty.get['d1'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
		                {/foreach}
		            </select>
		            <select name="d2" class="citySelect">
		                <option value="">{#choose#}</option>
                        {foreach from=$ds['d2'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d2'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
		            </select>
		            <select name="d3" class="citySelect">
		                <option value="">{#choose#}</option>
                        {foreach from=$ds['d3'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d3'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
	                </select>
	                <select name="d4" class="citySelect">
	                    <option value="">{#choose#}</option>
                        {foreach from=$ds['d4'] item=item}
                        <option value="{$item['id']}" {if $smarty.get['d4'] == $item['id']}selected="selected"{/if}>{$item['name']}</option>
                        {/foreach}
	                </select>
                </div>
            </td>
        </tr>
        <tr>
          <td><select name="search_field_name" >
              {foreach from=$search_map['search_field'] key=key item=item}
              <option value="{$key}" {if $smarty.get['search_field_name'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
          </td>
          <td><input type="text" value="{$smarty.get['search_field_value']}" name="search_field_value" class="txt"></td>
          
          <td><select name="register_channel">
              <option  value="">{#register_channel#}</option>
              {foreach from=$search_map['register_channel'] key=key item=item}
              <option  value="{$key}" {if $smarty.get['register_channel'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
         </td>
         <td><select name="register_sort">
              <option  value="">{#register_time#}</option>
              {foreach from=$search_map['register_sort'] key=key item=item}
              <option  value="{$key}" {if $smarty.get['register_sort'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
         </td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
   </form>
   
   <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5>操作提示</h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>通过会员管理，你可以进行查看、编辑会员资料以及删除会员等操作</li>
            <li>你可以根据条件搜索会员，然后选择相应的操作</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
   <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2">会员</th>
          <th>注册渠道</th>
          <th>地区</th>
          <th>邀请人</th>
          <th>状态</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$list['data'] item=item key=key}
        <tr class="hover member">
          <td class="w24"></td>
          <td class="w120 picture">
            <div class=""><span class="thumb"><i></i><img src="{resource_url('img/nophoto.gif')}"/></span></div></td>
          <td>
            <p class="name"><strong>手机号码:{$item['mobile']|escape}</strong></p>
            <p><strong>名称:&nbsp;</strong><span>{$item['username']|escape}</span></p>
            <p><strong>性别:&nbsp;</strong><span>{if $item['sex'] == 'M'}男{elseif $item['sex'] == 'F'}女{else}保密{/if}</span></p>
            <p class="smallfont">注册时间:&nbsp;{$item['reg_date']|date_format:"%Y-%m-%d %H:%M:%S"}</p>
            <p class="smallfont">注册IP:&nbsp;{$item['reg_ip']}</p>
            <p class="smallfont">注册渠道:&nbsp;{$item['channel_name']}</p>
            <div class="im">
                {if $item['email'] != ''}
                <span class="email">
                    <a href="mailto:{$item['email']}" class="yes" title="电子邮箱:{$item['email']|escape}">{$item['email']}</a>
                </span>
                {/if}
                {if $item['qq'] != ''}
                <span class="qq">
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$item['qq']}&site=qq&menu=yes" class="yes"  title="QQ: {$item['qq']|escape}">&nbsp;</a>
                </span>
                {/if}
                {if $item['weixin'] != ''}
                <span class="weixin"><a href="javascript:void(0)" title="{$item['weixin']|escape}">{$item['weixin']|escape}</a></span>
                {/if}
            </div>
          </td>
          <td>
            <p><span>{$item['channel_name']|escape}</span></p>
          </td>
          <td class="align-center w120">
            {if $item['inviter']}
            <div class=""><a href="{admin_site_url('member/edit')}/{$item['inviter']}"><img src="{resource_url($inviterInfo[$item['inviter']]['avatar_small'])}" /></a></div>
            <p>{$inviterInfo[$item['inviter']]['mobile']}</p>
            {/if}
          </td>
          <td>
           {$memberDs[$item['d1']]['name']}{$memberDs[$item['d2']]['name']}{$memberDs[$item['d3']]['name']}{$memberDs[$item['d4']]['name']}
          </td>
          <td>{if $item['status'] == 0}注册完成{else}注册未提交{/if}</td>
          <td class="align-center"><a href="{admin_site_url('member/edit')}/{$item['uid']}">编辑</a></td>
        </tr>
      {/foreach}
      </tbody>
      <tfoot class="tfoot">
        <tr>
          <td colspan="11">
            {include file="common/pagination.tpl"}
        </tr>
      </tfoot>
    </table>
{include file="common/main_footer.tpl"}