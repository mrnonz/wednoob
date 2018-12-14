<?php

/* 
 *  This site Design with Bootstrap and Develop source code by. DJdai Yodsapon
 *  Can contact DJdai at y.wongchuen@gmail.com or +66800257281 (Thailand Number)
 */

?>

<div class="panel panel-default" style="margin-bottom: 10px;">
    <div class="panel-heading" style="padding: 8px;"><h4 style="margin: 0px;"><i class="fa fa-steam-square fa-lg" aria-hidden="true"></i> ค้นหาด้วย Steam URL</h4></div>
    <div class="panel-body" style="padding: 8px;">
        <div class="well well-sm" style="margin: 0px;">
            <strong>ตัวอย่าง Steam URL : </strong><br/>
            https://store.steampowered.com/app/000000/<br/>
            https://store.steampowered.com/sub/000000/
        </div>
        <form name="browse_steamurl_frm" id="browse_steamurl_frm" method="POST" action="javascript:void(0);">
            <div class="form-group" style="margin: 5px 0px 0px 0px;">
                <div class="input-group">
                    <input type="url" id="steam_url" name="steam_url" class="form-control" placeholder="กรอก STEAM URL" autocomplete="off" required=""/>
                    <div class="input-group-btn">
                        <button type="submit" id="browse_steamurl_btn" name="browse_steamurl_btn" onclick="send_steamurl();"  class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</button>
                    </div>
                </div>
                <div id="browse_steamurl_rs"></div>
            </div>
        </form>
    </div>
</div>