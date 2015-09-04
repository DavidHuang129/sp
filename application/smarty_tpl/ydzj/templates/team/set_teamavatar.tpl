{include file="common/header.tpl"}
{$feedback}
<div class="handle_area">
    {form_open_multipart(site_url('team/set_teamavatar/'|cat:$teamid),"id='setAvatarForm'")}
    <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
    <input type="hidden" name="new_avatar" value="{if $new_avatar}{$new_avatar}{/if}" />
    <input type="hidden" name="avatar_id" value="{$avatar_id}" />
    <input type="hidden" name="default_avatar" value="{$default_avatar}"/>
    <div id="profile_avatar">
        <div class="row" style="position:relative;">
            <label class="side_lb" for="avatar_txt"><em>*</em>球队合影：</label>
            <input type="file" class="at_txt" id="avatar_txt" name="avatar" value=""/>
        </div>
        <div class="warning">头像格式JPG 尺寸图片最小尺寸 800x800 正方形照片</div>
        {$avatar_error}
        <div class="row">
            <input type="submit" name="submit" class="primaryBtn" value="保存"/>
        </div>
        <div class="row" id="preview">
        	{if $new_avatar}
        	<img src="{base_url($new_avatar)}"/>
        	{else}
        	<img src="{base_url($default_avatar)}"/>
        	{/if}
        </div>
        
    </div>
    </form>
</div>

<script>


</script>
{include file="common/footer.tpl"}