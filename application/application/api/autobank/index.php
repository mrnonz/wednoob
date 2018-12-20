<?php
error_reporting(0);
date_default_timezone_set('Asia/Bangkok');
$KOBChecker = true;
include_once('bank.php');
class TrueWallet{

	private $ch;
	private $username;
	private $password;
	public $balance;

	public function setLogin($user,$pass){
		$this->username=$user;
		$this->password=$pass;
	}

	public function login(){
		$loginUrl = 'https://wallet.truemoney.com/user/login';
		$this->ch = curl_init();
		curl_setopt($this->ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36');
		curl_setopt($this->ch, CURLOPT_URL, $loginUrl);
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'email='.urlencode($this->username).'&password='.urlencode($this->password));
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, '.htcookies');
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		if(preg_match('/Whoops/',curl_exec($this->ch))){
			return false;
		}else{
			curl_setopt($this->ch, CURLOPT_URL, 'https://wallet.truemoney.com/wallet');
			curl_setopt($this->ch, CURLOPT_POST, 0);
			$html = curl_exec($this->ch);
			preg_match('/"balance":(.*?),/', $html, $balance);
			$this->balance=$balance[1];
			return true;
		}
	}

	public function getTransaction(){
		curl_setopt($this->ch, CURLOPT_URL, 'https://wallet.truemoney.com/api/transaction_history');
		curl_setopt($this->ch, CURLOPT_POST, 0);
		$html = curl_exec($this->ch);
		$json=json_decode($html,true);
		$json=$json['data']['activities'];
		foreach($json as $i=>$val){
			curl_setopt($this->ch, CURLOPT_URL, 'https://wallet.truemoney.com/api/transaction_history_detail?reportID='.urlencode($val['reportID']));
			curl_setopt($this->ch, CURLOPT_POST, 0);
			$data=json_decode(curl_exec($this->ch),true);
			$data=$data['data'];
			$vreturn[$i]['reportID']=$val['reportID'];
			preg_match('/([0-9]{2})\/([0-9]{2})\/([0-9]{2}) ([0-9]{2}):([0-9]{2})/', serialize($data), $dt);
			$vreturn[$i]['time']=strtotime($dt[1].'-'.$dt[2].'-20'.$dt[3].'T'.$dt[4].':'.$dt[5].':00+0700');
			preg_match('/"Transaction ID";s:5:"value";s:10:"(.*?)"/', serialize($data), $txid);
			$vreturn[$i]['txid']=$txid[1];
			$vreturn[$i]['channel']=$data['section2']['column1']['cell1']['titleTh'].' : '.$data['section2']['column1']['cell1']['value'];
			$vreturn[$i]['detail']='';
			if(isset($data['section2']['column1']['cell2'])){
				$vreturn[$i]['detail'].=' '.$data['section2']['column1']['cell2']['titleTh'].' : '.$data['section2']['column1']['cell2']['value'].' ';
			}
			$vreturn[$i]['detail'].='('.$data['ref1'].')';
			if(isset($data['personalMessage']['value'])){
				$vreturn[$i]['detail'].=' ข้อความ : '.$data['personalMessage']['value'];
			}
			$vreturn[$i]['value']=$data['amount'];
			$vreturn[$i]['tx_hash'] = md5($vreturn[$i]['txid']);
		}
		return array_reverse($vreturn);
	}

}
class KTB
{
    private $username;
    private $password;
    private $ch;
    private $identity;
    private $accnum;
    private $accdisp;
    private $is_netbank;
    private $accdata = array();
    public $balance;
    public static function xml2array($xmlObject)
    {
        $out = json_decode(json_encode((array) $xmlObject), true);
        return $out;
    }
    public function setLogin($user, $pass)
    {
        $this->username = $user;
        $this->password = $pass;
    }
    public function setAccountNumber($accnum, $netbank = false)
    {
        if (!is_string($accnum)) {
            die("Account number must be string.");
        }
        if (strlen($accnum) !== 10) {
            die("Account number must be 10 digits.");
        }
        $this->accnum     = $accnum;
        $this->accdisp    = substr($accnum, 0, 3) . '-' . substr($accnum, 3, 1) . '-' . substr($accnum, 4, 5) . '-' . substr($accnum, 9, 1);
        $this->is_netbank = $netbank;
    }
    private function DecodeMP3($filedata)
    {
        $post = array(
            'license_key' => $GLOBALS['license_key'],
            'app_id' => 'KOBBank',
            'mp3' => base64_encode($filedata)
        );
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://customer.api.shayennn.com/ktb/captcha_analyzer.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function login()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/');
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, '.htcookies');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36');
        curl_exec($this->ch);
        $CaptchaMP3 = '';
        while ($CaptchaMP3 === '') {
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/captcha/verifyImg');
            $CaptchaImage = curl_exec($this->ch);
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/CaptchaSound');
            $CaptchaMP3 = curl_exec($this->ch);
        }
        $CaptchaText = $this->DecodeMP3($CaptchaMP3);
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/Login.do');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'cmd=login&imageCode=' . $CaptchaText . '&userId=' . $this->username . '&password=' . $this->password);
        $html = curl_exec($this->ch);
        if (preg_match('/(ยินดีต้อนรับ)/', $html)) {
            return true;
        }
        return false;
    }
    public function getTransaction()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/main.jsp');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.ktbnetbank.com/consumer/Login.do');
        $html = curl_exec($this->ch);
        preg_match('/(sessionKey = \')(.*?)(\';)/', $html, $sess);
        $sessionkey = $sess[2];
        if ($this->is_netbank) {
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/NetSavingAccount.do?cmd=init&sessId=' . urlencode($sessionkey));
        } else {
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/SavingAccount.do?cmd=init&sessId=' . urlencode($sessionkey));
        }
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.ktbnetbank.com/consumer/main.jsp');
        $html          = curl_exec($this->ch);
        $this->accdata = $this->xml2array(simplexml_load_string($html));
        $this->accdata = $this->accdata['DATA'];
        if (count($this->accdata) == 0) {
            die("It's no account.");
        }
        if (!isset($this->accdata['OID'])) {
            for ($i = 0; $i < count($this->accdata); $i++) {
                if ($this->accdata[$i]['ACCOUNTNO'] == $this->accnum) {
                    $this->accdata = $this->accdata[$i];
                    $this->balance=$this->accdata['WITHDRAWABLE'];
                    break;
                }
            }
        } else {
            if ($this->accdata['ACCOUNTNO'] != $this->accnum) {
                die("Not found that account.");
            }
            $this->balance=$this->accdata['WITHDRAWABLE'];
        }
        $this->balance=floatval(str_replace(',', '', $this->balance));
        if ($this->is_netbank) {
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/NetSavingAccount.do?cmd=showDetails');
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'from_date=&to_date=&radios=&specific_peroid=&accountNo=' . urlencode($this->accdata['ACCOUNTNO']) . '&accountNoDisp=' . urlencode($this->accdata['ACCOUNTNODISPLAY']) . '&newAliasName=&oldAliasName=&avaliableBalance=' . urlencode($this->accdata['AMOUNT']) . '&accountSelectedItem=%5Bobject%20Object%5D&amount=&radiosEditAmount=&note=&flageRegNetFeeZero=' . urlencode($this->accdata['FLAGE_REG_NETFEEZERO']) . '&sessId=' . urlencode($sessionkey));
            curl_exec($this->ch);
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/Netbank/myaccount/netsaving/net_saving_accountdetail.jsp?sessId=' . urlencode($sessionkey));
            curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.ktbnetbank.com/consumer/main.jsp');
            curl_setopt($this->ch, CURLOPT_POST, 0);
            curl_exec($this->ch);
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/NetSavingAccount.do?cmd=viewStatement');
            curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.ktbnetbank.com/consumer/main.jsp');
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'from_date=' . date('d-m-o', strtotime('-1 days')) . '&to_date=' . date('d-m-o') . '&radios=date_peroid&specific_peroid=currentMonth&accountNo=' . urlencode($this->accdata['ACCOUNTNO']) . '&accountNoDisp=' . urlencode($this->accdata['ACCOUNTNODISPLAY']) . '&newAliasName=&oldAliasName=&avaliableBalance=' . urlencode($this->accdata['AMOUNT']) . '&accountSelectedItem=%5Bobject%20Object%5D&amount=&radiosEditAmount=&note=&flageRegNetFeeZero=' . urlencode($this->accdata['FLAGE_REG_NETFEEZERO']) . '&sessId=' . urlencode($sessionkey));
        } else {
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/SavingAccount.do?cmd=showDetails');
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'from_date=&to_date=&radios=&specific_peroid=&accountNo=' . urlencode($this->accdata['ACCOUNTNO']) . '&accountNoDisp=' . urlencode($this->accdata['ACCOUNTNODISPLAY']) . '&newAliasName=&oldAliasName=&avaliableBalance=' . urlencode($this->accdata['AMOUNT']) . '&accountSelectedItem=%5Bobject%20Object%5D&amount=&radiosEditAmount=&note=&sessId=' . urlencode($sessionkey));
            curl_exec($this->ch);
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/Netbank/myaccount/saving/saving_accountdetail.jsp?sessId=' . urlencode($sessionkey));
            curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.ktbnetbank.com/consumer/main.jsp');
            curl_setopt($this->ch, CURLOPT_POST, 0);
            curl_exec($this->ch);
            curl_setopt($this->ch, CURLOPT_URL, 'https://www.ktbnetbank.com/consumer/SavingAccount.do?cmd=viewStatement');
            curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.ktbnetbank.com/consumer/main.jsp');
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'from_date=' . date('d-m-o', strtotime('-1 days')) . '&to_date=' . date('d-m-o') . '&radios=date_peroid&specific_peroid=currentMonth&accountNo=' . urlencode($this->accdata['ACCOUNTNO']) . '&accountNoDisp=' . urlencode($this->accdata['ACCOUNTNODISPLAY']) . '&newAliasName=&oldAliasName=&avaliableBalance=' . urlencode($this->accdata['AMOUNT']) . '&accountSelectedItem=%5Bobject%20Object%5D&amount=&radiosEditAmount=&note=&sessId=' . urlencode($sessionkey));
        }
        $txlist = $this->xml2array((simplexml_load_string(curl_exec($this->ch))));
        $txlist = $txlist['DATA'];
        if (isset($txlist['ERRORMESSAGE'])) {
            return array();
        }
        if (isset($txlist['DATE'])) {
            $txlist = array(
                $txlist
            );
        }
        foreach ($txlist as $key => $val) {
            $val['time']      = strtotime($val['DATE']);
            $val['tran_type'] = ($val['TRANSACTION']);
            $val['detail']    = (is_array($val['DESCRIPTION'])) ? json_encode($val['DESCRIPTION']) : $val['DESCRIPTION'];
            $val['channel']   = ($val['BRANCH']);
            $val['value']     = floatval(str_replace(',', '', $val['AMOUNT']));
            $val['tx_hash']   = md5(strtotime($val['DATE']) . $val['AMOUNT'] . $val['BALANCE']);
            unset($val['DATE']);
            unset($val['CHEQUE_NO']);
            unset($val['PERIOD']);
            unset($val['FEE']);
            unset($val['CURRENTAMT']);
            unset($val['PRINCIPALAMT']);
            unset($val['PENALTYAMT']);
            unset($val['DUEDATE']);
            unset($val['INTERESTAMT']);
            unset($val['TAX']);
            unset($val['TRANSACTION']);
            unset($val['DESCRIPTION']);
            unset($val['BRANCH']);
            unset($val['AMOUNT']);
            unset($val['BALANCE']);
            $txlist[$key] = $val;
        }
        return $txlist;
    }
}
class SCB
{
    private $username;
    private $password;
    private $accnum;
    private $ch;
    private $SESSIONEASY;
    private $accid;
    public $balance;

