{include file="common/main_header.tpl"}
{config_load file="article.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#cms_title_class#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('cms_article_class/index')}"><span>{#manage#}</span></a></li>
      	<li><a href="{admin_site_url('cms_article_class/add')}" {if !$info['id']}class="current"{/if}><span>{#add#}</span></a></li>
      	{if $info['id']}
      	<li><a href="{admin_site_url('cms_article_class/edit?id=')}{$info['id']}" class="current"><span>{#edit#}</span></a></li>
      	{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['id']}
  {form_open(admin_site_url('cms_article_class/edit'),'id="add_form"')}
  {else}
  {form_open(admin_site_url('cms_article_class/add'),'id="add_form"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#cms_title_class#}名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">开启状态: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="status1" {if $info['status'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="status0" {if $info['status'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="status1" name="status" {if $info['status'] == 1}checked{/if} value="1" type="radio">
            <input id="status0" name="status" {if $info['status'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">{form_error('status')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">{#list_tplname#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['list_tplname']|escape}" name="list_tplname" id="list_tplname" class="txt">
          </td>
          <td class="vatop tips">{form_error('list_tplname')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">{#detail_tplname#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="text" value="{$info['detail_tplname']|escape}" name="detail_tplname" id="detail_tplname" class="txt">
          </td>
          <td class="vatop tips">{form_error('detail_tplname')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['ac_sort']}{$info['ac_sort']}{else}255{/if}" name="ac_sort" id="ac_sort" class="txt"></td>
          <td class="vatop tips">{form_error('ac_sort')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	$(function(){
		
	})
 </script>
{include file="common/main_footer.tpl"}