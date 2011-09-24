<?php

class UserController extends BaseController
{
    const ERROR_PASSWORD_RECOVER_AUTH = 'Вы не можете восстановить пароль будучи авторизованным!';


    public function filters() 
    {
    	return array('accessControl');
    }
    
    public function accessRules()
    {
        return array(
            array('deny',
	        	  'actions' => array(
	              	  'ActivateAccountRequest',
	        		  'ChangePasswordRequest',
	        		  'ActivateAccount',
	        		  'Registration',
	        		  'ChangePassword',
	        		  'Login'
	        	  ),
	        	  'users' => array('@')
        	)
        );
    }


    
    public static function actionsTitles() 
    {
        return array(
            "Login"                  => "Авторизация",
            "Logout"                 => "Выход",
            "Registration"           => "Регистрация",
            "ActivateAccount"        => "Активация аккаунта",
            "ActivateAccountRequest" => "Запрос на активацию аккаунта",
            "ChangePassword"         => "Смена пароля",
            "ChangePasswordRequest"  => "Запрос на смену пароля",
        );
    }


    public function loadModel($id)
    {
        $model=User::model()->findByPk((int)$id);
        if($model===null)
        {
            $this->pageNotFound();
        }

        return $model;
    }


    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']))
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    public function actionLogin()
    {	
        $model = new User(User::SCENARIO_LOGIN);

        $form = new BaseForm('users.LoginForm', $model);

        if (isset($_POST['User']['email']) && isset($_POST['User']["password"]))
        {
            $model->attributes = $_POST['User'];

            if ($model->validate())
            {
                $identity = new UserIdentity($model->email, $model->password);
                if ($identity->authenticate())
                {   
                    $this->redirect($this->url('/'));
                }
                else 
                {
                    $auth_error = $identity->errorCode;
                    if ($auth_error == UserIdentity::ERROR_NOT_ACTIVE) 
                    {
                    	$auth_error.= "<br/><a href='" . $this->url('/activateAccountRequest') . "'>
                    							Мне не пришло письмо, активировать аккаунт повторно
                    						</a>";
                    }
                    else if ($auth_error == UserIdentity::ERROR_UNKNOWN) 
                    {
                    	$auth_error.= "<br/><a href='" . $this->url('/changePasswordRequest') . "'>
                    							Восстановить пароль
                    						</a>";                    
                    }
                }    
            }
        }

        $this->render('login', array(
            'form'       => $form,
            'auth_error' => isset($auth_error) ? $auth_error : null
        ));
    }


    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }


    public function actionRegistration()
    {
        Setting::model()->checkRequired(array(
            User::SETTING_REGISTRATION_MAIL_BODY,
            User::SETTING_REGISTRATION_MAIL_SUBJECT,
            User::SETTING_REGISTRATION_DONE_MESSAGE
        ));

        $user = new User(User::SCENARIO_REGISTRATION);
        $form = new BaseForm('users.RegistrationForm', $user);

        if (isset($_POST['User']))
        {
            $user->attributes = $_POST['User'];
            if ($user->validate())
            {
                $user->password = md5($user->password);
                $user->generateActivateCode();
                $user->save(false);
                
                $assignment = new AuthAssignment();
                $assignment->itemname = AuthItem::ROLE_DEFAULT;
                $assignment->userid   = $user->id;
                $assignment->save();                
                
                $user->sendActivationMail();
				
                Yii::app()->user->setFlash(
                    'done',
                    Setting::model()->getValue(User::SETTING_REGISTRATION_DONE_MESSAGE)
                );

                $this->redirect($_SERVER['REQUEST_URI']);
            }
        }

        $this->render('registration', array('form' => $form));
    }


    public function actionActivateAccount($code, $email)
    {
        $user = User::model()->findByAttributes(array('activate_code' => $code));
       	 
        if ($user && md5($user->email) == $email)
        {	
            if (strtotime($user->date_create) + 24 * 3600 > time())
            {	
                $user->activate_date = null;
                $user->activate_code = null;
                $user->status        = User::STATUS_ACTIVE;
                $user->save();
   
		        Yii::app()->user->setFlash(
		        	'acrivate_done', 
		        	'Активация аккаунта прошла успешно! Вы можете авторизоваться.'
		        );
		        
		        $this->redirect($this->url('/login'));
            }
            else
            {
                $activate_error = 'С момента регистрации прошло больше суток!';
            }
        }
        else
        {
            $activate_error = 'Неверные данные активации аккаунта!';
        }
	
        $this->render('activateAccount', array(
        	'activate_error' => isset($activate_error) ? $activate_error : null
        ));
    }


    public function actionActivateAccountRequest()
    {	
        $model = new User(User::SCENARIO_ACTIVATE_REQUEST);

        $form = new BaseForm('users.ActivateRequestForm', $model);

        if (isset($_POST['User']))
        {
            $model->attributes = $_POST['User'];
            if ($model->validate())
            {
                $user = $model->findByAttributes(array('email' => $_POST['User']['email']));

                if (!$user)
                {
                    $error = UserIdentity::ERROR_UNKNOWN;
                }
                else
                {
                    switch ($user->status)
                    {
                        case User::STATUS_NEW:
                            $user->generateActivateCode();
                            $user->save();
                            $user->sendActivationMail();
                            
                            Yii::app()->user->setFlash('done', 'На ваш Email отправлено письмо с дальнейшими инструкциями');
                            
                            $this->redirect($this->url('/activateAccountRequest'));
                            break;

                        case User::STATUS_ACTIVE:
                            $error = UserIdentity::ERROR_ALREADY_ACTIVE;
                            break;

                        case User::STATUS_BLOCKED:
                            $error = UserIdentity::ERROR_BLOCKED;
                            break;
                    }
                }
            }
        }

        $this->render('activateAccountRequest', array(
            'form'  => $form,
            'error' => isset($error) ? $error : null
        ));
    }