    private function getFormData($html, $key)
    {
        preg_match('/<input.*?name="'.$key.'".*?id="'.$key.'".*?value="(.*?)".*?>/', $html, $ml);
        return $ml[1];
    }

    public function setLogin($user, $pass)
    {
        $this->username=$user;
        $this->password=$pass;
    }

    public function setAccountNumber($accnum)
    {
        if (!is_string($accnum)) {
            die("Account number must be string.");
        }
        if (strlen($accnum)!==10) {
            die("Account number must be 10 digits.");
        }
        $this->accnum=$accnum;
    }

    public function logout()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://ibanking.bangkokbank.com/LogOut.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $html = curl_exec($this->ch);
    }

    public function login()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array( 'Expect:' ));
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.scbeasy.com/v1.4/site/presignon/index.asp');
        /*if($_SERVER['REMOTE_ADDR']=='127.0.0.1'){
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->ch, CURLOPT_PROXY, '127.0.0.1:8888');
        }*/
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, '.htcookies');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.101 Safari/537.36');
        $temp=curl_exec($this->ch);
        ////////////////////////////////////////////////
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.scbeasy.com/online/easynet/page/lgn/login.aspx');
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.scbeasy.com/v1.4/site/presignon/index.asp');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, ('LANG=T&LOGIN='.$this->username.'&PASSWD='.$this->password.'&lgin.x=24&lgin.y=21'));
        $temp = curl_exec($this->ch);
        if (preg_match('/error_signon.aspx/', $temp)) {
            return false;
        }
        preg_match('/<INPUT TYPE="HIDDEN" NAME="SESSIONEASY" VALUE="(.*?)">/', $temp, $ml);
        $this->SESSIONEASY=$ml[1];
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.scbeasy.com/online/easynet/page/firstpage.aspx');
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.scbeasy.com/online/easynet/page/lgn/login.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, ('SESSIONEASY='.$this->SESSIONEASY));
        $temp=curl_exec($this->ch);
        preg_match('/inp_value = new Array\(\'(.*?)\'\);/', $temp, $ml);
        $this->SESSIONEASY=$ml[1];
        return true;
    }

    public function getTransaction()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.scbeasy.com/online/easynet/page/acc/acc_mpg.aspx');
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.scbeasy.com/online/easynet/page/firstpage.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, ('SESSIONEASY='.$this->SESSIONEASY.'&undefined=undefined'));
        $html = curl_exec($this->ch);
        preg_match('/inp_value = new Array\(\'(.*?)\'\);/', $html, $ml);
        $this->SESSIONEASY=$ml[1];
        if (!preg_match('/'.$this->accnum.'/', $html)) {
            die("Not found that account.");
        }
        preg_match('/View\$ctl(.*?)\$SaCa_LinkButton.*?'.$this->accnum.'/', $html, $temp);
        $this->accid=$temp[1];
        $html2=str_replace(array("\r","\t","\n"), "", $html);
        preg_match('/'.$this->accnum.'(.*?)<\/tr>/', $html2, $temp);
        preg_match('/[0-9,]{1,}\.[0-9]{2}/', $temp[1], $temp);
        $this->balance=$temp[0];
        $this->balance=floatval(str_replace(',', '', $this->balance));
        $__EVENTTARGET='ctl00$DataProcess$SaCaGridView$ctl'.$this->accid.'$SaCaView_LinkButton';
        $__EVENTARGUMENT='';
        $__VIEWSTATE=$this->getFormData($html, "__VIEWSTATE");
        $__VIEWSTATEGENERATOR=$this->getFormData($html, "__VIEWSTATEGENERATOR");
        ////////////////////////////////////////////////
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.scbeasy.com/online/easynet/page/acc/acc_mpg.aspx');
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.scbeasy.com/online/easynet/page/acc/acc_mpg.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS,
        '__EVENTTARGET='.urlencode($__EVENTTARGET).
        '&__EVENTARGUMENT='.urlencode($__EVENTARGUMENT).
        '&__VIEWSTATE='.urlencode($__VIEWSTATE).
        '&SESSIONEASY='.$this->SESSIONEASY.
        '&__VIEWSTATEGENERATOR='.urlencode($__VIEWSTATEGENERATOR)
        );
        $temp=curl_exec($this->ch);
        preg_match('/<INPUT TYPE="HIDDEN" NAME="SESSIONEASY" VALUE="(.*?)">/', $temp, $ml);
        $this->SESSIONEASY=$ml[1];
        ////////////////////////////////////////////////
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.scbeasy.com/online/easynet/page/acc/acc_bnk_bln.aspx');
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.scbeasy.com/online/easynet/page/acc/acc_mpg.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, ('SESSIONEASY='.$this->SESSIONEASY));
        $html = curl_exec($this->ch);
        $__EVENTTARGET='ctl00$DataProcess$Link2';
        $__EVENTARGUMENT='';
        $__VIEWSTATE=$this->getFormData($html, "__VIEWSTATE");
        $__VIEWSTATEGENERATOR=$this->getFormData($html, "__VIEWSTATEGENERATOR");
        preg_match('/inp_value = new Array\(\'(.*?)\'\);/', $html, $ml);
        $this->SESSIONEASY=$ml[1];
        preg_match('/value="(.*?)".*?'.$this->accnum.'/', $html, $temp);
        $DDLAcctNo=$temp[1];
        ////////////////////////////////////////////////
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.scbeasy.com/online/easynet/page/acc/acc_bnk_bln.aspx');
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.scbeasy.com/online/easynet/page/acc/acc_bnk_bln.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS,
        '__EVENTTARGET='.urlencode($__EVENTTARGET).
        '&__EVENTARGUMENT='.urlencode($__EVENTARGUMENT).
        '&__VIEWSTATE='.urlencode($__VIEWSTATE).
        '&__LASTFOCUS='.
        '&'.urlencode('ctl00$DataProcess$DDLAcctNo').'='.urlencode($DDLAcctNo).
        '&SESSIONEASY='.$this->SESSIONEASY.
        '&__VIEWSTATEGENERATOR='.urlencode($__VIEWSTATEGENERATOR)
        );
        $temp=curl_exec($this->ch);
        preg_match('/<INPUT TYPE="HIDDEN" NAME="SESSIONEASY" VALUE="(.*?)">/', $temp, $ml);
        $this->SESSIONEASY=$ml[1];
        ////////////////////////////////////////////////
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.scbeasy.com/online/easynet/page/acc/acc_bnk_tst.aspx');
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.scbeasy.com/online/easynet/page/acc/acc_bnk_bln.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, ('SESSIONEASY='.$this->SESSIONEASY));
        $html = iconv('TIS-620', 'UTF-8', curl_exec($this->ch));
        $html=str_replace(array("\r","\t","\n"), "", $html);
        preg_match('/<table cellspacing="0" id="DataProcess_GridView" style="width:100%;border-collapse:collapse;">(.*?)<\/table>/', $html, $temp);
        if (!isset($temp[1])) {
            return array();
        }
        preg_match_all('/<tr( style="background\-color:White;")?>.*?">(.*?)<\/.*?">(.*?)<\/.*?">(.*?)<\/.*?">(.*?)<\/.*?">(.*?)<\/.*?">(.*?)<\/.*?">(.*?)<\/.*?<\/tr>/', $temp[1], $temp);
        for ($i=0;$i<count($temp[0]);$i++) {
            $data[$i]['time']=strtotime(str_replace('/', '-', $temp[2][$i]).'T'.$temp[3][$i].':00+0700');
            $data[$i]['channel']=$temp[5][$i].' ('.$temp[4][$i].')';
            $data[$i]['detail']=$temp[8][$i];
            $data[$i]['value']=floatval(str_replace(',', '', ($temp[6][$i]=='&nbsp;')?$temp[7][$i]:$temp[6][$i]));
            $data[$i]['tx_hash'] = ($data[$i]['time'] . $data[$i]['value'] . $data[$i]['channel'] . $data[$i]['detail']);
            $data[$i]['tx_hash'] = md5($data[$i]['tx_hash']);
        }
        ////////////////////////////////////////////////
        if (isset($data[0])) {
            return ($data);
        }
        return array();
    }
}
class BBL
{
    private $username;
    private $password;
    private $accnum;
    private $accdisp;
    private $ch;
    public $balance;

