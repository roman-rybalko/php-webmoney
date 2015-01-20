<?php

namespace baibaratsky\WebMoney\Api\X\X3;

use baibaratsky\WebMoney\Api\X;
use baibaratsky\WebMoney\Exception\ApiException;
use baibaratsky\WebMoney\Signer;
use baibaratsky\WebMoney\Request\RequestValidator;

/**
 * Class Request
 *
 * @link https://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X3
 */
class Request extends X\Request
{
    /** @var string wmid */
    protected $signerWmid;

    /** @var string getoperations\purse */
    protected $parameterPurse;

    /** @var int getoperations\wmtranid */
    protected $parameterTransactionId;

    /** @var int getoperations\tranid */
    protected $parameterTransactionExternalId;

    /** @var int getoperations\wminvid */
    protected $parameterInvoiceId;

    /** @var int getoperations\orderid */
    protected $parameterExternalInvoiceId;

    /** @var \DateTime getoperations\datestart */
    protected $parameterStartDate;

    /** @var \DateTime getoperations\datefinish */
    protected $parameterEndDate;

    public function __construct($authType = self::AUTH_CLASSIC)
    {
        if ($authType === self::AUTH_CLASSIC) {
            $this->url = 'https://w3s.webmoney.ru/asp/XMLOperations.asp';
        } elseif ($authType === self::AUTH_LIGHT) {
            $this->url = 'https://w3s.wmtransfer.com/asp/XMLOperationsCert.asp';
        } else {
            throw new ApiException('This interface doesn\'t support the authentication type given.');
        }

        parent::__construct($authType);
    }

    /**
     * @return array
     */
    protected function getValidationRules()
    {
        return array(
            RequestValidator::TYPE_REQUIRED => array('parameterPurse', 'parameterStartDate', 'parameterEndDate'),
            RequestValidator::TYPE_DEPEND_REQUIRED => array(
                'signerWmid' => array('authType' => array(self::AUTH_CLASSIC)),
            ),
        );
    }

    /**
     * @return string
     */
    public function getData()
    {
        $xml = '<w3s.request>';
        $xml .= self::xmlElement('reqn', $this->requestNumber);
        $xml .= self::xmlElement('wmid', $this->signerWmid);
        $xml .= self::xmlElement('sign', $this->signature);
        $xml .= '<getoperations>';
        $xml .= self::xmlElement('purse', $this->parameterPurse);
        $xml .= self::xmlElement('wmtranid', $this->parameterTransactionId);
        $xml .= self::xmlElement('tranid', $this->parameterTransactionExternalId);
        $xml .= self::xmlElement('wminvid', $this->parameterInvoiceId);
        $xml .= self::xmlElement('orderid', $this->parameterExternalInvoiceId);
        $xml .= self::xmlElement('datestart', $this->parameterStartDate->format('Ymd H:i:s'));
        $xml .= self::xmlElement('datefinish', $this->parameterEndDate->format('Ymd H:i:s'));
        $xml .= '</getoperations>';
        $xml .= '</w3s.request>';

        return $xml;
    }

    /**
     * @return string
     */
    public function getResponseClassName()
    {
        return Response::className();
    }

    /**
     * @param Signer $requestSigner
     *
     */
    public function sign(Signer $requestSigner = null)
    {
        if ($this->authType === self::AUTH_CLASSIC) {
            $this->signature = $requestSigner->sign($this->parameterPurse . $this->requestNumber);
        }
    }

    /**
     * @param string $signerWmid
     */
    public function setSignerWmid($signerWmid)
    {
        $this->signerWmid = $signerWmid;
    }

    /**
     * @return string
     */
    public function getSignerWmid()
    {
        return $this->signerWmid;
    }

    /**
     * @param string $parameterPurse
     */
    public function setParameterPurse($parameterPurse)
    {
        $this->parameterPurse = $parameterPurse;
    }

    /**
     * @return string
     */
    public function getParameterPurse()
    {
        return $this->parameterPurse;
    }

    /**
     * @param int $parameterTransactionId
     */
    public function setParameterTransactionId($parameterTransactionId)
    {
        $this->parameterTransactionId = $parameterTransactionId;
    }

    /**
     * @return int
     */
    public function getParameterTransactionId()
    {
        return $this->parameterTransactionId;
    }

    /**
     * @param int $parameterTransactionExternalId
     */
    public function setParameterTransactionExternalId($parameterTransactionExternalId)
    {
        $this->parameterTransactionExternalId = $parameterTransactionExternalId;
    }

    /**
     * @return int
     */
    public function getParameterTransactionExternalId()
    {
        return $this->parameterTransactionExternalId;
    }

    /**
     * @param int $parameterExternalInvoiceId
     */
    public function setParameterExternalInvoiceId($parameterExternalInvoiceId)
    {
        $this->parameterExternalInvoiceId = $parameterExternalInvoiceId;
    }

    /**
     * @return int
     */
    public function getParameterExternalInvoiceId()
    {
        return $this->parameterExternalInvoiceId;
    }

    /**
     * @param int $parameterInvoiceId
     */
    public function setParameterInvoiceId($parameterInvoiceId)
    {
        $this->parameterInvoiceId = $parameterInvoiceId;
    }

    /**
     * @return int
     */
    public function getParameterInvoiceId()
    {
        return $this->parameterInvoiceId;
    }

    /**
     * @param \DateTime $parameterStartDate
     */
    public function setParameterStartDate($parameterStartDate)
    {
        $this->parameterStartDate = $parameterStartDate;
    }

    /**
     * @return \DateTime
     */
    public function getParameterStartDate()
    {
        return $this->parameterStartDate;
    }

    /**
     * @param \DateTime $parameterEndDate
     */
    public function setParameterEndDate($parameterEndDate)
    {
        $this->parameterEndDate = $parameterEndDate;
    }

    /**
     * @return \DateTime
     */
    public function getParameterEndDate()
    {
        return $this->parameterEndDate;
    }
}
