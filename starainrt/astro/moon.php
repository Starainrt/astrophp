<?php

namespace Starainrt\Astro;
class Moon
{
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
    public function MoonLo($JD) //'月球平黄经
    {
        $T = ($JD - 2451545) / 36525;
        $MoonLo = 218.3164591 + 481267.88134236 * $T - 0.0013268 * $T * $T + $T * $T * $T / 538841 - $T * $T * $T * $T / 65194000;
	return $MoonLo;
    }
    public function SunMoonAngle($JD )// '月日距角
    {
        // Dim T As Double
        $T = ($JD - 2451545) / 36525;
        $SunMoonAngle = 297.8502042 + 445267.1115168 * $T - 0.00163 * $T*$T + $T * $T * $T / 545868 - $T * $T * $T * $T / 113065000;
	return $SunMoonAngle;
    }
    public function MoonM($JD){// '月平近点角
        //Dim T As Double
        $T = ($JD - 2451545) / 36525;
        $MoonM = 134.9634114 + 477198.8676313 * $T + 0.008997 * $T * $T + $T * $T * $T / 69699 - $T * $T * $T * $T / 14712000;
	return $MoonM;
    }
    public function MoonLonX($JD ){ // As Double '月球经度参数(到升交点的平角距离)
        //Dim T As Double
        $T = ($JD - 2451545) / 36525;
        $MoonLonX = 93.2720993 + 483202.0175273 * $T - 0.0034029 * $T *$T - $T * $T * $T / 3526000 + $T * $T * $T * $T / 863310000;
	return $MoonLonX;
    }
   public function MoonI($JD)
    {
	$T = ($JD - 2451545) / 36525;
	$D = $this->Limit360($this->SunMoonAngle($JD));
        $earth=new Earth();
	$IsunM = $earth->sunM($JD);
	$IMoonM = $this->MoonM($JD);
	$F =$this->Limit360( $this->MoonLonX($JD));
	$E = 1 - 0.002516 * $T - 0.0000074 * $T*$T;
	$A1 = 119.75 + 131.849 * $T;
	$A2 = $this->Limit360(53.09 + 479264.29 * $T);
	$A3 = $this->Limit360(313.45 + 481266.484 * $T);
	//die($D." ".$F." ".$E);
	$Ifun[1][1] = 0; $Ifun[1][2] = 0; $Ifun[1][3] = 1; $Ifun[1][4] = 0; $Ifun[1][5] = 6288744;
	$Ifun[2][ 1] = 2; $Ifun[2][ 2] = 0; $Ifun[2][ 3] = -1; $Ifun[2][ 4] = 0; $Ifun[2][ 5] = 1274027;
	$Ifun[3][ 1] = 2; $Ifun[3][ 2] = 0; $Ifun[3][ 3] = 0; $Ifun[3][ 4] = 0; $Ifun[3][ 5] = 658314;
	$Ifun[4][ 1] = 0; $Ifun[4][ 2] = 0; $Ifun[4][ 3] = 2; $Ifun[4][ 4] = 0; $Ifun[4][ 5] = 213618;
	$Ifun[5][ 1] = 0; $Ifun[5][ 2] = 1; $Ifun[5][ 3] = 0; $Ifun[5][ 4] = 0; $Ifun[5][ 5] = -185116;
	$Ifun[6][ 1] = 0; $Ifun[6][ 2] = 0; $Ifun[6][ 3] = 0; $Ifun[6][ 4] = 2; $Ifun[6][ 5] = -114332;
	$Ifun[7][ 1] = 2; $Ifun[7][ 2] = 0; $Ifun[7][ 3] = -2; $Ifun[7][ 4] = 0; $Ifun[7][ 5] = 58793;
	$Ifun[8][ 1] = 2; $Ifun[8][ 2] = -1; $Ifun[8][ 3] = -1; $Ifun[8][ 4] = 0; $Ifun[8][ 5] = 57066;
	$Ifun[9][ 1] = 2; $Ifun[9][ 2] = 0; $Ifun[9][ 3] = 1; $Ifun[9][ 4] = 0; $Ifun[9][ 5] = 53322;
	$Ifun[10][ 1] = 2; $Ifun[10][ 2] = -1; $Ifun[10][ 3] = 0; $Ifun[10][ 4] = 0; $Ifun[10][ 5] = 45758;
	$Ifun[11][ 1] = 0; $Ifun[11][ 2] = 1; $Ifun[11][ 3] = -1; $Ifun[11][ 4] = 0; $Ifun[11][ 5] = -40923;
	$Ifun[12][ 1] = 1; $Ifun[12][ 2] = 0; $Ifun[12][ 3] = 0; $Ifun[12][ 4] = 0; $Ifun[12][ 5] = -34720;
	$Ifun[13][ 1] = 0; $Ifun[13][ 2] = 1; $Ifun[13][ 3] = 1; $Ifun[13][ 4] = 0; $Ifun[13][ 5] = -30383;
	$Ifun[14][ 1] = 2; $Ifun[14][ 2] = 0; $Ifun[14][ 3] = 0; $Ifun[14][ 4] = -2; $Ifun[14][ 5] = 15327;
	$Ifun[15][ 1] = 0; $Ifun[15][ 2] = 0; $Ifun[15][ 3] = 1; $Ifun[15][ 4] = 2; $Ifun[15][ 5] = -12528;
	$Ifun[16][ 1] = 0; $Ifun[16][ 2] = 0; $Ifun[16][ 3] = 1; $Ifun[16][ 4] = -2; $Ifun[16][ 5] = 10980;
	$Ifun[17][ 1] = 4; $Ifun[17][ 2] = 0; $Ifun[17][ 3] = -1; $Ifun[17][ 4] = 0; $Ifun[17][ 5] = 10675;
	$Ifun[18][ 1] = 0; $Ifun[18][ 2] = 0; $Ifun[18][ 3] = 3; $Ifun[18][ 4] = 0; $Ifun[18][ 5] = 10034;
	$Ifun[19][ 1] = 4; $Ifun[19][ 2] = 0; $Ifun[19][ 3] = -2; $Ifun[19][ 4] = 0; $Ifun[19][ 5] = 8548;
	$Ifun[20][ 1] = 2; $Ifun[20][ 2] = 1; $Ifun[20][ 3] = -1; $Ifun[20][ 4] = 0; $Ifun[20][ 5] = -7888;
	$Ifun[21][ 1] = 2; $Ifun[21][ 2] = 1; $Ifun[21][ 3] = 0; $Ifun[21][ 4] = 0; $Ifun[21][ 5] = -6766;
	$Ifun[22][ 1] = 1; $Ifun[22][ 2] = 0; $Ifun[22][ 3] = -1; $Ifun[22][ 4] = 0; $Ifun[22][ 5] = -5163;
	$Ifun[23][ 1] = 1; $Ifun[23][ 2] = 1; $Ifun[23][ 3] = 0; $Ifun[23][ 4] = 0; $Ifun[23][ 5] = 4987;
	$Ifun[24][ 1] = 2; $Ifun[24][ 2] = -1; $Ifun[24][ 3] = 1; $Ifun[24][ 4] = 0; $Ifun[24][ 5] = 4036;
	$Ifun[25][ 1] = 2; $Ifun[25][ 2] = 0; $Ifun[25][ 3] = 2; $Ifun[25][ 4] = 0; $Ifun[25][ 5] = 3994;
	$Ifun[26][ 1] = 4; $Ifun[26][ 2] = 0; $Ifun[26][ 3] = 0; $Ifun[26][ 4] = 0; $Ifun[26][ 5] = 3861;
	$Ifun[27][ 1] = 2; $Ifun[27][ 2] = 0; $Ifun[27][ 3] = -3; $Ifun[27][ 4] = 0; $Ifun[27][ 5] = 3665;
	$Ifun[28][ 1] = 0; $Ifun[28][ 2] = 1; $Ifun[28][ 3] = -2; $Ifun[28][ 4] = 0; $Ifun[28][ 5] = -2689;
	$Ifun[29][ 1] = 2; $Ifun[29][ 2] = 0; $Ifun[29][ 3] = -1; $Ifun[29][ 4] = 2; $Ifun[29][ 5] = -2602;
	$Ifun[30][ 1] = 2; $Ifun[30][ 2] = -1; $Ifun[30][ 3] = -2; $Ifun[30][ 4] = 0; $Ifun[30][ 5] = 2390;
	$Ifun[31][ 1] = 1; $Ifun[31][ 2] = 0; $Ifun[31][ 3] = 1; $Ifun[31][ 4] = 0; $Ifun[31][ 5] = -2348;
	$Ifun[32][ 1] = 2; $Ifun[32][ 2] = -2; $Ifun[32][ 3] = 0; $Ifun[32][ 4] = 0; $Ifun[32][ 5] = 2236;
	$Ifun[33][ 1] = 0; $Ifun[33][ 2] = 1; $Ifun[33][ 3] = 2; $Ifun[33][ 4] = 0; $Ifun[33][ 5] = -2120;
	$Ifun[34][ 1] = 0; $Ifun[34][ 2] = 2; $Ifun[34][ 3] = 0; $Ifun[34][ 4] = 0; $Ifun[34][ 5] = -2069;
	$Ifun[35][ 1] = 2; $Ifun[35][ 2] = -2; $Ifun[35][ 3] = -1; $Ifun[35][ 4] = 0; $Ifun[35][ 5] = 2048;
	$Ifun[36][ 1] = 2; $Ifun[36][ 2] = 0; $Ifun[36][ 3] = 1; $Ifun[36][ 4] = -2; $Ifun[36][ 5] = -1773;
	$Ifun[37][ 1] = 2; $Ifun[37][ 2] = 0; $Ifun[37][ 3] = 0; $Ifun[37][ 4] = 2; $Ifun[37][ 5] = -1595;
	$Ifun[38][ 1] = 4; $Ifun[38][ 2] = -1; $Ifun[38][ 3] = -1; $Ifun[38][ 4] = 0; $Ifun[38][ 5] = 1215;
	$Ifun[39][ 1] = 0; $Ifun[39][ 2] = 0; $Ifun[39][ 3] = 2; $Ifun[39][ 4] = 2; $Ifun[39][ 5] = -1110;
	$Ifun[40][ 1] = 3; $Ifun[40][ 2] = 0; $Ifun[40][ 3] = -1; $Ifun[40][ 4] = 0; $Ifun[40][ 5] = -892;
	$Ifun[41][ 1] = 2; $Ifun[41][ 2] = 1; $Ifun[41][ 3] = 1; $Ifun[41][ 4] = 0; $Ifun[41][ 5] = -810;
	$Ifun[42][ 1] = 4; $Ifun[42][ 2] = -1; $Ifun[42][ 3] = -2; $Ifun[42][ 4] = 0; $Ifun[42][ 5] = 759;
	$Ifun[43][ 1] = 0; $Ifun[43][ 2] = 2; $Ifun[43][ 3] = -1; $Ifun[43][ 4] = 0; $Ifun[43][ 5] = -713;
	$Ifun[44][ 1] = 2; $Ifun[44][ 2] = 2; $Ifun[44][ 3] = -1; $Ifun[44][ 4] = 0; $Ifun[44][ 5] = -700;
	$Ifun[45][ 1] = 2; $Ifun[45][ 2] = 1; $Ifun[45][ 3] = -2; $Ifun[45][ 4] = 0; $Ifun[45][ 5] = 691;
	$Ifun[46][ 1] = 2; $Ifun[46][ 2] = -1; $Ifun[46][ 3] = 0; $Ifun[46][ 4] = -2; $Ifun[46][ 5] = 596;
	$Ifun[47][ 1] = 4; $Ifun[47][ 2] = 0; $Ifun[47][ 3] = 1; $Ifun[47][ 4] = 0; $Ifun[47][ 5] = 549;
	$Ifun[48][ 1] = 0; $Ifun[48][ 2] = 0; $Ifun[48][ 3] = 4; $Ifun[48][ 4] = 0; $Ifun[48][ 5] = 537;
	$Ifun[49][ 1] = 4; $Ifun[49][ 2] = -1; $Ifun[49][ 3] = 0; $Ifun[49][ 4] = 0; $Ifun[49][ 5] = 520;
	$Ifun[50][ 1] = 1; $Ifun[50][ 2] = 0; $Ifun[50][ 3] = -2; $Ifun[50][ 4] = 0; $Ifun[50][ 5] = -487;
	$Ifun[51][ 1] = 2; $Ifun[51][ 2] = 1; $Ifun[51][ 3] = 0; $Ifun[51][ 4] = -2; $Ifun[51][ 5] = -399;
	$Ifun[52][ 1] = 0; $Ifun[52][ 2] = 0; $Ifun[52][ 3] = 2; $Ifun[52][ 4] = -2; $Ifun[52][ 5] = -381;
	$Ifun[53][ 1] = 1; $Ifun[53][ 2] = 1; $Ifun[53][ 3] = 1; $Ifun[53][ 4] = 0; $Ifun[53][ 5] = 351;
	$Ifun[54][ 1] = 3; $Ifun[54][ 2] = 0; $Ifun[54][ 3] = -2; $Ifun[54][ 4] = 0; $Ifun[54][ 5] = -340;
	$Ifun[55][ 1] = 4; $Ifun[55][ 2] = 0; $Ifun[55][ 3] = -3; $Ifun[55][ 4] = 0; $Ifun[55][ 5] = 330;
	$Ifun[56][ 1] = 2; $Ifun[56][ 2] = -1; $Ifun[56][ 3] = 2; $Ifun[56][ 4] = 0; $Ifun[56][ 5] = 327;
	$Ifun[57][ 1] = 0; $Ifun[57][ 2] = 2; $Ifun[57][ 3] = 1; $Ifun[57][ 4] = 0; $Ifun[57][ 5] = -323;
	$Ifun[58][ 1] = 1; $Ifun[58][ 2] = 1; $Ifun[58][ 3] = -1; $Ifun[58][ 4] = 0; $Ifun[58][ 5] = 299;
	$Ifun[59][ 1] = 2; $Ifun[59][ 2] = 0; $Ifun[59][ 3] = 3; $Ifun[59][ 4] = 0; $Ifun[59][ 5] = 294;
	$Ifun[60][ 1] = 2; $Ifun[60][ 2] = 0; $Ifun[60][ 3] = -1; $Ifun[60][ 4] = -2; $Ifun[60][ 5] = 0;
	$MoonI=0;
	For($i=1;$i<61;$i++){
	    if (abs($Ifun[$i][2])== 1) 
	        $TEMP = $this->SinR($Ifun[$i][ 1] * $D + $Ifun[$i][ 2] * $IsunM + $Ifun[$i][3] * $IMoonM + $Ifun[$i][4] * $F) * $Ifun[$i][5] *$E;
	    elseif (abs($Ifun[$i][ 2])== 2)
	        $TEMP = $this->SinR($Ifun[$i][ 1] * $D + $Ifun[$i][ 2] * $IsunM + $Ifun[$i][ 3] * $IMoonM + $Ifun[$i][4] * $F) * $Ifun[$i][5] * $E * $E;
	    else
	        $TEMP = $this->SinR($Ifun[$i][1] * $D + $Ifun[$i][ 2] * $IsunM + $Ifun[$i][ 3] * $IMoonM + $Ifun[$i][ 4] * $F) * $Ifun[$i][ 5];
	    $MoonI = $MoonI + $TEMP;
	}
	$MoonI = $MoonI + 3958 * $this->SinR($A1) + 1962 * $this->SinR($this->MoonLo($JD) - $F) + 318 * $this->SinR($A2);
	return $MoonI;
    }
	
