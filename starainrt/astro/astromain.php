<?php
namespace Starainrt\Astro;
define("PI",M_PI);
class AstroMain
{
    private function deg2arg($deg)
    {
        return $deg/180*PI;
    }
    private function arg2deg($arg)
    {
	return $arg*180/PI;
    }
    private function SinR($du)
    {
        $du = $du/180*M_PI;
        return sin($du);
    }
    private function CosR($du)
    {
        $du = $du/180*M_PI;
        return cos($du);
    }
    private function TanR($du)
    {
        $du = $du/180*M_PI;
        return tan($du);
    }
    private function ArcSin($X,$models=0){
        $tmp =asin($X);
        if($models==1)
            $tmp= $tmp / M_PI * 180;
	return $tmp;
    }
    private function ArcCos($X,$models=0){
        $tmp =acos($X);
        if($models==1)
            $tmp= $tmp / M_PI * 180;
	return $tmp;
    }
    private function ArcTan($X,$models=0){
        $tmp =atan($X);
        if($models==1)
            $tmp= $tmp / M_PI * 180;
            return $tmp;
    }
    private function Limit360($deg)
    {
        while($deg>360)
            $deg-=360;
        while($deg<0)
            $deg+=360;
        return $deg;
    }
        private function separate($arg)
        {
            $neg=false;
            if($arg<0)
            {
               $neg=true;
               $arg=abs($arg);
            }
            $min=($arg-floor($arg))*60;
            $sec=($min-floor($min))*60;
            $arg=floor($arg);
            $min=floor($min);
            if($neg)
                $arg=-$arg;
            $result=array("deg"=>$arg,"min"=>$min,"sec"=>$sec);
            return $result;
        }
	/*
	 @name: 儒略日计算
	 @dec: 计算给定时间的儒略日，1582年改力后为格里高利历，之前为儒略历
	 @     请注意，传入的时间在天文计算中一般为力学时，应当注意和世界时的转化
	*/
	public function JDECalc($Year,$Month,$Day) {
		if ($Month == 1 || $Month == 2) {
			$Year = $Year - 1;
			$Month = $Month + 12;
		}
		$tmpvar=$Year.'-'.$Month.'-'.floor($Day);
		if ( $tmpvar<= '1582-10-04') {
			$tmpvarB = 0;
		}else{
			$tmpvarA = intval($Year / 100);
			$tmpvarB = 2 - $tmpvarA + intval($tmpvarA / 4);
		}
		return ( floor(365.25 * ($Year + 4716)) + floor(30.6001 * ($Month + 1)) + $Day + $tmpvarB - 1524.5);
	}
	/*
	 @name: 获得当前儒略日时间：当地世界时，非格林尼治时间
	*/
	public function GetNowJDE()
	{
		$Time = (date('s')/ 3600 + date('i') / 60 + date('H')) / 24;
		$NowJDE =$this->JDECalc(date('Y'), date('m'), date('d') + $Time);
		return $NowJDE;
	}
	/*
	  @name: Delta T
	  @dec :  下述函数均为Delta T，力学时与世界时转换函数,有效期为1900~2100年！
	 */
	private function dt_ext ($y, $jsd){   #本函数来源：寿星万年历    二次曲线外推，用于数值外插
		$dy = ($y - 1820)/100;
		return (-20 + $jsd * $dy * $dy);
	}
	private function dt_cal($y){      #本函数来源：寿星万年历   //传入年， 返回世界时UT与原子时（力学时 TD）之差, ΔT = TD - UT
		$dt_at = array(                   
			-4000,  108371.7, -13036.80,  392.000,    0.0000,
			-500,   17201.0,   -627.82,   16.170,   -0.3413,
			-150,   12200.6,   -346.41,    5.403,   -0.1593,
			150,    9113.8,   -328.13,   -1.647,    0.0377,
			500,    5707.5,   -391.41,    0.915,    0.3145,
			900,    2203.4,   -283.45,   13.034,   -0.1778,
			1300,     490.1,    -57.35,    2.085,   -0.0072,
			1600,     120.0,     -9.81,   -1.532,    0.1403,
			1700,      10.2,     -0.91,    0.510,   -0.0370,
			1800,      13.4,     -0.72,    0.202,   -0.0193,
			1830,       7.8,     -1.81,    0.416,   -0.0247,
			1860,       8.3,     -0.13,   -0.406,    0.0292,
			1880,      -5.4,      0.32,   -0.183,    0.0173,
			1900,      -2.3,      2.06,    0.169,   -0.0135,
			1920,      21.2,      1.69,   -0.304,    0.0167,
			1940,      24.2,      1.22,   -0.064,    0.0031,
			1960,      33.2,      0.51,    0.231,   -0.0109,
			1980,      51.0,      1.29,   -0.026,    0.0032,
			2000,      63.87,     0.1,     0,        0,
			2005,      64.7,      0.4,     0,        0,                 //一次项记为x, 则 10x=0.4秒/年*(2015-2005), 解得x=0.4
			2015,      69
		);
		$y0 = $dt_at[count($dt_at) - 2];        //表中最后一年
		$t0 = $dt_at[count($dt_at)-1];            //表中最后一年的 deltatT
		if($y >= $y0){                      
			$jsd = 30;                            // sjd是y1年之后的加速度估计
	 						 // 瑞士星历表jsd=31, NASA网站jsd=32, skmap的jsd=29
			if($y > $y0 + 100)  
                        {
				return(dt_ext($y, $jsd));   
                        }
			$v  = floor(dt_ext($y, $jsd));                 //二次曲线外推
			$dv = dt_ext($y0, $jsd) - $t0;           // ye年的二次外推与te的差
			return ($v - $dv*($y0 + 100 - $y)/100);
		}
		$d = $dt_at;
		for($i=0;$i<count($d);$i+=5 )  {
			if($y < $d[$i + 5]) break;           // 判断年所在的区间
		}
		$t1 = ($y - $d[$i]) / ($d[$i + 5] - $d[$i]) * 10     ;       //////// 三次插值， 保证精确性
		$t2 = $t1 * $t1;
		$t3 = $t2 * $t1;
		$res = $d[$i + 1] +$d[$i + 2] * $t1 +$d[$i + 3] * $t2 +$d[$i + 4] * $t3;
		return( $res );
	}
	public function DeltaT($Date,$IsJDE=false) #传入年或儒略日，传出为秒
        {
		if($IsJDE)
		{
			$Year=($Date - 2451545) / 365.25+0.1+2000;
		}else{
			$Year=$Date;
		}
		if($Year<2010)
		{
			$Result=$this->dt_cal($Year);
			return $Result;
		}
		if($Year<2020 && $Year>=2010)
		{
			$Result=$this->dt_cal($Year)-2.8-($Year-2017)*0.029915;
			return $Result;
		}
		if($Year>2019 && $Year<=2050)
		{
			$tmpvart=$Year-2000;
			$Result=62.92 + 0.32217 * $tmpvart + 0.005589 * $tmpvart*$tmpvart;
			return $Result;
		}
		if($Year>2050 && $Year <=2150)
		{
			$Result=-20 + 32 * (($Year-1820)/100)^2 - 0.5628 * (2150 - $Year);
			return $Result;
		}
		if($Year>2150)
		{
			$tmp=($Year-1820)/100;
			$Result= -20 + 32 * $tmp*$tmp;
			return $Result;
		}
		return $Result;
	}	
	public Function TD2UT($JDE,$UT2TD=true) # true 世界时转力学时CC，false 力学时转世界时VV
	{
		$DeltaT=$this->DeltaT($JDE,true);
		if($UT2TD)
		{
			return $JDE+$DeltaT/3600/24;
		}else{
			return $JDE-$DeltaT/3600/24;
		}
	}
        /*
         * @name: JDE转日期，输出为数组
         */
        public function JDE2Date($JD)
        {
            $JD = $JD +0.5;
            $Z = intval($JD);
            $F = $JD - $Z;
            if ($Z < 2299161)
                $A = $Z;
            else{
                $alpha = floor(($Z - 1867216.25) / 36524.25);
                $A = $Z + 1 + $alpha- floor($alpha / 4);
            }
            $B = $A + 1524;
            $C = floor(($B - 122.1) / 365.25);
            $D = floor(365.25 * $C);
            $E = floor(($B - $D) / 30.6001);
            $Days = $B - $D - floor(30.6001 * $E) + $F;
            if($E < 14) $Months = $E - 1;
            if($E == 14 || $E == 15) $Months = $E - 13;
            if($Months > 2) $Years = $C - 4716;
            if ($Months == 1 || $Months == 2 ) $Years = $C - 4715;
            $time=$this->separate((($Days - floor($Days)) * 24));
            $DateCalc['date'] = $Years . "年" . $Months . "月" . floor($Days) . "日" ;
            $DateCalc['time'] = $time['deg']."时".$time['min']."分".round($time['sec'])."秒";
            $DateCalc['year']=$Years;$DateCalc['month']=$Months;$DateCalc['day']=$Days;
            $DateCalc["hour"]=$time['deg'];$DateCalc["minute"]=$time['min'];$DateCalc["second"]=$time['sec'];
            return $DateCalc;
        }
    /*
     * 坐标变换，黄道转赤道
     */
    public function LoToRa($lo,$bo)
    {
        $earth=new Earth();
	$ra=atan2(($this->SinR($lo)*$this->CosR($earth->sita($this->GetNowJDE()-8/24))-$this->TanR($bo)*$this->SinR($earth->sita($this->GetNowJDE()-8/24))),$this->CosR($lo));
	$ra=$ra*180/M_PI;
	if($ra<0) $ra+=360;
	return $ra;
    }  
    public function BoToDec($lo,$bo)
    {
        $earth=new Earth();
	$dec=$this->ArcSin($this->SinR($bo)*$this->CosR($earth->sita($this->GetNowJDE()-8/24))+$this->CosR($bo)*$this->SinR($earth->sita($this->GetNowJDE()-8/24))*$this->SinR($lo),1);  
	return $dec;
    }
    /*
     * 赤道坐标岁差变换$st $end 为JDE时刻
     */
    public function ZuoBiaoSuiCha(&$ra,&$dec,$st,$end)
    {
	 $t = ($end-$st)/36525;
	 $l = (2306.2181*$t + 0.30188*$t*$t + 0.017998*$t*$t*$t)/3600;
	 $z = (2306.2181*$t + 1.09468*$t*$t + 0.018203*$t*$t*$t)/3600;
	 $o = (2004.3109*$t - 0.42665*$t*$t+0.041833*$t*$t*$t)/3600;
	 $A = $this->CosR($dec)*$this->SinR($ra+$l);
	 $B = $this->CosR($o)*$this->CosR($dec)*$this->CosR($ra+$l) - $this->SinR($o)*$this->SinR($dec);
	 $C = $this->SinR($o)*$this->CosR($dec)*$this->CosR($ra+$l) + $this->CosR($o)*$this->SinR($dec);
	 $ras=atan2($A,$B);
	 $ras=$ras*180/pi;
	 if($ras<0)$ras+=360;
	 $ra=$ras+$z;
	 $dec=$this->ArcSin($C,1);
	 return 0;
    }
    /*
     * 地心坐标转站心坐标，参数分别为，地心赤经赤纬 纬度经度，jde，离地心位置au
     */
    public function pcosi($lat,$h=0)
    {
	$b=6356.755;
	$a=6378.14;
	$f=1/298.257;
	$e=0.08181922;
	$u=$this->ArcTan($b/$a*$this->TanR($lat),1);
	//$psin=$b/$a*SinR($u)+$h/6378140*SinR($lat);
	$pcos=$this->CosR($u)+$h/6378140*$this->CosR($lat);
	return $pcos;
    }
    public function psini($lat,$h=0)
    {
	$b=6356.755;
	$a=6378.14;
	$f=1/298.257;
	$e=0.08181922;
	$u=$this->ArcTan($b/$a*$this->TanR($lat),1);
	$psin=$b/$a*$this->SinR($u)+$h/6378140*$this->SinR($lat);
	//$pcos=CosR($u)+$h/6378140*CosR($lat);
	return $psin;
    }
    public function ZhanXinRa($ra,$dec,$lat,$lon,$jd,$au,$h=0) //jd为格林尼治标准时
    {
        $star=new Star();
	$sinpi=$this->SinR(0.0024427777777)/$au;
	$pcosi=$this->pcosi($lat,$h);
	//echo $pcosi." ".psini($lat,$h)."<br />";
	$tH=$this->Limit360($star->SeeStarTime($jd)*15+$lon-$ra);
	//echo CosR($dec)-$pcosi*$sinpi*SinR($tH)."<br />";
	$nra=atan2(-$pcosi*$sinpi*$this->SinR($tH),($this->CosR($dec)-$pcosi*$sinpi*$this->SinR($tH)))*180/M_PI;
	return $ra+$nra;
    }
    public function ZhanXinDec($ra,$dec,$lat,$lon,$jd,$au,$h=0) //jd为格林尼治标准时
    {
        $star=new Star();
	$sinpi=$this->SinR(0.0024427777777)/$au;
	$pcosi=$this->pcosi($lat,$h);
	$psini=$this->psini($lat,$h);
	$tH=$this->Limit360($star->SeeStarTime($jd)*15+$lon-$ra);
	$nra=atan2(-$pcosi*$sinpi*$this->SinR($tH),($this->CosR($dec)-$pcosi*$sinpi*$this->SinR($tH)))*180/M_PI;
	//echo (SinR($dec)-$psini*$sinpi)*CosR($nra)."<br />";
	//echo (CosR($dec)-$pcosi*$sinpi*SinR($tH))."<br />";
	$ndec=atan2(($this->SinR($dec)-$psini*$sinpi)*$this->CosR($nra),($this->CosR($dec)-$pcosi*$sinpi*$this->SinR($tH)))*180/M_PI;
	return $ndec;
    }
    public function ZhanXinLo($lo,$bo,$lat,$lon,$jd,$au,$h=0) //jd为格林尼治标准时
    {
        $earth=new Earth();
        $star=new Star();
	$C=$this->pcosi($lat,$h);
	$S=$this->psini($lat,$h);
	$sinpi=$this->SinR(0.0024427777777)/$au;
	$tH=$this->Limit360($star->SeeStarTime($jd)*15+$lon-$ra);
	$N=$this->CosR($lo)*$this->CosR($bo)-$C*$sinpi*$this->CosR($tH);
	$nlo=atan2($this->SinR($lo)*$this->CosR($bo)-$sinpi*($S*$this->SinR($earth->sita($jd))+$C*$this->CosR($earth->sita($jd))*$this->SinR($tH)),N)*180/M_PI;
	return $nlo;
    }
    public function ZhanXinBo($lo,$bo,$lat,$lon,$jd,$au,$h=0) //jd为格林尼治标准时
    {
        $earth=new Earth();
        $star=new Star();
	$C=$this->pcosi($lat,$h);
	$S=$this->psini($lat,$h);
	$sinpi=$this->SinR(0.0024427777777)/$au;
	$tH=$this->Limit360($star->SeeStarTime($jd)*15+$lon-$ra);
	$N=$this->CosR($lo)*$this->CosR($bo)-$C*$sinpi*$this->CosR($tH);
	$nlo=atan2($this->SinR($lo)*$this->CosR($bo)-$sinpi*($S*$this->SinR($earth->sita($jd))+$C*$this->CosR($earth->sita($jd))*$this->SinR($tH)),N)*180/M_PI;
	$nbo=atan2($this->CosR($nlo)*($this->SinR($bo)-$sinpi*($S*$this->CosR($earth->sita($jd))-$C*$this->SinR($earth->sita($jd))*$this->SinR($tH))),N)*180/M_PI;
	return $nbo;
    }


