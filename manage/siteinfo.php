<?php
use Corelib\Func;
use Corelib\Method;
use Corelib\Valid;
use Make\Library\Uploader;
use Make\Database\Pdosql;
use Manage\ManageFunc;

/***
Info
***/
class Info extends \Controller\Make_Controller {

    public function init(){
        $this->layout()->mng_head();
        $this->layout()->view(PH_MANAGE_PATH.'/html/siteinfo/info.tpl.php');
        $this->layout()->mng_foot();
    }

    public function func()
    {
        function set_checked($arr, $val)
        {
            $setarr = array(
                'Y' => '',
                'N' => '',
                'O' => ''
            );
            foreach ($setarr as $key => $value) {
                if ($key == $arr[$val] || ($key == 'N' && !$arr[$val])) {
                    $setarr[$key] = 'checked';
                }
            }
            return $setarr;
        }

        function mb_division($arr)
        {
            $ex = explode('|', $arr['mb_division']);
            $arr = array();
            for ($i = 1; $i <= count($ex); $i++) {
                $arr[$i] = $ex[(int)$i - 1];
            }
            return $arr;
        }
    }

    public function make()
    {
        $manage = new ManageFunc();
        $sql = new Pdosql();

        $manage->make_target('사이트 기본 정보|회원가입 입력 항목 설정|정책 및 약관|여분필드');

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("config")}
            WHERE cfg_type='engine'
            ", []
        );

        $arr = array();

        do {
            $sql->specialchars = 1;
            $sql->nl2br = 1;

            $cfg = $sql->fetchs();
            $arr[$cfg['cfg_key']] = $cfg['cfg_value'];

            if ($cfg['cfg_key'] == 'policy' || $cfg['cfg_key'] == 'privacy') {
                $sql->specialchars = 0;
                $sql->nl2br = 0;

                $arr[$cfg['cfg_key']] = $sql->fetch('cfg_value');
            }

        } while($sql->nextRec());

        //logo file
        if ($arr['logo']) {
            $arr[0]['logo'] = Func::get_fileinfo($arr['logo']);
        }

        //favicon file
        if ($arr['favicon']) {
            $arr[0]['favicon'] = Func::get_fileinfo($arr['favicon']);
        }

        $ex = explode('|', $arr['st_exp']);

        for ($i = 1; $i <= 10; $i++) {
            $arr['st_'.$i.'_exp'] = $ex[$i - 1];
        }

        if ($arr['logo'] != '') {
            $is_logo_show = true;

        } else {
            $is_logo_show = false;
        }

        if ($arr['favicon'] != '') {
            $is_favicon_show = true;

        } else {
            $is_favicon_show = false;
        }

        $write = array();

        if (isset($arr)) {
            foreach ($arr as $key => $value) {
                $write[$key] = $value;
            }

        } else {
            $write = null;
        }

        $this->set('manage', $manage);
        $this->set('is_logo_show', $is_logo_show);
        $this->set('is_favicon_show', $is_favicon_show);
        $this->set('print_target', $manage->print_target());
        $this->set('use_mobile', set_checked($arr, 'use_mobile'));
        $this->set('use_emailchk', set_checked($arr, 'use_emailchk'));
        $this->set('use_mb_phone', set_checked($arr, 'use_mb_phone'));
        $this->set('use_phonechk', set_checked($arr, 'use_phonechk'));
        $this->set('use_mb_telephone', set_checked($arr, 'use_mb_telephone'));
        $this->set('use_mb_address', set_checked($arr, 'use_mb_address'));
        $this->set('use_mb_gender', set_checked($arr, 'use_mb_gender'));
        $this->set('mb_division', mb_division($arr));
        $this->set('write', $write);
    }

    public function form()
    {
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'siteinfoForm');
        $form->set('type', 'multipart');
        $form->set('action', PH_MANAGE_DIR.'/siteinfo/info-submit');
        $form->run();
    }

}

/***
Submit for Info
***/
class Info_submit {

