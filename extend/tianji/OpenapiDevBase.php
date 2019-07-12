<?php
class OpenapiDevBase
{
	protected $appId = 2010343;
	protected $orgPrivateKey = '-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQDNm48GlGG+oIPJiOCjoGGZ5EXsf3PNNynbbn+stzV35OYWXNyO
Ep9LH+42HKosnbGEsw5iL06fzshyxmSOKK41Fps37DBqDkUfCso3q/mAu8uJqKir
mPm5wj7j43Cqttr0IP1du0+A0B61FoYK8dHqnhsMrsG0xHtgWkblkfobMQIDAQAB
AoGBAI4wB8cbEkWMJ9dVq1Q884JDVP/qXCENBwtS7UR6JqXVTDEm4vf1dOe1Gz2c
sSrNmxgT49yOrqbhj8mf3aZaB648xPqmSk3r3JizrCI525SpC/jW4k06RbpGUwBZ
+bYySNOuvv8KExLNgernq/HSbBkPE/eIGoER91kWkHL9AYtpAkEA97Q/xRa9jD/7
kLG3lmdgZzpOX6snvJ2/AjH73EqVbwK66TmoGQU+dWfbOvyLI1HXL5KPSfVgYTZu
ND1VOnG29wJBANR+YrLFMbKpamABn+WA33iW9bPRq8fq8G+QVMcvZA3isipROj4h
eOdsxk4Mpfc0ISaiNaKagOUi+FQY/Jeg7RcCQQCYL5VSeNNCPPlJf/bEoIT5Rzhp
zNVgLCbzqVQNl4FSMAI4UqU1oiQqrAFkr06pB5pG7yu8C9cIQxHYZKpdewonAkBP
5rIwLIwSdTfFn/bC8qGVE5aSJh4kz0fXe3sVZtGFkx+RX/e5kxaGVtV+Va02dgid
IVNvsA8Vmf+sh7S7Q0zDAkEAoTtRbRBw9Wm/4H5lXGREmPe+FeD7OAsC3Tes2xA8
Ag7nWbT6/bwnTtYz9QGcbhl1jyKIr+pP6Meu6PGa08Zwsg==
-----END RSA PRIVATE KEY-----';
	
	protected $rong360Url = 'http://openapi.rong360.com/gateway';
	protected $_toBeSigned  = null;
	protected $_postData    = null;

	public function sendRequest($bizData, $method)
	{/*{{{*/
        $params = array(
            'method'        => $method,
            'app_id'	=> $this->appId,
            'version'	=> '1.0',
            'sign_type'	=> 'RSA',
            'format'	=> 'json',
            'timestamp'	=> time()
        );
        $params['biz_data'] = json_encode($bizData);

        $this->_toBeSigned = $this->getSignContent($params);
        $params['sign'] = $this->sign($this->_toBeSigned);

        $resp = $this->_crulPost($params, $this->rong360Url);
        return ($resp);
	}/*}}}*/
    
    protected function getSignContent($params)
    {/*{{{*/
    	ksort($params);
    
    	$i = 0; 
    	$stringToBeSigned = "";
    	foreach ($params as $k => $v) {
    		if ($i == 0) {
    			$stringToBeSigned .= "$k" . "=" . "$v";
    		} else {
    			$stringToBeSigned .= "&" . "$k" . "=" . "$v";
    		}
    		$i++;
    	}
    	unset ($k, $v);
    	return $stringToBeSigned;
    }/*}}}*/
    
    protected function sign($data) 
    {/*{{{*/
    	$res = $this->orgPrivateKey;
    	openssl_sign($data, $sign, $res);
    	$sign = base64_encode($sign);
    	return $sign;
    }/*}}}*/
    
    private function _crulPost($postData, $url='')
    {/*{{{*/
    	if(empty($url)){
    		return false;
    	}
    
        try
        {
            $this->_postData = http_build_query($postData);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_postData);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSLVERSION, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            $res = curl_exec($curl);

            $errno = curl_errno($curl);
            $curlInfo = curl_getinfo($curl);
            $errInfo = 'curl_err_detail: ' . curl_error($curl);
            $errInfo .= ' curlInfo:'. json_encode($curlInfo);

            $arrRet = json_decode($res, true);

            //统一记录日志
            $logLevel = 'info';
            if(!is_array($arrRet) || $arrRet['error']!=200) {
                $logLevel = 'warning';
            }
            curl_close($curl);
        }catch(Exception $e)
            {
                print_r($e->getMessage());
            }
    
    
    	if($arrRet['errno']==0){
    		return $arrRet;
    	}

    	return $arrRet;
    }/*}}}*/
}