    public function GXCLo($lo,$bo,$jd)  //光行差修正 
    {
        $earth=new Earth();
	$k=20.49552;
	$sunlo=$earth->SunTrueLo($jd);
	$e=$earth->Earthe($jd);
	$epi=$earth->EarthPI($jd);
	$tmp=(-$k*$this->CosR($sunlo-$lo)+$e*$k*$this->CosR($epi-$lo))/$this->CosR($bo);
	return $tmp;
    }

    public function GXCBo($lo,$bo,$jd)
    {
        $earth=new Earth();
	$k=20.49552;
	$sunlo=$earth->SunTrueLo($jd);
	$e=$earth->Earthe($jd);
	$epi=$earth->EarthPI($jd);
	$tmp=-$k*$this->SinR($bo)*($this->SinR($sunlo-$lo)-$e*$this->SinR($epi-$lo));
	return $tmp;
    }
    /*
     *****                  地球、太阳运动部分    *****
     *****                  地球、太阳运动部分    *****
     */
    
    /*
     * 黄赤交角 EclipticObliquity
     * 参数nutation为True时，计算交角章动影响，否则为平黄赤交角。
     */
    public function EclipticObliquity($jde,$nutation=true){
        $earth=new Earth();
        $jde=$this->TD2UT($jde,true);
        return $earth->EclipticObliquity($jde, $nutation);
    }
    /*
     * 黄经章动 nutation in longitude
     */
     public function NutationLong($jde){
          $earth=new Earth();
          $jde=$this->TD2UT($jde,true);
          return $earth->HJZD($jde);
     }
     /*
      *  交角章动 nutation in obliquity
      */
      public function ObliquityNutation($jde){
          $earth=new Earth();
          $jde=$this->TD2UT($jde,true);
          return $earth->JJZD($jde);
     }
     /*
      *  太阳几何黄经
      */
     public function SunLo($jde)
     {
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->SunLo($jde);
     }
     /*
      * 太阳平近点角
      */
     public function SunMeanAnomaly($jde)
     {
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->sunM($jde);
     }
     /*
      *  地球偏心率
      */
     public function EarthEccentricity($jde)
     {
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->Earthe($jde);
     }
     /*
      * 近日点经度
      */
     public function EarthPerihelionLong($jde){
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->EarthPI($jde);
     }
     /*
      * 太阳中心方程
      */
     public function SunMidFunc($jde)
     {
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->SunMidFun($jde);
     }
     /*
      *  太阳真黄经 简便算法
      */
     public function SunTrueLo($jde)
     {
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->SunTrueLo($jde);
     }
     /*
      *  太阳视黄经 简便算法
      */
     public function SunSeeLo($jde)
     {
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->SunSeeLo($jde);
     }
     /*
      *  太阳真赤经
      */
     public function SunTrueRa($jde){
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->SunTrueRa($jde);
     }
     /*
      * 太阳视赤经
      */
     public function SunSeeRa($jde){
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->SunSeeRa($jde);
     }
      /*
      *  太阳真赤纬
      */
     public function SunTrueDec($jde){
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->SunTrueDec($jde);
     }
     /*
      * 太阳视赤纬
      */
     public function SunSeeDec($jde){
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->SunSeeDec($jde);
     }
     /*
      * 均时差
      */
     public function SunEquationTime($jde)
     {
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->SunTime($jde);
     }
     /*
      *  日地距离 mode=1 快速非精确算法 mode=2 vsop算法
      */
     public function SolarDistance($jde,$mode=2)
     {
         $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         if($mode==1)
         {
             return $earth->RDJL($jde);
         }else{
             return $earth->EarthAway($jde);
         }
     }
     /*
      *  太阳真黄经 VSOP算法
      */
     public function HSunTrueLo($jde){
          $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->HSunTrueLo($jde);
     }
     /*
      * 太阳视黄经 VSOP算法
      */
      public function HSunSeeLo($jde){
          $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->HSunSeeLo($jde);
     }
       /*
      *  太阳真赤经 VSOP算法
      */
     public function HSunTrueRa($jde){
          $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->HSunTrueRa($jde);
     }
     /*
      * 太阳视赤经 VSOP算法
      */
      public function HSunSeeRa($jde){
          $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->HSunSeeRa($jde);
     }
       /*
      *  太阳真赤纬 VSOP算法
      */
     public function HSunTrueDec($jde){
          $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->HSunTrueDec($jde);
     }
     /*
      * 太阳视赤纬 VSOP算法
      */
      public function HSunSeeDec($jde){
          $earth=new Earth();
         $jde=$this->TD2UT($jde,true);
         return $earth->HSunSeeDec($jde);
     }
     /*
      * 太阳每日中天时间 传入当地0时
      */
     public function SunTZTime($jde,$lon,$tz){
         $earth=new Earth();
         return $earth->GetSunTZTime($jde, $lon, $tz);
     }
     /*
      * 晨昏朦影 传入当地0时
      */
     public function MorningTwilight($jde,$lon,$lat,$tz,$an=-6){
          $earth=new Earth();
          return $earth->GetAsaTime($jde,$lon,$lat,$tz,$an);
          #注 由于SunHeight函数已为世界时计算模式，所以此计算结果无需进行世界时转换
     }
     public function EveningTwilight($jde,$lon,$lat,$tz,$an=-6){
          $earth=new Earth();
          return $earth->GetBanTime($jde,$lon,$lat,$tz,$an);
     }
     /*
      * 日出日落时间
      */
     public function SunRiseTime($jde,$lon,$lat,$tz,$zs=0,$mode=0)
     {
         $earth=new Earth();
         if($mode){
             return $earth->GetSunRiseTime1($jde,$lon,$lat,$tz,$zs);
         }else{
             return $earth->GetSunRiseTime($jde,$lon,$lat,$tz,$zs);
         }
     }
     public function SunDownTime($jde,$lon,$lat,$tz,$zs=0,$mode=0)
     {
         $earth=new Earth();
         if($mode){
             return $earth->GetSunDownTime1($jde,$lon,$lat,$tz,$zs);
         }else{
             return $earth->GetSunDownTime($jde,$lon,$lat,$tz,$zs);
         }
     }
     /*
      * 太阳时角
      */
     public function SunTimeAngle($jde,$lon,$lat,$tz){
         $earth=new Earth();
         return $earth->SunTimeAngle($jde, $lon, $lat, $tz);
     }
     /*
      * 太阳方位角
      */
     public function SunAngle($jde,$lon,$lat,$tz){
          $earth=new Earth();
          return $earth->SunAngle($jde, $lon, lat, $tz);
     }
     /*
      * 太阳高度角
      */
     public function SunHeight($jde,$lon,$lat,$tz){
          $earth=new Earth();
          return $earth->SunHeight($jde, $lon, $lat, $tz);
     }