    public function init()
    {
        $sql = new Pdosql();
        $manage = new ManageFunc();
        $uploader = new Uploader();

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post', 'title, domain, description, use_mobile, use_emailchk, email, tel, mb_division, theme, privacy, policy, favicon_del, uploaded_favicon, logo_del, uploaded_logo, use_mb_phone, use_phonechk, use_mb_telephone, use_mb_address, use_mb_gender, st_1, st_2, st_3, st_4, st_5, st_6, st_7, st_8, st_9, st_10, st_exp');
        $file = Method::request('file', 'favicon, logo');
        $manage->req_hidden_inp('post');

        Valid::get(
            array(
                'input' => 'title',
                'value' => $req['title']
            )
        );
        Valid::get(
            array(
                'input' => 'domain',
                'value' => $req['domain']
            )
        );
        Valid::get(
            array(
                'input' => 'email',
                'value' => $req['email']
            )
        );

        for ($i = 0; $i < count($req['mb_division']); $i++) {
            if (!$req['mb_division'][$i]) {
                Valid::error('', '회원 등급별 명칭을 모두 입력해 주세요.');
            }
        }

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("config")}
            WHERE cfg_type='engine'
            ", []
        );

        $arr = array();

        do {
            $arr[$sql->fetch('cfg_key')] = $sql->fetch('cfg_value');

        } while($sql->nextRec());

        $uploader->path= PH_DATA_PATH.'/manage';
        $uploader->chkpath();

        $favicon_name = '';

        if ($file['favicon']['size'] > 0) {
            $uploader->file = $file['favicon'];
            $uploader->intdict = 'ico';

            if ($uploader->chkfile('match') !== true) {
                Valid::error('favicon','허용되지 않는 파비콘 유형입니다.');
            }

            $favicon_name = $uploader->replace_filename($file['favicon']['name']);

            if (!$uploader->upload($favicon_name)) {
                Valid::error('favicon', '파비콘 업로드 실패');
            }
        }

        if (($file['favicon']['size'] > 0 && $arr['favicon'] != '') || $req['favicon_del'] == 'checked') {
            $uploader->drop($arr['favicon']);
        }
        if ($arr['favicon'] != '' && !$file['favicon']['name'] && $req['favicon_del'] != 'checked') {
            $favicon_name = $arr['favicon'];
        }

        $logo_name = '';

        if ($file['logo']['size'] > 0) {
            $uploader->file = $file['logo'];
            $uploader->intdict = SET_IMGTYPE;
            if ($uploader->chkfile('match') !== true) {
                Valid::error('logo', '허용되지 않는 로고 유형입니다.');
            }
            $logo_name = $uploader->replace_filename($file['logo']['name']);
            if (!$uploader->upload($logo_name)) {
                Valid::error('logo', '로고 업로드 실패');
            }
        }
        if (($file['logo']['size'] > 0 && $arr['logo'] != '') || $req['logo_del'] == 'checked') {
            $uploader->drop($arr['logo']);
        }
        if ($arr['logo'] != '' && !$file['logo']['name'] && $req['logo_del'] != 'checked') {
            $logo_name = $arr['logo'];
        }

        if ($req['use_phonechk'] == 'Y' && $arr['use_sms'] != 'Y') {
            Valid::error('use_phonechk', 'SMS 문자 발송 기능이 활성화되지 않아 휴대전화 인증을 사용할 수 없습니다.');
        }

        if ($req['use_mb_phone'] == 'N' && $req['use_phonechk'] == 'Y') {
            Valid::error('use_phonechk', '휴대전화 입력이 활성화되지 않아 휴대전화 인증을 사용할 수 없습니다.');
        }

        $mb_division = implode('|', $req['mb_division']);
        $st_exp = $sql->etcfd_exp(implode('|', $req['st_exp']));

        $data = array(
            'title' => $req['title'],
            'domain' => $req['domain'],
            'description' => $req['description'],
            'use_mobile' => $req['use_mobile'],
            'use_emailchk' => $req['use_emailchk'],
            'email' => $req['email'],
            'tel' => $req['tel'],
            'favicon' => $favicon_name,
            'logo' => $logo_name,
            'mb_division' => $mb_division,
            'privacy' => $req['privacy'],
            'policy' => $req['policy'],
            'use_mb_phone' => $req['use_mb_phone'],
            'use_phonechk' => $req['use_phonechk'],
            'use_mb_telephone' => $req['use_mb_telephone'],
            'use_mb_address' => $req['use_mb_address'],
            'use_mb_gender' => $req['use_mb_gender'],
            'st_1' => $req['st_1'],
            'st_2' => $req['st_2'],
            'st_3' => $req['st_3'],
            'st_4' => $req['st_4'],
            'st_5' => $req['st_5'],
            'st_6' => $req['st_6'],
            'st_7' => $req['st_7'],
            'st_8' => $req['st_8'],
            'st_9' => $req['st_9'],
            'st_10' => $req['st_10'],
            'st_exp' => $st_exp
        );

        foreach ($data as $key => $value) {
            $sql->query(
                "
                UPDATE
                {$sql->table("config")}
                SET
                cfg_value=:col1
                WHERE cfg_type='engine' AND cfg_key='{$key}'
                ",
                array(
                    $value
                )
            );
        }

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 변경 되었습니다.'
            )
        );
        Valid::turn();
    }
}

