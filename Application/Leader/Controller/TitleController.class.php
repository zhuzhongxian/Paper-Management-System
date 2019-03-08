<?php
namespace Leader\Controller;
use Think\Controller;
class TitleController extends BaseController {
    public function title(){
      $leader = session('Leader');
    	   $title = D('name');
            $p = empty($_GET['p'])?0:$_GET['p'];
            // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
            //$articles = $title->order('id asc')->page($p,2)->select();
            $articles = $title->selectByDept($p,2,$leader['sid']);
            $this->assign('data',$articles);        // 赋值数据集
            //数据分页
            $data['sid'] = $leader['sid'];
            $count      = $title->where($data)->count();// 查询满足要求的总记录数
            $Page =  $this->getPages($count,2);
            //$show       = $Page->show();// 分页显示输出
            $this->assign('fenye',$Page->show());// 赋值分页输出
           $this->display();
    }

    /**
   * 上传Excel文件
   */
  public function upload() {
  	header("content-type:text/html;charset=utf-8");
    //实例化上传类
    $upload = new \Think\Upload();// 实例化上传类
    //设置附件上传文件大小200Kib
    $upload->mixSize = 2000000;
    //设置附件上传类型
    $upload->allowExts = array('xls', 'xlsx', 'csv');
    // 设置附件上传根目录
    $upload->rootPath  =     './Uploads/'; 
    //保持上传文件名不变
    $upload->saveName = '';
    //存在同名文件是否是覆盖
    $upload->uploadReplace = true;
    //上传文件
    $info   =   $upload->upload();
    if (!$info) {  //如果上传失败,提示错误信息
      $this->error($upload->getError());
    } else {  //上传成功
      //获取上传保存文件名
      $fileName = $info['file']['savename'];
      $filepath = $info['file']['savepath'];
     //var_dump($info);exit;
    header("content-type:text/html;charset=utf-8");
    //引入phpExcel类
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.IOFactory");
    //redirect传来的文件名

 
    //文件路径
    $filePath = './Uploads/' .$filepath . $fileName;
    //var_dump($filePath);exit;

    //实例化PHPExcel类
    //$PHPExcel = new \PHPExcel();
    //默认用excel2007读取excel，若格式不对，则用之前的版本进行读取
    $PHPReader = new \PHPExcel_Reader_Excel2007();
    if (!$PHPReader->canRead($filePath)) {
      $PHPReader = new \PHPExcel_Reader_Excel5();
      if (!$PHPReader->canRead($filePath)) {
        echo 'no Excel';
        return;
      }
    }
 
    //读取Excel文件
    $PHPExcel = $PHPReader->load($filePath);
    //读取excel文件中的第一个工作表
    $sheet = $PHPExcel->getSheet(0);
    //取得最大的列号
    $allColumn = $sheet->getHighestColumn();
    //取得最大的行号
    $allRow = $sheet->getHighestRow();
    //从第二行开始插入,第一行是列名
    for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
      //获取A列的值
       $id = $PHPExcel->getActiveSheet()->getCell("A" . $currentRow)->getValue();
      //获取B列的值
      $name = $PHPExcel->getActiveSheet()->getCell("B" . $currentRow)->getValue();
       
      $m = M('Name');
      $leader = session('Leader');
      $num = $m->add(array('id' => $id,'name' => $name,'sid' => $leader['sid']));
    }
    if ($num > 0) {
      $this->success('导入成功！');
    } else {
      $this->error('导入失败！');
    }
    }
  }

  public function deltitle(){
    $id = $_GET['id'];
    $title = D('name');
    $rel = $title->where(array('id'=>$id))->delete();
    if($rel){
      $this->success("删除题目成功");
    }else{
      $this->error("删除题目失败");
    }

  }
 
  /**
   *
   * 导入Excel文件
   */
  // public function importExcel() {
  //   header("content-type:text/html;charset=utf-8");
  //   //引入phpExcel类
  //   import("Org.Util.PHPExcel");
  //   import("Org.Util.PHPExcel.IOFactory");
  //   //redirect传来的文件名
  //   $fileName = $_GET['fileName'];
  //   $filepath = $_GET['filepath'];
 
  //   //文件路径
  //   $filePath = './Uploads/' .$filepath . $fileName;
  //   var_dump($filePath);exit;

  //   //实例化PHPExcel类
  //   //$PHPExcel = new \PHPExcel();
  //   //默认用excel2007读取excel，若格式不对，则用之前的版本进行读取
  //   $PHPReader = new \PHPExcel_Reader_Excel2007();
  //   if (!$PHPReader->canRead($filePath)) {
  //     $PHPReader = new \PHPExcel_Reader_Excel5();
  //     if (!$PHPReader->canRead($filePath)) {
  //       echo 'no Excel';
  //       return;
  //     }
  //   }
 
  //   //读取Excel文件
  //   $PHPExcel = $PHPReader->load($filePath);
  //   //读取excel文件中的第一个工作表
  //   $sheet = $PHPExcel->getSheet(0);
  //   //取得最大的列号
  //   $allColumn = $sheet->getHighestColumn();
  //   //取得最大的行号
  //   $allRow = $sheet->getHighestRow();
  //   //从第二行开始插入,第一行是列名
  //   for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
  //     //获取A列的值
  //     // $id = $PHPExcel->getActiveSheet()->getCell("A" . $currentRow)->getValue();
  //     //获取B列的值
  //     $name = $PHPExcel->getActiveSheet()->getCell("B" . $currentRow)->getValue();
       
  //     $m = M('Name');
  //     // $num = $m->add(array('id' => $id, 'name' => $name));
  //     $num = $m->add(array('name' => $name));
  //   }
  //   if ($num > 0) {
  //     echo "添加成功！";
  //   } else {
  //     echo "添加失败！";
  //   }
  // }


}