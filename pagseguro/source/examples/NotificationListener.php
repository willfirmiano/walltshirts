<?php
/*
************************************************************************
Copyright [2011] [PagSeguro Internet Ltda.]

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
************************************************************************
*/

require_once "../PagSeguroLibrary/PagSeguroLibrary.php";

class NotificationListener  {

    public static function ValidaTransaction($code, $type) {
    	
    	if ( $code && $type ) {
			
    		$notificationType = new PagSeguroNotificationType($type);
    		$strType = $notificationType->getTypeFromValue();
    		$transaction = 'false';
			
			switch($strType) {
				
				case 'TRANSACTION':
					$transaction = self::TransactionNotification($code);
					break;
				
				default:
					LogPagSeguro::error("Unknown notification type [".$notificationType->getValue()."]");
					
			}
			self::printLog($strType);
			return $transaction;
			
		} else {
			
			LogPagSeguro::error("Invalid notification parameters.");
			self::printLog();
			
		}
		
    }
    
    
    private static function TransactionNotification($notificationCode) {
		/*
		* #### Crendencials ##### 
		* Substitute the parameters below with your credentials (e-mail and token)
		* You can also get your credentails from a config file. See an example:
		* $credentials = PagSeguroConfig::getAccountCredentials();
		*/
    	$credentials = PagSeguroConfig::getAccountCredentials();    	
		
    	try {
    		$transaction = PagSeguroNotificationService::checkTransaction($credentials, $notificationCode);
    		return $transaction;
    	} catch (PagSeguroServiceException $e) {
    		die($e->getMessage());
    	}
    	
    }
    
    
    private static function printLog($strType = null) {
    	$count = 4;
    	echo "<h2>Receive notifications</h2>";
    	if($strType) { 
    		echo "<h4>notifcationType: $strType</h4>";
    	}
    	echo "<p>Last <strong>$count</strong> items in <strong>log file:</strong></p><hr>";
    	echo LogPagSeguro::getHtml($count);
    }
	
}
?>