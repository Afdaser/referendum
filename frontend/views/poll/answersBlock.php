<?php /* @var $answers PollOption[] */ ?>
<?php foreach($answers as $answer):?>
	<div class="item_add-answer clearfix">
		<?php echo $answer->title; ?>
		<div class="right_text_count" data-id="<?php echo $answer->id; ?>"><?php echo $answer->rating; ?></div>
		<a href="javascript:void(0)" class="add_answer_btn" data-id="<?php echo $answer->id; ?>">
		</a>
	</div>
<?php endforeach;?>