/***
Plugins
***/
class Plugins extends \Controller\Make_Controller {

    public function init(){
        $this->layout()->mng_head();
        $this->layout()->view(PH_MANAGE_PATH.'/html/siteinfo/plugins.tpl.php');
        $this->layout()->mng_foot();
    }

    public function func()
    {
        function set_checked($arr, $val)
        {
            $setarr = array(
                'Y' => '',
                'N' => ''
            );
            foreach ($setarr as $key => $value) {
                if ($key == $arr[$val] || ($key == 'N' && !$arr[$val])) {
                    $setarr[$key] = 'checked';
                }
            }
            return $setarr;
        }
    }

    public function make()
    {
        $manage = new ManageFunc();
        $sql = new Pdosql();

        $manage->make_target('Google reCaptcha 연동|SNS 로그인 API 관리|외부 SMTP(메일서버) 연동|Object Storage(AWS S3) 연동|SMS 문자발송(NCP SENS) 연동');

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("config")}
            WHERE cfg_type='engine'
            ", []
        );

        $arr = array();

        do {
            $cfg = $sql->fetchs();
            $arr[$cfg['cfg_key']] = $cfg['cfg_value'];

        } while($sql->nextRec());

        if (isset($arr)) {
            foreach ($arr as $key => $value) {
                $write[$key] = $value;
            }

        } else {
            $write = null;
        }

        $this->set('manage', $manage);
        $this->set('print_target', $manage->print_target());
        $this->set('use_recaptcha', set_checked($arr, 'use_recaptcha'));
        $this->set('use_s3', set_checked($arr, 'use_s3'));
        $this->set('use_sns_ka', set_checked($arr, 'use_sns_ka'));
        $this->set('use_sns_nv', set_checked($arr, 'use_sns_nv'));
        $this->set('use_smtp', set_checked($arr, 'use_smtp'));
        $this->set('use_sms', set_checked($arr, 'use_sms'));
        $this->set('use_feedsms', set_checked($arr, 'use_feedsms'));
        $this->set('write', $write);
    }

    public function form()
    {
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'pluginsForm');
        $form->set('type', 'html');
        $form->set('action', PH_MANAGE_DIR.'/siteinfo/plugins-submit');
        $form->run();
    }

}

/***
Submit for Plugins
***/
class Plugins_submit {

