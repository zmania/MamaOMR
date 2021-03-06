<?
/* include package */
require_once("Model/Core/DBmanager/DBmanager.php");
require_once('Model/ManGong/Book.php');
require_once('Model/ManGong/Test.php');
require_once('Model/ManGong/MQuestion.php');
require_once('Model/ManGong/Record.php');
require_once('Model/ManGong/Teacher.php');


/* set variable */ 
//$intWriterSeq = $_SESSION[$_COOKIE['member_token']]['member_seq'];
$intWriterSeq = SMART_OMR_TEACHER_SEQ;
$strMemberSeq = $_REQUEST['view']=='manager'?$_REQUEST['ms']:$_SESSION['smart_omr']['member_key'];//student seq
$strBookSeq = $_REQUEST['bs'];


/* create object */
$resMangongDB = new DB_manager('MAIN_SERVER');
$objBook = new Book($resMangongDB);
$objTest = new Test($resMangongDB);
$objQuestion = new MQuestion($resMangongDB);
$objRecord = new Record($resMangongDB);
$objTeacher = new Teacher($resMangongDB);

/*main process*/	
//1. get book info isbn code
$arrSearch = array();
$arrSearch['md5(seq)'] = $strBookSeq;
$arrBook = $objBook->getBook($arrSearch);
$arrBook[0]['writer_info'] = $objTeacher->getTeacher($intWriterSeq);

//get all test info 
$arrTestListByBook = $objBook->getTestListByBook($strBookSeq);

//set arr survey seq
$arrTestsSeq = array();
foreach ($arrTestListByBook as $key => $arrResult) {
	//set all survey seq
	array_push($arrTestsSeq, $arrResult['test_seq']);
	
	//get test count
	$arrTestListByBook[$key]['test_question_cnt'] = $objQuestion->getQuestionCountInTest($arrResult['test_seq']);
	$arrTestListByBook[$key]['test_record'] = $objRecord->getTotalUserRecord($arrResult['test_seq']);
	$arrTestRecord = $arrTestListByBook[$key]['test_record'][0];
	$arrTestListByBook[$key]['score_avarage'] = $arrTestRecord['user_count']?round($arrTestRecord['total_user_score']/$arrTestRecord['user_count'],1):0;
	
	//get my test revision info
	$arrTestListByBook[$key]['my_record_list'] = $objRecord->getMemberRecords($strMemberSeq,$arrResult['test_seq']);
}

if(count($arrTestsSeq)){
	//get book's question count 
	$intBookQuestionCnt = $objQuestion->getQuestionCountInTest(null,$arrTestsSeq);
	//$intTestJoinUserCount = $objTest->getTestsJoinUserTotalCount(null,$arrTestsSeq);
	
	//get book's record
	$arrUserTotalRecord = $objRecord->getTotalUserRecord(null,$arrTestsSeq);
}

/* make output */
$arr_output['book_info'] = $arrBook;
$arr_output['book_cover_img'] = $arr_output['book_info'][0]['cover_url']?$arr_output['book_info'][0]['cover_url']:"/smart_omr/_images/default_cover.png";
$arr_output['book_test_list'] = $arrTestListByBook;
//$arr_output['book_join_usre_count'] = $intTestJoinUserCount;
$arr_output['book_total_question_cnt'] = $intBookQuestionCnt;
$arr_output['book_user_total_record'] = $arrUserTotalRecord;
$arr_output['book_join_count'] = $arrUserTotalRecord[0]['user_count']?$arrUserTotalRecord[0]['user_count']:0;
$arr_output['book_score_avarage'] = $arrUserTotalRecord[0]['user_count']?round($arrUserTotalRecord[0]['total_user_score']/$arrUserTotalRecord[0]['user_count'],1):0;
?>