    public function MoonR($JD)
    {
	$T = ($JD - 2451545) / 36525;
	$D = $this->SunMoonAngle($JD);
        $earth=new Earth();
	$IsunM = $earth->sunM($JD);
	$IMoonM = $this->Limit360(MoonM($JD));
	$F = $this->Limit360($this->MoonLonX($JD));
	$E = 1 - 0.002516 * $T - 0.0000074 * $T*$T;
	$Ifun[1][ 1] = 0; $Ifun[1][ 2] = 0; $Ifun[1][ 3] = 1; $Ifun[1][ 4] = 0; $Ifun[1][ 5] = -20905355;
	$Ifun[2][ 1] = 2; $Ifun[2][ 2] = 0; $Ifun[2][ 3] = -1; $Ifun[2][ 4] = 0; $Ifun[2][ 5] = -3699111;
	$Ifun[3][ 1] = 2; $Ifun[3][ 2] = 0; $Ifun[3][ 3] = 0; $Ifun[3][ 4] = 0; $Ifun[3][ 5] = -2955968;
	$Ifun[4][ 1] = 0; $Ifun[4][ 2] = 0; $Ifun[4][ 3] = 2; $Ifun[4][ 4] = 0; $Ifun[4][ 5] = -569925;
	$Ifun[5][ 1] = 0; $Ifun[5][ 2] = 1; $Ifun[5][ 3] = 0; $Ifun[5][ 4] = 0; $Ifun[5][ 5] = 48888;
	$Ifun[6][ 1] = 0; $Ifun[6][ 2] = 0; $Ifun[6][ 3] = 0; $Ifun[6][ 4] = 2; $Ifun[6][ 5] = -3149;
	$Ifun[7][ 1] = 2; $Ifun[7][ 2] = 0; $Ifun[7][ 3] = -2; $Ifun[7][ 4] = 0; $Ifun[7][ 5] = 246158;
	$Ifun[8][ 1] = 2; $Ifun[8][ 2] = -1; $Ifun[8][ 3] = -1; $Ifun[8][ 4] = 0; $Ifun[8][ 5] = -152138;
	$Ifun[9][ 1] = 2; $Ifun[9][ 2] = 0; $Ifun[9][ 3] = 1; $Ifun[9][ 4] = 0; $Ifun[9][ 5] = -170733;
	$Ifun[10][ 1] = 2; $Ifun[10][ 2] = -1; $Ifun[10][ 3] = 0; $Ifun[10][ 4] = 0; $Ifun[10][ 5] = -204586;
	$Ifun[11][ 1] = 0; $Ifun[11][ 2] = 1; $Ifun[11][ 3] = -1; $Ifun[11][ 4] = 0; $Ifun[11][ 5] = -129620;
	$Ifun[12][ 1] = 1; $Ifun[12][ 2] = 0; $Ifun[12][ 3] = 0; $Ifun[12][ 4] = 0; $Ifun[12][ 5] = 108743;
	$Ifun[13][ 1] = 0; $Ifun[13][ 2] = 1; $Ifun[13][ 3] = 1; $Ifun[13][ 4] = 0; $Ifun[13][ 5] = 104755;
	$Ifun[14][ 1] = 2; $Ifun[14][ 2] = 0; $Ifun[14][ 3] = 0; $Ifun[14][ 4] = -2; $Ifun[14][ 5] = 10321;
	$Ifun[15][ 1] = 0; $Ifun[15][ 2] = 0; $Ifun[15][ 3] = 1; $Ifun[15][ 4] = 2; $Ifun[15][ 5] = 0;
	$Ifun[16][ 1] = 0; $Ifun[16][ 2] = 0; $Ifun[16][ 3] = 1; $Ifun[16][ 4] = -2; $Ifun[16][ 5] = 79661;
	$Ifun[17][ 1] = 4; $Ifun[17][ 2] = 0; $Ifun[17][ 3] = -1; $Ifun[17][ 4] = 0; $Ifun[17][ 5] = -34782;
	$Ifun[18][ 1] = 0; $Ifun[18][ 2] = 0; $Ifun[18][ 3] = 3; $Ifun[18][ 4] = 0; $Ifun[18][ 5] = -23210;
	$Ifun[19][ 1] = 4; $Ifun[19][ 2] = 0; $Ifun[19][ 3] = -2; $Ifun[19][ 4] = 0; $Ifun[19][ 5] = -21636;
	$Ifun[20][ 1] = 2; $Ifun[20][ 2] = 1; $Ifun[20][ 3] = -1; $Ifun[20][ 4] = 0; $Ifun[20][ 5] = 24208;
	$Ifun[21][ 1] = 2; $Ifun[21][ 2] = 1; $Ifun[21][ 3] = 0; $Ifun[21][ 4] = 0; $Ifun[21][ 5] = 30824;
	$Ifun[22][ 1] = 1; $Ifun[22][ 2] = 0; $Ifun[22][ 3] = -1; $Ifun[22][ 4] = 0; $Ifun[22][ 5] = -8379;
	$Ifun[23][ 1] = 1; $Ifun[23][ 2] = 1; $Ifun[23][ 3] = 0; $Ifun[23][ 4] = 0; $Ifun[23][ 5] = -16675;
	$Ifun[24][ 1] = 2; $Ifun[24][ 2] = -1; $Ifun[24][ 3] = 1; $Ifun[24][ 4] = 0; $Ifun[24][ 5] = -12831;
	$Ifun[25][ 1] = 2; $Ifun[25][ 2] = 0; $Ifun[25][ 3] = 2; $Ifun[25][ 4] = 0; $Ifun[25][ 5] = -10445;
	$Ifun[26][ 1] = 4; $Ifun[26][ 2] = 0; $Ifun[26][ 3] = 0; $Ifun[26][ 4] = 0; $Ifun[26][ 5] = -11650;
	$Ifun[27][ 1] = 2; $Ifun[27][ 2] = 0; $Ifun[27][ 3] = -3; $Ifun[27][ 4] = 0; $Ifun[27][ 5] = 14403;
	$Ifun[28][ 1] = 0; $Ifun[28][ 2] = 1; $Ifun[28][ 3] = -2; $Ifun[28][ 4] = 0; $Ifun[28][ 5] = -7003;
	$Ifun[29][ 1] = 2; $Ifun[29][ 2] = 0; $Ifun[29][ 3] = -1; $Ifun[29][ 4] = 2; $Ifun[29][ 5] = 0;
	$Ifun[30][ 1] = 2; $Ifun[30][ 2] = -1; $Ifun[30][ 3] = -2; $Ifun[30][ 4] = 0; $Ifun[30][ 5] = 10056;
	$Ifun[31][ 1] = 1; $Ifun[31][ 2] = 0; $Ifun[31][ 3] = 1; $Ifun[31][ 4] = 0; $Ifun[31][ 5] = 6322;
	$Ifun[32][ 1] = 2; $Ifun[32][ 2] = -2; $Ifun[32][ 3] = 0; $Ifun[32][ 4] = 0; $Ifun[32][ 5] = -9884;
	$Ifun[33][ 1] = 0; $Ifun[33][ 2] = 1; $Ifun[33][ 3] = 2; $Ifun[33][ 4] = 0; $Ifun[33][ 5] = 5751;
	$Ifun[34][ 1] = 0; $Ifun[34][ 2] = 2; $Ifun[34][ 3] = 0; $Ifun[34][ 4] = 0; $Ifun[34][ 5] = 0;
	$Ifun[35][ 1] = 2; $Ifun[35][ 2] = -2; $Ifun[35][ 3] = -1; $Ifun[35][ 4] = 0; $Ifun[35][ 5] = -4950;
	$Ifun[36][ 1] = 2; $Ifun[36][ 2] = 0; $Ifun[36][ 3] = 1; $Ifun[36][ 4] = -2; $Ifun[36][ 5] = 4130;
	$Ifun[37][ 1] = 2; $Ifun[37][ 2] = 0; $Ifun[37][ 3] = 0; $Ifun[37][ 4] = 2; $Ifun[37][ 5] = 0;
	$Ifun[38][ 1] = 4; $Ifun[38][ 2] = -1; $Ifun[38][ 3] = -1; $Ifun[38][ 4] = 0; $Ifun[38][ 5] = -3958;
	$Ifun[39][ 1] = 0; $Ifun[39][ 2] = 0; $Ifun[39][ 3] = 2; $Ifun[39][ 4] = 2; $Ifun[39][ 5] = 0;
	$Ifun[40][ 1] = 3; $Ifun[40][ 2] = 0; $Ifun[40][ 3] = -1; $Ifun[40][ 4] = 0; $Ifun[40][ 5] = 3258;
	$Ifun[41][ 1] = 2; $Ifun[41][ 2] = 1; $Ifun[41][ 3] = 1; $Ifun[41][ 4] = 0; $Ifun[41][ 5] = 2616;
	$Ifun[42][ 1] = 4; $Ifun[42][ 2] = -1; $Ifun[42][ 3] = -2; $Ifun[42][ 4] = 0; $Ifun[42][ 5] = -1897;
	$Ifun[43][ 1] = 0; $Ifun[43][ 2] = 2; $Ifun[43][ 3] = -1; $Ifun[43][ 4] = 0; $Ifun[43][ 5] = -2117;
	$Ifun[44][ 1] = 2; $Ifun[44][ 2] = 2; $Ifun[44][ 3] = -1; $Ifun[44][ 4] = 0; $Ifun[44][ 5] = 2354;
	$Ifun[45][ 1] = 2; $Ifun[45][ 2] = 1; $Ifun[45][ 3] = -2; $Ifun[45][ 4] = 0; $Ifun[45][ 5] = 0;
	$Ifun[46][ 1] = 2; $Ifun[46][ 2] = -1; $Ifun[46][ 3] = 0; $Ifun[46][ 4] = -2; $Ifun[46][ 5] = 0;
	$Ifun[47][ 1] = 4; $Ifun[47][ 2] = 0; $Ifun[47][ 3] = 1; $Ifun[47][ 4] = 0; $Ifun[47][ 5] = -1423;
	$Ifun[48][ 1] = 0; $Ifun[48][ 2] = 0; $Ifun[48][ 3] = 4; $Ifun[48][ 4] = 0; $Ifun[48][ 5] = -1117;
	$Ifun[49][ 1] = 4; $Ifun[49][ 2] = -1; $Ifun[49][ 3] = 0; $Ifun[49][ 4] = 0; $Ifun[49][ 5] = -1571;
	$Ifun[50][ 1] = 1; $Ifun[50][ 2] = 0; $Ifun[50][ 3] = -2; $Ifun[50][ 4] = 0; $Ifun[50][ 5] = -1739;
	$Ifun[51][ 1] = 2; $Ifun[51][ 2] = 1; $Ifun[51][ 3] = 0; $Ifun[51][ 4] = -2; $Ifun[51][ 5] = 0;
	$Ifun[52][ 1] = 0; $Ifun[52][ 2] = 0; $Ifun[52][ 3] = 2; $Ifun[52][ 4] = -2; $Ifun[52][ 5] = -4421;
	$Ifun[53][ 1] = 1; $Ifun[53][ 2] = 1; $Ifun[53][ 3] = 1; $Ifun[53][ 4] = 0; $Ifun[53][ 5] = 0;
	$Ifun[54][ 1] = 3; $Ifun[54][ 2] = 0; $Ifun[54][ 3] = -2; $Ifun[54][ 4] = 0; $Ifun[54][ 5] = 0;
	$Ifun[55][ 1] = 4; $Ifun[55][ 2] = 0; $Ifun[55][ 3] = -3; $Ifun[55][ 4] = 0; $Ifun[55][ 5] = 0;
	$Ifun[56][ 1] = 2; $Ifun[56][ 2] = -1; $Ifun[56][ 3] = 2; $Ifun[56][ 4] = 0; $Ifun[56][ 5] = 0;
	$Ifun[57][ 1] = 0; $Ifun[57][ 2] = 2; $Ifun[57][ 3] = 1; $Ifun[57][ 4] = 0; $Ifun[57][ 5] = 1165;
	$Ifun[58][ 1] = 1; $Ifun[58][ 2] = 1; $Ifun[58][ 3] = -1; $Ifun[58][ 4] = 0; $Ifun[58][ 5] = 0;
	$Ifun[59][ 1] = 2; $Ifun[59][ 2] = 0; $Ifun[59][ 3] = 3; $Ifun[59][ 4] = 0; $Ifun[59][ 5] = 0;
	$Ifun[60][ 1] = 2; $Ifun[60][ 2] = 0; $Ifun[60][ 3] = -1; $Ifun[60][ 4] = -2; $Ifun[60][ 5] = 8752;
	$MoonR=0;
	for($i=1;$i<61;$i++)
	{
	    if(abs($Ifun[$i][ 2])== 1)
	        $TEMP = $this->CosR($Ifun[$i][ 1] * $D + $Ifun[$i][ 2] * $IsunM + $Ifun[$i][ 3] * $IMoonM + $Ifun[$i][ 4] * $F) * $Ifun[$i][ 5] * $E;
	    elseif(abs($Ifun[$i][2])== 2)
	            $TEMP = $this->CosR($Ifun[$i][ 1] * $D + $Ifun[$i][ 2] * $IsunM + $Ifun[$i][ 3] * $IMoonM + $Ifun[$i][ 4] * $F) * $Ifun[$i][ 5] * $E * $E;
	    else
	        $TEMP = $this->CosR($Ifun[$i][ 1] * $D + $Ifun[$i][ 2] * $IsunM + $Ifun[$i][ 3] * $IMoonM + $Ifun[$i][ 4] * $F) * $Ifun[$i][ 5];
	    $MoonR =$MoonR + $TEMP;
	}
	return $MoonR;
    }
	
