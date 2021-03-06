<?php
namespace Module\Contactform;

use Corelib\Func;
use Corelib\Method;
use Corelib\Valid;
use Make\Database\Pdosql;
use Make\Library\Mail;

/***
Form
***/
class Form extends \Controller\Make_Controller {

    public function init()
    {
        $this->layout()->view(MOD_CONTACTFORM_THEME_PATH.'/form.tpl.php');
    }

    public function make()
    {
        $this->set('captcha', Func::get_captcha('', 1));
    }

    public function form()
    {
        $form = new \Controller\Make_View_Form();
        $form->set('id', 'contactForm');
        $form->set('type', 'html');
        $form->set('action', MOD_CONTACTFORM_DIR.'/controller/form/contactus-submit');
        $form->run();
    }

}

/***
Submit for Form
***/
class Contactus_submit {

    public function init()
    {
        global $MODULE_CONTACTFORM_CONF, $CONF;

        $sql = new Pdosql();
        $mail = new Mail();

        Method::security('referer');
        Method::security('request_post');
        $req = Method::request('post','name, email, phone, article, captcha, contact_1, contact_2, contact_3, contact_4, contact_5, contact_6, contact_7, contact_8, contact_9, contact_10');

        Valid::get(
            array(
                'input' => 'name',
                'value' => $req['name']
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
        Valid::get(
            array(
                'input' => 'phone',
                'value' => $req['phone'],
                'check' => array(
                    'defined' => 'phone'
                )
            )
        );
        Valid::get(
            array(
                'input' => 'article',
                'value' => $req['article']
            )
        );

        if (!IS_MEMBER) {
            if (!Func::chk_captcha($req['captcha'])) {
                Valid::set(
                    array(
                        'return' => 'error',
                        'input' => 'captcha',
                        'err_code' => 'NOTMATCH_CAPTCHA'
                    )
                );
                Valid::turn();
            }
        }

        //insert
        $sql->query(
            "
            INSERT INTO {$sql->table("mod:contactform")}
            (mb_idx,article,name,email,phone,regdate,contact_1,contact_2,contact_3,contact_4,contact_5,contact_6,contact_7,contact_8,contact_9,contact_10)
            VALUES
            (:col1,:col2,:col3,:col4,:col5,now(),:col6,:col7,:col8,:col9,:col10,:col11,:col12,:col13,:col14,:col15)
            ",
            array(
                MB_IDX, $req['article'], $req['name'], $req['email'], $req['phone'], $req['contact_1'], $req['contact_2'], $req['contact_3'], $req['contact_4'], $req['contact_5'], $req['contact_6'], $req['contact_7'], $req['contact_8'], $req['contact_9'], $req['contact_10']
            )
        );

        //mail
        $memo = '
            ????????? ????????? ?????????????????????.<br /><br />
            <a href="'.PH_DOMAIN.'/manage/mod/'.MOD_CONTACTFORM.'/result/result">'.PH_DOMAIN.'/manage/mod/'.MOD_CONTACTFORM.'/result/result</a> ??? ????????????<br />
            ?????? ???????????? ?????? ??? ???????????????.
        ';
        $mail->set(
            array(
                'to' => array(
                    [
                        'email' => $CONF['email']
                    ]
                ),
                'subject' => '????????? ????????? ?????????????????????.',
                'memo' => $memo
            )
        );
        $mail->send();

        //????????? ?????? ????????? ??????
        Func::add_mng_feed(
            array(
                'from' => $MODULE_CONTACTFORM_CONF['title'],
                'msg' => '<strong>'.$req['name'].'</strong>?????? ????????? ????????? ??????????????????.',
                'link' => '/manage/mod/'.MOD_CONTACTFORM.'/result/result'
            )
        );

        //return
        Valid::set(
            array(
                'return' => 'alert->reload',
                'msg' => '????????? ??????????????? ?????? ???????????????.',
            )
        );
        Valid::turn();
    }

}