    private function getFormData($html, $key)
    {
        preg_match('/<input.*?name="'.$key.'".*?id="'.str_replace(array('\$'), '_', $key).'" value="(.*?)".*?\/>/', $html, $ml);
        return $ml[1];
    }

    public function setLogin($user, $pass)
    {
        $this->username=$user;
        $this->password=$pass;
    }

    public function setAccountNumber($accnum)
    {
        if (!is_string($accnum)) {
            die("Account number must be string.");
        }
        if (strlen($accnum)!==10) {
            die("Account number must be 10 digits.");
        }
        $this->accnum=$accnum;
        $this->accdisp=substr($accnum, 0, 3).'-'.substr($accnum, 3, 1).'-'.substr($accnum, 4, 6);
    }

    public function logout()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://ibanking.bangkokbank.com/LogOut.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $html = curl_exec($this->ch);
    }

    public function login()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, 'https://ibanking.bangkokbank.com/SignOn.aspx');
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, '.htcookies');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36');
        $temp=curl_exec($this->ch);
        $VIEWSTATE=$this->getFormData($temp, "__VIEWSTATE");
        $VIEWSTATEGENERATOR=$this->getFormData($temp, "__VIEWSTATEGENERATOR");
        $EVENTVALIDATION=$this->getFormData($temp, "__EVENTVALIDATION");
        $EVENTTARGET=$this->getFormData($temp, "__EVENTTARGET");
        $EVENTARGUMENT=$this->getFormData($temp, "__EVENTARGUMENT");
        ////////////////////////////////////////////////
        curl_setopt($this->ch, CURLOPT_URL, 'https://ibanking.bangkokbank.com/SignOn.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, ('txtID='.$this->username.'&txtPwd='.$this->password.'&__VIEWSTATE='.urlencode($VIEWSTATE).'&__VIEWSTATEGENERATOR='.urlencode($VIEWSTATEGENERATOR).'&__EVENTVALIDATION='.urlencode($EVENTVALIDATION).'&__EVENTTARGET='.urlencode($EVENTTARGET).'&__EVENTARGUMENT='.urlencode($EVENTARGUMENT).'&btnLogOn='.urlencode('Log On').'&DES_Group=GROUPMAIN'));
        $temp = curl_exec($this->ch);
        if (preg_match('/aspxerrorpath/', $temp)) {
            return false;
        }
        return true;
    }

    public function getTransaction()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://ibanking.bangkokbank.com/workspace/16AccountActivity/wsp_AccountSummary_AccountSummaryPage.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $html = curl_exec($this->ch);
        preg_match('/javascript:dataPostPage\(&#39;wsp_AccountActivity_Saving_Current.aspx&#39;, &#39;..\/navigator\/nav_AccountActivity.aspx&#39;, &#39;(.*?)&#39;, &#39;'.$this->accnum.'&#39;\)">.*?<\/a>/', $html, $temp);
        if (count($temp)!==2) {
            die("Not found that account.");
        }
        $AccIndex=$temp[1];
        $temp2=str_replace("\n", '', $html);
        preg_match('/'.$this->accnum.'.*?lblAcctBal">(.*?)</', $temp2, $temp);
        $this->balance=$temp[1];
        $this->balance=floatval(str_replace(',', '', $this->balance));
        $__RequestVerificationToken=$this->getFormData($html, "__RequestVerificationToken");
        $__EVENTTARGET=$this->getFormData($html, "__EVENTTARGET");
        $__EVENTARGUMENT=$this->getFormData($html, "__EVENTARGUMENT");
        $__VIEWSTATE=$this->getFormData($html, "__VIEWSTATE");
        $__VIEWSTATEGENERATOR=$this->getFormData($html, "__VIEWSTATEGENERATOR");
        $__PREVIOUSPAGE=$this->getFormData($html, "__PREVIOUSPAGE");
        $__EVENTVALIDATION=$this->getFormData($html, "__EVENTVALIDATION");
        $ctl00=$this->getFormData($html, 'ctl00\$ctl00\$C\$CW\$hidCollapseFlag');
        ////////////////////////////////////////////////
        curl_setopt($this->ch, CURLOPT_URL, 'https://ibanking.bangkokbank.com/workspace/16AccountActivity/wsp_AccountActivity_Saving_Current.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_REFERER, 'https://ibanking.bangkokbank.com/workspace/16AccountActivity/wsp_AccountSummary_AccountSummaryPage.aspx');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, '__RequestVerificationToken='.urlencode($__RequestVerificationToken).'&__EVENTTARGET='.urlencode($__EVENTTARGET).'&__EVENTARGUMENT='.urlencode($__EVENTARGUMENT).'&__VIEWSTATE='.urlencode($__VIEWSTATE).'&__VIEWSTATEGENERATOR='.urlencode($__VIEWSTATEGENERATOR).'&__PREVIOUSPAGE='.urlencode($__PREVIOUSPAGE).'&__EVENTVALIDATION='.urlencode($__EVENTVALIDATION).'&AcctID='.urlencode($this->accnum).'&AcctIndex='.urlencode($AccIndex).'&'.urlencode('ctl00$ctl00$C$CW$hidCollapseFlag').'='.urlencode($ctl00));
        $html = curl_exec($this->ch);
        $html=str_replace(array("\r","\t","\n"), "", $html);
        preg_match_all('/<tr class="RowGridView(All|Even)" valign="top">(.*?)<\/tr>/', $html, $temp);
        foreach ($temp[2] as $mkey => $val) {
            preg_match('/lblItemDate">(.*?) (.*?) (.*?) (.*?)</', $val, $ml);
            $data[$mkey]['time']=strtotime(($ml[3]-543).'-'.str_replace(array('ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'), array('01','02','03','04','05','06','07','08','09','10','11','12'), $ml[2]).'-'.$ml[1].'T'.$ml[4].':00+07:00');
            preg_match('/Channel" title="(.*?)" class="ib-qtip-left-async">(.*?)</', $val, $ml);
            $data[$mkey]['channel']=$ml[1].' ('.$ml[2].')';
            preg_match('/Description">(.*?)</', $val, $ml);
            $data[$mkey]['detail']=$ml[1];
            preg_match('/Credit">(.*?)</', $val, $ml);
            preg_match('/Debit">(.*?)</', $val, $ml2);
            $data[$mkey]['value']=floatval((str_replace(',', '', $ml[1])!=='')?str_replace(',', '', $ml[1]):(-1*str_replace(',', '', $ml2[1])));
            $data[$mkey]['tx_hash'] = md5($data[$mkey]['time'] . $data[$mkey]['value'] . $ml[1]);
        }
        ////////////////////////////////////////////////
        if (isset($data[0])) {
            return array_reverse($data);
        }
        return array();
    }
}
class KBANK
{
    private $username;
    private $password;
    private $accnum;
    private $accdisp;
    private $ch;
    public $balance;
    public function setLogin($user, $pass)
    {
        $this->username = $user;
        $this->password = $pass;
    }
    public function setAccountNumber($accnum)
    {
        if (!is_string($accnum)) {
            die("Account number must be string.");
        }
        if (strlen($accnum) !== 10) {
            die("Account number must be 10 digits.");
        }
        $this->accnum  = $accnum;
        $this->accdisp = substr($accnum, 0, 3) . '-' . substr($accnum, 3, 1) . '-' . substr($accnum, 4, 5) . '-' . substr($accnum, 9, 1);
    }
    public function login()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, 'https://online.kasikornbankgroup.com/K-Online/preLogin/popupPreLogin.jsp?lang=en');
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, '.htcookies');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'isConfirm=T');
        curl_exec($this->ch);
        curl_setopt($this->ch, CURLOPT_URL, 'https://online.kasikornbankgroup.com/K-Online/login.jsp?lang=en');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $html = curl_exec($this->ch);
        $html = str_replace(array(
            "\r",
            "\t",
            "\n"
        ), "", $html);
        preg_match('/(\<input type="hidden" name="tokenId" id="tokenId" value=")(.*?)("\/\>)/', $html, $temp);
        $sessid = $temp[2];
        curl_setopt($this->ch, CURLOPT_URL, 'https://online.kasikornbankgroup.com/K-Online/login.do');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'tokenId=' . $sessid . '&userName=' . $this->username . '&password=' . $this->password . '&cmd=authenticate&locale=en&app=0');
        $temp = curl_exec($this->ch);
        if (preg_match('/.*?Invalid User ID or Password.*?/', $temp)) {
            return false;
        }
        curl_setopt($this->ch, CURLOPT_URL, 'https://online.kasikornbankgroup.com/K-Online/indexHome.jsp');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $temp = curl_exec($this->ch);
        curl_setopt($this->ch, CURLOPT_URL, 'https://online.kasikornbankgroup.com/K-Online/ib/redirectToIB.jsp?r=' . rand(1000, 9999));
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $html = curl_exec($this->ch);
        $html = str_replace(array(
            "\r",
            "\t",
            "\n"
        ), "", $html);
        preg_match('/(\<input type="hidden" name="txtParam" value=")(.*?)(" \/\>)/', $html, $temp);
        $txtParam = $temp[2];
        curl_setopt($this->ch, CURLOPT_URL, 'https://ebank.kasikornbankgroup.com/retail/security/Welcome.do');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'txtParam=' . $txtParam);
        $html = curl_exec($this->ch);
        if (preg_match('/.*?Unsuccessful Login.*?/', $html)) {
            return false;
        }
        curl_setopt($this->ch, CURLOPT_URL, 'https://ebank.kasikornbankgroup.com/retail/RetailWelcome.do');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $html=curl_exec($this->ch);
        $html=str_replace(array("\r","\t","\n"), "", $html);
        preg_match_all('/<table.*Available<br>Balance(.*)apply_web_card_en/', $html, $temp);
        preg_match_all('/<td(.*?)>(.*?)<\/td>/', $temp[1][0], $temp);
        $this->balance=$temp[2][2];
        $this->balance=floatval(str_replace(',', '', $this->balance));
        return true;
    }
    public function getTransaction()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://ebank.kasikornbankgroup.com/retail/cashmanagement/TodayAccountStatementInquiry.do');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $html = curl_exec($this->ch);
        $html = str_replace(array(
            "\r",
            "\t",
            "\n"
        ), "", $html);
        preg_match('/(\<input type="hidden" name="org.apache.struts.taglib.html.TOKEN" value=")(.*?)("\>)/', $html, $temp);
        $TOKEN = $temp[2];
        preg_match_all('/(\<option value=\")([0-9]{5,})(\"\>' . $this->accdisp . ')/', $html, $temp);
        if (count($temp) == 0) {
            die("Not found that account.");
        }
        $ACCNUM = $temp[2][0];
        curl_setopt($this->ch, CURLOPT_URL, 'https://online.kasikornbankgroup.com/K-Online/checkSession.jsp');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        curl_exec($this->ch);
        curl_setopt($this->ch, CURLOPT_URL, 'https://ebank.kasikornbankgroup.com/retail/cashmanagement/TodayAccountStatementInquiry.do');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'org.apache.struts.taglib.html.TOKEN=' . $TOKEN . '&acctId=' . $ACCNUM . '&action=detail&st=0');
        $html = curl_exec($this->ch);
        $html = str_replace(array(
            "\r",
            "\t",
            "\n"
        ), "", $html);
        preg_match('/\<table bordercolor=#ffffff cellspacing=0 cellpadding=0 width=\"100%\"  border=1 rules=\"rows\"\>.+?\<\/table\>/', $html, $temp);
        preg_match_all('/(\<tr\>)(.*?)(\<\/tr\>)/', $temp[0], $temp);
        foreach ($temp[2] as $mkey => $val) {
            if ($mkey != 0 && (!preg_match('/.*?Record not found.*?/', $val))) {
                preg_match_all('/<td class=inner_table_.*?>\s?(.*?)<\/td>/', $val, $temp2);
                foreach ($temp2[1] as $key => $val) {
                    switch ($key) {
                        case 0:
                            $data[$mkey - 1]['time'] = $val;
                            $data[$mkey - 1]['time'] = str_replace('/', '-', $data[$mkey - 1]['time']);
                            $data[$mkey - 1]['time'] = str_replace('<br>', '', $data[$mkey - 1]['time']);
                            preg_match('/([0-9]{2})-([0-9]{2})-([0-9]{2})\s{30}([0-9]{2}:[0-9]{2}:[0-9]{2})/', $data[$mkey - 1]['time'], $ar);
                            $data[$mkey - 1]['time'] = strtotime($ar[1] . '-' . $ar[2] . '-20' . $ar[3] . 'T' . $ar[4] . '+0700');
                            break;
                        case 1:
                            $data[$mkey - 1]['channel'] = $val;
                            break;
                        case 2:
                            $data[$mkey - 1]['detail'] = $val;
                            break;
                        case 3:
                            if ($val != '') {
                                $data[$mkey - 1]['value'] = (0 - floatval(str_replace(',', '', $val)));
                            }
                            break;
                        case 4:
                            if ($val != '') {
                                $data[$mkey - 1]['value'] = floatval(str_replace(',', '', $val));
                            }
                            break;
                        case 5:
                            $data[$mkey - 1]['fee'] = floatval(str_replace(',', '', $val));
                            break;
                        case 6:
                            $data[$mkey - 1]['acc_num'] = $val;
                            break;
                        case 7:
                            $data[$mkey - 1]['detail'] .= ' (' . $val . ')';
                            break;
                    }
                }
                $data[$mkey - 1]['tx_hash'] = md5($data[$mkey - 1]['time'] . $data[$mkey - 1]['value']);
            }
        }
        curl_setopt($this->ch, CURLOPT_URL, 'https://online.kasikornbankgroup.com/K-Online/checkSession.jsp');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        curl_exec($this->ch);
        if (isset($data[0])) {
            return array_reverse($data);
        }
        return array();
    }
}
class TMB
{
    private $username;
    private $password;
    private $accnum;
    private $accdisp;
    private $ch;
    private $site_version;
    private $app_version;
    private $token;
    private $tknid;
    private $acc;
    public $balance;

