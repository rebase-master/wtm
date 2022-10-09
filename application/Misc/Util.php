 <?php
 class Util{
 public static function makeTime($created){
     $current = strtotime(date('Y-m-d H:i:s', time()+5.5*60*60));
     $time=floor(($current-$created)/60);
	  $t=$time;    
	  if($time<1)
		  $t="A few seconds ago";
	  else if($time>=1 && $time<2)
		  $t=$time." minute ago";
	  else if($time>=2 && $time<60)
		  $t=$time." minutes ago";
	  else if($time>=60 && $time<120)
	  $t=(floor($time/60))." hour ago";
	  else if($time>=120 && $time<1440)
	  $t=(floor($time/60))." hours ago";
	  else if($time>=1440 && $time<2880)
	  $t=(floor($time/1440))." day ago";
	  else if($time>=2880 && $time<4320)
	  $t=(floor($time/1440))." days ago";
	  else if($time>=4320)
	  $t=strftime("%b %d, '%y", $created);
//	  $t=strftime("%b %d, %Y at %I:%M %p", $created);
  return $t;
  }
     public static function makeShortTime($created){
         $current = strtotime(date('Y-m-d H:i:s', time()+5.5*60*60));
         $time=floor(($current-$created)/60);
         $t=$time;
         if($time<1)
             $t="a few seconds";
         else if($time>=1 && $time<2)
             $t=$time." min";
         else if($time>=2 && $time<60)
             $t=$time." mins";
         else if($time>=60 && $time<120)
             $t=(floor($time/60))." hr";
         else if($time>120 && $time<=1440)
             $t=(floor($time/60))." hr";
         else if($time>1440 && $time<2880)
             $t=(floor($time/1440))." d";
         else if($time>=2880 && $time<4320)
             $t=(floor($time/1440))." d";
         else if($time>=4320)
             $t=strftime("%b %d at %I:%M %p", $created);
         //try %j for day number w/0 leading zero in Linux. It doesn't work on windows
         return $t;
     }

     public static function convert_smart_quotes($string)
{ 
    $search = array(chr(145), 
                    chr(146), 
                    chr(147), 
                    chr(148), 
                    chr(151),
					'&acirc;��',
					'&acirc;�'); 
 
    $replace = array("'", 
                     "'", 
                     '"', 
                     '"', 
                     '-',
					 '"',
					 '"'); 
 
    return str_replace($search, $replace, $string); 
} 
 
}