    public function init()
    {
        $sql = new Pdosql();
        $manage = new ManageFunc();

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post', 'use_recaptcha, recaptcha_key1, recaptcha_key2, use_sns_ka, sns_ka_key1, sns_ka_key2, use_sns_nv, sns_nv_key1, sns_nv_key2, use_smtp, smtp_server, smtp_port, smtp_id, smtp_pwd, use_s3, s3_key1, s3_key2, s3_key3, s3_key4, s3_key5, use_sms, use_feedsms, sms_toadm, sms_from, sms_key1, sms_key2, sms_key3, sms_key4');
        $manage->req_hidden_inp('post');

        if ($req['use_recaptcha'] == 'Y') {
            Valid::get(
                array(
                    'input' => 'recaptcha_key1',
                    'value' => $req['recaptcha_key1']
                )
            );
            Valid::get(
                array(
                    'input' => 'recaptcha_key2',
                    'value' => $req['recaptcha_key2']
                )
            );
        }

        if ($req['use_sns_ka'] == 'Y') {
            Valid::get(
                array(
                    'input' => 'sns_ka_key1',
                    'value' => $req['sns_ka_key1']
                )
            );
            Valid::get(
                array(
                    'input' => 'sns_ka_key2',
                    'value' => $req['sns_ka_key2']
                )
            );
        }

        if ($req['use_sns_nv'] == 'Y') {
            Valid::get(
                array(
                    'input' => 'sns_nv_key1',
                    'value' => $req['sns_nv_key1']
                )
            );
            Valid::get(
                array(
                    'input' => 'sns_nv_key2',
                    'value' => $req['sns_nv_key2']
                )
            );
        }

        if ($req['use_smtp'] == 'Y') {
            Valid::get(
                array(
                    'input' => 'smtp_server',
                    'value' => $req['smtp_server']
                )
            );
            Valid::get(
                array(
                    'input' => 'smtp_port',
                    'value' => $req['smtp_port']
                )
            );
            Valid::get(
                array(
                    'input' => 'smtp_id',
                    'value' => $req['smtp_id']
                )
            );
            Valid::get(
                array(
                    'input' => 'smtp_pwd',
                    'value' => $req['smtp_pwd']
                )
            );
        }

        if ($req['use_s3'] == 'Y') {
            Valid::get(
                array(
                    'input' => 's3_key1',
                    'value' => $req['s3_key1']
                )
            );
            Valid::get(
                array(
                    'input' => 's3_key2',
                    'value' => $req['s3_key2']
                )
            );
            Valid::get(
                array(
                    'input' => 's3_key3',
                    'value' => $req['s3_key3']
                )
            );
            Valid::get(
                array(
                    'input' => 's3_key4',
                    'value' => $req['s3_key4']
                )
            );
            Valid::get(
                array(
                    'input' => 's3_key5',
                    'value' => $req['s3_key5']
                )
            );
        }

        if ($req['use_sms'] == 'Y') {
            Valid::get(
                array(
                    'input' => 'sms_from',
                    'value' => $req['sms_from']
                )
            );
            Valid::get(
                array(
                    'input' => 'sms_key1',
                    'value' => $req['sms_key1']
                )
            );
            Valid::get(
                array(
                    'input' => 'sms_key2',
                    'value' => $req['sms_key2']
                )
            );
            Valid::get(
                array(
                    'input' => 'sms_key3',
                    'value' => $req['sms_key3']
                )
            );
            Valid::get(
                array(
                    'input' => 'sms_key4',
                    'value' => $req['sms_key4']
                )
            );
        }

        if ($req['use_feedsms'] == 'Y') {
            if ($req['use_sms'] != 'Y') {
                Valid::error($req['use_sms'], '피드 SMS 발송을 위해선 SMS가 활성화 되어야 합니다.');
            }
            if (!$req['sms_toadm']) {
                Valid::error($req['sms_toadm'], '피드 SMS 발송을 위해선 수신 받을 연락처가 입력 되어야 합니다.');
            }
        }

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("config")}
            WHERE cfg_type='engine'
            ", []
        );

        $arr = array();

        do {
            $arr[$sql->fetch('cfg_key')] = $sql->fetch('cfg_value');

        } while($sql->nextRec());

        $data = array(
            'use_recaptcha' => $req['use_recaptcha'],
            'recaptcha_key1' => $req['recaptcha_key1'],
            'recaptcha_key2' => $req['recaptcha_key2'],
            'use_sns_ka' => $req['use_sns_ka'],
            'sns_ka_key1' => $req['sns_ka_key1'],
            'sns_ka_key2' => $req['sns_ka_key2'],
            'use_sns_nv' => $req['use_sns_nv'],
            'sns_nv_key1' => $req['sns_nv_key1'],
            'sns_nv_key2' => $req['sns_nv_key2'],
            'use_smtp' => $req['use_smtp'],
            'smtp_server' => $req['smtp_server'],
            'smtp_port' => $req['smtp_port'],
            'smtp_id' => $req['smtp_id'],
            'smtp_pwd' => $req['smtp_pwd'],
            'use_s3' => $req['use_s3'],
            's3_key1' => $req['s3_key1'],
            's3_key2' => $req['s3_key2'],
            's3_key3' => $req['s3_key3'],
            's3_key4' => $req['s3_key4'],
            's3_key5' => $req['s3_key5'],
            'use_sms' => $req['use_sms'],
            'use_feedsms' => $req['use_feedsms'],
            'sms_toadm' => $req['sms_toadm'],
            'sms_from' => $req['sms_from'],
            'sms_key1' => $req['sms_key1'],
            'sms_key2' => $req['sms_key2'],
            'sms_key3' => $req['sms_key3'],
            'sms_key4' => $req['sms_key4']
        );

        foreach ($data as $key => $value) {
            $sql->query(
                "
                UPDATE
                {$sql->table("config")}
                SET
                cfg_value=:col1
                WHERE cfg_type='engine' AND cfg_key='{$key}'
                ",
                array(
                    $value
                )
            );
        }

        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 변경 되었습니다.'
            )
        );
        Valid::turn();
    }
}