    public function MoonB($JD)
    {
	$T = ($JD - 2451545) / 36525;
	$D = $this->Limit360($this->SunMoonAngle($JD));
        $earth=new Earth();
	$IsunM = $this->Limit360($earth->sunM($JD));
	$IMoonM = $this->Limit360($this->MoonM($JD));
	$F = $this->Limit360($this->MoonLonX($JD));
	$E = 1 - 0.002516 * $T - 0.0000074 * $T*$T;
	$A1 = $this->Limit360(119.75 + 131.849 * $T);
	$A2 = $this->Limit360(53.09 + 479264.29 * $T);
	$A3 = $this->Limit360(313.45 + 481266.484 * $T);
	//die($IsunM." ".$IMoonM." ".$A3);
	$Ifun[1][ 1] = 0; $Ifun[1][ 2] = 0; $Ifun[1][ 3] = 0; $Ifun[1][ 4] = 1; $Ifun[1][ 5] = 5128122;
	$Ifun[2][ 1] = 0; $Ifun[2][ 2] = 0; $Ifun[2][ 3] = 1; $Ifun[2][ 4] = 1; $Ifun[2][ 5] = 280602;
	$Ifun[3][ 1] = 0; $Ifun[3][ 2] = 0; $Ifun[3][ 3] = 1; $Ifun[3][ 4] = -1; $Ifun[3][ 5] = 277693;
	$Ifun[4][ 1] = 2; $Ifun[4][ 2] = 0; $Ifun[4][ 3] = 0; $Ifun[4][ 4] = -1; $Ifun[4][ 5] = 173237;
	$Ifun[5][ 1] = 2; $Ifun[5][ 2] = 0; $Ifun[5][ 3] = -1; $Ifun[5][ 4] = 1; $Ifun[5][ 5] = 55413;
	$Ifun[6][ 1] = 2; $Ifun[6][ 2] = 0; $Ifun[6][ 3] = -1; $Ifun[6][ 4] = -1; $Ifun[6][ 5] = 46271;
	$Ifun[7][ 1] = 2; $Ifun[7][ 2] = 0; $Ifun[7][ 3] = 0; $Ifun[7][ 4] = 1; $Ifun[7][ 5] = 32573;
	$Ifun[8][ 1] = 0; $Ifun[8][ 2] = 0; $Ifun[8][ 3] = 2; $Ifun[8][ 4] = 1; $Ifun[8][ 5] = 17198;
	$Ifun[9][ 1] = 2; $Ifun[9][ 2] = 0; $Ifun[9][ 3] = 1; $Ifun[9][ 4] = -1; $Ifun[9][ 5] = 9266;
	$Ifun[10][ 1] = 0; $Ifun[10][ 2] = 0; $Ifun[10][ 3] = 2; $Ifun[10][ 4] = -1; $Ifun[10][ 5] = 8822;
	$Ifun[11][ 1] = 2; $Ifun[11][ 2] = -1; $Ifun[11][ 3] = 0; $Ifun[11][ 4] = -1; $Ifun[11][ 5] = 8216;
	$Ifun[12][ 1] = 2; $Ifun[12][ 2] = 0; $Ifun[12][ 3] = -2; $Ifun[12][ 4] = -1; $Ifun[12][ 5] = 4324;
	$Ifun[13][ 1] = 2; $Ifun[13][ 2] = 0; $Ifun[13][ 3] = 1; $Ifun[13][ 4] = 1; $Ifun[13][ 5] = 4200;
	$Ifun[14][ 1] = 2; $Ifun[14][ 2] = 1; $Ifun[14][ 3] = 0; $Ifun[14][ 4] = -1; $Ifun[14][ 5] = -3359;
	$Ifun[15][ 1] = 2; $Ifun[15][ 2] = -1; $Ifun[15][ 3] = -1; $Ifun[15][ 4] = 1; $Ifun[15][ 5] = 2463;
	$Ifun[16][ 1] = 2; $Ifun[16][ 2] = -1; $Ifun[16][ 3] = 0; $Ifun[16][ 4] = 1; $Ifun[16][ 5] = 2211;
	$Ifun[17][ 1] = 2; $Ifun[17][ 2] = -1; $Ifun[17][ 3] = -1; $Ifun[17][ 4] = -1; $Ifun[17][ 5] = 2065;
	$Ifun[18][ 1] = 0; $Ifun[18][ 2] = 1; $Ifun[18][ 3] = -1; $Ifun[18][ 4] = -1; $Ifun[18][ 5] = -1870;
	$Ifun[19][ 1] = 4; $Ifun[19][ 2] = 0; $Ifun[19][ 3] = -1; $Ifun[19][ 4] = -1; $Ifun[19][ 5] = 1828;
	$Ifun[20][ 1] = 0; $Ifun[20][ 2] = 1; $Ifun[20][ 3] = 0; $Ifun[20][ 4] = 1; $Ifun[20][ 5] = -1794;
	$Ifun[21][ 1] = 0; $Ifun[21][ 2] = 0; $Ifun[21][ 3] = 0; $Ifun[21][ 4] = 3; $Ifun[21][ 5] = -1749;
	$Ifun[22][ 1] = 0; $Ifun[22][ 2] = 1; $Ifun[22][ 3] = -1; $Ifun[22][ 4] = 1; $Ifun[22][ 5] = -1565;
	$Ifun[23][ 1] = 1; $Ifun[23][ 2] = 0; $Ifun[23][ 3] = 0; $Ifun[23][ 4] = 1; $Ifun[23][ 5] = -1491;
	$Ifun[24][ 1] = 0; $Ifun[24][ 2] = 1; $Ifun[24][ 3] = 1; $Ifun[24][ 4] = 1; $Ifun[24][ 5] = -1475;
	$Ifun[25][ 1] = 0; $Ifun[25][ 2] = 1; $Ifun[25][ 3] = 1; $Ifun[25][ 4] = -1; $Ifun[25][ 5] = -1410;
	$Ifun[26][ 1] = 0; $Ifun[26][ 2] = 1; $Ifun[26][ 3] = 0; $Ifun[26][ 4] = -1; $Ifun[26][ 5] = -1344;
	$Ifun[27][ 1] = 1; $Ifun[27][ 2] = 0; $Ifun[27][ 3] = 0; $Ifun[27][ 4] = -1; $Ifun[27][ 5] = -1335;
	$Ifun[28][ 1] = 0; $Ifun[28][ 2] = 0; $Ifun[28][ 3] = 3; $Ifun[28][ 4] = 1; $Ifun[28][ 5] = 1107;
	$Ifun[29][ 1] = 4; $Ifun[29][ 2] = 0; $Ifun[29][ 3] = 0; $Ifun[29][ 4] = -1; $Ifun[29][ 5] = 1021;
	$Ifun[30][ 1] = 4; $Ifun[30][ 2] = 0; $Ifun[30][ 3] = -1; $Ifun[30][ 4] = 1; $Ifun[30][ 5] = 833;
	$Ifun[31][ 1] = 0; $Ifun[31][ 2] = 0; $Ifun[31][ 3] = 1; $Ifun[31][ 4] = -3; $Ifun[31][ 5] = 777;
	$Ifun[32][ 1] = 4; $Ifun[32][ 2] = 0; $Ifun[32][ 3] = -2; $Ifun[32][ 4] = 1; $Ifun[32][ 5] = 671;
	$Ifun[33][ 1] = 2; $Ifun[33][ 2] = 0; $Ifun[33][ 3] = 0; $Ifun[33][ 4] = -3; $Ifun[33][ 5] = 607;
	$Ifun[34][ 1] = 2; $Ifun[34][ 2] = 0; $Ifun[34][ 3] = 2; $Ifun[34][ 4] = -1; $Ifun[34][ 5] = 596;
	$Ifun[35][ 1] = 2; $Ifun[35][ 2] = -1; $Ifun[35][ 3] = 1; $Ifun[35][ 4] = -1; $Ifun[35][ 5] = 491;
	$Ifun[36][ 1] = 2; $Ifun[36][ 2] = 0; $Ifun[36][ 3] = -2; $Ifun[36][ 4] = 1; $Ifun[36][ 5] = -451;
	$Ifun[37][ 1] = 0; $Ifun[37][ 2] = 0; $Ifun[37][ 3] = 3; $Ifun[37][ 4] = -1; $Ifun[37][ 5] = 439;
	$Ifun[38][ 1] = 2; $Ifun[38][ 2] = 0; $Ifun[38][ 3] = 2; $Ifun[38][ 4] = 1; $Ifun[38][ 5] = 422;
	$Ifun[39][ 1] = 2; $Ifun[39][ 2] = 0; $Ifun[39][ 3] = -3; $Ifun[39][ 4] = -1; $Ifun[39][ 5] = 421;
	$Ifun[40][ 1] = 2; $Ifun[40][ 2] = 1; $Ifun[40][ 3] = -1; $Ifun[40][ 4] = 1; $Ifun[40][ 5] = -366;
	$Ifun[41][ 1] = 2; $Ifun[41][ 2] = 1; $Ifun[41][ 3] = 0; $Ifun[41][ 4] = 1; $Ifun[41][ 5] = -351;
	$Ifun[42][ 1] = 4; $Ifun[42][ 2] = 0; $Ifun[42][ 3] = 0; $Ifun[42][ 4] = 1; $Ifun[42][ 5] = 331;
	$Ifun[43][ 1] = 2; $Ifun[43][ 2] = -1; $Ifun[43][ 3] = 1; $Ifun[43][ 4] = 1; $Ifun[43][ 5] = 315;
	$Ifun[44][ 1] = 2; $Ifun[44][ 2] = -2; $Ifun[44][ 3] = 0; $Ifun[44][ 4] = -1; $Ifun[44][ 5] = 302;
	$Ifun[45][ 1] = 0; $Ifun[45][ 2] = 0; $Ifun[45][ 3] = 1; $Ifun[45][ 4] = 3; $Ifun[45][ 5] = -283;
	$Ifun[46][ 1] = 2; $Ifun[46][ 2] = 1; $Ifun[46][ 3] = 1; $Ifun[46][ 4] = -1; $Ifun[46][ 5] = -229;
	$Ifun[47][ 1] = 1; $Ifun[47][ 2] = 1; $Ifun[47][ 3] = 0; $Ifun[47][ 4] = -1; $Ifun[47][ 5] = 223;
	$Ifun[48][ 1] = 1; $Ifun[48][ 2] = 1; $Ifun[48][ 3] = 0; $Ifun[48][ 4] = 1; $Ifun[48][ 5] = 223;
	$Ifun[49][ 1] = 0; $Ifun[49][ 2] = 1; $Ifun[49][ 3] = -2; $Ifun[49][ 4] = -1; $Ifun[49][ 5] = -220;
	$Ifun[50][ 1] = 2; $Ifun[50][ 2] = 1; $Ifun[50][ 3] = -1; $Ifun[50][ 4] = -1; $Ifun[50][ 5] = -220;
	$Ifun[51][ 1] = 1; $Ifun[51][ 2] = 0; $Ifun[51][ 3] = 1; $Ifun[51][ 4] = 1; $Ifun[51][ 5] = -185;
	$Ifun[52][ 1] = 2; $Ifun[52][ 2] = -1; $Ifun[52][ 3] = -2; $Ifun[52][ 4] = -1; $Ifun[52][ 5] = 181;
	$Ifun[53][ 1] = 0; $Ifun[53][ 2] = 1; $Ifun[53][ 3] = 2; $Ifun[53][ 4] = 1; $Ifun[53][ 5] = -177;
	$Ifun[54][ 1] = 4; $Ifun[54][ 2] = 0; $Ifun[54][ 3] = -2; $Ifun[54][ 4] = -1; $Ifun[54][ 5] = 176;
	$Ifun[55][ 1] = 4; $Ifun[55][ 2] = -1; $Ifun[55][ 3] = -1; $Ifun[55][ 4] = -1; $Ifun[55][ 5] = 166;
	$Ifun[56][ 1] = 1; $Ifun[56][ 2] = 0; $Ifun[56][ 3] = 1; $Ifun[56][ 4] = -1; $Ifun[56][ 5] = -164;
	$Ifun[57][ 1] = 4; $Ifun[57][ 2] = 0; $Ifun[57][ 3] = 1; $Ifun[57][ 4] = -1; $Ifun[57][ 5] = 132;
	$Ifun[58][ 1] = 1; $Ifun[58][ 2] = 0; $Ifun[58][ 3] = -1; $Ifun[58][ 4] = -1; $Ifun[58][ 5] = -119;
	$Ifun[59][ 1] = 4; $Ifun[59][ 2] = -1; $Ifun[59][ 3] = 0; $Ifun[59][ 4] = -1; $Ifun[59][ 5] = 115;
	$Ifun[60][ 1] = 2; $Ifun[60][ 2] = -2; $Ifun[60][ 3] = 0; $Ifun[60][ 4] = 1; $Ifun[60][ 5] = 107;
	$MoonB=0;
	for($i=1;$i<61;$i++){
	    if(abs($Ifun[$i][ 2])== 1)
	        $TEMP = $this->SinR($Ifun[$i][ 1] * $D + $Ifun[$i][ 2] * $IsunM + $Ifun[$i][ 3] * $IMoonM + $Ifun[$i][ 4] * $F) * $Ifun[$i][ 5] * $E;
	    elseif (abs($Ifun[$i][ 2])==2)
	            $TEMP = $this->SinR($Ifun[$i][ 1] * $D + $Ifun[$i][ 2] * $IsunM + $Ifun[$i][ 3] * $IMoonM + $Ifun[$i][ 4] * $F) * $Ifun[$i][ 5] * $E * $E;
	    else
	        $TEMP = $this->SinR($Ifun[$i][ 1] * $D + $Ifun[$i][ 2] * $IsunM + $Ifun[$i][ 3] * $IMoonM + $Ifun[$i][ 4] * $F) * $Ifun[$i][ 5];
	    $MoonB = $MoonB + $TEMP;
	}
	//$MoonB = $MoonB + 3958 * SinR($A1) + 1962 * SinR(MoonLo($JD) - $F) + 318 * SinR($A2);
	$MoonB+=-2235 * $this->SinR(MoonLo($JD)) + 382 * $this->SinR($A3) + 175 * $this->SinR($A1 - $F ) + 175 * $this->SinR($A1 + $F ) + 127 * $this->SinR( $this->MoonLo($JD) - $IMoonM ) - 115 * sin( $this->MoonLo($JD) +$IMoonM );
	return $MoonB;
    }
    public function MoonTrueLo($JD)
    {
	return ($this->Limit360($this->MoonLo($JD) + ($this->MoonI($JD) / 1000000)));
		// return (MoonI($JD));
    }
    public function MoonTrueBo($JD)
    {
	return  ($this->MoonB($JD) / 1000000);
    }
    public function MoonAway($JD)//'月地距离
    {
	$MoonAway = 385000.56 + $this->MoonR($JD) / 1000;
        return $MoonAway;
    }
    /*
     * @name 月球视黄经
     */
    public function MoonSeeLo($JD) 
    {
        $earth=new Earth();
        return $this->MoonTrueLo($JD)+$earth->HJZD($JD);
    }
    /*
     * 月球真赤纬
     */
    public function MoonTrueDec($JD)
    {
        $earth=new Earth();
	$MoonLo=$this->MoonSeeLo($JD);
	$MoonBo=$this->MoonTrueBo($JD);
	$tmp=$this->SinR($MoonBo)*$this->CosR($earth->sita($JD))+$this->CosR($MoonBo)*$this->SinR($earth->sita($JD))*$this->SinR($MoonLo);
	$res=$this->ArcSin($tmp,1);
	return $res;
    }
    /*
     * 月球真赤经
     */
    public function MoonTrueRa($JD){
        $earth=new Earth();
	$MoonLo=$this->MoonSeeLo($JD);
	$MoonBo=$this->MoonTrueBo($JD);
	$tmp=($this->SinR($MoonLo)*$this->CosR($earth->sita($JD))-$this->TanR($MoonBo)*$this->SinR($earth->sita($JD)))/$this->CosR($MoonLo);
	$tmp=$this->ArcTan($tmp,1);
	if($MoonLo>=90 && $MoonLo<180)
            $tmp = 180 + $tmp;
        elseif($MoonLo>=180 && $MoonLo<270)
            $tmp = 180 + $tmp;
        elseif($MoonLo>=270 && $MoonLo<=360)
            $tmp = 360 + $tmp;
	return $tmp;
    }

