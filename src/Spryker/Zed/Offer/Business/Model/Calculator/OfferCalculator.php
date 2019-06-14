<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Offer\Dependency\Facade\OfferToCartFacadeInterface;
use Spryker\Zed\Offer\Dependency\Service\OfferToUtilQuantityServiceInterface;

class OfferCalculator implements OfferCalculatorInterface
{
    /**
     * @var \Spryker\Zed\Offer\Dependency\Facade\OfferToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\Offer\Dependency\Service\OfferToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\Offer\Dependency\Facade\OfferToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\Offer\Dependency\Service\OfferToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        OfferToCartFacadeInterface $cartFacade,
        OfferToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->cartFacade = $cartFacade;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function calculate(OfferTransfer $offerTransfer): OfferTransfer
    {
        $quoteTransfer = $offerTransfer->getQuote();

        $quoteTransfer = $this->removeEmptyVouchers($quoteTransfer);
        $quoteTransfer = $this->addItems($quoteTransfer);
        $quoteTransfer = $this->addIncomingItems($quoteTransfer);
        $quoteTransfer = $this->reload($quoteTransfer);

        $offerTransfer->setQuote($quoteTransfer);

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function reload(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addIncomingItems(QuoteTransfer $quoteTransfer)
    {
        $incomingItems = new ArrayObject();
        foreach ($quoteTransfer->getIncomingItems() as $itemTransfer) {
            if ($itemTransfer->getSku()) {
                $incomingItems->append($itemTransfer);
            }
        }

        foreach ($incomingItems as $itemTransfer) {
            $cartChangeTransfer = new CartChangeTransfer();
            $cartChangeTransfer->setQuote($quoteTransfer);
            $cartChangeTransfer->addItem($itemTransfer);

            $quoteTransfer = $this->cartFacade->add($cartChangeTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addItems(QuoteTransfer $quoteTransfer)
    {
        $items = clone $quoteTransfer->getItems();
        $quoteTransfer->setItems(new ArrayObject());

        foreach ($items as $itemTransfer) {
            if ($this->isQuantityLessOrEqual($itemTransfer->getQuantity(), 0)) {
                continue;
            }

            $cartChangeTransfer = new CartChangeTransfer();
            $cartChangeTransfer->setQuote($quoteTransfer);
            $cartChangeTransfer->addItem($itemTransfer);

            $quoteTransfer = $this->cartFacade->add($cartChangeTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityLessOrEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityLessOrEqual($firstQuantity, $secondQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeEmptyVouchers(QuoteTransfer $quoteTransfer)
    {
        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();

        foreach ($quoteTransfer->getVoucherDiscounts() as $key => $discountTransfer) {
            if (!$discountTransfer->getVoucherCode()) {
                $voucherDiscounts->offsetUnset($key);
            }
        }

        $quoteTransfer->setVoucherDiscounts($voucherDiscounts);

        return $quoteTransfer;
    }
}
