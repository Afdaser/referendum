<?php

class UserController extends Controller
{

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

	}

    /*
     * Save main data
     */
    private function main($user,$attributes){
        $error = null;
        $user->scenario = 'main';
        $user->setMain($attributes);
        if($user->validate(null, false)){
            $user->save(false);
        }

        return $user;
    }

    /*
     * Save user interests
     */
    private function interests($user,$attributes){
        $error = null;

        if(isset($user->interests)){
            $interests = $user->interests;
        } else {
            $interests = new UserInterest;
            $interests->date_add = date('Y-m-d H:i:s');
        }

        $interests->setInterests($attributes);
        if(!$interests->validate(null, false) || !$interests->save(false)){
            $error = json_encode(CHtml::errorSummary($interests));
        }

        return $error;
    }

    /*
     * Save user secondary education
     */
    private function secondaryEducation($user,$attributes){
        $error = null;
        if(isset($user->secondaryEducation)){
            $secondary = $user->secondaryEducation;
        } else {
            $secondary = new UserSecondaryEducation;
            $secondary->date_add = date('Y-m-d H:i:s');
        }

        $secondary->setEducation($attributes);
        if(!$secondary->validate(null, false) || !$secondary->save(false)){
            $error = CHtml::errorSummary($secondary);
        }

        return $error;
    }

    /*
     * save user high education
     */
    private function highEducation($user,$attributes){
        $error = null;
        if(isset($user->highEducation)){
            $high = $user->highEducation;
        } else {
            $high = new UserHighEducation;
            $high->date_add = date('Y-m-d H:i:s');
        }

        $high->setEducation($attributes);
        if(!$high->validate(null, false) || !$high->save(false)){
            $error = CHtml::errorSummary($high);
        }

        return $error;
    }

    /*
     * save user career information
     */
    private function career($user,$attributes){
        $error = null;

        if(isset($user->career)){
            $career = $user->career;
        } else {
            $career = new UserCareer;
            $career->date_add = date('Y-m-d H:i:s');
        }

        $career->setCareer($attributes);
        if(!$career->validate(null, false) || !$career->save(false)){
            $error = json_encode(CHtml::errorSummary($career));
        }

        return $error;
    }

    /*
      * Change user profile by categories
      */
    public function actionProfile()
    {
        //only for authorized users
        if(!Yii::app()->user->isGuest){
            if($attributes = Yii::app()->request->getParam('Profile')){
                $category = CHtml::encode(Yii::app()->request->getParam('category', false));
                $error = NULL;
                $user = User::model()->findByPk(Yii::app()->user->id);
                $user->oldEmail = $user->email;
                
                switch($category){
                    case 'main':{
                        $user = self::main($user,$attributes);
                        if($user->hasErrors()){
                            $error = json_encode(CHtml::errorSummary($user));
                        }
                        break;
                    }
                    case 'interests':{
                        if(!$error = self::interests($user,$attributes)) {
                            $user = User::model()->findByPk(Yii::app()->user->id);
                        }
                        break;
                    }
                    case 'education':{
                        $secError = null;
                        $highError = null;

                        if(isset($attributes['secEduc'])){
                            $secError = self::secondaryEducation($user,$attributes['secEduc']);
                        }

                        if(isset($attributes['highEduc'])){
                            $highError = self::highEducation($user,$attributes['highEduc']);
                        }

                        if($secError || $highError){
                            $error = json_encode($secError . $highError);
                        } else {
                            $user = User::model()->findByPk(Yii::app()->user->id);
                        }

                        break;
                    }
                    case 'career':{
                        if(!$error = self::career($user,$attributes)) {
                            $user = User::model()->findByPk(Yii::app()->user->id);
                        }
                        break;
                    }
                    case 'password':{
                        $category = 'settings';
                        $user->scenario = 'password';
                        $oldPassword = $user->password;
                        $user->setPassword($attributes);
                        if($oldPassword){
                            if(!$user->oldPassword){
                                $user->addError('oldPassword',Yii::t("user", 'Введіть поточний пароль!'));
                            } elseif($oldPassword != crypt($user->oldPassword,$oldPassword)) {
                                $user->addError('oldPassword',Yii::t("user", 'Введіть вірний поточний пароль!'));
                            }
                        }

                        if(!$user->hasErrors()){
                            if($user->validate(null, false)){
                                $user->password = crypt($user->password, StringHelper::blowfishSalt());
                                $user->save(false);
                            }
                        }
                        if($user->hasErrors()){
                            $error = json_encode(CHtml::errorSummary($user));
                        }
                        break;
                    }
                    case 'email':{
                        $category = 'settings';
                        $user->scenario = 'email';
                        $user->oldEmail = $user->email;
                        $user->email = CHtml::encode($attributes['email']);
                        if(!$user->validate(null, false) || !$user->save(false)){
                            $error = json_encode(CHtml::errorSummary($user));
                        } else {
                            $user->oldEmail = $user->email;
                        }
                        break;
                    }
                    default:{

                    }

                }

                self::renderProfile($user,$category,$error);
            } else {
                $user = User::model()->findByPk(Yii::app()->user->id);
                $user->oldEmail = $user->email;
                self::renderProfile($user);
            }
        } else {
            $this->redirect('/');
        }
    }

    /*
     * Render user profile
     */
    private function renderProfile($user,$category='main',$error=NULL,$other=false){
        $commentsCount = User::getNewCommentsCount(Yii::app()->user->id);
        $answersCount = User::getNewAnswersCount();
        $this->menu = array(
            array('label' => Yii::t("main", "Головна"), 'url' => array('site/myPolls'),'linkOptions'=>array('class'=>'back_to_main')),
            array('label' => Yii::t("main", "Нові коментарі").'<span class="count_poll">'. $commentsCount .'</span>', 'url' => array('user/newComments')),
            array('label' => Yii::t("main", "Нові відповіді").'<span class="count_poll">'. $answersCount .'</span>', 'url' => array('user/newAnswers')),
            array('label' => Yii::t("main", "Мій профіль"), 'url' => array('user/profile')),
        );

        $this->render('/site/index', array(
            'category' => 'profile',
            'profileCategory' => $category,
            'user' => $user,
            'error' => $error,
            'other' => $other,
        ));
    }

    /*
     * Save data after registration
     */
    public function actionRegistration(){
        if(!Yii::app()->user->isGuest){
            if($attributes = Yii::app()->request->getParam('Profile')){
                $category = CHtml::encode(Yii::app()->request->getParam('category', false));
                $error = NULL;
                $other = false;
                $user = User::model()->findByPk(Yii::app()->user->id);

                switch($category){
                    case 'main':{
                        $user = self::main($user,$attributes);
                        if($user->hasErrors()){
                            $error = json_encode(CHtml::errorSummary($user));
                        } else {
                            $user->is_active = 1;
                            $user->save();
                            $other = true;
                        }
                        break;
                    }
                    case 'other':{
                        $other = false;
                        $error = '';
                        if(isset($attributes['interests'])){
                            $tmp = self::interests($user,$attributes['interests']);
                            if($tmp && $tmp != ''){
                                $error .= json_encode($tmp);
                            }
                        }

                         if(isset($attributes['secEduc'])){
                             $tmp = self::secondaryEducation($user,$attributes['secEduc']);
                             if($tmp && $tmp != ''){
                                 $error .= json_encode($tmp);
                             }
                         }

                         if(isset($attributes['highEduc'])){
                             $tmp = self::highEducation($user,$attributes['highEduc']);
                             if($tmp && $tmp != ''){
                                 $error .= json_encode($tmp);
                             }
                         }

                         if(isset($attributes['career'])){
                             $tmp = self::career($user,$attributes['career']);
                             if($tmp && $tmp != ''){
                                 $error .= json_encode($tmp);
                             }
                         }

                         if($error == ''){
                             $error = null;
                         }

                    }
                }

                self::renderProfile($user,'main',$error,$other);
            } else {
                $this->redirect('/');
            }
        } else {
            $this->redirect('/');
        }
    }

    /*
     * Render page New Comments at user profile
     */
    public function actionNewComments(){
        if(!Yii::app()->user->isGuest){
            $commentsCount = User::getNewCommentsCount(Yii::app()->user->id);
            $answersCount = User::getNewAnswersCount();
            $this->menu = array(
                array('label' => Yii::t("main", "Головна"), 'url' => array('site/myPolls'),'linkOptions'=>array('class'=>'back_to_main')),
                array('label' => Yii::t("main", "Нові коментарі").'<span class="count_poll">'. $commentsCount .'</span>', 'url' => array('user/newComments')),
                array('label' => Yii::t("main", "Нові відповіді").'<span class="count_poll">'. $answersCount .'</span>', 'url' => array('user/newAnswers')),
                array('label' => Yii::t("main", "Мій профіль"), 'url' => array('user/profile')),
            );

            $polls = User::getPollsWithNewComments();

            $this->render('/site/index', array(
                'category' => 'newComments',
                'polls' => $polls
            ));
        } else {
            $this->redirect('/');
        }
    }

    /*
     * Render page New Answers at user profile page
     */
    public function actionNewAnswers(){
        if(!Yii::app()->user->isGuest){
            $commentsCount = User::getNewCommentsCount(Yii::app()->user->id);
            $answersCount = User::getNewAnswersCount();
            $this->menu = array(
                array('label' => Yii::t("main", "Головна"), 'url' => array('site/myPolls'),'linkOptions'=>array('class'=>'back_to_main')),
                array('label' => Yii::t("main", "Нові коментарі").'<span class="count_poll">'. $commentsCount .'</span>', 'url' => array('user/newComments')),
                array('label' => Yii::t("main", "Нові відповіді").'<span class="count_poll">'. $answersCount .'</span>', 'url' => array('user/newAnswers')),
                array('label' => Yii::t("main", "Мій профіль"), 'url' => array('user/profile')),
            );

            $comments = User::getCommentsWithAnswers();

            $this->render('/site/index', array(
                'category' => 'newAnswers',
                'comments' => $comments
            ));
        } else {
            $this->redirect('/');
        }
    }

    /*
     * Check all poll comments as read by poll author
     */
    public function actionReadComments(){
        if (Yii::app()->request->isAjaxRequest){
            $result = 0;
            if(!Yii::app()->user->isGuest){
                $id = intval(Yii::app()->request->getPost('id'));
                $poll = Poll::model()->findByPk($id);
                if($poll){
                    if($poll->user_id == Yii::app()->user->id){
                        self::readPollComments($poll);
                        $result = 1;
                    }
                }
            }
            echo $result;
        }
    }

    /*
     * Check all comments for user polls as read
     */
    public function actionReadAllComments(){
        if (Yii::app()->request->isAjaxRequest){
            if(!Yii::app()->user->isGuest){
                $polls = User::getPollsWithNewComments();
                foreach($polls as $poll){
                    self::readPollComments($poll);
                }
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    private function readPollComments($poll){
        if($comments = $poll->pollComments(array('condition'=>'has_new = 1'))){
            foreach($comments as $comment){
                $comment->is_new = 0;
                $comment->has_new = 0;
                $comment->save();
            }
        }
    }

    /*
     * Check all answer for user comment as read by comment author
     */
    public function actionReadAnswers(){
        if (Yii::app()->request->isAjaxRequest){
            $result = 0;
            if(!Yii::app()->user->isGuest){
                $id = intval(Yii::app()->request->getPost('id'));
                $comment = PollComment::model()->findByPk($id);
                if($comment){
                    if($comment->user_id == Yii::app()->user->id){
                        self::readCommentAnswers($comment);
                        $result = 1;
                    }
                }
            }
            echo $result;
        }
    }

    /*
     * Check answers for all user comments as read by author
     */
    public function actionReadAllAnswers(){
        if (Yii::app()->request->isAjaxRequest){
            if(!Yii::app()->user->isGuest){
                $comments = User::getCommentsWithAnswers();
                foreach($comments as $comment){
                    self::readCommentAnswers($comment);
                }
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /*
     * Set comment answers as read
     */
    private function readCommentAnswers($comment){
        if($comments = $comment->commentChilds(array('condition'=>'read_by_parent = 0'))){
            foreach($comments as $comment){
                $comment->read_by_parent = 1;
                $comment->save();
            }
        }
    }

    /*
     * User password recovery
     */
    public function actionPasswordRecovery(){
        $this->menu = array();
        $model = new PasswordRecoveryForm;
        $data = Yii::app()->request->getParam('PasswordRecovery');
        if(is_array($data)){
            $model->email = CHtml::encode($data['email']);
            if($model->validate()){
                if($model->checkEmail()){
                    if(self::changeUserPassword($model->email)){
                        $this->render('passwordRecoverySuccess',array(
                            'model' => $model,
                        ));
                    } else {
                        $model->addError('email',Yii::t('user','При відновленні паролю виникла помилка! Спробуйте пізніше!'));
                    }
                } else {
                    $model->addError('email',Yii::t('user','Аккаунт з такою адресою не зареєстрований'));
                }
            }

            if($model->hasErrors()){
                $errors = $model->getErrors();
                foreach($errors as $error){
                    $model->error .= $error[0];
                }
            }
        }

        $this->render('passwordRecovery',array(
            'model' => $model,
        ));
    }

    /*
     * Generate new password for user
     */
    private function changeUserPassword($email){
        $result = false;
        $user = User::model()->findByAttributes(array('email'=>$email));
        $password = StringHelper::generatePassword();
        $user->password = crypt($password, StringHelper::blowfishSalt());
        if($user->save()){
            self::sendMail($email,$password,$user->login);
            $result = true;
        }

        return $result;
    }

    /*
     * Send mail with new password for user
     */
    private function sendMail($email,$password,$login){
        $to      = $email;
        $subject = 'Изменение пароля';
        $message = 'Ваш пароль успешно восстановлен!' . "\r\n" .
            'Данные для входа:' . "\r\n" .
            'Ваш логин: ' . $login . "\r\n" .
            'Ваш пароль: ' . $password;

        $headers = 'From: ' . StringHelper::EMAIL . "\r\n" .
            'Reply-To: ' . StringHelper::EMAIL . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }

    /*
         * Captcha
         */
    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'yii\\captcha\\CaptchaAction',
                'height'=>80,
                'width'=>160,
                'offset' => 5,
            ),
        );
    }
}
