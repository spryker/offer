<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;

interface OfferFacadeInterface
{
    /**
     * Specification:
     * - Return list of offers, using filter and pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerList
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOffers(OfferListTransfer $offerList): OfferListTransfer;

    /**
     * Specification:
     * - Get offer transfer by offer id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getOfferById(OfferTransfer $offerTransfer): OfferTransfer;

    /**
     * Specification:
     * - Creates a new order in the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function createOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;

    /**
     * Specification:
     * - Updates quote_data with a quote transfer fields as json
     * - Updates offer fields
     * - A record must exist, otherwise throws an exception
     *
     * @api
     *
     * @param OfferTransfer $offerTransfer
     * @return OfferResponseTransfer
     *
     * @throws \Exception
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;
}
