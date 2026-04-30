<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGeneralInquiryRequest;
use App\Http\Requests\StoreProductInquiryRequest;
use App\Http\Requests\StoreRepairInquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;

class InquiryController extends Controller
{
    public function storeProduct(StoreProductInquiryRequest $request): RedirectResponse
    {
        $this->throttle($request->ip());

        $inquiry = Inquiry::create([
            'type'   => 'product',
            'status' => 'new',
            'source' => 'website',
            ...$request->validated(),
        ]);

        return back()->with('success', [
            'tracking_id' => $inquiry->tracking_id,
            'message'     => "Thanks, {$inquiry->customer_name}. We've received your inquiry and will respond within a few hours.",
        ]);
    }

    public function storeRepair(StoreRepairInquiryRequest $request): RedirectResponse
    {
        $this->throttle($request->ip());

        $inquiry = Inquiry::create([
            'type'   => 'repair',
            'status' => 'new',
            'source' => 'website',
            ...$request->validated(),
        ]);

        return back()->with('success', [
            'tracking_id' => $inquiry->tracking_id,
            'message'     => "Booking confirmed for {$inquiry->customer_name}. A technician will be in touch shortly.",
        ]);
    }

    public function storeGeneral(StoreGeneralInquiryRequest $request): RedirectResponse
    {
        $this->throttle($request->ip());

        $data = $request->validated();
        $subject = $data['subject'] ?? null;
        unset($data['subject']);

        $inquiry = Inquiry::create([
            'type'   => 'general',
            'status' => 'new',
            'source' => 'website',
            'meta'   => $subject ? ['subject' => $subject] : null,
            ...$data,
        ]);

        return back()->with('success', [
            'tracking_id' => $inquiry->tracking_id,
            'message'     => "Thanks, {$inquiry->customer_name}. We'll get back to you within one business day.",
        ]);
    }

    protected function throttle(string $key): void
    {
        $limiterKey = "inquiry:{$key}";
        if (RateLimiter::tooManyAttempts($limiterKey, 8)) {
            abort(429, 'Too many inquiries — please try again in a few minutes.');
        }

        RateLimiter::hit($limiterKey, 60 * 10);
    }
}
