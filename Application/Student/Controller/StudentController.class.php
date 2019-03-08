<?php
namespace Student\Controller;
use Think\Controller;
use Org\Util;
/*
学生块 包括学生要实现的的所有功能
*/
class StudentController extends BaseController {
	// 构造函数判断是否处于登录状态
	//public function _initialize()
	//{
		// $id = session('category.Id');
	 //    $name=session('category.Name');
	 //    //判断用户是否登录
	 //    if(empty($id)||empty($name)){
	 //      	$this -> error('请先登录。。。');exit;
	      
		// }
	//}
	/*
	判断学生当前选题状态
	*/
	protected function check_select_state() {
		$studentModel=M('paper_student');
		$studentId=session('category.Id');
		$studentModel -> field('topic_state1,topic_state2');
		$selectState = $studentModel -> select($studentId);	
		$state1 = $this -> get_state1($selectState[0]['topic_state1']);
		$state2 = $this -> get_state2($selectState[0]['topic_state2']);
		return array(
				'topic_state1' => $state1,
				'topic_state2' => $state2,
			);
	}
	protected function get_state1($number) {
		switch ($number) {
			case 0:
				return "教师还未批";
			break;
			
			case 1:
				return "教师批准";
			break;

			case 2:
				return "教师未批准";
			break;	
			default:
				return "";
			break;
		}
	}
	protected function get_state2($number) {
		switch ($number) {
			case 0:
				return "系主任还未批";
			break;
			
			case 1:
				return "系主任批准";
			break;

			case 2:
				return "系主任未批准";
			break;	
			default:
				return "";
			break;
		}
	}	
	/*
	首页显示学生信息
	 */
	protected function check_student_state() {
		$studentModel=M('paper_student');
		$studentId=session('category.Id');
		$studentState=$studentModel-> query("select state from paper_student where id='".$studentId."'");
		switch ($studentState[0]['state']) {
			case 1:
				return "未选题";
			break;
			
			case 2:
				return "选题已通过";
			break;

			case 3:
				return "选题未通过";
			break;

			case 4:
				return "已提交开题报告和开题PPT";
			break;

			case 5:
				return "已提交论文和答辩PPT";
			break;

			default:
			return "a";
			break;
		}
	}
	/*
	主页显示学生个人信息
	 */
	public function index() {
		/*session('category.department','软件');
		session('category.Id','1');
		session('category.Name',"房小涵");*/

		$studentState=$this->check_student_state();
		$studentModel = M('paper_student');
		$studentInfo = $studentModel ->find(session('category.Id'));

		$model =M('paper_system')-> field('name');
		$department = $model ->select($studentInfo['sid']);

		$topic_state=$this -> check_select_state();
		

		$this -> assign('state', $studentState);  //读入当前学生选题状态
		$this -> assign('studentInfo', $studentInfo); //读入次学生的信息
		$this -> assign('department', $department[0]['name']); //读入当前学生的所在系
		//读入当前系统的两个选题状态
		$this -> assign('topic_state1', $topic_state['topic_state1']);
		$this -> assign('topic_state2', $topic_state['topic_state2']);
		$this -> display();
	}
	/*
	学生选择或自拟答辩题目
	 */
	public function page_select_title() {
		
		$state=$this -> check_student_state();
		$topic_state=$this -> check_select_state();

		$this -> assign('state',$state);
		if ($topic_state['topic_state1']=="教师未批准" || $topic_state['topic_state2']== "系主任未批准" ) {
			$this -> assign('topic_state', "选题不通过，请重新选题");
		}
		elseif ($topic_state['topic_state1']=="教师还未批" || $topic_state['topic_state2']== "系主任还未批" ) {
			$this -> assign('topic_state', "系统还未开始审核，可修改选题");
		}
		elseif($state == "未选题" ) {
			$this -> assign('topic_state', "还没选题，请选题");
		} else {
			$this -> assign('topic_state', "选题以通过请不要重复选题");
		}

		$this -> assign('name',session('category.Name'));

		//$sid=session('category.department');
          $department=session('category.department');
		//$titleInfo = M('paper_name')->query("select id,name from paper_name where sid='".$sid."'");
          $titleInfo = M('paper_name')->query("select id,name from paper_name where sid='".$department['id']."'");
		$this->assign('titleInfo',$titleInfo);
		$this -> display();
	}
	/*
	学生选择课题读入
	 */
	public function title_select() {
		$state=$this -> check_student_state();
		if($state=="未选题" || $state="选题未通过") {
			$model = M('paper_student');
			$student_title=array(
							'id' 	=> session('category.Id'),
							'topic' => $_GET['topic'],
							'state' => 3, //选题更改状态
							);
			if($model -> save($student_title)) {
				$this -> success("选题成功,",U('Student/page_select_title'));
			}else {
				$this -> error();
			}
		}else {
			$this -> error("当前状态".$state."不可选题");
		}	
	}
	/*
	学生修改个人信息和密码
	 */
	public function page_change_password() {
		$this -> display();
	}
	/*
	进行修改密码的操作
	*/
	public function do_change_password() {
		$prepassword =substr(md5(I('prepassword')),0,10);
		
		$studentModel = M('paper_student');
		$studentInfo = $studentModel->select(session('category.Id'));
		
		if (I('password1') != I('password2')) {
			$this -> error("两次输的密码不一致");
		}
		elseif($studentInfo[0]['password'] != $prepassword) {
			$this -> error("预设密码不对");
		}
		else {
			$change_password  =array('id' => session('category.Id'), 'password' => substr(md5(I('password1')),0,10), );
			if ($studentModel -> save($change_password)) {
				$this -> success("修改密码成功请重新登录");
				// 清空session
				session(null); 
			}
			else {
				$this -> error("修改密码失败");
			}
		}

	}
	/*
	学生上传PPT和答辩论文
	 */
	public function page_upload_paper() {
		$state=$this -> check_student_state();
		$this -> assign('state',$state);

		$studentState=$this->check_student_state();
		$this -> assign('state', $studentState);
		$this -> display();
	}
	/*
	学生上传开题PPT以及开题报告
	 */
	public function page_upload_report() {
		$state=$this -> check_student_state();
		$this -> assign('state',$state);

		$studentState=$this->check_student_state();
		$this -> assign('state', $studentState);

		$this -> display();
	}
	/*测试*/
	public function demo() {
		$a=$this -> check_select_state();
		dump($a);
	}
	/*
	判断当前是否可以上传，和上传类别 1 为开题 2 为答辩
	*/
	private function can_upload( $kind ) {
		$state = $this -> check_student_state();
		if($state=="选题已通过" && $kind=1) {
			return 1;
		}
		elseif ($state=="已提交开题报告和开题PPT" && $kind=2) {
			return 2;
		}
		else {
			$this -> error("当前状态不可上传");
		}
	}
	/*
	进行上传操作
	*/
	public function do_upload() {
		$kind=$_GET['kind']; //获取上传类型 1 为开题 2 为答辩
		$upload_type=$this -> can_upload($kind);
		$studentModel = M('paper_student');
		load('@/upload'); //Common下的upload函数 正确返回一个当前上传的信息 错误返回一个错误信息的字符串
		if( $upload_type==1) {
			// 两个一个为开题PPT一个为开题报告的地址
			//$file['kyppt']=upload($_FILES['ktppt'],1,'Public/upload/student/opening_report/ppt'); 
			//$file['ktppt']=upload($_FILES['ktppt'],1,'Public/upload/student/opening_report/ppt');
			//$file['ktbg']=upload($_FILES['ktbg'],2,'Public/upload/student/opening_report/report');
		    $file['ktppt']=upload($_FILES['ktppt'],1,'Public/upload/student');
			$file['ktbg']=upload($_FILES['ktbg'],2,'Public/upload/student');
			$array = array(
					//'ktppt' => $file['ppt'],
				    'ktppt' => $file['ktppt'],
					'ktbg'  => $file['ktbg'],
				);
			if (is_array($file['ktppt']) && is_array($file['ktbg'])) {
				$student_kt_upload=array(
								'id' 	=> session('category.Id'), 
								'ppt1'  => $array['ktppt']['name'],
								'report'=> $array['ktbg']['name'],
								'state' => 4, //已提交开题报告和开题PPT
								);
				if($studentModel -> save($student_kt_upload)) {
					$this -> success("上传开题PPT以及开题报告成功");
				}else {
					$this -> error();
				}
			}
			elseif (!is_array($file['ktppt']) && !is_array($file['ktbg'])) {
				$this -> error($file['ktppt'].$file['ktbg']);
			}
			elseif (!is_array($file['ktppt']) && is_array($file['ktbg'])) {
				$this -> error($file['ktppt']);
			}else {
				$this -> error($file['ktbg']);
			}
		}
		elseif ($upload_type==2) {
			// 两个一个为论文PPT一个为论文的地址
			//$file['ppt']=upload($_FILES['dbppt'],1,'Public/upload/student/answer/ppt');
			//$file['lw']=upload($_FILES['dblw'],2,'Public/upload/student/answer/page');
		    $file['ppt']=upload($_FILES['dbppt'],1,'Public/upload/student');
			$file['lw']=upload($_FILES['dblw'],2,'Public/upload/student');
			$array = array(
					'ppt' => $file['ppt'],
					'lw'  => $file['lw'],
				);
			if (is_array($file['ppt']) && is_array($file['lw'])) {
				//
				$student_answer_upload=array(
								'id' 	=> session('category.Id'),
								'ppt2'  => $array['ppt']['name'],
								'paper'=>  $array['ppt']['name'],
								'state' => 5, //已提交答辩报告和答辩PPT
								);
				if($studentModel -> save($student_answer_upload)) {
					$this -> success("上传开题PPT以及开题报告成功");
				}else {
					$this -> error();
				}
			}
			elseif (!is_array($file['ppt']) && !is_array($file['lw'])) {
				$this -> error($file['ppt'].$file['lw']);
			}
			elseif (!is_array($file['ppt']) && is_array($file['lw'])) {
				$this -> error($file['ppt']);
			}else {
				$this -> error($file['lw']);
			}
		}
	}
}