    public function MoonLight($JD)
    {
        $earth-new Earth();
	//$MoonRa=MoonTrueRa($JD);
	//$MoonDec=MoonTrueDec($JD);
	$MoonBo=$this->MoonTrueBo($JD);
	//$SunRa=SunSeeRa($JD);
	//$SunDec=SunSeeDec($JD);
	$SunLo=$earth->HSunSeeLo($JD);
	$MoonLo=$this->MoonSeeLo($JD);
	$tmp= $this->CosR($MoonBo)*$this->CosR($SunLo-$MoonLo);
	$R=$earth->RDJL($JD)*149597870.691;
	$i= $R*$this->SinR($this->ArcCos($tmp,1))/($this->MoonAway($JD)-$R*$tmp);
	$i=$this->ArcTan($i,1);
	if($i<0) $i+=180;
	if($i>180) $i-=180;
	$k = ( 1+$this->CosR($i) ) / 2;
	//die($k);
	return $k;
    }
/*
 * $C=0朔月时刻 =1 望月
 */
    public function CalcMoonS($Year,$C=0)
    {
	$k=floor(($Year-2000)*12.3685);
	//$k=-283;
	if($C==1) $k+=0.5;
	$T = $k/1236.85;
	$JDE=2451550.09765+29.530588853*$k + 0.0001337*$T*$T - 0.000000150*$T*$T*$T + 0.00000000073*$T*$T*$T*$T;
	//太阳平近点角：
	$M=$this->Limit360(2.5534+29.10535669*$k-0.0000218*$T*$T-0.00000011*$T*$T*$T);
	//月亮的平近点角：
	$N=$this->Limit360(201.5643+385.81693528*$k+0.0107438*$T*$T+0.00001239*$T*$T*$T-0.000000058*$T*$T*$T*$T);
	//月亮的纬度参数：
	$F=$this->Limit360(160.7108+390.67050274*$k-0.0016341*$T*$T-0.00000227*$T*$T*$T+0.000000011*$T*$T*$T*$T);
	//月亮轨道升交点经度：
	$O=$this->Limit360(124.7746-1.56375580*$k+0.0020691*$T*$T+0.00000215*$T*$T*$T);
	$E = 1 - 0.002516*$T - 0.0000074*$T*$T;
	//die($E." ".$M." ".$N." ".$F." ".$O);
	$ZQ[]=$N; $ZQ[]=$M; $ZQ[]=2*$N; $ZQ[]=2*$F; $ZQ[]=$N-$M;
	$ZQ[]=$N+$M;  $ZQ[]=2*$M; $ZQ[]=$N-2*$F; $ZQ[]=$N+2*$F ;$ZQ[]=2*$N+$M;
	$ZQ[]=3*$N;$ZQ[]=$M+2*$F;$ZQ[]=$M-2*$F;$ZQ[]=2*$N-$M; ;$ZQ[]=$O;
	$ZQ[]=$N+2*$M;$ZQ[]=2*$N-2*$F;$ZQ[]=3*$M;$ZQ[]=$N+$M-2*$F;$ZQ[]=2*$N+2*$F;
	$ZQ[]=$N+$M+2*$F;$ZQ[]=$N-$M+2*$F;$ZQ[]=$N-$M-2*$F;$ZQ[]=3*$N+$M;$ZQ[]=4*$N;
	if($C==0)
	{
		$MN[]=-0.40720;$MN[]=0.17241*$E;$MN[]=0.01608;$MN[]=0.01039;$MN[]=0.00739*$E;
		$MN[]=-0.00514*$E;$MN[]=0.00208*$E*$E;$MN[]=-0.00111;$MN[]=-0.00057;$MN[]=0.00056*$E;
		$MN[]=-0.00042;$MN[]=0.00042*$E;$MN[]=0.00038*$E;$MN[]=-0.00024*$E;$MN[]=-0.00017;
		$MN[]=-0.00007;$MN[]=0.00004;$MN[]=0.00004;$MN[]=0.00003;$MN[]=0.00003;
		$MN[]=-0.00003;$MN[]=0.00003;$MN[]=-0.00002;$MN[]=-0.00002;$MN[]=0.00002;
	}else{
		$MN[]=-0.40614;$MN[]=0.17302*$E;$MN[]=0.01614;$MN[]=0.01043;$MN[]=0.00734*$E;
		$MN[]=-0.00515*$E;$MN[]=0.00209*$E*$E;$MN[]=-0.00111;$MN[]=-0.00057;$MN[]=0.00056*$E;
		$MN[]=-0.00042;$MN[]=0.00042*$E;$MN[]=0.00038*$E;$MN[]=-0.00024*$E;$MN[]=-0.00017;
		$MN[]=-0.00007;$MN[]=0.00004;$MN[]=0.00004;$MN[]=0.00003;$MN[]=0.00003;
		$MN[]=-0.00003;$MN[]=0.00003;$MN[]=-0.00002;$MN[]=-0.00002;$MN[]=0.00002;
	}
	$i=0;$tmp=0;
	foreach($ZQ as $ttmp)
	{
		$tmp+=$this->SinR($ttmp)*$MN[$i];
		$i++;
	}
	//die($tmp);
	$A1 = 299.77 + 0.107408*$k - 0.009173*$T*$T;
	$A2 = 251.88 + 0.016321*$k;
	$A3 = 251.83 + 26.651886*$k;
	$A4 = 349.42 + 36.412478*$k;
	$A5 = 84.66 + 18.206239*$k;
	$A6 = 141.74 + 53.303771*$k;
	$A7 = 207.14 + 2.453732*$k;
	$A8 = 154.84 + 7.306860*$k;
	$A9 = 34.52 + 27.261239*$k;
	$A10= 207.19 + 0.121824*$k;
	$A11= 291.34 + 1.844379*$k;
	$A12=161.72 + 24.198154*$k;
	$A13= 239.56 + 25.513099*$k;
	$A14= 331.55 + 3.592518*$k;
	$tmp2=325*$this->SinR($A1) + 165*$this->SinR($A2) + 164*$this->SinR($A3) + 126*$this->SinR($A4) + 110*$this->SinR($A5) + 62*$this->SinR($A6) + 60*$this->SinR($A7) + 56*$this->SinR($A8) + 47*$this->SinR($A9) + 42*$this->SinR($A10) + 40*$this->SinR($A11) + 37*$this->SinR($A12) + 35*$this->SinR($A13) + 23*$this->SinR($A14);
	$tmp2/=1000000;
	//die($tmp2);
	$JDE=$JDE+$tmp2+$tmp;
	return $JDE;
    }

/*
 * @name $C=0 上弦 1 下弦
 */
    public function CalcMoonX($Year,$C=0)
    {
	$k=floor(($Year-2000)*12.3685)+0.25;
	//$k=544.25;
	if($C==1) $k+=0.5;
	$T = $k/1236.85;
	$JDE=2451550.09765+29.530588853*$k + 0.0001337*$T*$T - 0.000000150*$T*$T*$T + 0.00000000073*$T*$T*$T*$T;
	//DIE($JDE);
	//太阳平近点角：
	$M=$this->Limit360(2.5534+29.10535669*$k-0.0000218*$T*$T-0.00000011*$T*$T*$T);
	//月亮的平近点角：
	$N=$this->Limit360(201.5643+385.81693528*$k+0.0107438*$T*$T+0.00001239*$T*$T*$T-0.000000058*$T*$T*$T*$T);
	//月亮的纬度参数：
	$F=$this->Limit360(160.7108+390.67050274*$k-0.0016341*$T*$T-0.00000227*$T*$T*$T+0.000000011*$T*$T*$T*$T);
	//月亮轨道升交点经度：
	$O=$this->Limit360(124.7746-1.56375580*$k+0.0020691*$T*$T+0.00000215*$T*$T*$T);
	$E = 1 - 0.002516*$T - 0.0000074*$T*$T;
	//die($E." ".$M." ".$N." ".$F." ".$O);
	$ZQ[]=$N; $ZQ[]=$M; $ZQ[]=$N+$M; $ZQ[]=2*$N; $ZQ[]=2*$F;
	$ZQ[]=$N-$M;  $ZQ[]=2*$M; $ZQ[]=$N-2*$F; $ZQ[]=$N+2*$F ;$ZQ[]=3*$N;
	$ZQ[]=2*$N-$M;$ZQ[]=$M+2*$F;$ZQ[]=$M-2*$F;$ZQ[]=$N+2*$M; ;$ZQ[]=2*$N+$M;
	$ZQ[]=$O;$ZQ[]=$N-$M-2*$F;$ZQ[]=2*$N+2*$F;$ZQ[]=$N+$M+2*$F;$ZQ[]=$N-2*$F;
	$ZQ[]=$N+$M-2*$F;$ZQ[]=3*$M;$ZQ[]=2*$N-2*$F;$ZQ[]=$N-$M+2*$F;$ZQ[]=$M+3*$N;
	$MN[]=-0.62801;$MN[]=0.17172*$E;$MN[]=-0.01183*$E;$MN[]=0.00862;$MN[]=0.00804;
	$MN[]=0.00454*$E;$MN[]=0.00204*$E*$E;$MN[]=-0.00180;$MN[]=-0.00070;$MN[]=-0.00040;
	$MN[]=-0.00034*$E;$MN[]=0.00032*$E;$MN[]=0.00032*$E;$MN[]=-0.00028*$E*$E;$MN[]=0.00027*$E;
	$MN[]=-0.00017;$MN[]=-0.00005;$MN[]=0.00004;$MN[]=-0.00004;$MN[]=0.00004;
	$MN[]=0.00003;$MN[]=0.00003;$MN[]=0.00002;$MN[]=0.00002;$MN[]=-0.00002;
	$i=0;$tmp=0;
	foreach($ZQ as $ttmp)
	{
		$tmp+=$this->SinR($ttmp)*$MN[$i];
		$i++;
	}
	//die($tmp);
	$W=0.00306-0.00038*$E*$this->CosR($M)+0.00026*$this->CosR($N)-0.00002 *$this->CosR($N-$M) + 0.00002*$this->CosR($N+$M)+0.00002*$this->CosR(2*$F);
	//die($W);
	$A1 = 299.77 + 0.107408*$k - 0.009173*$T*$T;
	$A2 = 251.88 + 0.016321*$k;
	$A3 = 251.83 + 26.651886*$k;
	$A4 = 349.42 + 36.412478*$k;
	$A5 = 84.66 + 18.206239*$k;
	$A6 = 141.74 + 53.303771*$k;
	$A7 = 207.14 + 2.453732*$k;
	$A8 = 154.84 + 7.306860*$k;
	$A9 = 34.52 + 27.261239*$k;
	$A10= 207.19 + 0.121824*$k;
	$A11= 291.34 + 1.844379*$k;
	$A12=161.72 + 24.198154*$k;
	$A13= 239.56 + 25.513099*$k;
	$A14= 331.55 + 3.592518*$k;
	$tmp2=325*$this->SinR($A1) + 165*$this->SinR($A2) + 164*$this->SinR($A3) + 126*$this->SinR($A4) + 110*$this->SinR($A5) + 62*$this->SinR($A6) + 60*$this->SinR($A7) + 56*$this->SinR($A8) + 47*$this->SinR($A9) + 42*$this->SinR($A10) + 40*$this->SinR($A11) + 37*$this->SinR($A12) + 35*$this->SinR($A13) + 23*$this->SinR($A14);
	$tmp2/=1000000;
	//die($tmp2);
	//die($JDE." ".$tmp." ".$tmp2." ".$W);
	$JDE=$JDE+$tmp2+$tmp;
	if($C==0)
		$JDE+=$W;
	else
		$JDE-=$W;
	return $JDE;
    }
    /*
     * 月球方位角
     */
     public function MoonAngle($JD,$Lon,$Lat,$TZ)
    {
         $astro=new AstroMain();
         $star=new Star();
	 #$JD=$JD-8/24+$TZ/24;
	 $tmp=($TZ*15-$Lon)*4/60;
	 //$truejd=$JD-$tmp/24;
	 $calcjd=$JD-$TZ/24;
	 $ra=$this->MoonTrueRa($astro->TD2UT($calcjd));
	 $dec= $this->MoonTrueDec($astro->TD2UT($calcjd));
         $away=$this->MoonAway($astro->TD2UT($calcjd))/149597870.7;
	 $ndec=$astro->ZhanXinDec($ra,$dec,$Lat,$Lon,$JD-$TZ/24,$away,0);
	 $nra=$astro->ZhanXinRa($ra,$dec,$Lat,$Lon,$JD-$TZ/24,$away,0);
	 $st=$this->Limit360($star->SeeStarTime($calcjd)*15+$Lon);
	 $H=$this->Limit360($st-$nra);
	 $tmp2=$this->SinR($H)/($this->CosR($H)*$this->SinR($Lat)-$this->TanR($ndec)*$this->CosR($Lat));
	 $Angle=$this->ArcTan($tmp2,1);
	 if($Angle<0)
	 {
		if($H/15<12)
            return $Angle+360;
        else
            return $Angle+180;
	 }
	 else
	 {
		 if($H/15<12)
            return $Angle+180;
        else
            return $Angle;
	 }

    }
    public function MoonHeight($JD,$Lon,$Lat,$TZ)
    {
         $astro=new AstroMain();
         $star=new Star();
	 #$JD=$JD-8/24+$TZ/24;
	 $tmp=($TZ*15-$Lon)*4/60;
	 //$truejd=$JD-$tmp/24;
	 $calcjd=$JD-$TZ/24;
	 $ra=$this->MoonTrueRa($astro->TD2UT($calcjd));
	 $dec= $this->MoonTrueDec($astro->TD2UT($calcjd));
	$away=$this->MoonAway($astro->TD2UT($calcjd))/149597870.7;
	 $ndec=$astro->ZhanXinDec($ra,$dec,$Lat,$Lon,$JD-$TZ/24,$away,0);
	 $nra=$astro->ZhanXinRa($ra,$dec,$Lat,$Lon,$JD-$TZ/24,$away,0);
	 $st=$this->Limit360($star->SeeStarTime($calcjd)*15+$Lon);
	 $H=$this->Limit360($st-$nra);
	 $tmp2=$this->SinR($Lat)*$this->SinR($ndec)+$this->CosR($ndec)*$this->CosR($Lat)*$this->CosR($H);
	 return  $this->ArcSin($tmp2,1);
    }
    public function GetMoonTZTime($JD,$Lon,$Lat,$TZ)  //实际中天时间
    {
	$JD=floor($JD)+0.5;
	//$tmp=($TZ*15-$Lon)*4/60;
	$ttm=$this->MoonTimeAngle($JD,$Lon,$Lat,$TZ);
	if($ttm>0 && $ttm<180)
		$JD+=0.5;
	$JD1=$JD;
	do{
            $JD0 = $JD1;
            $stDegree = $this->MoonTimeAngle($JD0,$Lon,$Lat,$TZ) - 359.599;         
            $stDegreep = ($this->MoonTimeAngle($JD0 + 0.000005,$Lon,$Lat,$TZ)
                      - $this->MoonTimeAngle($JD0 - 0.000005,$Lon,$Lat,$TZ)) / 0.00001;
            $JD1 = $JD0 - $stDegree / $stDegreep;
	}while((floor($JD1 - $JD0) > 0.000001));
	return $JD1;
    }
    
