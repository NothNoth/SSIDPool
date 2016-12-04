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
           case 'getHistory':
             $this->getHistory();
           break;
           case 'backupCurrent':
             $this->backupCurrent();
           break;
           case 'downloadHistory':
	     $this->downloadHistory();
           break;
           case 'deleteHistory':    
             $this->deleteHistory();
           break;
           case 'viewSSIDFile':
             $this->viewSSIDFile();
           break;
        }
    }

    private function getSSIDCount()
    {
      $ssidfile = "/etc/pineapple/ssid_file";
      $this->response = array("error" => "",
                              "ssidCount" => count(file($ssidfile)) - 1 );
    }

    private function getHistory()
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

    private function downloadHistory()
    {                                   
      $this->response = array("download" => $this->downloadFile("/pineapple/modules/SSIDPool/backups/".$this->request->file));
    }

    private function deleteHistory()
    {                                   
      exec("rm -rf /pineapple/modules/SSIDPool/backups/".$this->request->file);
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




