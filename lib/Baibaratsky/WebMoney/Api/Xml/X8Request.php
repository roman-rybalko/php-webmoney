<?php
namespace Baibaratsky\WebMoney\Api\Xml;

use Baibaratsky\WebMoney\Exception\ApiException;
use Baibaratsky\WebMoney\Signer\RequestSigner;
use Baibaratsky\WebMoney\Validator\RequestValidator;

/**
 * Class X8Request
 *
 * @link https://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X8
 */
class X8Request extends ApiRequest
{
    /** @var string wmid */
    protected $_signerWmid;

    /** @var string testwmpurse/wmid */
    protected $_wmid;

    /** @var string testwmpurse/purse */
    protected $_purse;

    /**
     * @param string $authType
     * @throws ApiException
     */
    public function __construct($authType = self::AUTH_CLASSIC)
    {
        if ($authType === self::AUTH_CLASSIC) {
            $this->_url = 'https://w3s.webmoney.ru/asp/XMLFindWMPurseNew.asp';
        } elseif ($authType === self::AUTH_LIGHT) {
            $this->_url = 'https://w3s.wmtransfer.com/asp/XMLFindWMPurseCertNew.asp';
        } else {
            throw new ApiException('This interface doesn\'t support the authentication type given.');
        }

        parent::__construct($authType);
    }

    /**
     * @return array
     */
    protected function _getValidationRules()
    {
        return array(
            RequestValidator::TYPE_REQUIRED => array('requestNumber'),
            RequestValidator::TYPE_DEPEND_REQUIRED => array(
                'signerWmid' => array('authType' => array(self::AUTH_CLASSIC)),
            ),
        );
    }

    /**
     * @return string
     */
    public function getXml()
    {
        $xml = '<w3s.request>';
        $xml .= self::_xmlElement('reqn', $this->_requestNumber);
        $xml .= self::_xmlElement('wmid', $this->_signerWmid);
        $xml .= self::_xmlElement('sign', $this->_signature);
        $xml .= '<testwmpurse>';
        $xml .= self::_xmlElement('wmid', $this->_wmid);
        $xml .= self::_xmlElement('purse', $this->_purse);
        $xml .= '</testwmpurse>';
        $xml .= '</w3s.request>';

        return $xml;
    }

    /**
     * @return string
     */
    public function getResponseClassName()
    {
        return 'Baibaratsky\WebMoney\Api\Xml\X8Response';
    }

    /**
     * @param RequestSigner $requestSigner
     */
    public function sign(RequestSigner $requestSigner = null)
    {
        if ($this->_authType === self::AUTH_CLASSIC) {
            $this->_signature = $requestSigner->sign($this->_wmid . $this->_purse);
        }
    }

    /**
     * @param string $testWmid
     */
    public function setWmid($testWmid)
    {
        $this->_wmid = $testWmid;
    }

    /**
     * @return string
     */
    public function getWmid()
    {
        return $this->_wmid;
    }

    /**
     * @param string $testPurse
     */
    public function setPurse($testPurse)
    {
        $this->_purse = $testPurse;
    }

    /**
     * @return string
     */
    public function getPurse()
    {
        return $this->_purse;
    }
}
