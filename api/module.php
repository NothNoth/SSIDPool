<?php namespace pineapple;


class SSIDPool extends Module
{
    public function route()
    {
        switch ($this->request->action) 
        {
           case 'getSSIDCount':
             $this->getSSIDCount();
           break;
           case 'getSSIDPool':
             $this->getSSIDPool();
           break;
           case 'backupCurrent':
             $this->backupCurrent();
           break;
           case 'downloadSSIDFile':
	     $this->downloadSSIDFile();
           break;
           case 'deleteSSIDFile':    
             $this->deleteSSIDFile();
           break;
           case 'viewSSIDFile':
             $this->viewSSIDFile();
           break;
           case 'restoreSSIDFile':    
             $this->restoreSSIDFile();
           break;
           case 'mergeAllSSID':    
             $this->mergeAllSSID();
           break;
        }
    }

    private function getSSIDCount()
    {
      $ssidfile = "/etc/pineapple/ssid_file";
      $this->response = array("error" => "",
                              "ssidCount" => count(file($ssidfile)) - 1 );
    }

    private function getSSIDPool()
    {

      $this->streamFunction = function ()
      { 
        $backupPath = "/sd/modules/SSIDPool/backups/";              
        $log_list = array_reverse(glob($backupPath . "*"));
                                                                          
        echo '[';                                                         
        for($i=0;$i<count($log_list);$i++)                                
        {                                                                 
          $info = explode("_", basename($log_list[$i]));            
          $entryDate = gmdate('Y-m-d H-i-s', $info[1]);
          $entryName = basename($log_list[$i]);                     
          $c = count(file($backupPath . $entryName)) - 1;       
                                                                    
          echo json_encode(array($entryDate, $c, $entryName));          
                                                          
          if($i!=count($log_list)-1) echo ',';            
        }                                                    
        echo ']';                                               
      }; 

    }

    private function downloadSSIDFile()
    {                                   
      $this->response = array("download" => $this->downloadFile("/pineapple/modules/SSIDPool/backups/".$this->request->file));
    }

    private function deleteSSIDFile()
    {                                   
      exec("rm -rf /pineapple/modules/SSIDPool/backups/".$this->request->file);
    } 

    private function restoreSSIDFile()
    {
      $ssidfile = "/etc/pineapple/ssid_file";
      exec("cat /pineapple/modules/SSIDPool/backups/".$this->request->file." > $ssidfile");
      $this->response = array("ssidCount" => count(file($ssidfile)) - 1);
    }

    private function mergeAllSSID()
    {
      $ssidfile = "/etc/pineapple/ssid_file";
      exec("cat /pineapple/modules/SSIDPool/backups/* | sort | uniq > $ssidfile");
      $this->response = array("ssidCount" => count(file($ssidfile)) - 1);
    }

    private function viewSSIDFile()  
    {                                   
      exec ("cat /pineapple/modules/SSIDPool/backups/".$this->request->file, $output);                                             
      if(!empty($output))               
        $this->response = array("output" => implode("\n", $output));
      else                                                                                                           
        $this->response = array("output" => "Empty SSID file...");
    }

    private function backupCurrent()
    {
      $ssidfile = "/etc/pineapple/ssid_file";
      $destpath = "/sd/modules/SSIDPool/backups/";
      $file = "ssid_" . time() . ".txt";
      mkdir($destpath);
      if (copy($ssidfile, $destpath . $file) == TRUE)
        $this->response = array("error" => "Saved to " . $file);
      else
        $this->response = array("error" => "failure");
    }
}




