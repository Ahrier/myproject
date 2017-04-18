<?php

/**
 * Class HandInterface 汉德询报价接口
 */
class HandInterface
{
    //获得code地址
    const URLCODE =         "http://gomrodev.train.going-link.com/wsdl/modules/cux/GOMRO/WS/AUTH/itf_client_auth.svc";
    //获得access_token地址
    const URLACCESSTOKEN =  "http://gomrodev.train.going-link.com/wsdl/modules/cux/GOMRO/WS/AUTH/itf_client_access_token.svc";
    //获得refresh_token地址
    const URLREFRESHTOKEN = "http://gomrodev.train.going-link.com/wsdl/modules/cux/GOMRO/WS/AUTH/itf_client_refresh_token.svc";
    //获得数据地址
    const URLINQUIRY =      "http://gomrodev.train.going-link.com/wsdl/modules/cux/GOMRO/WS/PUR_RFX/itf_pur_rfx_items_query.svc";



    //获取询报价数据
    public static function GetInquiryList()
    {
        //刷新token
        /*$arrToken = HandInterface::GetToken();
        HandInterface::refreshAccessToken($arrToken[0]);*/
        $arrToken = HandInterface::GetToken();
        $params = ['HEADER' => ['TOKEN' => $arrToken[1]],'PARAM'=> [ 'PAGE' => 1, 'PAGE_NUM' => 10, 'ITEM_CATEGORY_DESC' => '', 'ITEM_CODE' => '', 'ITEM_DESC' => '', 'BRAND' => '', 'ITEM_MODEL' => '', 'ADDRESS' => '', 'DELIVERY' => '', 'FEEDBACK_START_TIME' => '', 'FEEDBACK_END_TIME' => '', 'STATUS' => '', 'COMPANY' => '', 'CONTACT' => '']];
        $config = ['trace' => true, 'encoding' => 'utf-8'];
        $soap = new SoapClient(self::URLINQUIRY, $config);
        /*
         *SoapHeader参数说明如下所示:
         *'http://tempuri.org/'   namespace(命名空间可省略)
         *'MySoapHeader'          SoapHeader头的类名
         *'array(...)'            存放标识身份的字符串参数
         *'true'                  是否必须处理该header
        */
        $u = new SoapHeader(self::URLINQUIRY,'TOKEN',$params,true);
        //添加soapheader
        $soap->__setSoapHeaders(array($u));

        try {
            //$data  = $soap->header($header);
            $soap->execute($params);
            $result = $soap-> __getLastResponse();
            $xmlcode = HandInterface::xmlObjToArr(simplexml_load_string($result));
            var_dump($result);
        } catch (Exception $e) {
            print_r($e);
        }
    }
    //刷新access_token
    public static function refreshAccessToken($refresh_token)
    {
        $params = ['client_id' => '0C2555C9F386A799', 'client_secret' => '84F7DEFA0E3066ED508B1CD400678F78', 'state' => 'state', 'refresh_token' => $refresh_token, 'grant_type' => 'authorization_code'];
        $config = ['trace' => true, 'encoding' => 'utf-8'];
        $soap = new SoapClient(self::URLREFRESHTOKEN, $config);
        try {
            $soap->execute($params);
            $result = $soap->__getLastResponse();
            $xmlcode = HandInterface::xmlObjToArr(simplexml_load_string($result));
            $access_token = $xmlcode['children']['soapenv:body'][0]['children']['response_data'][0]['attributes']['access_token'];
            return $access_token;
        } catch (Exception $e) {
            print_r($e);
        }
    }

    //得到refresh_token-access_token
    public static function GetToken()
    {
        $code = HandInterface::GetCode();
        $params = ['client_id' => '0C2555C9F386A799', 'client_secret' => '84F7DEFA0E3066ED508B1CD400678F78', 'state' => 'state', 'code' => $code, 'grant_type' => 'authorization_code'];
        $config = ['trace' => true, 'encoding' => 'utf-8'];
        $soap = new SoapClient(self::URLACCESSTOKEN, $config);
        try {
            $soap->execute($params);
            $result = $soap->__getLastResponse();
            $xmlcode = HandInterface::xmlObjToArr(simplexml_load_string($result));

            $refresh_token = $xmlcode['children']['soapenv:body'][0]['children']['response_data'][0]['attributes']['refresh_token'];
            $access_token = $xmlcode['children']['soapenv:body'][0]['children']['response_data'][0]['attributes']['access_token'];
            $arr = array();
            $arr[0] = $refresh_token;
            $arr[1] = $access_token;
            return $arr;
        } catch (Exception $e) {
            print_r($e);
        }
    }

    //得到code
    public static function GetCode()
    {
        $params = ['client_id' => '0C2555C9F386A799', 'state' => 'state', 'response_type' => 'code'];
        $config = ['trace' => true, 'encoding' => 'utf-8'];
        $soap = new SoapClient(self::URLCODE, $config);
        try {
            $soap->execute($params);
            $result = $soap->__getLastResponse();
            $xmlcode = HandInterface::xmlObjToArr(simplexml_load_string($result));
            $code = $xmlcode['children']['soapenv:body'][0]['children']['response_data'][0]['attributes']['auth_code'];
            return $code;
        } catch (Exception $e) {
            print_r($e);
        }
    }


    //将xml转化成数组
    public static function xmlObjToArr($obj)
    {
        $namespace = $obj->getDocNamespaces(true);
        $namespace[NULL] = NULL;

        $children = array();
        $attributes = array();
        $name = strtolower((string)$obj->getName());

        $text = trim((string)$obj);
        if (strlen($text) <= 0) {
            $text = NULL;
        }

        // get info for all namespaces
        if (is_object($obj)) {
            foreach ($namespace as $ns => $nsUrl) {
                // atributes
                $objAttributes = $obj->attributes($ns, true);
                foreach ($objAttributes as $attributeName => $attributeValue) {
                    $attribName = strtolower(trim((string)$attributeName));
                    $attribVal = trim((string)$attributeValue);
                    if (!empty($ns)) {
                        $attribName = $ns . ':' . $attribName;
                    }
                    $attributes[$attribName] = $attribVal;
                }

                // children
                $objChildren = $obj->children($ns, true);
                foreach ($objChildren as $childName => $child) {
                    $childName = strtolower((string)$childName);
                    if (!empty($ns)) {
                        $childName = $ns . ':' . $childName;
                    }
                    $children[$childName][] = self::xmlObjToArr($child);
                }
            }
        }
        return array(
            'name' => $name,
            'text' => $text,
            'attributes' => $attributes,
            'children' => $children
        );
    }
    //根据key得到多维数组的value
    /*public static function getValues($array,$code)
    {
        foreach ($array as $key=>$val){
            if($key === $code){
                return $val;
            }else{
                if(is_array($val) && !empty($val)){
                    HandInterface::getValues($val,$code);
                }
            }
        }
    }*/


}
HandInterface::GetInquiryList();
?>