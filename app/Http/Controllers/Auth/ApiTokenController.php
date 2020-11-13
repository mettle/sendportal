<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\ApiTokenRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApiTokenController extends Controller
{
    /** @var ApiTokenRepository */
    private $apiTokensRepo;

    public function __construct(ApiTokenRepository $apiTokensRepo)
    {
        $this->apiTokensRepo = $apiTokensRepo;
    }

    /**
     * @throws Exception
     */
    public function index(): View
    {
        $tokens = $this->apiTokensRepo->all(auth()->user()->currentWorkspaceId());

        return view('api-tokens.index', compact('tokens'));
    }

    /**
     * @throws Exception
     */
    public function store(): RedirectResponse
    {
        $newToken = Str::random(32);

        $this->apiTokensRepo->store(auth()->user()->currentWorkspaceId(), ['api_token' => $newToken]);

        return redirect()
            ->route('api-tokens.index');
    }

    /**
     * @throws Exception
     */
    public function destroy(int $tokenId): RedirectResponse
    {
        $this->apiTokensRepo->destroy(auth()->user()->currentWorkspaceId(), $tokenId);

        return redirect()->back();
    }
}
