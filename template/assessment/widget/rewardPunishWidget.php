<div id="reward_punish_form" >
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">奖惩</b></p>
    </div>
    <div class="sm_div mlr30">
        <table class="sm_xsmbadd" width="100%">
            <tr style="display: none;">
                <td width="26%">
                    <div class="smfl">
                        <span><em class="c-yel">*</em></span>
                        <select name="rpType">
                            <option value="1">奖励</option>
                            <option value="2">惩罚</option>
                        </select>
                    </div>
                </td>
                <td width="26%">
                    <div class="smfl">
                        <span><em class="c-yel">*</em>说明：</span>
                        <input type="text" tagname="rpIntro" name="rpIntro_new_[@]" class="{validate:{required:true}}">
                    </div>
                </td>
                <td width="26%">
                    <div class="smfl">
                        <span><em class="c-yel">*</em>金额/百分比：</span>
                        <select name="unitType">
                            <option value="1">金额</option>
                            <option value="2">百分百</option>
                        </select>
                    </div>
                </td>
                <td width="26%">
                    <div class="smfl">
                        <input type="text" tagname="rpUnitValue" name="rpUnitValue_new_[@]" class="{validate:{required:true,rpUnit:true}}">
                    </div>
                </td>
                <td width="15%" class="sm_xsmbadd_td2">
                    <div class="del_td" onclick="Assess.prototype.delRpItem(this)">
                        <input type="button" class="btn67"  name="del" value="删除">
                    </div>
                </td>
            </tr>
            <?php if($rpData){?>
                <?php foreach($rpData as $key=>$itemData){?>
                    <tr>
                        <td width="26%">
                            <div class="smfl">
                                <span><em class="c-yel">*</em></span>
                                <select name="rpType">
                                    <option value="1" <?php if($itemData['rpType']==1){?>selected="selected" <?php }?>>奖励</option>
                                    <option value="2" <?php if($itemData['rpType']==2){?>selected="selected" <?php }?>>惩罚</option>
                                </select>
                            </div>
                        </td>
                        <td width="26%">
                            <div class="smfl">
                                <span><em class="c-yel">*</em>说明：</span>
                                <input type="text" tagname="rpIntro" name="rpIntro_old_<?=$key?>" value="<?=$itemData['rpIntro']?>" class="{validate:{required:true}}">
                            </div>
                        </td>
                        <td width="20%">
                            <div class="smfl">
                                <span><em class="c-yel">*</em>金额/百分比：</span>
                                <select name="unitType">
                                    <option value="1" <?php if($itemData['unitType']==1){?>selected="selected" <?php }?>>金额</option>
                                    <option value="2" <?php if($itemData['unitType']==2){?>selected="selected" <?php }?>>百分比</option>
                                </select>
                            </div>
                        </td>
                        <td width="20%">
                            <div class="smfl">
                                <input type="text" tagname="rpUnitValue" name="rpUnitValue_old_<?=$key?>" value="<?=$itemData['rpUnitValue']?>" class="{validate:{required:true,rpUnit:true}}">
                            </div>
                        </td>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="del_td" onclick="Assess.prototype.delRpItem(this)">
                                <input type="button" class="btn67"  name="del" value="删除">
                            </div>
                        </td>
                    </tr>
                <?php }?>
            <?php }?>
        </table>
    </div>
    <div class="add_reward"><a href="javascript:void(0);">添加</a></div>