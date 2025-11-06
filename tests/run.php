<?php
require_once __DIR__ . '/../includes/helpers.php';

$tests = [
    'sanitize_company_id strips non-digits and limits length' => function () {
        $input = 'AB12-34 56 78 90 12';
        $expected = '123456789012';
        return sanitize_company_id($input) === $expected;
    },
    'sanitize_company_id returns empty string for no digits' => function () {
        return sanitize_company_id('---') === '';
    },
    'is_valid_company_name rejects null and whitespace' => function () {
        return !is_valid_company_name(null) && !is_valid_company_name("   ") && is_valid_company_name('شرکت نمونه');
    },
    'build_verification_message handles missing settings gracefully' => function () {
        $default = build_verification_message([]);
        $withPhone = build_verification_message(['expert_phone' => '021123456']);
        $withName = build_verification_message(['expert_name' => 'کارشناس']);
        $withAll = build_verification_message(['expert_name' => 'کارشناس', 'expert_phone' => '021123456']);

        return
            $default === 'شناسه شما ثبت شد. لطفاً منتظر تایید مدیریت بمانید.' &&
            strpos($withPhone, '021123456') !== false &&
            strpos($withName, 'کارشناس') !== false &&
            strpos($withAll, 'کارشناس') !== false &&
            strpos($withAll, '021123456') !== false;
    },
    'can_download_for_company requires approved status' => function () {
        return can_download_for_company(['is_approved' => 1]) &&
            !can_download_for_company(['is_approved' => 0]) &&
            !can_download_for_company(null);
    },
];

$failures = 0;
foreach ($tests as $description => $callback) {
    $result = false;
    try {
        $result = (bool) $callback();
    } catch (Throwable $e) {
        $result = false;
    }

    if ($result) {
        echo "[PASS] {$description}\n";
    } else {
        echo "[FAIL] {$description}\n";
        $failures++;
    }
}

if ($failures > 0) {
    exit(1);
}

echo "All tests passed.\n";
