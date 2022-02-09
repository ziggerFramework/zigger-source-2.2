<input type="hidden" name="type" value="add" />
<input type="hidden" name="new_caidx" value="" />

<p class="sment">통합검색 결과에 노출될 콘텐츠를 추가하세요.<br />마우스로 드래그 하여 순서를 변경할 수 있습니다.</p>

<div class="sortable">

    <?php foreach ($print_arr as $list) { ?>
        <div class="st-1d">
            <h4><a href="#" class="modify-btn"><input type="hidden" name="idx[]" value="<?php echo $list['idx']; ?>" /><input type="hidden" name="org_caidx[]" value="<?php echo $list['caidx']; ?>" /><input type="hidden" name="caidx[]" value="<?php echo $list['caidx']; ?>" data-depth="1" /><?php echo $list['title']; ?></a><i class="fa fa-trash-alt st-del del-1d ajbtn"></i></h4>
        </div>
    <?php } ?>

</div>
<a href="#" class="st-add no-mar add-1d ajbtn"><i class="fa fa-plus"></i> 통합검색 콘텐츠 추가</a>
