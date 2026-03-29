<?php

use Escalated\Laravel\Services\EmailChannelService;

beforeEach(function () {
    $this->service = new EmailChannelService;
});

describe('DKIM Status Check', function () {
    it('returns unknown for empty domain', function () {
        expect($this->service->checkDkimStatus(''))->toBe('unknown');
    });

    it('returns pending when no DKIM records found', function () {
        // Use a domain very unlikely to have DKIM
        $result = $this->service->checkDkimStatus('thisdomain-does-not-exist-12345.test');

        expect($result)->toBeIn(['pending', 'unknown']);
    });
});

describe('Email Addresses', function () {
    it('returns empty array when nothing configured', function () {
        expect($this->service->getAddresses())->toBe([]);
    });

    it('saves and retrieves addresses', function () {
        $addresses = [
            [
                'email' => 'support@example.com',
                'display_name' => 'Support',
                'department_id' => 1,
            ],
        ];

        $this->service->saveAddresses($addresses);
        $result = $this->service->getAddresses();

        expect($result)->toHaveCount(1);
        expect($result[0]['email'])->toBe('support@example.com');
        expect($result[0]['dkim_status'])->toBe('unknown');
    });

    it('sets default reply address', function () {
        $this->service->setDefaultReplyAddress('noreply@example.com');

        expect($this->service->getDefaultReplyAddress())->toBe('noreply@example.com');
    });
});
