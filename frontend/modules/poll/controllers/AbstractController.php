<?php

namespace frontend\modules\poll\controllers;

use Yii;
use \yii\web\Controller;
use yii\helpers\Url;

use common\models\User;

/**
 * Description of AbstractController
 *
 * @author alex
 */
abstract class AbstractController extends Controller {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
//    public $layout = '//layouts/main';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = [];
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = [];

    /**
     * init on every page request
     */
    function init()
    {
        if (!Yii::$app->user->isGuest) {
//            Yii 1:
//            $user = User::model()->findByPk(intval(Yii::$app->user->id));
//            Yii 2:
            $user = Yii::$app->user->identity;

//            if (isset($user->state) && ($user->state == 1)) {
//                Yii::$app->user->logout();
//                $this->redirect(Yii::$app->homeUrl);
//            }
        }

        parent::init();
//        Yii::$app->urlManager->setAppLanguage();
//        if (empty($this->pageTitle)){
//            $this->pageTitle = Yii::t("main", "Online-statistic.com - сайт який повністю присвячен статистиці");
//        }
        $this->menu = [
            "HotPolls" => [
                'label' => Yii::t("main", "Гарячі теми"),
                'url' => '/', // ['site/hotPolls')
                'active' => false,
            ], 
            "ActualPolls" => [
                'label' => Yii::t("main", "Актуальні для вас теми"),
                'url' => Url::toRoute(['/poll/site/actual-polls']),
                'active' => false,
            ],
            "MyPolls" => [
                'label' => Yii::t("main", "Ваші опитування"),
                'url' => Url::toRoute(['/poll/site/my-polls']),
                'active' => false,
            ],
        ];
        $uriCurent = Yii::$app->request->url;
        foreach ($this->menu as $key => $item) {
            if($uriCurent == $item['url']){
                $this->menu[$key]['active'] = true;
            }
            
        }
//        echo '<h2>Uri:</h2>';
//        echo Yii::$app->request->url;
//        echo '<h1>AbstractController</h1><pre>';
//        var_dump($this->menu);
//        echo '</pre>';
//die(__FILE__.'#'.__LINE__);
    }

}
