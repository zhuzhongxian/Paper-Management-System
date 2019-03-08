<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends BaseController {
	public function showUser(){
		$model1=D('Admin');
        $count=$model1->count();
		$data1= $model1->select();
		$size=sizeof($data1);
		$m1=D('System');
		$sys=$m1->where('name = "教务部"')->select();//获取教务部的ID给管理员赋值ID
		//$sys1=$sys['id'];
		//var_dump($sys[0]['id']);die;
		for($i=0;$i<$size;$i++){
			//array_push($data1[$i]['type'],$i);
			$data1[$i]['sid']=$sys[0]['id'];
			$data1[$i]['type']="1";
		}
		//$data1=array_fill(3,1,"1");
		//$data1['system']="1";
		//var_dump($data1);die;
		$model2=D('Leader');
        $count=$count+$model2->count();
		$data2=$model2->field('id,name,password,sid')->select();
		$size=sizeof($data2);
		for($i=0;$i<$size;$i++){
			//array_push($data1[$i]['type'],$i);
			$data2[$i]['type']="2";
		}
		//$data2['system']="2";
		
		$model3=D('Teacher');
        $count=$count+$model3->count();
		$data3=$model3->field('id,name,password,sid')->select();
		$size=sizeof($data3);
		for($i=0;$i<$size;$i++){
			//array_push($data1[$i]['type'],$i);
			$data3[$i]['type']="3";
		}
		//$data3['system']="3";


		$model4=D('Student');
        $count=$count+$model4->count();
		$data4=$model4->field('id,name,password,sid')->select();
		$size=sizeof($data4);
		for($i=0;$i<$size;$i++){
			//array_push($data1[$i]['type'],$i);
			$data4[$i]['type']="4";
		}
		//$data4['system']="4";
        //var_dump($count);die;
		$data=array_merge($data1,$data2,$data3,$data4);
		$m=D('System');    //二次查表找出系部ID对应的系部 	前台显示
		foreach ($data as $key => $value) {
			if ($value['sid']) {
				$info = $m->find($value['sid']);
				$data[$key]['systemname']=$info['name']; 
			}
		}

		//var_dump($data);die;
		$this->assign('data',$data);
    	$this->show();

    }//shwoUser end

    public function showAdmin(){
        $model=D('Admin');
        $count=$model->count();
        $page=new\Think\Page($count,10);
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');//当分页页码数少于rollPage时不会显示首页和末页
        $page->setConfig('firest','首页');
        $show=$page->show();//通过show方法生成url
        $data= $model->limit($page->firstRow,$page->listRows)->select();
        $size=sizeof($data);
        $m1=D('System');
        $sys=$m1->where('name = "教务部"')->select();//获取教务部的ID给管理员赋值ID
        //$sys1=$sys['id'];
        //var_dump($sys[0]['id']);die;
        for($i=0;$i<$size;$i++){
            //array_push($data1[$i]['type'],$i);
            $data[$i]['sid']=$sys[0]['id'];
            $data[$i]['type']="1";
        }
        //var_dump($data);die;
        $m=D('System');    //二次查表找出系部ID对应的系部    前台显示
        foreach ($data as $key => $value) {
            if ($value['sid']) {
                $info = $m->find($value['sid']);
                $data[$key]['systemname']=$info['name']; 
            }
        }

        //var_dump($data);die;
        $this->assign('data',$data);
        $this->assign('show',$show);
        $this->show();
    }//showAdmin end

    public function showLeader(){
        $model=D('Leader');
        $count=$model->count();
        $page=new\Think\Page($count,5);
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');//当分页页码数少于rollPage时不会显示首页和末页
        $page->setConfig('firest','首页');
        $show=$page->show();//通过show方法生成url
        $data=$model->limit($page->firstRow,$page->listRows)->field('id,name,password,sid')->select();
        $size=sizeof($data);
        for($i=0;$i<$size;$i++){
            //array_push($data1[$i]['type'],$i);
            $data[$i]['type']="2";
        }
         //var_dump($data);die;
        $m=D('System');    //二次查表找出系部ID对应的系部    前台显示
        foreach ($data as $key => $value) {
            if ($value['sid']) {
                $info = $m->find($value['sid']);
                $data[$key]['systemname']=$info['name']; 
            }
        }

        //var_dump($data);die;
        $this->assign('data',$data);
        $this->assign('show',$show);
        $this->show();
    }//showLeader end

    public function showTeacher(){
        $model=D('Teacher');
        $count=$model->count();
        $page=new\Think\Page($count,15);
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');//当分页页码数少于rollPage时不会显示首页和末页
        $page->setConfig('firest','首页');
        $show=$page->show();//通过show方法生成url
        $data=$model->limit($page->firstRow,$page->listRows)->field('id,name,password,sid')->select();
        $size=sizeof($data);
        for($i=0;$i<$size;$i++){
            //array_push($data1[$i]['type'],$i);
            $data[$i]['type']="3";
        }
         //var_dump($data);die;
        $m=D('System');    //二次查表找出系部ID对应的系部    前台显示
        foreach ($data as $key => $value) {
            if ($value['sid']) {
                $info = $m->find($value['sid']);
                $data[$key]['systemname']=$info['name']; 
            }
        }

        //var_dump($data);die;
        $this->assign('data',$data);
        $this->assign('show',$show);
        $this->show();
    }//showTeacher end

    public function showStudent(){
        $model=D('Student');
        $count=$model->count();
        $page=new\Think\Page($count,20);
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');//当分页页码数少于rollPage时不会显示首页和末页
        $page->setConfig('firest','首页');
        $show=$page->show();//通过show方法生成url
        $data=$model->limit($page->firstRow,$page->listRows)->field('id,name,password,sid')->select();
        $size=sizeof($data);
        for($i=0;$i<$size;$i++){
            //array_push($data1[$i]['type'],$i);
            $data[$i]['type']="4";
        }
        //$data4['system']="4";
        //var_dump($count);die;
        $m=D('System');    //二次查表找出系部ID对应的系部    前台显示
        foreach ($data as $key => $value) {
            if ($value['sid']) {
                $info = $m->find($value['sid']);
                $data[$key]['systemname']=$info['name']; 
            }
        }

        //var_dump($data);die;
        $this->assign('data',$data);
        $this->assign('show',$show);
        $this->show();
    }//showStudent end
  
    public function intoUser(){
    	if (IS_POST) {
    		$fileInfo=$_FILES['file_stu'];
    		//var_dump($fileInfo);die;
    		$newName=uploadFile($fileInfo);
    		//var_dump($newName);die;

    		 vendor("PHPExcel.PHPExcel");
        $file_name=$newName;
        //echo($file_name);die;
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));//判断导入表格后缀格式
        //echo($extension);die;
        if ($extension == 'xlsx') { //判断是什么后缀
        $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
        } else if ($extension == 'xls'){
        $objReader =\PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
        }
        $sheet =$objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();//取得总行数
        $highestColumn =$sheet->getHighestColumn(); //取得总列数
        //echo($highestColumn);die;
        //D('pro_info')->execute('truncate table pro_info');
        for ($i = 2; $i <= $highestRow; $i++) {
        //看这里看这里,前面小写的a是表中的字段名，后面的大写A是excel中位置
        $data['id'] =trim($objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue());
        $data['name'] =trim($objPHPExcel->getActiveSheet()->getCell("B" .$i)->getValue());
        $data['sid'] =trim($objPHPExcel->getActiveSheet()->getCell("C" .$i)->getValue());
        $data['type'] =trim($objPHPExcel->getActiveSheet()->getCell("D" .$i)->getValue());
        $data['password'] =substr(md5('123456'),0,10);
        //echo($data);die;
       //var_dump($data);die;
        switch ($data['type']) {
        	case 1:
        		$roll1=D('Admin')->add($data);
        		break;
        	case 2:
        		$roll2=D('Leader')->add($data);
        		break;
        	case 3:
        		$roll3=D('Teacher')->add($data);
        		break;
        	case 4:
        		$roll4=D('Student')->add($data);
        		break;
        }//switch end
        $arr[]=$data; //把所有数据存入数组中 前台显示
        unlink($newName);
        //var_dump($arr);die;
        //echo($arr);die;
        //D('System')->add($data);
        }//for end
        //$this->success('导入成功!');
        //var_dump($arr);die;
        $this->assign('data',$arr);
        $this->show();
    	}else{
    		$this->show();
    	}
    }//intoUser end

    public function del(){
    	$id=I('get.id');
    	$type=I('get.type');
    	//var_dump($type);die;
    	switch ($type) {//判断用户类型 选择操作的表
    		case 1:
    			$values=D('Admin')->where('id='.$id)->delete();
    			break;
    		case 2:
    			$values=D('Leader')->where('id='.$id)->delete();
    			break;
    		case 3:
    			$values=D('Teacher')->where('id='.$id)->delete();
    			break;
    		case 4:
    			$values=D('Student')->where('id='.$id)->delete();
    			break;
    		default:
    			break;
    	}
    	if ($values) {
    		 if($type==1){
                    $this->success('删除成功',U('showAdmin'),3);
                }
                else if ($type==2) {
                    $this->success('删除成功',U('showLeader'),3);
                }
                else if ($type==3) {
                    $this->success('删除成功',U('showTeacher'),3);
                }
                else{
                    $this->success('删除成功',U('showStudent'),3);
                }
            
    	}else{
    		$this->error('删除失败');
    	}
    }//delete end

    public function editUser(){
    	if(IS_POST){
    		//$type=I('get.type');
    		$post=I('post.');
    		//$post['type']=$type;
    		//var_dump($post);die;
    		if ($post['password']) {//判断是否修改密码
    			$post['password']=substr(md5($post['password']),0,10);//加密后截取10位做为密码
    		}else{//如果没有修改就获取隐藏域中的password1原密码
    			$post['password']=$post['password1'];
    		}
    		//var_dump($post['type']);die;
    		switch ($post['type']) {
    			case 1:
    				$values=D('Admin')->save($post);
    				break;
    			case 2:
    				$values=D('Leader')->save($post);
    				break;
    			case 3:
    				$values=D('Teacher')->save($post);
    				break;
    			case 4:
    				$values=D('Student')->save($post);
    				break;
    			default:
    				break;
    		}
    		if ($values!==false) {
                if($post['type']==1){
        			$this->success('修改成功',U('showAdmin'),3);
                }
                else if ($post['type']==2) {
                    $this->success('修改成功',U('showLeader'),3);
                }
                else if ($post['type']==3) {
                    $this->success('修改成功',U('showTeacher'),3);
                }
                else{
                    $this->success('修改成功',U('showStudent'),3);
                }
    		}else{
    			$this->error('修改失败');
    		}

    	}else{
    	$id=I('get.id');
    	$type=I('get.type');
        //var_dump($type);die;
    	switch ($type) {
    		case 1:
    			$data=D('Admin')->find($id);
    			$data['system']="教务部";
    			break;
    		case 2:
    			$data=D('Leader')->find($id);
    			break;
    		case 3:
    			$data=D('Teacher')->find($id);
    			break;
    		case 4:
    			$data=D('Student')->find($id);
    			break;
    		
    		default:
    			break;
    	}
        $data['type']=$type;//保存type传到下一个页面
    	if ($data['sid']) {//获取除了教务部 其他部的名字
    		$data1=D('System')->field('name')->find($data['sid']);
    		$data['system']=$data1['name'];
    		//$data['type']=$type;//保存type传到下一个页面

    	}
    	//var_dump($data);die;
    	$this->assign('data',$data);
    	$this->show();
      }
    }//edit end


}