<?php

namespace common\models\mixin;

use yii\helpers\ArrayHelper;

/**
 * Description of DropDownItems
 *
 * @author alex
 */
trait DropDownItems {

    public static function dropDownItems($curentId = null) {
        return ArrayHelper::map(self::find()->isActive($curentId)->all(), 'id', 'name');
    }

    public static function dropDownAllItems() {
        return self::find()->select(['name', 'id'])->indexBy('id')->column();
    }
}
