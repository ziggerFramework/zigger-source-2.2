<?php
use Corelib\Func;
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Make\Library\Uploader;
use Manage\ManageFunc;

/***
Info
***/
class Info extends \Controller\Make_Controller {

    public function init()
    {
        $this->layout()->mng_head();
        $this->layout()->view(PH_MANAGE_PATH.'/html/adminfo.tpl.php');
        $this->layout()->mng_foot();
    }

    public function make()
    {
        global $MB;

        $manage = new ManageFunc();

        if ($MB['adm'] != 'Y') {
            $func->err_back(ERR_MSG_1);
        }

        $profileimg = '';
        if ($MB['profileimg']) {
            $fileinfo = Func::get_fileinfo($MB['profileimg']);
            $profileimg = $fileinfo['replink'];
        }

        $this->set('manage', $manage);
        $this->set('profileimg', $profileimg);
    }

    public function form()
    {
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'adminfoForm');
        $form->set('type', 'multipart');
        $form->set('action', PH_MANAGE_DIR.'/adm/info-submit');
        $form->run();
    }

}

/***
Submit for Info
***/
class Info_submit{

    public function init(){
        global $MB;

        $sql = new Pdosql();
        $uploader = new Uploader();
        $manage = new ManageFunc();

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post', 'id, name, pwd, pwd2, email');
        $file = Method::request('file', 'profileimg');
        $manage->req_hidden_inp('post');

        Valid::get(
            array(
                'input' => 'id',
                'value' => $req['id'],
                'check' => array(
                    'defined' => 'idx'
                )
            )
        );
        Valid::get(
            array(
                'input' => 'name',
                'value' => $req['name'],
                'check' => array(
                    'defined' => 'nickname'
                )
            )
        );
        Valid::get(
            array(
                'input' => 'email',
                'value' => $req['email'],
                'check' => array(
                    'defined' => 'email'
                )
            )
        );

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("member")}
            WHERE mb_id=:col1 AND mb_dregdate IS NULL AND mb_adm!='Y'
            ",
            array(
                $req['id']
            )
        );

        if ($sql->getcount() > 0) {
            Valid::error('id', '이미 존재하는 아이디입니다.');
        }

        $sql->query(
            "
            SELECT *
            FROM {$sql->table("member")}
            WHERE mb_id=:col1 AND mb_dregdate IS NULL
            ",
            array(
                $req['email']
            )
        );

        if ($sql->getcount() > 0) {
            Valid::error('email', '다른 회원이 사용중인 email 입니다.');
        }

        if ($req['pwd'] != $req['pwd2']) {
            Valid::error('pwd2', '비밀번호와 비밀번호 확인이 일치하지 않습니다.');
        }

        $uploader->path= PH_DATA_PATH.'/memberprofile';
        $uploader->chkpath();

        $profileimg_name = '';

        if ($file['profileimg']['size'] > 0) {
            $uploader->file = $file['profileimg'];
            $uploader->intdict = SET_IMGTYPE;
            if ($uploader->chkfile('match') !== true) {
                Valid::error('profileimg', '허용되지 않는 프로필 이미지 유형입니다.');
            }
            $profileimg_name = $uploader->replace_filename($file['profileimg']['name']);
            if (!$uploader->upload($profileimg_name)) {
                Valid::error('profileimg', '프로필 이미지 업로드 실패');
            }
        }
        if (($file['profileimg']['size'] > 0 && $MB['profileimg'] != '')) {
            $uploader->drop($MB['profileimg']);
        }
        if ($MB['profileimg'] != '' && !$file['profileimg']['name']) {
            $profileimg_name = $MB['profileimg'];
        }

        if ($req['pwd'] != '') {

            Valid::get(
                array(
                    'input' => 'pwd',
                    'value' => $req['pwd'],
                    'check' => array(
                        'defined' => 'password'
                    )
                )
            );

            $sql->query(
                "
                UPDATE {$sql->table("member")}
                SET mb_id=:col1,mb_name=:col2,mb_pwd=password(:col3),mb_email=:col4,mb_profileimg=:col5
                WHERE mb_adm='Y' AND mb_idx=:col6
                ",
                array(
                    $req['id'], $req['name'], $req['pwd'], $req['email'], $profileimg_name, $MB['idx']
                )
            );

        } else {

            $sql->query(
                "
                UPDATE {$sql->table("member")}
                SET mb_id=:col1,mb_name=:col2,mb_pwd=:col3,mb_email=:col4,mb_profileimg=:col5
                WHERE mb_adm='Y' AND mb_idx=:col6
                ",
                array(
                    $req['id'], $req['name'], $MB['pwd'], $req['email'], $profileimg_name, $MB['idx']
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
