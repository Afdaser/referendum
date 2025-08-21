<?php
foreach($comments as $comment){
    if(isset($isNew)){
        $this->renderPartial('/poll/profile_comment',array('comment'=>$comment,'isNew'=>true));
    } else {
        $this->renderPartial('/poll/comment',array('comment'=>$comment));
    }
}