    public function __construct()
    {
        $this->ch = curl_init();
        //curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($this->ch, CURLOPT_PROXY, '127.0.0.1:8888');
        curl_setopt($this->ch, CURLOPT_COOKIE, "f5_cspm=1234");
        curl_setopt($this->ch, CURLOPT_ENCODING, "gzip, deflate, br");
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, '.htcookies');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
    }

    public function setLogin($user, $pass)
    {
        $this->username=$user;
        $this->password=$pass;
    }

    public function setAccountNumber($accnum)
    {
        if (!is_string($accnum)) {
            die("Account number must be string.");
        }
        if (strlen($accnum)!==10) {
            die("Account number must be 10 digits.");
        }
        $this->accnum=$accnum;
        $this->accdisp='0000'.$accnum;
    }

    private function _getdata($url, $ref=false)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tmbdirect.com'));
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, 0);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
            'Origin: https://www.tmbdirect.com',
            'Accept-Language: en-US,en;q=0.8',
            'Connection: keep-alive',
            )
        );
        if ($ref!==false) {
            curl_setopt($this->ch, CURLOPT_REFERER, $ref);
        }
        return curl_exec($this->ch);
    }

    private function _postdata($url, $data, $ref=false)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tmbdirect.com'));
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
            'Accept: */*',
            'Origin: https://www.tmbdirect.com',
            'Accept-Language: en-US,en;q=0.8',
            'Connection: keep-alive',
            'Content-Length: '.strlen($data),
            'X-Kony-Authorization: '.$this->token,
            'Expect:',
            )
        );
        if ($ref!==false) {
            curl_setopt($this->ch, CURLOPT_REFERER, $ref);
        }
        return curl_exec($this->ch);
    }

    private function _postjsonover($url, $data, $ref=false)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tmbdirect.com'));
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Origin: https://www.tmbdirect.com',
            'Accept-Language: en-US,en;q=0.8',
            'Connection: keep-alive',
            'Content-Length: '.strlen($data),
            'X-Kony-App-Secret: 9bca06a1518df6af31382131cd911009',
            'X-Kony-App-Key: ae8cd4fb292a12727a4833c9fdb8ccb0',
            'X-HTTP-Method-Override: GET',
            'Expect:',
            )
        );
        if ($ref!==false) {
            curl_setopt($this->ch, CURLOPT_REFERER, $ref);
        }
        return curl_exec($this->ch);
    }

    private function _postjson($url, $data, $ref=false)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tmbdirect.com'));
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Origin: https://www.tmbdirect.com',
            'Accept-Language: en-US,en;q=0.8',
            'Connection: keep-alive',
            'Content-Length: '.strlen($data),
            'X-Kony-App-Secret: 9bca06a1518df6af31382131cd911009',
            'X-Kony-App-Key: ae8cd4fb292a12727a4833c9fdb8ccb0',
            'Expect:',
            )
        );
        if ($ref!==false) {
            curl_setopt($this->ch, CURLOPT_REFERER, $ref);
        }
        return curl_exec($this->ch);
    }

    private function _postjsonauth($url, $data, $ref=false)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tmbdirect.com'));
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Origin: https://www.tmbdirect.com',
            'Accept-Language: en-US,en;q=0.8',
            'Connection: keep-alive',
            'Content-Length: '.strlen($data),
            'X-Kony-Authorization: '.$this->token,
            'Expect:',
            )
        );
        if ($ref!==false) {
            curl_setopt($this->ch, CURLOPT_REFERER, $ref);
        }
        return curl_exec($this->ch);
    }

    public function login()
    {
        $temp=$this->_getdata('https://www.tmbdirect.com/');
        preg_match('/tmb\/kdw(.*?)"/', $temp, $a_version);
        $this->site_version=$a_version[1];
        $temp=$this->_getdata('https://www.tmbdirect.com/tmb/kdw'.$this->site_version, 'https://www.tmbdirect.com/');
        preg_match('/\$KG\["version"\] = "(.*?)";/', $temp, $a_appversion);
        $this->app_version=$a_appversion[1];
        $this->_postjsonover('https://www.tmbdirect.com/authService/100000004/appconfig', 'ltrim=function%20()%7Breturn%20this.replace(%2F%5E%5Cs%2B%2F%2C%22%22)%7D&rtrim=function%20()%7Breturn%20this.replace(%2F%5Cs%2B%24%2F%2C%22%22)%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        $temp=$this->_postjson('https://www.tmbdirect.com/authService/100000004/login', '', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        $array_token=json_decode($temp, true);
        $this->token=$array_token['claims_token']['value'];
        $this->_postdata('https://www.tmbdirect.com/services/TMBMIBService0/getPhrases', 'events=%5B%5D&timestamp=&localeId=th_TH&platform1=D&appID=TMB&appver='.$this->app_version.'&serviceID=getPhrases&locale=th_TH&app_name=TMBUI&channel=wap&platform=thinclient&cacheid=&tknid=&httpheaders=%7B%7D&httpconfig=%7B%22timeout%22%3A60000%7D&konyreportingparams=%7B%22os%22%3A%2255%22%2C%22dm%22%3A%22%22%2C%22did%22%3A%22%22%2C%22ua%22%3A%22Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F55.0.2883.87%20Safari%2F537.36%22%2C%22aid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22aname%22%3A%22TMBMIB_20170121%22%2C%22chnl%22%3A%22desktop%22%2C%22plat%22%3A%22windows%22%2C%22aver%22%3A%221.0.12.32%22%2C%22atype%22%3A%22native%22%2C%22stype%22%3A%22b2c%22%2C%22kuid%22%3A%22%22%2C%22mfaid%22%3A%225590390f-1c47-4bc0-a1aa-d4854e48ef05%22%2C%22mfbaseid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22mfaname%22%3A%22TMBMIB_20170121%22%2C%22sdkversion%22%3A%221.0.0.0%22%2C%22sdktype%22%3A%22js%22%2C%22rsid%22%3A%220%22%2C%22svcid%22%3A%22getPhrases%22%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        $temp=$this->_postdata('https://www.tmbdirect.com/services/TMBMIBService0/GetCampaign', 'events=%5B%5D&widgetName=segCampaignImage&formName=frmIBPreLogin&appChannel=I&prelogin=Y&appID=TMB&appver='.$this->app_version.'&serviceID=GetCampaign&locale=th_TH&app_name=TMBUI&channel=wap&platform=thinclient&cacheid=&tknid=&httpheaders=%7B%7D&httpconfig=%7B%22timeout%22%3A180000%7D&konyreportingparams=%7B%22os%22%3A%2255%22%2C%22dm%22%3A%22%22%2C%22did%22%3A%22%22%2C%22ua%22%3A%22Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F55.0.2883.87%20Safari%2F537.36%22%2C%22aid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22aname%22%3A%22TMBMIB_20170121%22%2C%22chnl%22%3A%22desktop%22%2C%22plat%22%3A%22windows%22%2C%22aver%22%3A%221.0.12.32%22%2C%22atype%22%3A%22native%22%2C%22stype%22%3A%22b2c%22%2C%22kuid%22%3A%22%22%2C%22mfaid%22%3A%225590390f-1c47-4bc0-a1aa-d4854e48ef05%22%2C%22mfbaseid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22mfaname%22%3A%22TMBMIB_20170121%22%2C%22sdkversion%22%3A%221.0.0.0%22%2C%22sdktype%22%3A%22js%22%2C%22rsid%22%3A%220%22%2C%22svcid%22%3A%22GetCampaign%22%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        $array_tknid=json_decode($temp, true);
        $this->tknid=$array_tknid['tknid'];
        $temp=$this->_postdata('https://www.tmbdirect.com/services/TMBMIBService0/GetCampaign', 'events=%5B%5D&widgetName=segCampaignImage&formName=frmIBPreLogin&appChannel=I&prelogin=Y&appID=TMB&appver='.$this->app_version.'&serviceID=GetCampaign&locale=th_TH&app_name=TMBUI&channel=wap&platform=thinclient&cacheid=&tknid='.$this->tknid.'&httpheaders=%7B%7D&httpconfig=%7B%22timeout%22%3A180000%7D&konyreportingparams=%7B%22os%22%3A%2255%22%2C%22dm%22%3A%22%22%2C%22did%22%3A%22%22%2C%22ua%22%3A%22Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F55.0.2883.87%20Safari%2F537.36%22%2C%22aid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22aname%22%3A%22TMBMIB_20170121%22%2C%22chnl%22%3A%22desktop%22%2C%22plat%22%3A%22windows%22%2C%22aver%22%3A%221.0.12.32%22%2C%22atype%22%3A%22native%22%2C%22stype%22%3A%22b2c%22%2C%22kuid%22%3A%22%22%2C%22mfaid%22%3A%225590390f-1c47-4bc0-a1aa-d4854e48ef05%22%2C%22mfbaseid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22mfaname%22%3A%22TMBMIB_20170121%22%2C%22sdkversion%22%3A%221.0.0.0%22%2C%22sdktype%22%3A%22js%22%2C%22rsid%22%3A%220%22%2C%22svcid%22%3A%22GetCampaign%22%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        $array_tknid=json_decode($temp, true);
        $this->tknid=$array_tknid['tknid'];
        $temp=$this->_postdata('https://www.tmbdirect.com/services/TMBMIBService9/IBVerifyLoginEligibility', 'events=%5B%5D&loginId='.$this->username.'&userid='.$this->username.'&password='.$this->password.'&appID=TMB&appver='.$this->app_version.'&serviceID=IBVerifyLoginEligibility&locale=th_TH&app_name=TMBUI&channel=wap&platform=thinclient&cacheid=&tknid='.$this->tknid.'&httpheaders=%7B%7D&httpconfig=%7B%22timeout%22%3A180000%7D&konyreportingparams=%7B%22os%22%3A%2255%22%2C%22dm%22%3A%22%22%2C%22did%22%3A%22%22%2C%22ua%22%3A%22Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F55.0.2883.87%20Safari%2F537.36%22%2C%22aid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22aname%22%3A%22TMBMIB_20170121%22%2C%22chnl%22%3A%22desktop%22%2C%22plat%22%3A%22windows%22%2C%22aver%22%3A%221.0.12.32%22%2C%22atype%22%3A%22native%22%2C%22stype%22%3A%22b2c%22%2C%22kuid%22%3A%22%22%2C%22mfaid%22%3A%225590390f-1c47-4bc0-a1aa-d4854e48ef05%22%2C%22mfbaseid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22mfaname%22%3A%22TMBMIB_20170121%22%2C%22sdkversion%22%3A%221.0.0.0%22%2C%22sdktype%22%3A%22js%22%2C%22rsid%22%3A%220%22%2C%22svcid%22%3A%22IBVerifyLoginEligibility%22%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        $array_result=json_decode($temp, true);
        $this->tknid=$array_result['tknid'];
        if ($array_result['httpStatusCode']==200) {
            $temp=$this->_postdata('https://www.tmbdirect.com/services/TMBMIBService0/LoginProcessServiceExecuteIB', 'events=%5B%5D&rqUUId=&LoginInd=login&TriggerEmail=yes&activationCompleteFlag=Login&appID=TMB&appver='.$this->app_version.'&serviceID=LoginProcessServiceExecuteIB&locale=th_TH&app_name=TMBUI&channel=wap&platform=thinclient&cacheid=&tknid='.$this->tknid.'&httpheaders=%7B%7D&httpconfig=%7B%22timeout%22%3A180000%7D&konyreportingparams=%7B%22os%22%3A%2255%22%2C%22dm%22%3A%22%22%2C%22did%22%3A%22%22%2C%22ua%22%3A%22Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F55.0.2883.87%20Safari%2F537.36%22%2C%22aid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22aname%22%3A%22TMBMIB_20170121%22%2C%22chnl%22%3A%22desktop%22%2C%22plat%22%3A%22windows%22%2C%22aver%22%3A%221.0.12.32%22%2C%22atype%22%3A%22native%22%2C%22stype%22%3A%22b2c%22%2C%22kuid%22%3A%22%22%2C%22mfaid%22%3A%225590390f-1c47-4bc0-a1aa-d4854e48ef05%22%2C%22mfbaseid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22mfaname%22%3A%22TMBMIB_20170121%22%2C%22sdkversion%22%3A%221.0.0.0%22%2C%22sdktype%22%3A%22js%22%2C%22rsid%22%3A%220%22%2C%22svcid%22%3A%22LoginProcessServiceExecuteIB%22%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
            $array_result=json_decode($temp, true);
            $this->tknid=$array_result['tknid'];

            return true;
        }

        return false;
    }

    public function logout($die=false)
    {
        $temp=$this->_postdata('https://www.tmbdirect.com/services/TMBMIBService0/logOutTMB', 'events=%5B%5D&channelId=01&timeOut=false&deviceId=hdrIBPostLogin_lnkLogOut_onClick_seq0&languageCd=TH&appID=TMB&appver='.$this->app_version.'&serviceID=logOutTMB&locale=th_TH&app_name=TMBUI&channel=wap&platform=thinclient&cacheid=&tknid='.$this->tknid.'&httpheaders=%7B%7D&httpconfig=%7B%22timeout%22%3A180000%7D&konyreportingparams=%7B%22os%22%3A%2255%22%2C%22dm%22%3A%22%22%2C%22did%22%3A%22%22%2C%22ua%22%3A%22Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F55.0.2883.87%20Safari%2F537.36%22%2C%22aid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22aname%22%3A%22TMBMIB_20170121%22%2C%22chnl%22%3A%22desktop%22%2C%22plat%22%3A%22windows%22%2C%22aver%22%3A%221.0.12.32%22%2C%22atype%22%3A%22native%22%2C%22stype%22%3A%22b2c%22%2C%22kuid%22%3A%22%22%2C%22mfaid%22%3A%225590390f-1c47-4bc0-a1aa-d4854e48ef05%22%2C%22mfbaseid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22mfaname%22%3A%22TMBMIB_20170121%22%2C%22sdkversion%22%3A%221.0.0.0%22%2C%22sdktype%22%3A%22js%22%2C%22rsid%22%3A%220%22%2C%22svcid%22%3A%22logOutTMB%22%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        if ($die!==false) {
            die($die);
        }
    }

    public function getTransaction()
    {
        $temp=$this->_postdata('https://www.tmbdirect.com/services/TMBMIBService5/customerAccountInquiry', 'events=%5B%5D&activationCompleteFlag=true&upgradeSkip=&appID=TMB&appver='.$this->app_version.'&serviceID=customerAccountInquiry&locale=th_TH&app_name=TMBUI&channel=wap&platform=thinclient&cacheid=&tknid='.$this->tknid.'&httpheaders=%7B%7D&httpconfig=%7B%22timeout%22%3A180000%7D&konyreportingparams=%7B%22os%22%3A%2255%22%2C%22dm%22%3A%22%22%2C%22did%22%3A%22%22%2C%22ua%22%3A%22Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F55.0.2883.87%20Safari%2F537.36%22%2C%22aid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22aname%22%3A%22TMBMIB_20170121%22%2C%22chnl%22%3A%22desktop%22%2C%22plat%22%3A%22windows%22%2C%22aver%22%3A%221.0.12.32%22%2C%22atype%22%3A%22native%22%2C%22stype%22%3A%22b2c%22%2C%22kuid%22%3A%22%22%2C%22mfaid%22%3A%225590390f-1c47-4bc0-a1aa-d4854e48ef05%22%2C%22mfbaseid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22mfaname%22%3A%22TMBMIB_20170121%22%2C%22sdkversion%22%3A%221.0.0.0%22%2C%22sdktype%22%3A%22js%22%2C%22rsid%22%3A%220%22%2C%22svcid%22%3A%22customerAccountInquiry%22%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        $array_result=json_decode($temp, true);
        $this->tknid=$array_result['tknid'];
        $found=false;
        foreach ($array_result['custAcctRec'] as $acc) {
            if ($acc['accId']==$this->accdisp) {
                $found=true;
                $this->acc=$acc;
            }
        }
        $this->balance=$this->acc['availableBal'];
        $this->balance=floatval(str_replace(',', '', $this->balance));
        if ($found===false) {
            $this->logout('Not found that account.');
        }
        $temp=$this->_postdata('https://www.tmbdirect.com/services/TMBMIBService7/depositAccountInquiry', 'events=%5B%5D&acctId='.$this->accdisp.'&appID=TMB&appver='.$this->app_version.'&serviceID=depositAccountInquiry&locale=th_TH&app_name=TMBUI&channel=wap&platform=thinclient&cacheid=&tknid='.$this->tknid.'&httpheaders=%7B%7D&httpconfig=%7B%22timeout%22%3A180000%7D&konyreportingparams=%7B%22os%22%3A%2255%22%2C%22dm%22%3A%22%22%2C%22did%22%3A%22%22%2C%22ua%22%3A%22Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F55.0.2883.87%20Safari%2F537.36%22%2C%22aid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22aname%22%3A%22TMBMIB_20170121%22%2C%22chnl%22%3A%22desktop%22%2C%22plat%22%3A%22windows%22%2C%22aver%22%3A%221.0.12.32%22%2C%22atype%22%3A%22native%22%2C%22stype%22%3A%22b2c%22%2C%22kuid%22%3A%22%22%2C%22mfaid%22%3A%225590390f-1c47-4bc0-a1aa-d4854e48ef05%22%2C%22mfbaseid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22mfaname%22%3A%22TMBMIB_20170121%22%2C%22sdkversion%22%3A%221.0.0.0%22%2C%22sdktype%22%3A%22js%22%2C%22rsid%22%3A%220%22%2C%22svcid%22%3A%22depositAccountInquiry%22%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        $array_result=json_decode($temp, true);
        $this->tknid=$array_result['tknid'];
        $temp=$this->_postdata('https://www.tmbdirect.com/services/TMBMIBService0/getAccntTransStmt', 'events=%5B%5D&noOfServiceCall=1&serviceName=AccntTransStmt&sessionFlag=2&AcctIdentValue='.$this->accdisp.'&FIIdent='.$this->acc['fiident'].'&BinData=&BinLength=&StartDt='.date('Y-m-d').'&EndDt='.date('Y-m-d').'&channelID=IB&spName=com.fnis.xes.ST&accName=&accType='.$this->acc['accType'].'&appID=TMB&appver='.$this->app_version.'&serviceID=getAccntTransStmt&locale=th_TH&app_name=TMBUI&channel=wap&platform=thinclient&cacheid=&tknid='.$this->tknid.'&httpheaders=%7B%7D&httpconfig=%7B%22timeout%22%3A180000%7D&konyreportingparams=%7B%22os%22%3A%2255%22%2C%22dm%22%3A%22%22%2C%22did%22%3A%22%22%2C%22ua%22%3A%22Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F55.0.2883.87%20Safari%2F537.36%22%2C%22aid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22aname%22%3A%22TMBMIB_20170121%22%2C%22chnl%22%3A%22desktop%22%2C%22plat%22%3A%22windows%22%2C%22aver%22%3A%221.0.12.32%22%2C%22atype%22%3A%22native%22%2C%22stype%22%3A%22b2c%22%2C%22kuid%22%3A%22%22%2C%22mfaid%22%3A%225590390f-1c47-4bc0-a1aa-d4854e48ef05%22%2C%22mfbaseid%22%3A%2215399fef-c6bd-400e-b57b-400b15d410d0%22%2C%22mfaname%22%3A%22TMBMIB_20170121%22%2C%22sdkversion%22%3A%221.0.0.0%22%2C%22sdktype%22%3A%22js%22%2C%22rsid%22%3A%220%22%2C%22svcid%22%3A%22getAccntTransStmt%22%7D', 'https://www.tmbdirect.com/tmb/kdw'.$this->site_version);
        $array_result=json_decode($temp, true);
        if (!isset($array_result['StmtRecord'])) {
            return array();
        }
        $this->tknid=$array_result['tknid'];
        foreach ($array_result['StmtRecord'] as $mkey => $val) {
            $data[$mkey]['time']=strtotime($val['OriginationDt']);
            $data[$mkey]['channel']=$val['TrnChannel'].' ('.$val['TrnType'].'_'.$val['TrnSubType'].')';
            $data[$mkey]['detail']=$val['TrnDesc1'];
            $data[$mkey]['value']=$val['Amount'];
            if ($val['TrnType']=='Debit') {
                $data[$mkey]['value']*=-1;
            }
            $data[$mkey]['fee']=0;
            $data[$mkey]['acc_num']=$val['OriginationDt'];
            $data[$mkey]['detail'].=' ('.$val['TrnDesc2'].')';
            $data[$mkey]['tx_hash'] = md5($data[$mkey]['time'].$data[$mkey]['detail'].$data[$mkey]['value'].$data[$mkey]['channel']);
        }
        $this->logout();
        if (isset($data[0])) {
            return array_reverse($data);
        }
        return array();
    }
}
class BAY{

	private $username;
	private $password;
	private $accnum;
	private $accdisp;
	private $ch;
	private $token;
	private $ma;
	public $balance;

	private function getFormData($html,$key){

		preg_match('/<input.*?name="'.$key.'".*?id="'.str_replace(array('\$'),'_',$key).'" value="(.*?)".*?\/>/',$html,$ml);
		return $ml[1];
	}

	public function setLogin($user,$pass){
		$this->username=$user;
		$this->password=$pass;
	}

	public function setAccountNumber($accnum){
		if(!is_string($accnum))die("Account number must be string.");
		if(strlen($accnum)!==10)die("Account number must be 10 digits.");
		$this->accnum=$accnum;
		$this->accdisp=substr($accnum,0,3).'-'.substr($accnum,3,1).'-'.substr($accnum,4,6);
	}

	public function logout(){
	}

	public function login(){
		$this->ch = curl_init();
		//curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		//curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		//curl_setopt($this->ch, CURLOPT_PROXY, '127.0.0.1:8888');
		curl_setopt($this->ch, CURLOPT_URL, 'https://www.krungsrionline.com/BAY.KOL.WebSite/Common/Login.aspx');
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, '.htcookies');
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36');
		$temp=curl_exec($this->ch);
		$VIEWSTATE=$this->getFormData($temp,"__VIEWSTATE");
		$VIEWSTATEGENERATOR=$this->getFormData($temp,"__VIEWSTATEGENERATOR");
		$EVENTVALIDATION=$this->getFormData($temp,"__EVENTVALIDATION");
		$EVENTARGUMENT=$this->getFormData($temp,"__EVENTARGUMENT");
		////////////////////////////////////////////////
		curl_setopt($this->ch, CURLOPT_URL, 'https://www.krungsrionline.com/BAY.KOL.WebSite/Common/Login.aspx');
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, ('__EVENTTARGET=ctl00%24cphForLogin%24lbtnLoginNew&__EVENTARGUMENT=&__VIEWSTATE='.urlencode($VIEWSTATE).'&__VIEWSTATEGENERATOR='.urlencode($VIEWSTATEGENERATOR).'&__EVENTVALIDATION='.urlencode($EVENTVALIDATION).'&user=&password=&username=&password=&ctl00%24cphForLogin%24username='.$this->username.'&ctl00%24cphForLogin%24password=&ctl00%24cphForLogin%24hdPassword='.$this->password.'&ctl00%24cphForLogin%24hddLanguage=TH'));
		$temp = curl_exec($this->ch);
		if(preg_match('/MyPortfolio\.aspx/',$temp))return true;
		return false;
	}

	public function getTransaction(){
		curl_setopt($this->ch, CURLOPT_URL, 'https://www.krungsrionline.com/BAY.KOL.WebSite/Pages/MyPortfolio.aspx?d');
		curl_setopt($this->ch, CURLOPT_POST, 0);
		$html = curl_exec($this->ch);
		preg_match('/token=(.*?)&ma/',$html ,$temp3);
		preg_match('/doPostBack\((.*?),&#39;&#39;\)">'.$this->accnum.'</',$html ,$temp);
		preg_match('/value="(.*?)\|'.$this->accnum.'"/',$html ,$temp2);
		if(count($temp)!==2)die("Not found that account.");
		$this->token=$temp3[1];
		$this->ma=$temp2[1];
		$VIEWSTATE=$this->getFormData($html,"__VIEWSTATE");
		$VIEWSTATEGENERATOR=$this->getFormData($html,"__VIEWSTATEGENERATOR");
		$EVENTVALIDATION=$this->getFormData($html,"__EVENTVALIDATION");
		$PREVIOUSPAGE=$this->getFormData($html,"__PREVIOUSPAGE");
		curl_setopt($this->ch, CURLOPT_URL, 'https://www.krungsrionline.com/BAY.KOL.WebSite/Pages/MyPortfolio.aspx/GraphDataAsset');
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, ('{}'));
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
			'Accept: application/json, text/javascript, */*; q=0.01',
			'Connection: keep-alive',
			'X-Requested-With: XMLHttpRequest',
			'Content-Type: application/json; charset=UTF-8',
			)
		);
		$html = json_decode(curl_exec($this->ch),true);
		foreach($html['d'] as $val){
			if($val['Name']==$this->accnum){
				$this->balance=$val['Value'];
			}
		}
		curl_setopt($this->ch, CURLOPT_URL, 'https://www.krungsrionline.com/BAY.KOL.WebSite/Pages/MyPortfolio.aspx?d');
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, ('ctl00%24smMain=ctl00%24cphSectionData%24udpMyport%7Cctl00%24cphSectionData%24rptDeposit%24ctl01%24ltField002&__EVENTTARGET=ctl00%24cphSectionData%24rptDeposit%24ctl01%24ltField002&__EVENTARGUMENT=&__LASTFOCUS=&__VIEWSTATE='.urlencode($VIEWSTATE).'&__VIEWSTATEGENERATOR='.urlencode($VIEWSTATEGENERATOR).'&__PREVIOUSPAGE='.urlencode($PREVIOUSPAGE).'&__EVENTVALIDATION='.urlencode($EVENTVALIDATION).'&ctl00%24wgTransfer%24ddlFromAccount='.$this->ma.'%7C'.$this->accnum.'&ctl00%24wgBillPayment%24ddl_WidgetBP_FromAccount='.$this->ma.'%7C'.$this->accnum.'&__ASYNCPOST=true'));
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
			'Connection: keep-alive',
			'Upgrade-Insecure-Requests: 1',
			)
		);
		$html = urldecode(curl_exec($this->ch));
		$boom=explode('|',$html);
		////////////////////////////////////////////////
		curl_setopt($this->ch, CURLOPT_URL, 'https://www.krungsrionline.com'.$boom[7]);
		curl_setopt($this->ch, CURLOPT_POST, 0);
		curl_setopt($this->ch, CURLOPT_REFERER, 'https://www.krungsrionline.com/BAY.KOL.WebSite/Pages/MyPortfolio.aspx?d');
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
			'Connection: keep-alive',
			'Upgrade-Insecure-Requests: 1',
			)
		);
		$html = curl_exec($this->ch);
		$html=str_replace(array("\r","\t","\n"),"",$html);
		preg_match('/<tbody>(.*?)<\/tbody>/',$html,$temp);
		preg_match_all('/<tr>(.*?)<\/tr>/',$temp[1],$temp);
		$data=array();
		foreach($temp[1] as $mkey=>$val){
			preg_match('/1">(.*?)\/(.*?)\/(.*?) (.*?):(.*?)</',$val,$ml);
			$data[$mkey]['time']=strtotime($ml[3].'-'.$ml[2].'-'.$ml[1].' '.$ml[4].':'.$ml[5].':00+07:00');
			preg_match('/6">(.*?)</',$val,$ml);
			$data[$mkey]['channel']=trim($ml[1]);
			preg_match('/2">(.*?)</',$val,$ml);
			$data[$mkey]['detail']=trim($ml[1]);
			preg_match('/4">(.*?)</',$val,$ml);
			preg_match('/3">(.*?)</',$val,$ml2);
			$data[$mkey]['value']=floatval(str_replace(',','',($ml[1]!=='')?$ml[1]:(-1*$ml2[1])));
			$data[$mkey]['tx_hash'] = md5($data[$mkey]['time'].$data[$mkey]['detail'].$data[$mkey]['value'].$data[$mkey]['channel']);
		}
		////////////////////////////////////////////////
		if(isset($data[0]))return ($data);
		return array();
	}

}
class TMTopup
{
    private $ch;
    private $username;
    private $password;
    public $balance;
    public function setLogin($user, $pass)
    {
        $this->username = $user;
        $this->password = $pass;
    }
    public function login()
    {
        $loginUrl = 'https://www.tmtopup.com/?cmd=login';
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36');
        curl_setopt($this->ch, CURLOPT_URL, $loginUrl);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'username=' . $this->username . '&password=' . $this->password);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, '.htcookies');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        if (preg_match('/เกิดข้อผิดพลาด/', curl_exec($this->ch))) {
            return false;
        }
        return true;
    }
    public function getTransaction()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.tmtopup.com/?cmd=member');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $html = curl_exec($this->ch);
        preg_match('/ยอดเงินคงเหลือในบัญชี <b>(.*?)<\/b>/', $html, $temp);
        $this->balance=$temp[1];
        $this->balance=floatval(str_replace(',', '', $this->balance));
        curl_setopt($this->ch, CURLOPT_URL, 'https://www.tmtopup.com/?cmd=member/transaction');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, 'InquiryType=specified&start_dt=' . date('d-m-o', strtotime("-89 days")) . '&end_dt=' . date('d-m-o'));
        $html = curl_exec($this->ch);
        $html = str_replace("\n", "", $html);
        $html = str_replace("\t", "", $html);
        preg_match('/(\<table align="center" border="0" cellpadding="3" cellspacing="1" class="new_table"\>)(.*?)(\<\/table\>)/', $html, $content);
        preg_match_all('/(\<tr\>)(\<td align="center"\>.*?\<\/td\>)(  \<\/tr\>)/', $content[2], $row);
        $i = 0;
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');
        $vreturn=array();
        foreach ($row[2] as $val) {
            preg_match_all('/(\<td align="center"\>)(.*?)(\<\/td\>)/', $val, $data);
            $vreturn[$i]['time']    = strtotime($data[2][0]);
            $vreturn[$i]['txid']    = $data[2][1];
            $vreturn[$i]['tx_hash'] = md5($data[2][1]);
            $vreturn[$i]['from']    = $data[2][4];
            $vreturn[$i]['detail']  = $data[2][3] . ' (' . $data[2][4] . ' --> ' . $data[2][5] . ')';
            $vreturn[$i]['value']   = (($data[2][2] == 'out') ? -1 : 1) * floatval(str_replace(',', '', $data[2][6]));
            $vreturn[$i]['fee']     = floatval(str_replace(',', '', $data[2][7]));
            $i++;
        }
        return array_reverse($vreturn);
    }
}
class Database
{
    private $conn = false;
    public function connect()
    {
        try {
            $this->conn = new PDO("mysql:host=" . $GLOBALS['database']['host'] . "; dbname=" . $GLOBALS['database']['dbname'], $GLOBALS['database']['username'], $GLOBALS['database']['password']);
            $result     = $this->conn->prepare("SET NAMES UTF8");
            $result->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
            return false;
        }
        return true;
    }
    public function getConnection()
    {
        return $this->conn;
    }
}
if (@$_GET['secret'] !== $GLOBALS['secret']['api']) {
    die('INVALID');
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://customer.api.shayennn.com/licensecheck.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
    'license_key' => $GLOBALS['license_key'],
    'app_id' => 'KOBBank'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);
