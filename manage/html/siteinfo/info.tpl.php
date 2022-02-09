<div id="sub-tit">
    <h2>기본정보 관리</h2>
    <em><i class="fa fa-exclamation-circle"></i>사이트 기본 정보 관리</em>
</div>

<!-- article -->
<article>

    <form <?php echo $this->form(); ?>>
        <?php echo $manage->print_hidden_inp(); ?>

        <?php echo $print_target[0]; ?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">사이트 기본 정보</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>사이트명</th>
                    <td>
                        <input type="text" name="title" class="inp" title="사이트명" value="<?php echo $write['title']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>도메인</th>
                    <td>
                        <input type="text" name="domain" class="inp" title="도메인" value="<?php echo $write['domain']; ?>" placeholder="http://mydomain" />
                        <span class="tbl_sment">외부 공유 링크, 웹메일 본문 내 접속 링크 등에 활용되므로 정확한 입력 필요</span>
                    </td>
                </tr>
                <tr>
                    <th>사이트 설명</th>
                    <td>
                        <input type="text" name="description" class="inp" title="사이트 설명" value="<?php echo $write['description']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>관리자 이메일</th>
                    <td>
                        <input type="text" name="email" class="inp" title="관리자 이메일" value="<?php echo $write['email']; ?>" placeholder="email@mydomain" />
                        <span class="tbl_sment">시스템의 웹메일 발송 주소로 활용 되므로 정확한 입력 필요</span>
                    </td>
                </tr>
                <tr>
                    <th>전화번호</th>
                    <td>
                        <input type="text" name="tel" class="inp" title="전화번호" value="<?php echo $write['tel']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>반응형 모바일</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_mobile" value="Y" <?php echo $use_mobile['Y']; ?> /> 사용</label>
                        <label><input type="radio" name="use_mobile" value="N" <?php echo $use_mobile['N']; ?> /> 사용안함</label>
                        <span class="tbl_sment">반응형 모바일이 비활성화 된 경우 모바일 Device에서 PC레이아웃이 출력됨</span>
                    </td>
                </tr>
                <tr>
                    <th>로고</th>
                    <td>
                        <input type="file" name="logo" />

                        <?php if ($is_logo_show) { ?>
                        <dl class="fileview">
                            <dt><img src="<?php echo $write[0]['logo']['replink']; ?>" /></dt>
                            <dd>
                                <strong>등록된 파일</strong>
                                <a href="<?php echo $write[0]['logo']['replink']; ?>" target="_blank"><?php echo $write[0]['logo']['orgfile']; ?></a>
                                <label class="ml10"><input type="checkbox" name="logo_del" value="checked" /> 삭제</label>
                            </dd>
                        </dl>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>파비콘</th>
                    <td>
                        <input type="file" name="favicon" />
                        <span class="tbl_sment">.ico 파일만 첨부 가능</span>

                        <?php if ($is_favicon_show) { ?>
                        <dl class="fileview no-dt">
                            <dd>
                                <strong>등록된 파일</strong>
                                <a href="<?php echo $write[0]['favicon']['replink']; ?>" target="_blank"><?php echo $write[0]['favicon']['orgfile']; ?></a>
                                <label class="ml10"><input type="checkbox" name="favicon_del" value="checked" /> 삭제</label>
                            </dd>
                        </dl>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>회원 이메일 인증</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_emailchk" value="Y" <?php echo $use_emailchk['Y']; ?> /> 사용</label>
                        <label><input type="radio" name="use_emailchk" value="N" <?php echo $use_emailchk['N']; ?> /> 사용안함</label>
                        <span class="tbl_sment">회원 이메일 인증 기능이 활성화 된 경우 이메일 인증을 완료한 회원만 가입 완료됨</span>
                    </td>
                </tr>
                <tr>
                    <th>회원 등급별 명칭</th>
                    <td>
                        <table class="table2">
                            <colgroup>
                                <col style="width: 10%;" />
                                <col style="width: 10%;" />
                                <col style="width: 10%;" />
                                <col style="width: 10%;" />
                                <col style="width: 10%;" />
                                <col style="width: 10%;" />
                                <col style="width: 10%;" />
                                <col style="width: 10%;" />
                                <col style="width: 10%;" />
                                <col style="width: 10%;" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>등급</th>
                                    <th>명칭</th>
                                    <th>등급</th>
                                    <th>명칭</th>
                                    <th>등급</th>
                                    <th>명칭</th>
                                    <th>등급</th>
                                    <th>명칭</th>
                                    <th>등급</th>
                                    <th>명칭</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="tac">1 (최고관리자)</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(1)" value="<?php echo $mb_division[1]; ?>" class="inp w100p" /></td>
                                    <th class="tac">2</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(2)" value="<?php echo $mb_division[2]; ?>" class="inp w100p" /></td>
                                    <th class="tac">3</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(3)" value="<?php echo $mb_division[3]; ?>" class="inp w100p" /></td>
                                    <th class="tac">4</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(4)" value="<?php echo $mb_division[4]; ?>" class="inp w100p" /></td>
                                    <th class="tac">5</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(5)" value="<?php echo $mb_division[5]; ?>" class="inp w100p" /></td>
                                </tr>
                                <tr>
                                    <th class="tac">6</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(6)" value="<?php echo $mb_division[6]; ?>" class="inp w100p" /></td>
                                    <th class="tac">7</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(7)" value="<?php echo $mb_division[7]; ?>" class="inp w100p" /></td>
                                    <th class="tac">8</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(8)" value="<?php echo $mb_division[8]; ?>" class="inp w100p" /></td>
                                    <th class="tac">9 (일반회원)</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(9)" value="<?php echo $mb_division[9]; ?>" class="inp w100p" /></td>
                                    <th class="tac">10 (비회원)</th>
                                    <td class="tac"><input type="text" name="mb_division[]" title="회원 등급별 명칭(10)" value="<?php echo $mb_division[10]; ?>" class="inp w100p" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <button type="submit" class="btn1"><i class="fa fa-check"></i> 변경완료</button>
            </div>
        </div>

        <?php echo $print_target[1]; ?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">기본 플러그인 사용 설정</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Google reCAPTCHA v2</th>
                    <td>
                        <table class="table2">
                            <tbody>
                                <tr>
                                    <th>사용 유무</th>
                                    <td>
                                        <label class="mr10"><input type="radio" name="use_recaptcha" value="Y" <?php echo $use_recaptcha['Y']; ?> /> 사용</label>
                                        <label><input type="radio" name="use_recaptcha" value="N" <?php echo $use_recaptcha['N']; ?> /> 사용안함</label>
                                        <span class="tbl_sment">
                                            기본 'Captcha' 대신 Google 'reCAPTCHA'를 사용하려면 체크 합니다.<br />
                                            reCAPTCHA는 공식 웹사이트에서 KEY 발급 후 사용 가능합니다.
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Site key</th>
                                    <td>
                                        <input type="text" name="recaptcha_key1" class="inp w50p" title="reCAPTCHA Site key" value="<?php echo $write['recaptcha_key1']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Secret key</th>
                                    <td>
                                        <input type="text" name="recaptcha_key2" class="inp w50p" title="reCAPTCHA Secret key" value="<?php echo $write['recaptcha_key2']; ?>" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>카카오 로그인</th>
                    <td>
                        <table class="table2">
                            <tbody>
                                <tr>
                                    <th>사용 유무</th>
                                    <td>
                                        <label class="mr10"><input type="radio" name="use_sns_ka" value="Y" <?php echo $use_sns_ka['Y']; ?> /> 사용</label>
                                        <label><input type="radio" name="use_sns_ka" value="N" <?php echo $use_sns_ka['N']; ?> /> 사용안함</label>
                                        <span class="tbl_sment">
                                            로그인 화면에서 카카오 로그인 기능을 사용하려면 체크 합니다.
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Client ID</th>
                                    <td>
                                        <input type="text" name="sns_ka_key1" class="inp w50p" title="카카오 Client ID" value="<?php echo $write['sns_ka_key1']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Client Secret</th>
                                    <td>
                                        <input type="text" name="sns_ka_key2" class="inp w50p" title="카카오 Client Secret" value="<?php echo $write['sns_ka_key2']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Callback URI</th>
                                    <td><?php echo PH_DOMAIN.'/plugin/snslogin/kakaologin.php'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>네이버 로그인</th>
                    <td>
                        <table class="table2">
                            <tbody>
                                <tr>
                                    <th>사용 유무</th>
                                    <td>
                                        <label class="mr10"><input type="radio" name="use_sns_nv" value="Y" <?php echo $use_sns_nv['Y']; ?> /> 사용</label>
                                        <label><input type="radio" name="use_sns_nv" value="N" <?php echo $use_sns_nv['N']; ?> /> 사용안함</label>
                                        <span class="tbl_sment">
                                            로그인 화면에서 네이버 로그인 기능을 사용하려면 체크 합니다.
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Client ID</th>
                                    <td>
                                        <input type="text" name="sns_nv_key1" class="inp w50p" title="네이버 Client ID" value="<?php echo $write['sns_nv_key1']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Client Secret</th>
                                    <td>
                                        <input type="text" name="sns_nv_key2" class="inp w50p" title="네이버 Client Secret" value="<?php echo $write['sns_nv_key2']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Callback URI</th>
                                    <td><?php echo PH_DOMAIN.'/plugin/snslogin/naverlogin.php'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <button type="submit" class="btn1"><i class="fa fa-check"></i> 변경완료</button>
            </div>
        </div>

        <?php echo $print_target[2]; ?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">정책 및 약관</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>개인정보처리방침</th>
                    <td>
                        <textarea name="privacy"><?php echo $write['privacy']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>이용약관</th>
                    <td>
                        <textarea name="policy"><?php echo $write['policy']; ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <button type="submit" class="btn1"><i class="fa fa-check"></i> 변경완료</button>
            </div>
        </div>

        <?php echo $print_target[3]; ?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">외부 메일서버(SMTP) 설정</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>외부 메일서버(SMTP) 사용</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_smtp" value="Y" <?php echo $use_smtp['Y']; ?> /> 사용</label>
                        <label><input type="radio" name="use_smtp" value="N" <?php echo $use_smtp['N']; ?> /> 사용안함</label>
                        <span class="tbl_sment">
                            zigger에서 발송되는 이메일을 로컬 메일서버가 아닌 외부 메일서버(SMTP)를 통해 발송
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>SMTP Server</th>
                    <td>
                        <input type="text" name="smtp_server" class="inp" title="SMTP Server" value="<?php echo $write['smtp_server']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Port</th>
                    <td>
                        <input type="text" name="smtp_port" class="inp w50" title="SMTP Port" value="<?php echo $write['smtp_port']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>ID</th>
                    <td>
                        <input type="text" name="smtp_id" class="inp" title="SMTP ID" value="<?php echo $write['smtp_id']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>
                        <input type="password" name="smtp_pwd" class="inp" title="SMTP Password" value="<?php echo $write['smtp_pwd']; ?>" />
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <button type="submit" class="btn1"><i class="fa fa-check"></i> 변경완료</button>
            </div>
        </div>

        <?php echo $print_target[4]; ?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="2" class="tal">Object Storage(AWS S3) 설정</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>사용 유무</th>
                    <td>
                        <label class="mr10"><input type="radio" name="use_s3" value="Y" <?php echo $use_s3['Y']; ?> /> 사용</label>
                        <label><input type="radio" name="use_s3" value="N" <?php echo $use_s3['N']; ?> /> 사용안함</label>
                        <span class="tbl_sment">
                            zigger에서 업로드 되는 모든 첨부파일을 원격 Object Storage(AWS S3)로 분산 업로드<br />
                            AWS (Amazon) S3 혹은 GCP (Google), Azure (Microsoft), NCP (Naver)등 S3와 호환되는 Object Storage를 zigger와 연동 가능
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Endpoint</th>
                    <td>
                        <input type="text" name="s3_key1" class="inp w50p" title="Object Storage Endpoint" value="<?php echo $write['s3_key1']; ?>" placeholder="https://s3.ap-northeast-2.amazonaws.com" />
                    </td>
                </tr>
                <tr>
                    <th>Bucket</th>
                    <td>
                        <input type="text" name="s3_key2" class="inp w50p" title="Object Storage Bucket" value="<?php echo $write['s3_key2']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Client key</th>
                    <td>
                        <input type="text" name="s3_key3" class="inp w50p" title="Object Storage Client key" value="<?php echo $write['s3_key3']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Secret key</th>
                    <td>
                        <input type="text" name="s3_key4" class="inp w50p" title="Object Storage Secret key" value="<?php echo $write['s3_key4']; ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Region</th>
                    <td>
                        <input type="text" name="s3_key5" class="inp w33p" title="Object Storage Region" value="<?php echo $write['s3_key5']; ?>" placeholder="ap-northeast-2" />
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <button type="submit" class="btn1"><i class="fa fa-check"></i> 변경완료</button>
            </div>
        </div>

        <?php echo $print_target[5]; ?>

        <table class="table1">
            <thead>
                <tr>
                    <th colspan="3" class="tal">여분필드 (st_1 ~ st_10)</th>
                </tr>
                <tr>
                    <th class="tal">필드명</th>
                    <th class="tal">필드 설명</th>
                    <th class="tal">저장 값</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= 10; $i++) { ?>
                <tr>
                    <th>st_<?php echo $i; ?></th>
                    <td>
                        <input type="text" name="st_exp[]" class="inp w100p" value="<?php echo $write['st_'.$i.'_exp']; ?>" />
                    </td>
                    <td>
                        <input type="text" name="st_<?php echo $i; ?>" class="inp w100p" value="<?php echo $write['st_'.$i]; ?>" />
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="center">
                <button type="submit" class="btn1"><i class="fa fa-check"></i> 변경완료</button>
            </div>
        </div>
    </form>

</article>
