<?php

namespace Own3d\Id\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @author Stefan Ensmann <stefan.e@own3d.tv>
 */
class UpdateWebhookValidation extends WebhookValidation
{
    private const SUPPORTED_TYPES = [
        'account_migration',
        'platform_authorization_revoked',
        'account_authorization_revoked',
        'platform_linked',
        'personal_information_updated',
        'preferences_updated',
    ];

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
        if (!in_array($request->input('type', ''), self::SUPPORTED_TYPES)) {
            return response()
                ->json([
                    'error' => 'Invalid update type',
                ], 400);
        }

        return parent::handle($request, $next);
    }
}