if ($result !== 'pass') {
    die("INVALID LICENSE");
}
switch (@$_GET['bank']) {
    case 'kbank':
        $bank_obj = new KBANK;
        $bank_obj->setAccountNumber($GLOBALS[$_GET['bank']]["acc_num"]);
        $bank_obj->setLogin($GLOBALS[$_GET['bank']]["username"], $GLOBALS[$_GET['bank']]["password"]);
        break;
    case 'scb':
        $bank_obj = new SCB;
        $bank_obj->setAccountNumber($GLOBALS[$_GET['bank']]["acc_num"]);
        $bank_obj->setLogin($GLOBALS[$_GET['bank']]["username"], $GLOBALS[$_GET['bank']]["password"]);
        break;
    case 'ktb':
        $bank_obj = new KTB;
        $bank_obj->setAccountNumber($GLOBALS[$_GET['bank']]["acc_num"], $GLOBALS[$_GET['bank']]["is_netbank_acc"]);
        $bank_obj->setLogin($GLOBALS[$_GET['bank']]["username"], $GLOBALS[$_GET['bank']]["password"]);
        break;
    case 'bbl':
        $bank_obj = new BBL;
        $bank_obj->setAccountNumber($GLOBALS[$_GET['bank']]["acc_num"]);
        $bank_obj->setLogin($GLOBALS[$_GET['bank']]["username"], $GLOBALS[$_GET['bank']]["password"]);
        break;
    case 'tmb':
        $bank_obj = new TMB;
        $bank_obj->setAccountNumber($GLOBALS[$_GET['bank']]["acc_num"]);
        $bank_obj->setLogin($GLOBALS[$_GET['bank']]["username"], $GLOBALS[$_GET['bank']]["password"]);
        break;
    case 'bay':
        $bank_obj = new BAY;
        $bank_obj->setAccountNumber($GLOBALS[$_GET['bank']]["acc_num"]);
        $bank_obj->setLogin($GLOBALS[$_GET['bank']]["username"], $GLOBALS[$_GET['bank']]["password"]);
        break;
    case 'tmtopup':
        $bank_obj = new TMTopup;
        $bank_obj->setLogin($GLOBALS[$_GET['bank']]["username"], $GLOBALS[$_GET['bank']]["password"]);
        break;
	case 'truewallet':
        $bank_obj = new TrueWallet;
        $bank_obj->setLogin($GLOBALS[$_GET['bank']]["username"], $GLOBALS[$_GET['bank']]["password"]);
        break;
    default:
        die('INVALID2');
        break;
}
if ($bank_obj->login()) {
    $trans = $bank_obj->getTransaction();
    $db    = new Database;
    if ($db->connect() == false) {
        die("CANNOT CONNECT TO DATABASE");
    }
    $conn = $db->getConnection();
    try {
        $insert = $conn->prepare('INSERT INTO `bank_payment` (`tx_hash`,`time`,`bank`,`txid`,`channel`,`value`,`fee`,`detail`,`checktime`,`status`,`tranferer`) VALUES (:tx_hash,:time,:bank,:txid,:channel,:value,:fee,:detail,:checktime,\'0\',\'\')');
    } catch (PDOException $e) {
        die('ERROR | MySQL(PrepareInsert) SAY => ' . $e->getMessage());
    }
    try {
        $select = $conn->prepare('SELECT * FROM `bank_payment` WHERE `tx_hash` LIKE :tx_hash AND `time` = :time AND `value` = :value AND `bank` LIKE :bank');
    } catch (PDOException $e) {
        die('ERROR | MySQL(PrepareSelect) SAY => ' . $e->getMessage());
    }
    try {
        $balance = $conn->prepare('INSERT INTO `bankbalance` (`bank`, `balance`, `updatetime`) VALUES(:bank, :balance, :updatetime) ON DUPLICATE KEY UPDATE `balance` = :balance, `updatetime` = :updatetime');
    } catch (PDOException $e) {
        die('ERROR | MySQL(PrepareINSERTUPDATE) SAY => ' . $e->getMessage());
    }
    $insertcount = 0;
    foreach ($trans as $key => $val) {
        try {
            $select->bindValue(':tx_hash', $val['tx_hash']);
            $select->bindValue(':time', $val['time']);
            $select->bindValue(':value', $val['value']);
            $select->bindValue(':bank', $_GET['bank']);
        } catch (PDOException $e) {
            die('ERROR | MySQL(bindValueSelect) SAY => ' . $e->getMessage());
        }
        try {
            $select->execute();
        } catch (PDOException $e) {
            die('ERROR | MySQL(executeSelect) SAY => ' . $e->getMessage());
        }
        if ($select->rowCount() == 0) {
            try {
                $insert->bindValue(':bank', $_GET['bank']);
                $insert->bindValue(':txid', (isset($val['txid']) ? $val['txid'] : ''));
                $insert->bindValue(':tx_hash', $val['tx_hash']);
                $insert->bindValue(':time', $val['time']);
                $insert->bindValue(':channel', (isset($val['channel']) ? $val['channel'] : ''));
                $insert->bindValue(':value', $val['value']);
                $insert->bindValue(':fee', (isset($val['fee']) ? $val['fee'] : 0));
                $insert->bindValue(':detail', $val['detail']);
                $insert->bindValue(':checktime', time());
            } catch (PDOException $e) {
                die('ERROR | MySQL(bindValueInsert) SAY => ' . $e->getMessage());
            }
            try {
                $raw = $insert->execute();
            } catch (PDOException $e) {
                die('ERROR | MySQL(executeSelect) SAY => ' . $e->getMessage());
            }
            if ($raw) {
                $insertcount++;
            }
        }
    }
    try {
        $balance->bindValue(':bank', $_GET['bank']);
        $balance->bindValue(':balance', $bank_obj->balance);
        $balance->bindValue(':updatetime', time());
    } catch (PDOException $e) {
        die('ERROR | MySQL(bindValueINSERTUPDATE) SAY => ' . $e->getMessage());
    }
    try {
        $balance->execute();
    } catch (PDOException $e) {
        die('ERROR | MySQL(executeINSERTUPDATE) SAY => ' . $e->getMessage());
    }
    die('SUCEED | INSERTED ' . $insertcount . ' RECORD');
} else {
    die('ERROR | CANNOT LOGIN');
}
die();
