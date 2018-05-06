<?php
namespace Starainrt\Astro;
class Star{
    private $basedir;
    function __construct() {
       $this->basedir= dirname(__FILE__);
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
    public function TrueStarTime($JD)
    {
	$T = ($JD - 2451545) / 36525;
	return ($this->Limit360(280.46061837 + 360.98564736629*($JD-2451545.0) + 0.000387933*$T*$T - $T*$T*$T/38710000)/15);
    }
    public function SeeStarTime($JD)
    {
        $earth=new Earth();
	$tmp=$this->TrueStarTime($JD);
	return $tmp+$earth->HJZD($JD)*cos($earth->sita($JD))/15;
    }
    public function StarAngle($RA,$DEC,$JD,$Lon,$Lat,$TZ)
    {
	 #$JD=$JD-8/24+$TZ/24;
	 $tmp=($TZ*15-$Lon)*4/60;
	 $truejd=$JD-$tmp/24;
	 $calcjd=$JD-$TZ/24;
	 $st=$this->Limit360($this->SeeStarTime($calcjd)*15+$Lon);
	 $H=$this->Limit360($st-$RA);
	 $tmp2=$this->SinR($H)/($this->CosR($H)*$this->SinR($Lat)-$this->TanR($DEC)*$this->CosR($Lat));
	 $Angle=$this->ArcTan($tmp2,1);
	 if($Angle<0){
            if($H/15<12)
                return $Angle+360;
            else
                return $Angle+180;
	 }else{
            if($H/15<12)
                return $Angle+180;
            else
                return $Angle;
	 }
    }
    public function StarHeight($RA,$DEC,$JD,$Lon,$Lat,$TZ)
    {
	 #$JD=$JD-8/24+$TZ/24;
	 $tmp=($TZ*15-$Lon)*4/60;
	 $truejd=$JD-$tmp/24;
	 $calcjd=$JD-$TZ/24;
	 $st=$this->Limit360($this->SeeStarTime($calcjd)*15+$Lon);
	 $H=$this->Limit360($st-$RA);
	 $tmp2=$this->SinR($Lat)*$this->SinR($DEC)+$this->CosR($DEC)*$this->CosR($Lat)*$this->CosR($H);
	 return  $this->ArcSin($tmp2,1);
    }
    
    public function IsXZ($ra,$dec) #转到J2000.0比较
    {
	if(abs($dec)>85){
            $res=$this->IsXZ1($ra,$dec);
            $res2=$this->IsXZ2($ra,$dec);
            if($res!=$res2){
                if($res2!="失败")
                    return $this->XZN($res2);
                else{
                    if($dec>0) 
                        return $this->XZN("UMI"); 
                    else 
                        return $this->XZN("OCT");
		}
            }else
		return $this->XZN($res);
	}else
            return $this->XZN(IsXZ1($ra,$dec));
    }
    private function tora(&$ra,&$dec)
    {
	$tmpra=$this->atan2($this->TanR($dec),$this->CosR($ra));
	$tmpra=$tmpra*180/3.141592653;
	$tmpdec=asin(-$this->CosR($dec)*$this->SinR($ra))*180/3.141592653;
	$ra=$tmpra;
	if($ra<0) $ra+=360;
	$dec=$tmpdec;
	return 0;
    }
    private function IsXZ2($ra,$dec)
    {
        $path=$this->basedir.DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."cst.db";
	if(($ra>=0 && $ra<=9*15) || ($ra>=22*15 && $ra<360)){
            if($dec>88.5)
                return "UMI";
	}
	$this->tora($ra,$dec);
	$db=new SQLite3($path);
	if(!$db)
            die($db->lastErrorMsg());
        for($g=1;$g<90;$g++){
            $name='';
            $flag=0;
            $ys=40;
            $c=null;
            $kotoba='SELECT * FROM cst WHERE xz=\''.$g."';";
            $res=$db->query($kotoba);
            while($row = $res->fetchArray(SQLITE3_ASSOC)){
		$this->tora($row['ra'],$row['dec']);
		$name=$row['ename'];
		if($row['ename']=='PSC' || $row['ename']=='SER2' || $row['ename']=='AQL' || $row['ename']=='AQR'
		 || $row['ename']=='CET' || $row['ename']=='TAU' || $row['ename']=='ERI' || $row['ename']=='ORI'  )
		{
                    if($ra>=0 && $ra <300){
                        if($row['ra']>300)
                            $row['ra']=$row['ra']-360;
                        $c[]=array('x'=>$row['ra'],'y'=>$row['dec']);
                    }else{
			if($row['ra']<300)
                            $row['ra']=$row['ra']+360;
                        $c[]=array('x'=>$row['ra'],'y'=>$row['dec']);
                    }
		}else
                    $c[]=array('x'=>$row['ra'],'y'=>$row['dec']);	
            }
            $num=count($c);
            $count=0;
            if($name=='AQR')
                $ys=-50;
            for($i=0;$i<$num-1;$i++){
                if($i==1){
                    if($this->IsCross(array('x'=>0,'y'=>$ys),array('x'=>$ra,'y'=>$dec),$c[$num-1],$c[0]))
			$count++;
                    }
		if($this->IsCross(array('x'=>0,'y'=>$ys),array('x'=>$ra,'y'=>$dec),$c[$i],$c[$i+1]))
			$count++;
		if($ra!=0 && ($c[$i]['y']-$ys)/$c[$i]['x']==($dec-$ys)/$ra && $flag==0){
			$flag=1;
			$count--;
		}
            }
            if($count%2==0)
		continue;
            else{
		$db->close();
		return $name;
		//return $count;
            }
        }
	$db->close();
	return '失败';
    }



    private function IsXZ1($ra,$dec)
    {
        $path=$this->basedir.DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."cst.db";
        $db=new SQLite3($path);
        if(!$db)
            die($db->lastErrorMsg());
        for($g=1;$g<90;$g++){
            $name='';
            $flag=0;
            $ys=50;
            $c=null;
            $kotoba='SELECT * FROM cst WHERE xz=\''.$g."';";
            $res=$db->query($kotoba);
             while($row = $res->fetchArray(SQLITE3_ASSOC)){
                $name=$row['ename'];
		if($row['ename']=='PSC' || $row['ename']=='OCT' || $row['ename']=='TUC' || $row['ename']=='PHE'
		 || $row['ename']=='SCL' || $row['ename']=='CET' || $row['ename']=='PEG' || $row['ename']=='AND'  || $row['ename']=='CAS' || $row['ename']=='CEP' || $row['ename']=='UMI')
		{
                    if($ra>=0 && $ra <300){
                        if($row['ra']>300)
                            $row['ra']=$row['ra']-360;
			$c[]=array('x'=>$row['ra'],'y'=>$row['dec']);
                    }else{
                        if($row['ra']<300)
                            $row['ra']=$row['ra']+360;
			$c[]=array('x'=>$row['ra'],'y'=>$row['dec']);
                    }
		}else
                    $c[]=array('x'=>$row['ra'],'y'=>$row['dec']);	
            }
            $num=count($c);
            $count=0;
            if($name=='CAS')
		$ys=-50;
            for($i=0;$i<$num-1;$i++){
		if($i==1){
                    if($this->IsCross(array('x'=>0,'y'=>$ys),array('x'=>$ra,'y'=>$dec),$c[$num-1],$c[0]))
                    $count++;
		}
		if($this->IsCross(array('x'=>0,'y'=>$ys),array('x'=>$ra,'y'=>$dec),$c[$i],$c[$i+1]))
                    $count++;
		if($ra!=0 && ($c[$i]['y']-$ys)/$c[$i]['x']==($dec-$ys)/$ra && $flag==0)
		{
                    $flag=1;
                    $count--;
		}
            }
            if($count%2==0)
		continue;
            else{
		$db->close();
		return $name;
		//return $count;
            }
        }
	$db->close();
	return '失败';
    }
    
    private function IsCross($a,$b,$c,$d)
    {
	$ac['x']=$a['x']-$c['x'];
	$ac['y']=$a['y']-$c['y'];
	$ad['x']=$a['x']-$d['x'];
	$ad['y']=$a['y']-$d['y'];
	$r1=$ac['x']*$ad['y']-$ad['x']*$ac['y'];
	$bc['x']=$b['x']-$c['x'];
	$bc['y']=$b['y']-$c['y'];
	$bd['x']=$b['x']-$d['x'];
	$bd['y']=$b['y']-$d['y'];
	$r2=$bc['x']*$bd['y']-$bd['x']*$bc['y'];
	//echo $r1.' '.$r2;
	if($r1*$r2>0) return 0;
	$ca['x']=$c['x']-$a['x'];
	$ca['y']=$c['y']-$a['y'];
	$cb['x']=$c['x']-$b['x'];
	$cb['y']=$c['y']-$b['y'];
	$r1=$ca['x']*$cb['y']-$cb['x']*$ca['y'];
	$da['x']=$d['x']-$a['x'];
	$da['y']=$d['y']-$a['y'];
	$db['x']=$d['x']-$b['x'];
	$db['y']=$d['y']-$b['y'];
	$r2=$da['x']*$db['y']-$db['x']*$da['y'];
	if($r1*$r2>0) return 0;
	//echo $c['x'].'<br />';
	//echo $c['y'].'<br />';
	//echo $d['x'].'<br />';
	//echo $d['y'].'<br />';
	//echo '<br />';
	
	return 1;
    }
    private function XZN($eng){	
        $xz=array(
        'AND'=>'仙女座',
        'ANT'=>'唧筒座',
	'APS'=>'天燕座',
	'AQR'=>'宝瓶座',
	'AQL'=>'天鹰座',
	'ARA'=>'天坛座',
	'ARI'=>'白羊座',
	'AUR'=>'御夫座',
	'BOO'=>'牧夫座',
	'CAE'=>'雕具座',
	'CAM'=>'鹿豹座',
	'CNC'=>'巨蟹座',
	'CVN'=>'猎犬座',
	'CMA'=>'大犬座',
	'CMI'=>'小犬座',
	'CAP'=>'摩羯座',
	'CAR'=>'船底座',
	'CAS'=>'仙后座',
	'CEN'=>'半人马座',
	'CEP'=>'仙王座',
	'CET'=>'鲸鱼座',
	'CHA'=>'蝘蜓座',
	'CIR'=>'圆规座',
	'COL'=>'天鸽座',
	'COM'=>'后发座',
	'CRA'=>'南冕座',
	'CRB'=>'北冕座',
	'CRV'=>'乌鸦座',
	'CRT'=>'巨爵座',
	'CRU'=>'南十字座',
	'CYG'=>'天鹅座',
	'DEL'=>'海豚座',
	'DOR'=>'剑鱼座',
	'DRA'=>'天龙座',
	'EQU'=>'小马座',
	'ERI'=>'波江座',
	'FOR'=>'天炉座',
	'GEM'=>'双子座',
	'GRU'=>'天鹤座',
	'HER'=>'武仙座',
	'HOR'=>'时钟座',
	'HYA'=>'长蛇座',
	'HYI'=>'水蛇座',
	'IND'=>'印第安座',
	'LAC'=>'蝎虎座',
	'LEO'=>'狮子座',
	'LMI'=>'小狮座',
	'LEP'=>'天兔座',
	'LIB'=>'天秤座',
	'LUP'=>'豺狼座',
	'LYN'=>'天猫座',
	'LYR'=>'天琴座',
	'MEN'=>'山案座',
	'MIC'=>'显微镜座',
	'MON'=>'麒麟座',
	'MUS'=>'苍蝇座',
	'NOR'=>'矩尺座',
	'OCT'=>'南极座',
	'OPH'=>'蛇夫座',
	'ORI'=>'猎户座',
	'PAV'=>'孔雀座',
	'PEG'=>'飞马座',
	'PER'=>'英仙座',
	'PHE'=>'凤凰座',
	'PIC'=>'绘架座',
	'PSC'=>'双鱼座',
	'PSA'=>'南鱼座',
	'PUP'=>'船尾座',
	'PYX'=>'罗盘座',
	'RET'=>'网罟座',
	'SGE'=>'天箭座',
	'SGR'=>'人马座',
	'SCO'=>'天蝎座',
	'SCL'=>'玉夫座',
	'SCT'=>'盾牌座',
	'SER1'=>'巨蛇座',
	'SER2'=>'巨蛇座',
	'SEX'=>'六分仪座',
	'TAU'=>'金牛座',
	'TEL'=>'望远镜座',
	'TRI'=>'三角座',
	'TRA'=>'南三角座',
	'TUC'=>'杜鹃座',
	'UMA'=>'大熊座',
	'UMI'=>'小熊座',
	'VEL'=>'船帆座',
	'VIR'=>'室女座',
        'VOL'=>'飞鱼座',
        'VUL'=>'狐狸座');
        return $xz[$eng];
    }
}