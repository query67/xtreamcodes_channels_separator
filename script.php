<?php
    $file = fopen("root.m3u","r");
    $i = 0;
    while(!feof($file))
    {
        $line = fgets($file);
        $i++;
        if(preg_match("/#EXTINF:-1,######/",$line) || strpos($line,"###") !== false)
        {
            $header = $line;
            $startpos = $i + 1;
            if(preg_match("/\w+\s?\w+?\s+?\w+/",$line,$matches) && strpos($line,"###") !==false)
            	$folder_name = str_replace(' ','_',$matches[0]);

            if(!file_exists($folder_name))
            {
                mkdir($folder_name);
            }
            if(file_exists($folder_name))
            {
                if(fopen($folder_name."/full.m3u","w"))
                {
                   
                    $lineNo = 0;
                    $file2 = fopen("root.m3u","r");
                    $file_path = "./".$folder_name."/full.m3u";
                    $wfile = fopen($file_path,'w');
                    fwrite($wfile,$header);
                    $count = 0;
                    while($c_line = fgets($file2))
                    {
                        $lineNo++;
                        if($lineNo >= $startpos)
                        {
                            if(preg_match("/#EXTINF:-1,######/",$c_line) ||  strpos($c_line,"###") !== false)
                            {
                                break;
                            }
                            fwrite($wfile,$c_line);
                            $count++;
                        }
                    }
                    
                    if($count >= 500)
                    {
                        $j = 1;
                        $total_file = ceil($count/500);
                        $start_index = 1;
                        $limit = 500;
                        while($j <= $total_file)
                        {
                            // echo $start_index . "\n";
                            $desired_file = fopen("./".$folder_name."/full.m3u",'r');
                            if($part = fopen("./".$folder_name."/part".$j.".m3u",'w'))
                            {
                                echo $start_index . "-->" . $limit . "\n";
                                while($d_line = fgets($desired_file))
                                {
                                    
                                    if($limit >= $start_index && $start_index <= $count)
                                    {
                                        fwrite($part,$d_line);
                                    }
                                    if($start_index == $limit)
                                        break;
                                    $start_index++;
                                }
                               
                            }
                         
                            // $start_index += 500;
                            $limit *= 2;
                            $j++;
                        }
                        echo "-------------------\n";
                    }
                }

            }
        }

    }
    fclose($file);
