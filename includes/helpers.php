<?php
if (!function_exists('sanitize_company_id')) {
    function sanitize_company_id(string $input): string
    {
        $digits = preg_replace('/\D+/', '', $input);
        return mb_substr($digits, 0, 15) ?: '';
    }
}

if (!function_exists('is_valid_company_name')) {
    function is_valid_company_name(?string $name): bool
    {
        if ($name === null) {
            return false;
        }
        return mb_strlen(trim($name)) > 0;
    }
}

if (!function_exists('build_verification_message')) {
    function build_verification_message(array $settings): string
    {
        $expertName = $settings['expert_name'] ?? '';
        $expertPhone = $settings['expert_phone'] ?? '';

        if (!$expertName && !$expertPhone) {
            return 'شناسه شما ثبت شد. لطفاً منتظر تایید مدیریت بمانید.';
        }

        if ($expertName && $expertPhone) {
            return "شناسه شما ثبت شد. برای احراز هویت، مدارک حقوقی شرکت را به شماره {$expertPhone} (کارشناس: {$expertName}) ارسال کنید. پس از تایید، امکان دانلود فایل فراهم می‌شود.";
        }

        if ($expertPhone) {
            return "شناسه شما ثبت شد. برای احراز هویت، مدارک حقوقی شرکت را به شماره {$expertPhone} ارسال کنید.";
        }

        return "شناسه شما ثبت شد. برای احراز هویت، مدارک حقوقی شرکت را برای کارشناس {$expertName} ارسال کنید.";
    }
}

if (!function_exists('can_download_for_company')) {
    function can_download_for_company(?array $company): bool
    {
        if (!$company) {
            return false;
        }

        return (int)($company['is_approved'] ?? 0) === 1;
    }
}
