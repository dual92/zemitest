<?php
	//----------------------------------------------------------------------------------------------
	//	
	//----------------------------------------------------------------------------------------------	
	class ZemiDBAPI{
		
		function __construct() {
	
		}
   
		//
		//	
		//
		function CheckPacket($conn){ 
						
			$headers = apache_request_headers();
									
			if( !$headers['Packet-Save'] ) return FALSE;
								
			//
			//	Check DBAPI 
			//
			$spDBAPI = $conn->prepare("EXEC ? = TM_ACCOUNT.DBO.SP_DBAPI_PACKET_CHECK  ?, ?, ?");

			$spDBAPI->bindParam(1, $nDBAPIResult, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 4			);
			$spDBAPI->bindParam(2, $headers['Device-Id']	);
			$spDBAPI->bindParam(3, $headers['Packet-Seq']);			
			$spDBAPI->bindParam(4, $strPacketData, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 2147483647 );
			
			$spDBAPI->execute();
	
			//
			//	Get Old Packet
			//
			if( $nDBAPIResult == 1 )
			{
				if( $strPacketData != '' )
				{
					echo $strPacketData;	
								
					return true;
				}		
			}
						
			return false;
		}
	
		
		//
		//		
		//	
		function SavePacket($conn){ 
		
			$headers = apache_request_headers();		
		
			if( $headers['Packet-Save'] )
			{	
				$spDBAPI = $conn->prepare("EXEC TM_ACCOUNT.DBO.SP_DBAPI_PACKET_SAVE ?, ?, ? ");

				$spDBAPI->bindParam(1, $headers['Device-Id']	);
				$spDBAPI->bindParam(2, $headers['Packet-Seq']	);
				$spDBAPI->bindParam(3, ob_get_contents()		);
				
				$spDBAPI->execute();
			}	
		}		
	}
?>