    public function MoonTimeAngle($JD,$Lon,$Lat,$TZ)
    {
        $star=new Star();
	$startime=$this->Limit360($star->SeeStarTime($JD-$TZ/24)*15+$Lon);
	$timeangle=$startime-$this->MoonTrueRa($JD-$TZ/24);
	if($timeangle<0)
		$timeangle+=360;
	return $timeangle;
    }
    public function GetMoonRiseTime1($JD,$Lon,$Lat,$TZ,$ZS=0)
    {
	$JD=floor($JD)+0.5;  #求0时JDE
	$JD1=$JD;
	$moonheight=$this->MoonHeight($JD,$Lon,$Lat,$TZ);  #求此时月亮高度
	$An=0;
	if($ZS!=0) $An=-0.8333;
	if($moonheight>0 || $this->MoonTimeAngle($JD1,$Lon,$Lat,$TZ)<180){  #月亮在地平线上或															#在落下与下中天之间
            do{
                $timeangle=$this->MoonTimeAngle($JD1,$Lon,$Lat,$TZ);
		if($timeangle>180)
                    $JD1+=0.05;
            }while($timeangle>180);  #时角判断近似取下中天
            do{
		$JD0 = $JD1;
		$stDegree = $this->MoonTimeAngle($JD0,$Lon,$Lat,$TZ) - 180;         
		$stDegreep = ($this->MoonTimeAngle($JD0 + 0.000005,$Lon,$Lat,$TZ)
                      - $this->MoonTimeAngle($JD0 - 0.000005,$Lon,$Lat,$TZ)) / 0.00001;
		$ki=$stDegree / $stDegreep;
		if(abs($ki)>0.007){
                    if($ki>0)
			$ki=0.003;
                    else
			$ki=-0.003;
		}
		$JD1 = $JD0 -$ki;
            }while($JD1 - $JD0> 0.000001);
            #$JD1=$JD1-8/24+$TZ/24;
            $JD1+=0.001;
            if($JD1>$JD+1 || $JD1<$JD) #判断是否拱极或不升
                return -1;
            if(($this->MoonHeight($JD1,$Lon,$Lat,$TZ)>$An))
		return -1;
        }
        while($this->MoonHeight($JD1,$Lon,$Lat,$TZ)-$An<-5)
            $JD1+=40/60/24;
	if($this->MoonHeight($JD1,$Lon,$Lat,$TZ)-$An>0)
            $JD1-=40/60/24;
	do{
            $JD0 = $JD1;
            $stDegree = $this->MoonHeight($JD0,$Lon,$Lat,$TZ) - $An;         
            $stDegreep = ($this->MoonHeight($JD0 + 0.000005,$Lon,$Lat,$TZ)
                      - $this->MoonHeight($JD0 - 0.000005,$Lon,$Lat,$TZ)) / 0.00001;
            $ki=$stDegree / $stDegreep;
            if(abs($ki)>0.007){
		if($ki>0)
                    $ki=0.003;
		else
                    $ki=-0.003;
            }
            $JD1 = $JD0 - $ki;
        }while(($JD1 - $JD0 > 0.00001));
	#$JD1=$JD1-8/24+$TZ/24;
	if($JD1>$JD+1 || $JD1<$JD)
            return -1;
	else
            return $JD1;
    }
    public function GetMoonDownTime1($JD,$Lon,$Lat,$TZ,$ZS=0)
    {
	$JD=floor($JD)+0.5;
	$JD1=$JD;
	$moonheight=$this->MoonHeight($JD,$Lon,$Lat,$TZ);
	$An=0;
		if($ZS!=0) $An=-0.8333;
	if($moonheight<0 || $this->MoonTimeAngle($JD1,$Lon,$Lat,$TZ)>180){
            do{
		$timeangle=$this->MoonTimeAngle($JD1,$Lon,$Lat,$TZ);
		if($timeangle>15)
                    $JD1+=0.03;
            }while($timeangle>15);
            do{
                $JD0 = $JD1;
		$stDegree = $this->MoonTimeAngle($JD0,$Lon,$Lat,$TZ) - 1;         
		$stDegreep = ($this->MoonTimeAngle($JD0 + 0.000005,$Lon,$Lat,$TZ)
                      - $this->MoonTimeAngle($JD0 - 0.000005,$Lon,$Lat,$TZ)) / 0.00001;
		$ki=$stDegree / $stDegreep;
		if(abs($ki)>0.007){
                    if($ki>0)
			$ki=0.003;
                    else
			$ki=-0.003;
		}
                $JD1 = $JD0 -$ki;
            }while($JD1 - $JD0> 0.000001);
            #$JD1=$JD1-8/24+$TZ/24;
            $JD1+=0.001;
            //echo DateCalc($JD1);
            if($JD1>$JD+1 || $JD1<$JD)
		return -1;
            if(($this->MoonHeight($JD1,$Lon,$Lat,$TZ)<$An))
		return -1;
	}
	while($this->MoonHeight($JD1,$Lon,$Lat,$TZ)-$An>5)
            $JD1+=40/60/24;
	if($this->MoonHeight($JD1,$Lon,$Lat,$TZ)-$An<0)
            $JD1-=40/60/24;	
	do{
            $JD0 = $JD1;
            $stDegree = $this->MoonHeight($JD0,$Lon,$Lat,$TZ) - $An;         
            $stDegreep = ($this->MoonHeight($JD0 + 0.000005,$Lon,$Lat,$TZ)
                      - $this->MoonHeight($JD0 - 0.000005,$Lon,$Lat,$TZ)) / 0.00001;
            $ki=$stDegree / $stDegreep;
            if(abs($ki)>0.007){
		if($ki>0)
                    $ki=0.003;
                else
                    $ki=-0.003;
            }
            $JD1 = $JD0 - $ki;
        }while(($JD1 - $JD0 > 0.00001));
	#$JD1=$JD1-8/24+$TZ/24;	
	if($JD1>$JD+1 || $JD1<$JD)
            return -1;
	else
            return $JD1;
    }

