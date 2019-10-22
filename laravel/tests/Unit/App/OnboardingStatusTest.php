<?php

namespace Tests\Unit\App;

use Mollie\Api\Types\OnboardingStatus;
use Tests\TestCase;

class OnboardingStatusTest extends TestCase
{
    /**
     * @dataProvider mollieOnboardingStatusDataProvider
     */
    public function testOnboardingStatus(\App\OnboardingStatus $onboardingStatus, string $method, bool $expected)
    {
        $this->assertEquals($expected, $onboardingStatus->{$method}());
    }

    public function mollieOnboardingStatusDataProvider(): array
    {
        return [
            "Needs data, payments disabled, outpayments disabled" => [
                "onboardingStatus" => new \App\OnboardingStatus(
                    OnboardingStatus::NEEDS_DATA,
                    false,
                    false,
                    "https://example.org"
                ),
                "method" => "paymentsAreDisabledBecauseMollieNeedsMoreData",
                "expected" => true
            ],
            "Needs data, payments enabled, outpayments disabled" => [
                "onboardingStatus" => new \App\OnboardingStatus(
                    OnboardingStatus::NEEDS_DATA,
                    true,
                    false,
                    "https://example.org"
                ),
                "method" => "settlementsAreDisabledBecauseMollieNeedsMoreData",
                "expected" => true
            ],
            "In review, payments enabled, outpayments disabled" => [
                "onboardingStatus" => new \App\OnboardingStatus(
                    OnboardingStatus::IN_REVIEW,
                    true,
                    false,
                    "https://example.org"
                ),
                "method" => "settlementsAreDisabledBecauseMollieIsReviewing",
                "expected" => true
            ],
            "In review, payments disabled, outpayments disabled" => [
                "onboardingStatus" => new \App\OnboardingStatus(
                    OnboardingStatus::IN_REVIEW,
                    false,
                    false,
                    "https://example.org"
                ),
                "method" => "paymentsAndSettlementsAreDisabledBecauseMollieIsReviewing",
                "expected" => true
            ],
            "Completed, payments enabled, outpayments enabled" => [
                "onboardingStatus" => new \App\OnboardingStatus(
                    OnboardingStatus::COMPLETED,
                    true,
                    true,
                    "https://example.org"
                ),
                "method" => "paymentsAndSettlementsAreEnabled",
                "expected" => true
            ]
        ];
    }
}