      /*
     *****                  历法、星座部分    *****
     *****                  历法、星座部分    *****
     */
     
     /*
      * 公历转农历
      */
     #传入公历的year month day，不进行合法性检测
     public function LunarCal($year,$month,$day){
         $earth= new Earth();
         $result=$earth->GetLunar($year, $month, $day, $lmonth, $lday, $leap);
         if($month<3 && $lmonth>10)
         {
             $lyear=$earth->GetGZ($year-1);
         }else{
             $lyear=$earth->GetGZ($year);
         }
         $output=array("LunarCal"=>$lyear.$result,"SolarCal"=>$year."年".$month."月".$day."日","Year"=>$lyear,"Month"=>$lmonth,"Day"=>$lday);
         return $output;
     }
     
     /*
      * 农历转公历
      */
     #传入的year为农历所在的公历年，month day为农历月 日，若为闰月 leap置true
     public function SolarCal($year,$month,$day,$leap){
         $earth= new Earth();
         $result=$this->JDE2Date($earth->GetSunCal($year,$month,$day,$leap));
         $year=$earth->GetGZ($year);
         $output=array("SolarCal"=>$result['date'],"LunarCal"=>$year.$month."月".$day."日","Year"=>$result['year'],"Month"=>$result['month'],"Day"=>$result['day']);
         return $output;
     }
     /*
      * 节气时间
      */
     public function JQTime($year,$angle){
         $earth=new Earth();
         return $earth->GetJQTime($year, $angle);
     }
     /*
      *  物候时间
      */
     public function WHTime($year,$angle){
         $earth=new Earth();
         return $earth->GetWHTime($year, $angle);
     }
     /*
      *  太阳所在星座
      */
     public function SunConstellation($jde)
     {
         $earth=new Earth();
         return $earth->GetXZ($jde);
     }
     /**
      *       月亮部分
      *       月亮部分
      * 
      */
     
     /*
      * 月出时间计算 传入 日期(JDE 世界时) 经度 纬度 时区 是否大气修正
      *      传出 升起时JDE世界时 
      */
     public function MoonRiseTime($jde,$lon,$lat,$tz,$isAero=true,$mode=0) //0=迭代算法一 1=迭代算法2
     {
         $moon=new Moon();
         if($mode==0){
             return $moon->GetMoonRiseTime($jde, $lon, $lat, $tz,$isAero);
         }else{
             return $moon->GetMoonRiseTime1($jde, $lon, $lat, $tz,$isAero);
         }
     }
     /*
      * 月落
      */
     public function MoonDownTime($jde,$lon,$lat,$tz,$isAero=true,$mode=0) //0=迭代算法一 1=迭代算法2
     {
         $moon=new Moon();
         if($mode==0){
             return $moon->GetMoonDownTime($jde, $lon, $lat, $tz,$isAero);
         }else{
             return $moon->GetMoonDownTime1($jde, $lon, $lat, $tz,$isAero);
         }
     }
     /**
      * 月球地心真赤经
      */
     
     /*
      * 月球地心真赤纬
      */
     
     /*
      * 月球地心视赤经
      */
     
     /*
      * 月球地心视赤纬
      */
}
