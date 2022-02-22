<div id="sub-tit">
    <h2>SMS 발송 내역</h2>
    <em><i class="fa fa-exclamation-circle"></i>SMS 발송 내역 확인</em>
</div>

<!-- sorting -->
<div id="list-sort">
    <ul>
        <li><a href="history"><em>전체 발송 수</em><p><?php echo $sent_total; ?></p></a></li>
        <li><a href="<?php echo $manage->sortlink("?sort=to_mb"); ?>"><em>특정회원 발송</em><p><?php echo $to_mb_total; ?></p></a></li>
        <li><a href="<?php echo $manage->sortlink("?sort=level_from"); ?>"><em>범위지정 발송</em><p><?php echo $level_from_total; ?></p></a></li>
        <li><a href="<?php echo $manage->sortlink("?sort=to_phone"); ?>"><em>비회원 발송</em><p><?php echo $to_phone_total; ?></p></a></li>
    </ul>
</div>

<!-- article -->
<article>

    <form id="list-sch" action="" method="get">
        <?php echo $manage->print_hidden_inp(); ?>

        <select name="where">
            <option value="to_mb" <?php echo $manage->sch_where("to_mb"); ?>>수신 회원 ID</option>
            <option value="subject" <?php echo $manage->sch_where("subject"); ?>>제목</option>
            <option value="to_phone" <?php echo $manage->sch_where("to_phone"); ?>>수신번호(비회원)</option>
        </select>
        <input type="text" name="keyword" class="keyword" value="<?php echo $keyword; ?>" placeholder="검색어를 입력하세요." />
        <button type="submit" class="btn1 small sbm"><i class="fa fa-search"></i>검색</button>
    </form>

    <span class="tbl_sment mb5">실제 발송 내역과 다를 수 있습니다. 자세한 발송 내역은 NCP SENS 콘솔을 확인 바랍니다.</span>

    <table class="table1 list">
        <colgroup>
            <col style="width: 50px;" />
            <col style="width: 80px;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
            <col style="width: auto;" />
        </colgroup>
        <thead>
            <tr>
                <th>No.</th>
                <th>유형</th>
                <th>수신번호(비회원)</th>
                <th>수신 범위</th>
                <th><a href="<?php echo $manage->orderlink("to_mb"); ?>">수신 회원 ID</a></th>
                <th><a href="<?php echo $manage->orderlink("subject"); ?>">제목</a></th>
                <th><a href="<?php echo $manage->orderlink("memo"); ?>">메시지 내용</a></th>
                <th><a href="<?php echo $manage->orderlink("regdate"); ?>">발송일</a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($print_arr as $list) { ?>
            <tr>
                <td class="no tac"><?php echo $list['no']; ?></td>
                <td class="tac"><?php echo $list['sendtype']; ?></td>
                <td class="tac"><?php echo $list[0]['print_to_phone']; ?></td>
                <td class="tac"><?php echo $list[0]['print_level']; ?></td>
                <td class="tac"><?php echo $list[0]['print_to_mb']; ?></td>
                <td class="tac"><?php echo $list[0]['subject']; ?></td>
                <td class="tac"><?php echo $list[0]['memo']; ?></td>
                <td class="tac">
                    <?php
                    echo $list['regdate'];
                    if ($list['use_resv'] == 'Y') {
                        echo '<br />(예약: '.$list['resv_date'].' '.$list['resv_hour'].':'.$list['resv_min'].')';
                    }
                    ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- no data -->
    <?php if (!$print_arr) { ?>
    <p id="list-nodata"><?php echo SET_NODATA_MSG; ?></p>
    <?php } ?>

    <!-- paging -->
    <div id="list-paging">
        <?php echo $pagingprint; ?>
    </div>

</article>