/***
Seo
***/
class Seo extends \Controller\Make_Controller {

    public function init(){
        $this->layout()->mng_head();
        $this->layout()->view(PH_MANAGE_PATH.'/html/siteinfo/seo.tpl.php');
        $this->layout()->mng_foot();
    }

    public function make()
    {
        $sql = new Pdosql();
        $manage = new ManageFunc();

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("config")}
            WHERE cfg_type='engine'
            ", []
        );

        $arr = array();

        do {
            $sql->specialchars = 1;
            $sql->nl2br = 1;

            $cfg = $sql->fetchs();
            $arr[$cfg['cfg_key']] = $cfg['cfg_value'];

            if ($cfg['cfg_key'] == 'script' || $cfg['cfg_key'] == 'meta') {
                $sql->specialchars = 0;
                $sql->nl2br = 0;

                $arr[$cfg['cfg_key']] = $sql->fetch('cfg_value');
            }

        } while($sql->nextRec());

        if ($arr['og_image'] != '') {
            $is_og_image_show = true;

        } else {
            $is_og_image_show = false;
        }

        //Make robots.txt
        $file = fopen(PH_PATH.'/robots.txt', 'r');
        $fileContent = '';
        while (!feof($file)) {
            $fileContent .= fgets($file);
        }
        fclose($file);
        $arr['robotrule'] = $fileContent;
        $arr[0]['og_image'] = Func::get_fileinfo($arr['og_image']);


        $write = array();

        if (isset($arr)) {
            foreach ($arr as $key => $value) {
                $write[$key] = $value;
            }

        } else {
            $write = null;
        }

        $this->set('manage', $manage);
        $this->set('is_og_image_show', $is_og_image_show);
        $this->set('write', $write);
    }

    public function form()
    {
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'metaconfForm');
        $form->set('type', 'multipart');
        $form->set('action', PH_MANAGE_DIR.'/siteinfo/seo-submit');
        $form->run();
    }

}

/***
Submit for Seo
***/
class Seo_submit {

    public function init()
    {
        $sql = new Pdosql();
        $manage = new ManageFunc();
        $uploader = new Uploader();

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post', 'og_type, og_title, og_description, og_url, naver_verific, google_verific, script, meta, og_image_del, robotrule');
        $file = Method::request('file', 'og_image');
        $manage->req_hidden_inp('post');

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("config")}
            WHERE cfg_type='engine'
            ", []
        );

        $arr = array();

        do {
            $arr[$sql->fetch('cfg_key')] = $sql->fetch('cfg_value');

        } while($sql->nextRec());

        $uploader->path= PH_DATA_PATH.'/manage';
        $uploader->chkpath();

        $og_image_name = '';

        if ($file['og_image']['size'] > 0) {
            $uploader->file = $file['og_image'];
            $uploader->intdict = SET_IMGTYPE;

            if ($uploader->chkfile('match') !== true) {
                Valid::error('og_image', '허용되지 않는 이미지 유형입니다.');
            }

            $og_image_name = $uploader->replace_filename($file['og_image']['name']);

            if (!$uploader->upload($og_image_name)) {
                Valid::error('og_image', '이미지 업로드 실패');
            }
        }

        if (($file['og_image']['size'] > 0 && $arr['og_image'] != '') || $req['og_image_del'] == 'checked') {
            $uploader->drop($arr['og_image']);
        }

        if ($arr['og_image'] != '' && !$file['og_image']['name'] && $req['og_image_del'] != 'checked'){
            $og_image_name = $arr['og_image'];
        }

        $data = array(
            'og_type' => $req['og_type'],
            'og_title' => $req['og_title'],
            'og_description' => $req['og_description'],
            'og_url' => $req['og_url'],
            'og_image' => $og_image_name,
            'naver_verific' => $req['naver_verific'],
            'google_verific' => $req['google_verific'],
            'script' => $req['script'],
            'meta' => $req['meta']
        );

        foreach ($data as $key => $value) {
            $sql->query(
                "
                UPDATE
                {$sql->table("config")}
                SET
                cfg_value=:col1
                WHERE cfg_type='engine' AND cfg_key='{$key}'
                ",
                array(
                    $value
                )
            );
        }

        //Make robots.txt
        $file = fopen(PH_PATH.'/robots.txt', 'w');
        fwrite($file, $req['robotrule']);
        fclose($file);


        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '성공적으로 변경 되었습니다.'
            )
        );
        Valid::turn();
    }

}