    public function GetMoonRiseTime($JD,$Lon,$Lat,$TZ,$ZS=0)
    {
        $astro=new AstroMain();
        $star=new Star();
	$JD=floor($JD)-0.5;
	//$tmp=($TZ*15-$Lon)*4/60;
	$startime=$this->Limit360($star->SeeStarTime($JD-$TZ/24)*15+$Lon);
	$tmp2= $this->MoonTrueDec($JD-$TZ/24);
	$tmp=$this->MoonTrueRa($JD-$TZ/24);
	if(($startime-$tmp)/15/24>0)
	{
            $JD++;
            $tmp2=$this->MoonTrueDec($JD-$TZ/24);
            $tmp=$this->MoonTrueRa($JD-$TZ/24);
	}
	$JD=$JD-($startime-$tmp)/15/24;
	$away=$this->MoonAway($JD-$TZ/24)/149597870.7;
	$tmp2=$astro->ZhanXinDec($tmp,$tmp2,$Lat,$Lon,$JD-$TZ/24,$away,0);
	if($Lat>0)
	{
            if(-90+$Lat>$tmp2)
		return -1; //极夜
            else if(90-$Lat<$tmp2)
		return -2; //极昼
	}else{
            if(90+$Lat<$tmp2)
		return -1; //极夜
            else if(-90-$Lat>$tmp2)
                return -2; //极昼
	}
	$tmp3=$this->ArcCos(-$this->TanR($tmp2)*$this->TanR($Lat),1)/15;
	$tmp4=$JD-$tmp3/24-25/60/24;
	//die(DateCalc($tmp4));
	$tmp4=$tmp4-$TZ/24+8/24;
	if ($this->MoonHeight($tmp4,$Lon,$Lat,$TZ)>0) $tmp4-=15/60/24;
	$JD1=$tmp4;
	$An=0;
	if($ZS!=0) $An=-0.8333;
	do{
            $JD0 = $JD1;
            $stDegree = $this->MoonHeight($JD0,$Lon,$Lat,$TZ) - $An;         
            $stDegreep = ($this->MoonHeight($JD0 + 0.000005,$Lon,$Lat,$TZ)
                      - $this->MoonHeight($JD0 - 0.000005,$Lon,$Lat,$TZ)) / 0.00001;
            $JD1 = $JD0 - $stDegree / $stDegreep;
	}while((floor($JD1 - $JD0) > 0.000001));
	$JD1=$JD1-8/24+$TZ/24;
	return $JD1;
    }
    public function GetMoonDownTime($JD,$Lon,$Lat,$TZ,$ZS=0)
    {
        $astro=new AstroMain();
        $star=new Star();
	$JD=floor($JD)-0.5;
	//$tmp=($TZ*15-$Lon)*4/60;
	$startime=$this->Limit360($star->SeeStarTime($JD-$TZ/24)*15+$Lon);
	$tmp2=$this->MoonTrueDec($JD-$TZ/24);
	$tmp=$this->MoonTrueRa($JD-$TZ/24);
	if(($startime-$tmp)/15/24>0)
	{
		$JD++;
		$tmp2=$this->MoonTrueDec($JD-$TZ/24);
		$tmp=$this->MoonTrueRa($JD-$TZ/24);
	}
	$JD=$JD-($startime-$tmp)/15/24;
	$away=$this->MoonAway($JD-$TZ/24)/149597870.7;
	$tmp2=$astro->ZhanXinDec($tmp,$tmp2,$Lat,$Lon,$JD-$TZ/24,$away,0);
	if($Lat>0)
	{
		if(-90+$Lat>$tmp2)
			return -1; //极夜
		else if(90-$Lat<$tmp2)
			return -2; //极昼
	}else{
            if(90+$Lat<$tmp2)
                return -1; //极夜
            else if(-90-$Lat>$tmp2)
		return -2; //极昼
	}
	$tmp3=$this->ArcCos(-$this->TanR($tmp2)*$this->TanR($Lat),1)/15;
	$tmp4=$JD+$tmp3/24-25/60/24;
	//die(DateCalc($tmp4));
	$tmp4=$tmp4-$TZ/24+8/24;
	if ($this->MoonHeight($tmp4,$Lon,$Lat,$TZ)<0) $tmp4-=15/60/24;
	$JD1=$tmp4;
	$An=0;
	if($ZS!=0) $An=-0.8333;
	do{
            $JD0 = $JD1;
            $stDegree = $this->MoonHeight($JD0,$Lon,$Lat,$TZ) - $An;         
            $stDegreep = ($this->MoonHeight($JD0 + 0.000005,$Lon,$Lat,$TZ)
                      - $this->MoonHeight($JD0 - 0.000005,$Lon,$Lat,$TZ)) / 0.00001;
            $JD1 = $JD0 - $stDegree / $stDegreep;
        }while((floor($JD1 - $JD0) > 0.000001));
	$JD1=$JD1-8/24+$TZ/24;
	return $JD1;
    }
}

