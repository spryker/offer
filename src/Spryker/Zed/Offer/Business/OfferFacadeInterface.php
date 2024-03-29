<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferResponseTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface OfferFacadeInterface
{
    /**
     * Specification:
     * - Return list of offers, using filter and pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOffers(OfferListTransfer $offerListTransfer): OfferListTransfer;

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
     * - Creates am offer in DB.
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
     * - Places an offer to a DB
     * - Takes a customer relation from Quote (filled as a part of checkout process from customer session)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function placeOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;

    /**
     * Specification:
     * - Updates quote_data with a quote transfer fields as json
     * - Updates offer fields
     * - A record must exist, otherwise throws an exception
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\OfferResponseTransfer
     */
    public function updateOffer(OfferTransfer $offerTransfer): OfferResponseTransfer;

    /**
     * Specification:
     *  - Recalculates offer items subtotal
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function aggregateOfferItemSubtotal(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * Specification:
     *  - Calculates saving amount for each offer item and hydrates one to the item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrateOfferWithSavingAmount(OfferTransfer $offerTransfer): OfferTransfer;

    /**
     * Specification:
     * - Uses quote offer fee to recalculate a quote grant total
     * - The offer fee is added to a current quote grand total
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateGrandTotal(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * Specification:
     * - Add a customer to quote based on a targeted offer customer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idOffer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteUsingOffer(QuoteTransfer $quoteTransfer, int $idOffer): QuoteTransfer;

    /**
     * Specification:
     * - Removes empty vouchers
     * - Cleans quote
     * - Adds to quote available items
     * - Adds to quote incoming items
     * - Recalculates a quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function calculateOffer(OfferTransfer $offerTransfer): OfferTransfer;
}