/***
Sitemap
***/
class Sitemap extends \Controller\Make_Controller {

    public function init()
    {
        $this->layout()->mng_head();
        $this->layout()->view(PH_MANAGE_PATH.'/html/siteinfo/sitemap.tpl.php');
        $this->layout()->mng_foot();
    }

    public function make(){

    }

    public function form(){
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'sitemapListForm');
        $form->set('type', 'html');
        $form->set('action', PH_MANAGE_DIR.'/siteinfo/sitemapList-submit');
        $form->run();
    }

    public function form2(){
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'sitemapMofidyForm');
        $form->set('type', 'html');
        $form->set('action', PH_MANAGE_DIR.'/siteinfo/sitemapModify-submit');
        $form->run();
    }

}

/***
Sitemap List
***/
class SitemapList extends \Controller\Make_Controller {

    public function init()
    {
        $this->layout()->view(PH_MANAGE_PATH.'/html/siteinfo/sitemapList.tpl.php');
    }

    public function make()
    {
        $sql = new Pdosql();
        $sql2 = new Pdosql();
        $sql3 = new Pdosql();

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("sitemap")}
            WHERE CHAR_LENGTH(caidx)=4
            ORDER BY caidx ASC
            ", []
        );
        $list_cnt = $sql->getcount();

        $print_arr = array();

        if ($list_cnt > 0) {
            do {
                $arr = $sql->fetchs();

                //depth 2
                $print_arr2 = array();
                if ($sql->fetch('children') > 0) {

                    $sql2->query(
                        "
                        SELECT *
                        FROM {$sql2->table("sitemap")}
                        WHERE SUBSTR(caidx,1,4)=:col1 AND CHAR_LENGTH(caidx)=8
                        ORDER BY caidx ASC
                        ",
                        array(
                            $arr['caidx']
                        )
                    );

                    do {
                        $arr2 = $sql2->fetchs();

                        //depth 3
                        $print_arr3 = array();
                        if ($sql2->fetch('children') > 0) {
                            $sql3->query(
                                "
                                SELECT *
                                FROM {$sql3->table("sitemap")}
                                WHERE SUBSTR(caidx,1,8)=:col1 AND CHAR_LENGTH(caidx)=12
                                ORDER BY caidx ASC
                                ",
                                array(
                                    $arr2['caidx']
                                )
                            );

                            do {
                                $arr3 = $sql3->fetchs();

                                $print_arr3[] = $arr3;
                            } while ($sql3->nextRec());
                        }
                        $arr2['3d'] = $print_arr3;

                        $print_arr2[] = $arr2;
                    } while ($sql2->nextRec());
                }
                $arr['2d'] = $print_arr2;

                $print_arr[] = $arr;

            } while ($sql->nextRec());
        }

        $this->set('print_arr', $print_arr);

    }

}

/***
Submit for Sitemap List
***/
class SitemapList_submit{

    public function init()
    {
        global $req;

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post', 'type, idx, org_caidx, caidx, new_caidx');

        switch ($req['type']) {
            case 'add' :
                $this->get_add();
                break;

            case 'modify' :
                $this->get_modify();
                break;
        }
    }

