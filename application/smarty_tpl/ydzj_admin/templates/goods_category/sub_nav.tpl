<div class="fixed-bar">
    <div class="item-title">
      <h3>{#category_title#}</h3>
      <ul class="tab-base">
      	<li><a {if $action == 'index'}class="current"{/if} href="{admin_site_url('goods_category/index')}"><span>{#manage#}</span></a></li>
      	<li><a {if $action == 'add'}class="current"{/if} href="{admin_site_url('goods_category/add')}"><span>{#add#}</span></a></li>
      	{if $info['id']}<li><a {if $action == 'edit'}class="current"{/if} href="{admin_site_url('goods_category/edit?id='|cat:$info['id'])}"><span>{#edit#}</span></a></li>{/if}
      </ul>
    </div>
  </div>
  