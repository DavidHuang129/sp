<div class="pagination" data-formid="{$page['form_id']}">
    {if ($page['pageLastNum'] > 1)}
        {if ($page['pageNow'] != 1)}
            <a href="javascript:void(0)" onclick="{$page['call_js']}(1,'{$page['form_id']}');return false;">第一页</a>
            <a href="javascript:void(0)" onclick="{$page['call_js']}({$page['pageNow'] - 1},'{$page['form_id']}');return false;">上一页</a>
        {else}
            <a href="javascript:void(0)" onclick="{$page['call_js']}(1,'{$page['form_id']}');return false;">第一页</a>
        {/if}

        {section name=a loop=($page['pageAe'] - $page['pageAb'] + 1)}
            {if ($page['pageNow'] == ($smarty.section.a.index + $page['pageAb']))}
                <a class="active" href="javascript:void(0)">{$smarty.section.a.index + $page['pageAb']}</a>
            {else}
                <a href="javascript:void(0)" onclick="{$page['call_js']}({{$smarty.section.a.index + $page['pageAb']}},'{$page['form_id']}');return false;">{{$smarty.section.a.index + $page['pageAb']}}</a>
            {/if}
        {/section}

        {if $page['pageNow'] != $page['pageLastNum']}
            <a href="javascript:void(0)" onclick="{$page['call_js']}({$page['pageNow'] + 1},'{$page['form_id']}');return false;">下一页</a>
            <a href="javascript:void(0)" onclick="{$page['call_js']}({$page['pageLastNum']},'{$page['form_id']}');return false;">最后页</a>
        {/if}
        
        <strong><label>找到{$page['pageSum']}{$page['pageUnit']}{$page['pageTit']}</label>{if $page['shortStyle'] == false}<input type="text" style="width:40px" name="jumpPage" value="{$page['pageNow']}" />&nbsp;<input type="button" name="jumpBtn" value="跳转" class="jumpBtn" />&nbsp;
        {/if}
        <span>第{$page['pageNow']}页/共{$page['pageLastNum']}页</span></strong>
    {else}
        {if $page['pageSum'] > 0}<strong><label>找到{$page['pageSum']}{$page['pageUnit']}{$page['pageTit']}</label>{/if}
    {/if}
</div>