    ///
    // add
    ///
    public function get_add()
    {
        global $req;

        $sql = new Pdosql();

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("sitemap")}
            ORDER BY idx DESC
            LIMIT 1
            ", []
        );

        $recent_idx = $sql->fetch('idx');

        if ($recent_idx) {
            $recent_idx++;

        } else {
            $recent_idx = 1;
        }

        switch (strlen($req['new_caidx'])) {
            case 4 :
            $new_depth = 1;
            break;

            case 8 :
            $new_len = 8;
            $new_depth = 2;
            break;

            case 12 :
            $new_len = 12;
            $new_depth = 3;
            break;
        }

        if ($new_depth >= 2) {
            $prt_caidx = substr($req['new_caidx'], 0, $new_len - 4);

            $sql->query(
                "
                SELECT COUNT(*) count
                FROM {$sql->table("sitemap")}
                WHERE caidx LIKE :col1
                ",
                array(
                    $prt_caidx.'%'
                )
            );
            $children_count = $sql->fetch('count');

            $sql->query(
                "
                UPDATE {$sql->table("sitemap")}
                SET
                children=:col1
                WHERE caidx=:col2
                ",
                array(
                    $children_count,
                    $prt_caidx
                )
            );
        }

        $sql->query(
            "
            INSERT INTO
            {$sql->table("sitemap")}
            (idx,caidx,title,children)
            VALUES
            (:col1,:col2,:col3,:col4)
            ",
            array(
                $recent_idx,
                $req['new_caidx'],
                '새로운 '.$new_depth.'차 카테고리',
                0
            )
        );

        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'sitemap_list.action(\'list_reload\');'
            )
        );
        Valid::turn();
    }

    ///
    // modify
    ///
    public function get_modify()
    {
        global $req, $where;

        $sql = new Pdosql();

        $where = '';

        if (count($req['idx']) < 1) {
            $where = 'idx!=-1';

        } else {
            for ($i = 0; $i < count($req['idx']); $i++) {
                if ($i == 0) {
                    $where .= 'idx!=\''.$req['idx'][$i].'\'';

                } else {
                    $where .= ' AND idx!=\''.$req['idx'][$i].'\'';
                }
            }
        }

        $sql->query(
            "
            DELETE
            FROM {$sql->table("sitemap")}
            WHERE $where
            ", []
        );

        $children_count = array();

        for ($i = 0; $i < count($req['idx']); $i++) {
            $sql->query(
                "
                SELECT COUNT(*) count
                FROM {$sql->table("sitemap")}
                WHERE caidx LIKE :col1
                ",
                array(
                    $req['org_caidx'][$i].'%'
                )
            );
            $children_count[$i] = $sql->fetch('count') - 1;
        }

        for ($i = 0; $i < count($req['idx']); $i++) {
            $sql->query(
                "
                UPDATE {$sql->table("sitemap")}
                SET
                caidx=:col1,children=:col2
                WHERE idx=:col3
                ",
                array(
                    $req['caidx'][$i],
                    $children_count[$i],
                    $req['idx'][$i]
                )
            );
        }

        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'sitemap_list.action(\'list_reload\');'
            )
        );
        Valid::turn();
    }
}

/***
Sitemap Modify
***/
class SitemapModify extends \Controller\Make_Controller {

    public function init(){
        $this->layout()->view(PH_MANAGE_PATH.'/html/siteinfo/sitemapModify.tpl.php');
    }

    public function func()
    {

        function set_checked($arr, $val){
            $setarr = array(
                'Y' => '',
                'N' => ''
            );
            foreach ($setarr as $key => $value) {
                if ($key == $arr[$val]) {
                    $setarr[$key] = 'checked';
                }
            }
            return $setarr;
        }
    }

    public function make()
    {
        $req = Method::request('get', 'idx');

        $sql = new Pdosql();

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("sitemap")}
            WHERE idx=:col1
            ",
            array(
                $req['idx']
            )
        );
        $arr = $sql->fetchs();

        $write = array();

        if (isset($arr)) {
            foreach ($arr as $key => $value) {
                $write[$key] = $value;
            }

        } else {
            $write = null;
        }

        $this->set('visible', set_checked($arr, 'visible'));
        $this->set('write', $write);
    }
}

/***
Submit for Sitemap Modify
***/
class SitemapModify_submit{

    public function init()
    {
        $manage = new ManageFunc();
        $sql = new Pdosql();

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post', 'idx, title, href, visible');

        Valid::get(
            array(
                'input' => 'title',
                'value' => $req['title']
            )
        );
        Valid::get(
            array(
                'input' => 'href',
                'value' => $req['href']
            )
        );

        $sql->query(
            "
            UPDATE {$sql->table("sitemap")}
            SET
            title=:col1,href=:col2,visible=:col3
            WHERE idx=:col4
            ",
            array(
                $req['title'],
                $req['href'],
                $req['visible'],
                $req['idx']
            )
        );

        Valid::set(
            array(
                'return' => 'callback',
                'function' => 'sitemap_list.action(\'secc_modify\');'
            )
        );
        Valid::turn();
    }

}
