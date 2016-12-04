registerController('SSIDPoolController', ['$api', '$scope', function($api, $scope) {
    $scope.ssidCount = 0;
    $scope.backupSuccess = "";
    // Init
    $api.request({
        module: 'SSIDPool',
        action: 'getSSIDCount'
    }, function(response) 
       {
        $scope.refreshHistory();
        $scope.ssidCount = response.ssidCount;
       }
       );

    //Save current SSID list
    $scope.backupCurrent = (function() {
        $api.request({
            module: 'SSIDPool',
            action: 'backupCurrent'
        }, function(response) {
            $scope.refreshHistory();
            $scope.backupSuccess = response.error;
        });
    });

    //Save current SSID list
    $scope.refreshHistory = (function() {
        $api.request({
            module: 'SSIDPool',
            action: 'getHistory'
        }, function(response) {
            $scope.history = response;
        });
    });
    //Download select file
    $scope.downloadHistory = (function(param) {
                        $api.request({                                                                                                        
                                        module: 'SSIDPool',
                                        action: 'downloadHistory',
                                        file: param
                        }, function(response) {
                                        if (response.error === undefined) {
                                                        window.location = '/api/?download=' + response.download;
                                        }
                        });
        });

  //Delete selected file
  $scope.deleteHistory = (function(param) {
        $api.request({                                                                                                                                
          module: "SSIDPool",             
          action: "deleteHistory",     
                file: param     
      }, function(response) {
          $scope.refreshHistory();    
      })                                
  });

  // View selected file
  $scope.viewSSIDFile = (function(param) {
        $api.request({                                                                                                                                
          module: "SSIDPool",             
          action: "viewSSIDFile",       
                file: param     
      }, function(response) {
          $scope.SSIDFile = response.output;
      })                          
  });


}]);
