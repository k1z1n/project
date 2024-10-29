<?php

namespace App\Http\Controllers;

use App\Services\HelperService;
use App\Services\RequestService;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;

class RequestController extends Controller
{

    protected RequestService $requestService;
    protected HelperService $helperService;

    public function __construct(
        RequestService $requestService,
        HelperService  $helperService
    )
    {
        $this->requestService = $requestService;
        $this->helperService = $helperService;
    }

    public function record($id)
    {
        $userId = auth()->id();

        $requests = $this->requestService->checkExistingRequests($userId, $id);

        if($redirect = $this->requestService->redirectForRequest($requests, $id)){
            return $redirect;
        }

        $this->requestService->createRequest($userId, $id);

        return $this->helperService->returnWithSuccess('student.one.course', 'Ваша заявка в обработке.', $id);
    }

}
