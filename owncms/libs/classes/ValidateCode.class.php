<?php

/**
 * 自定义验证码生成
 * 默认验证码图片120*45
 * 图片背景默认白色
 * 干扰线默认5条
 */
class ValidateCode
{
	
	private $width;			//画布宽度
	private $height;		//画布高度
	private $codeLen;		//验证字符数
	private $red;			//画布颜色red值
	private $green;			//画布颜色green值
	private $blue;			//画布颜色blue值
	private $codeCnt='';	//验证码内容
	private $img;			//画布
	private $CodeVal;		//验证码库
	private $CodeValLen;	//验证码库长度
	private $lineLen;		//干扰线数
	private $colorArr;		//验证码字符颜色组
    private $fontFile;      //字体
    private $fontsize;   //字体大小

	function __construct($arr=[])
	{	
		if(!session_start()){
				session_start();
		}


		$this->width=(isset($arr['width']) ? $arr['width'] : 120 );
		$this->height=(isset($arr['height']) ? $arr['height'] : 45 );
		$this->codeLen=(isset($arr['codeLen']) ? $arr['codeLen'] : 6 );
		$this->red=(isset($arr['red']) ? $arr['red'] : 255 );
		$this->green=(isset($arr['green']) ? $arr['green'] : 255 );
		$this->blue=(isset($arr['blue']) ? $arr['blue'] : 255 );
		$this->lineLen=(isset($arr['lineLen']) ? $arr['lineLen'] : 5 );
        $this->fontFile=(isset($arr['fontFile']) ? $arr['fontFile'] : FONT_PATH.'times.ttf' );
        $this->fontsize=(isset($arr['fontsize']) ? $arr['fontsize'] : 18 );


		//创建验证码库
		for($i=0;$i<128;$i++){
			if(preg_match('/^[0-9a-z]/i', chr($i))){
				$this->codeVal[]=chr($i);
			}
		}
		$this->CodeValLen=count($this->codeVal);
	}

	//创建画布
	private function createImg(){
		$this->img=imagecreatetruecolor($this->width, $this->height);

		//指定验证码背景颜色
		$imgbgColor=imagecolorallocate($this->img, $this->red, $this->green, $this->blue);
		imagefill($this->img, 0, 0, $imgbgColor);
	}


	//生成验证码并存入session
	private function getCode(){

		$fontWidth = $this->fontsize*0.6;   //12.6

        $step = $this->width/$this->codeLen; //17.9

		for($i=0;$i<$this->codeLen;$i++){
			$newColor=$this->createColor();
            $fontsize = rand(15,$this->fontsize);
			$fontcolor=imagecolorallocate($this->img,$newColor['red'],$newColor['green'],$newColor['blue']);

            $tempX=rand($step*$i,$step*($i+1)-$fontWidth);

            $tempY=rand(0.4*$this->height,0.8*$this->height);
			$valNow=$this->codeVal[rand(0,$this->CodeValLen-1)];

            imagettftext($this->img, $fontsize, rand(-30,30),$tempX, $tempY, $fontcolor, $this->fontFile, $valNow);

			$this->codeCnt.=$valNow;
		}

		$_SESSION['codeCnt']=$this->codeCnt;
	}

	//画干扰线
	private function createLine(){
		for($i=0;$i<$this->lineLen;$i++){
			$newColor=$this->createColor();
			$linecolor=imagecolorallocate($this->img,$newColor['red'],$newColor['green'],$newColor['blue']);

			$startX=rand(0,$this->width);
			if($startX-$this->width/3>0){
				$endX1=$startX-($this->width/3);
			}else{
				$endX1=0;
			}
			if($startX+$this->width/3>$this->width){
				$endX2=$this->width;
			}else{
				$endX2=$startX+$this->width/3;
			}

			$startY=rand(0,$this->height);
			if($startY-$this->height/2>0){
				$endY1=$startY-($this->height/2);
			}else{
				$endY1=0;
			}
			if($startY+$this->height/2>$this->height){
				$endY2=$this->height;
			}else{
				$endY2=$startY+$this->height/2;
			}

			imageline($this->img,$startX,$startY,rand($endX1,$endX2),rand($endY1,$endY2),$linecolor);
		}
	}

	//输出图像
	private function outPut(){
		header('Content-type:image/png');
		imagepng($this->img);
		imagedestroy($this->img);
	}

	//对外输出图像
	public function doImg(){
		$this->createImg();
		$this->getCode();
		$this->createLine();
		$this->outPut();

	}

	//颜色值去重生成
	private function createColor(){
		$newRed=rand(0,255);
		$newGreen=rand(0,255);
		$newBlue=rand(0,255);
		if($newRed==$this->red && $newGreen==$this->green && $newBlue==$this->blue){
			$this->createColor();
		}
		$colorArrLen=count($this->colorArr);

		for($i=0;$i<$colorArrLen;$i++){
			for($j=0;$j<3;$j++){
				if($newRed==$colorArr[$i]['red'] && $newGreen==$colorArr[$i]['green'] && $newBlue==$colorArr[$i]['blue']){
					$this->createColor();
				}
			}
		}
		$color=[
			'red'=>$newRed,
			'green'=>$newGreen,
			'blue'=>$newBlue
		];
		$colorArr[]=$color;

		return $color;
	}

}