<?php

namespace Own3d\Id\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @author Stefan Ensmann <stefan.e@own3d.tv>
 */
class WebhookValidation
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * 
     * @return Response
     */
    public function handle($request, $next)
    {
        $xSignature = $request->header('X-Signature', '');
        $xTimestamp = intval($request->header('X-Timestamp', 0));

        $input = $request->input();

        if (!$this->isValidTimestamp($xTimestamp)) {
            return response()
                ->json([
                    'error' => 'Request expired',
                ], 400);
        }

        if (!$this->isValidSignature($input, $xTimestamp, $xSignature)) {
            return response()
                ->json([
                    'error' => 'Invalid signature',
                ], 400);
        }

        return $next($request);
    }

    /**
     * Validates the timestamp of the incoming request.
     * 
     * @param int $timestamp
     * @param int $tolerance
     * 
     * @return bool
     */
    private function isValidTimestamp($timestamp) {
        return abs(time() - $timestamp) <= config('own3d-id.webhook_age_tolerance');
    }

    /**
     * Validates the signature of the incoming request.
     * 
     * @param array $payload
     * @param int $timestamp
     * @param string $receivedSignature
     * 
     * @return bool
     */
    private function isValidSignature($payload, $timestamp, $receivedSignature) {
        $data = $timestamp . json_encode($payload);
        $calculatedSignature = hash_hmac('sha256', $data, config('own3d-id.client_secret'));
        return hash_equals($calculatedSignature, $receivedSignature);
    }
}