//    public function actionChangePassword()
//    {
//        $model = new User(User::SCENARIO_CHANGE_PASSWORD);
//
//        if (isset($_POST['User']))
//        {
//            $model->attributes = $_POST['User'];
//            if ($model->validate())
//            {
//                $user = $model->findByAttributes(array('password' => md5($_POST['User']['password'])));
//                if ($user)
//                {
//                    $user->password = md5($_POST['User']['new_password']);
//                    $user->save();
//
//                    $this->redirect('/users/user/profile/msg/changePassword');
//                }
//                else
//                {
//                    $fail = true;
//                }
//            }
//        }
//
//        $this->render(
//            'ChangePassword',
//            array(
//                'model' => $model,
//                'fail'  => isset($fail) ? $fail : false,
//                'done'  => isset($done) ? $done : false
//            )
//        );
//    }


    public function actionChangePasswordRequest()
    {
    	Setting::model()->checkRequired(array(
    		User::SETTING_CHANGE_PASSWORD_REQUEST_MAIL_SUBJECT,
    		User::SETTING_CHANGE_PASSWORD_REQUEST_MAIL_BODY
    	));
    	
        $model = new User(User::SCENARIO_CHANGE_PASSWORD_REQUEST);
        $form  = new BaseForm('users.ChangePasswordRequestForm', $model);

        if (isset($_POST['User']))
        {
            $model->attributes = $_POST['User'];
            if ($model->validate())
            {
                $user = $model->findByAttributes(array('email' => $model->email));
                if ($user)
                {
                    if ($user->status == User::STATUS_ACTIVE)
                    {	
				        $user->password_recover_code = md5($user->password . $user->email . $user->id . time());
						
				        $mailer_letter = MailerLetter::model();
				        
				        $settings = Setting::model()->findCodesValues();
							
				        MailerModule::sendMail(
				        	$user->email, 
							$mailer_letter->compileText($settings[User::SETTING_CHANGE_PASSWORD_REQUEST_MAIL_SUBJECT]),
				        	$mailer_letter->compileText($settings[User::SETTING_CHANGE_PASSWORD_REQUEST_MAIL_SUBJECT])
				        );
 						
                        //$this->redirect('/users/user/ChangePasswordRequestDone');

                    }
                    else if ($user->status == User::STATUS_NEW)
                    {
                        $error = UserIdentity::ERROR_NOT_ACTIVE;
                    }
                    else
                    {
                        $error = UserIdentity::ERROR_BLOCKED;
                    }
                }
                else
                {
                    $error = UserIdentity::ERROR_UNKNOWN;
                }
            }
        }

        $this->render("changePasswordRequest", array(
            'form'  => $form,
            'error' => isset($error) ? $error : null
        ));
    }


    public function actionChangePassword($code, $email)
    {
		die("actionChangePassword");

        $model = new User(User::SCENARIO_CHANGE_PASSWORD);
        $form  = new BaseForm('users.ChangePasswordForm', $model);
        $user  = $model->findByPk($_GET['id']);

        if (!$user || $user->password_recover_code != $_GET['code'])
        {
            $this->forbidden();
        }
        else
        {
            if (strtotime($user->password_recover_date) + 24 * 3600 > time())
            {
                if (isset($_POST['User']))
                {
                    $model->attributes = $_POST['User'];
                    if ($model->validate())
                    {
                        $user->password_recover_code = null;
                        $user->password_recover_date = null;
                        $user->password = md5($_POST['User']['password']);
                        $user->save();

                        $_SESSION['ChangePassword'] = true;

                        $this->redirect('/users/user/login/from/password_recover');
                    }
                }
            }
            else
            {
                $error = 'С момента запроса на восстановление пароля прошло больше суток!';
            }
        }

        $this->render('changePassword', array(
            'model' => $model,
            'form'  => $form,
            'error' => isset($error) ? $error : null
        ));
    }
}
