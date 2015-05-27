<?php

namespace OpenOrchestra\BaseApi\Model;

/**
 * Interface ExpireableInterface
 */
interface ExpireableInterface
{
    /**
     * @param \DateTime $expiredAt
     */
    public function setExpiredAt(\DateTime $expiredAt);

    /**
     * @return \DateTime
     */
    public function getExpiredAt();

    /**
     * @return boolean
     */
    public function isExpired();
}
