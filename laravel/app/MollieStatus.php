<?php declare(strict_types=1);

namespace App;

use Mollie\Api\Resources\Onboarding;
use Mollie\Api\Types\OnboardingStatus;

class MollieStatus
{
    /**
     * @var string
     */
    private $status;
    /**
     * @var bool
     */
    private $canReceivePayments;
    /**
     * @var bool
     */
    private $canReceiveSettlements;
    /**
     * @var string
     */
    private $dashboardLink;

    private function __construct(string $status, bool $canReceivePayments, bool $canReceiveSettlements, string $onboardingLink)
    {
        $this->status = $status;
        $this->canReceivePayments = $canReceivePayments;
        $this->canReceiveSettlements = $canReceiveSettlements;
        $this->dashboardLink = $onboardingLink;
    }

    public static function fromOnboardingApiResponse(Onboarding $onboardingResponse): self
    {
        return new self(
            $onboardingResponse->status,
            $onboardingResponse->canReceivePayments,
            $onboardingResponse->canReceiveSettlements,
            $onboardingResponse->_links->dashboard->href
        );
    }

    public function paymentsAreDisabledBecauseMollieNeedsMoreData(): bool
    {
        return $this->needsData() && $this->canReceivePayments === false;
    }

    public function settlementsAreDisabledBecauseMollieNeedsMoreData(): bool
    {
        return $this->needsData() && $this->canReceivePayments && !$this->canReceiveSettlements;
    }

    public function settlementsAreDisabledBecauseMollieIsReviewing(): bool
    {
        return $this->inReview() && $this->canReceivePayments && !$this->canReceiveSettlements;
    }

    public function paymentsAndSettlementsAreDisabledBecauseMollieIsReviewing(): bool
    {
        return $this->inReview() && !$this->canReceivePayments && !$this->canReceiveSettlements;
    }

    public function paymentsAndSettlementsAreEnabled(): bool
    {
        return $this->isCompleted() && $this->canReceivePayments && $this->canReceiveSettlements;
    }

    private function needsData(): bool
    {
        return $this->status === OnboardingStatus::NEEDS_DATA;
    }

    private function inReview(): bool
    {
        return $this->status === OnboardingStatus::IN_REVIEW;
    }

    private function isCompleted(): bool
    {
        return $this->status === OnboardingStatus::COMPLETED;
    }

    public function getDashboardLink(): string
    {
        return $this->dashboardLink;
    }
}
