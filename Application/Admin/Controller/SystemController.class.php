<?php
namespace Admin\Controller;
use Think\Controller;
class SystemController extends BaseController {
    public function system(){
    	$model=D('System');
        $count=$model->count();//获得总的记录数
        $page=new\Think\Page($count,5);//每页显示
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');//当分页页码数少于rollPage时不会显示首页和末页
        $page->setConfig('firest','首页');
        $show=$page->show();//通过show方法生成url
        //var_dump($show);die;
    	$data=$model ->limit($page->firstRow,$page->listRows)->select();
        //var_dump($data);die;
    	$this-> assign('data',$data);
        $this->assign('show',$show);
        $this-> show();
    }//system end
    public function edit(){
 
    	if(IS_POST){
    		$post=I('post.');
    		$model=D('System');
    		$res=$model->save($post);
    		if($res !==false){
    			$this->success('修改成功',U('system'),3);
    		}else{
    			$this->error('修改失败');
    		}
    	}else{
    	$id=I('get.id');
    	$model=D('System');
    	$data=$model->find($id);
    	$this->assign('data',$data);
    	$this->show();
     }
    }//edit end
    public function del(){ //删除
    	$id=I('get.id');
    	//$model=M('System');
        //var_dump($id);die;
    	$data=D('System')->where("id="."'".$id."'")->delete();
        //var_dump($data);die;
    	if($data){
    		$this->success('删除成功',U('system'),3);
    	}else{
    		$this->error('删除失败');
    	}
    }//del end
    public function into(){
        if(IS_POST){
        $fileInfo=$_FILES['file_stu'];
        $newName=uploadFile($fileInfo);//上传文件 返回文件位置

        vendor("PHPExcel.PHPExcel");
        $file_name=$newName;
        //echo($file_name);die;
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));//判断导入表格后缀格式
        //echo($extension);die;
        if ($extension == 'xlsx') {
        $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
        } else if ($extension == 'xls'){
        $objReader =\PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
        }
        $sheet =$objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();//取得总行数
        $highestColumn =$sheet->getHighestColumn(); //取得总列数
        //D('pro_info')->execute('truncate table pro_info');
        for ($i = 2; $i <= $highestRow; $i++) {
        //看这里看这里,前面小写的a是表中的字段名，后面的大写A是excel中位置
        $data['id'] =trim($objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue());
        $data['name'] =trim($objPHPExcel->getActiveSheet()->getCell("B" .$i)->getValue());
        //看这里看这里,这个位置写数据库中的表名
        $arr[]=$data;
        $d=D('System');
        $er=$d->add($data);
        }
        unlink($newName);
        //$this->success('导入成功!');
        //var_dump($arr);die;
        $this->assign('data',$arr);
        $this->show();
        }//echo $newName;
        else{
            $this->show();
        }
    }// into end
}