<?php
if(!$viewID){
	$viewID = "SOMR_EXERCISE_BOOK_TEST_RESULT";
	include ($_SERVER ["DOCUMENT_ROOT"] . "/_connector/yellow.501.php");
}
?>
<? foreach($arr_output['wrong_answer'] as $intKey=>$arrWrongAnswer){ ?>
<div
	class="uk-width-1-1 <?=$arrWrongAnswer['result_flg']?'test_right_answer':'test_wrong_answer'?>"
	id="question_<?=$arrWrongAnswer['question_seq']?>"
	question_seq="<?=$arrWrongAnswer['question_seq']?>">
	<h4 class="uk-width-2-10 pull-left"><?=$arrWrongAnswer['order_number']?></h4>
	<div
		class="uk-width-8-10 btn-group uk-text-center ans_correct_<?=$arrWrongAnswer['question_type'];?>"
		data-toggle="buttons">
						<? if(!$arrWrongAnswer['wrong_note_list_seq']){ ?>
						<button type="button" class="uk-button btn-info"
			data-modal-type="editor"
			data-wrong-answer="<?=$arrWrongAnswer['seq'];?>">오답문제 입력</button>
						<? }else{ ?>
						<div class="uk-float-left">등록 : <?=$arrWrongAnswer['wrong_note_date'];?></div>
		<div class="uk-float-right">
			<button type="button" data-modal-type="editor"
				data-wrong-answer="<?=$arrWrongAnswer['seq'];?>">수정</button>
		</div>
						<? } ?>
					</div>
</div>
<? } ?>		